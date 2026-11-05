/**
 * Firebase Authentication (correo/contraseña + Google) para ProductosPro.
 *
 * Flujo: el navegador autentica contra Firebase, obtiene un ID token y se lo envía a Laravel,
 * que lo valida con Google y abre la sesión. Laravel nunca confía en el correo que manda el navegador.
 *
 * Requiere (definidos en resources/views/auth/_firebase-scripts.blade.php):
 *   window.FIREBASE_CONFIG, window.FIREBASE_ROUTES y los SDK compat de Firebase.
 */
(function () {
    'use strict';

    const cfg = window.FIREBASE_CONFIG || {};
    const routes = window.FIREBASE_ROUTES || {};

    let app = null;
    let auth = null;
    let firebaseReady = false;
    let readyPromise = Promise.resolve();
    let pendingUser = null; // usuario con correo sin verificar (para reenviar el enlace)

    // Detección del estado de configuración de Firebase
    const hasConfig = Boolean(cfg && cfg.apiKey && cfg.projectId && cfg.apiKey !== 'null');

    if (typeof firebase === 'undefined') {
        console.warn('El SDK de Firebase no se cargó. Revisa tu conexión a internet.');
    } else if (!hasConfig) {
        console.warn('Firebase no está configurado: completa las variables FIREBASE_* en el archivo .env');
    } else {
        try {
            app = firebase.apps.length ? firebase.app() : firebase.initializeApp(cfg);
            auth = firebase.auth();
            // La sesión real es la de Laravel; no dejamos una sesión huérfana en el navegador.
            readyPromise = auth.setPersistence(firebase.auth.Auth.Persistence.NONE).catch(() => { });
            firebaseReady = true;
        } catch (e) {
            console.error('Error al inicializar Firebase SDK:', e);
        }
    }

    /* ---------- Diccionario de Errores Amigables ---------- */

    const currentDomain = (window.location && window.location.hostname) ? window.location.hostname : 'tu dominio';

    const ERRORS = {
        'auth/invalid-email': 'El correo electrónico no es válido.',
        'auth/user-not-found': 'Correo o contraseña incorrectos.',
        'auth/wrong-password': 'Correo o contraseña incorrectos.',
        'auth/invalid-credential': 'Correo o contraseña incorrectos.',
        'auth/invalid-login-credentials': 'Correo o contraseña incorrectos.',
        'auth/email-already-in-use': 'Este correo ya tiene una cuenta registrada. Inicia sesión con tus credenciales.',
        'auth/weak-password': 'La contraseña es demasiado débil (mínimo 8 caracteres).',
        'auth/too-many-requests': 'Demasiados intentos fallidos. Por seguridad, espera unos minutos e inténtalo de nuevo.',
        'auth/network-request-failed': 'No se pudo conectar con los servidores de autenticación. Revisa tu conexión a internet.',
        'auth/user-disabled': 'Esta cuenta ha sido deshabilitada. Contacta al administrador del sistema.',
        'auth/operation-not-allowed': 'El proveedor seleccionado no está habilitado en Firebase Console (Authentication > Sign-in method).',
        'auth/popup-closed-by-user': 'Cerraste la ventana antes de completar el inicio de sesión.',
        'auth/popup-blocked': 'El navegador bloqueó la ventana emergente. Permite las ventanas emergentes o usa redirección.',
        'auth/cancelled-popup-request': 'Se canceló la ventana de inicio de sesión anterior. Inténtalo de nuevo.',
        'auth/unauthorized-domain': 'El dominio actual ("' + currentDomain + '") no está autorizado en Firebase. Agrégalo en Firebase Console > Authentication > Settings > Authorized domains.',
        'auth/account-exists-with-different-credential': 'Ya existe una cuenta con este correo creada con otro método. Inicia sesión con el método que usaste originalmente.',
        'auth/internal-error': 'Error interno de Firebase. Revisa que el proveedor seleccionado esté habilitado y configurado en Firebase Console.',
        'auth/api-key-not-valid.-please-pass-a-valid-api-key.': 'La API key de Firebase no es válida. Revisa las variables FIREBASE_* en el archivo .env.',
        'auth/invalid-api-key': 'La API key de Firebase no es válida. Revisa las variables FIREBASE_* en el archivo .env.',
        'auth/app-not-authorized': 'Esta aplicación web no está autorizada para autenticar usuarios con Firebase.'
    };

    function friendly(error) {
        if (!error) return 'Ocurrió un error inesperado.';
        if (error.userMessage) return error.userMessage;
        if (error.code && ERRORS[error.code]) return ERRORS[error.code];
        if (error.message && ERRORS[error.message]) return ERRORS[error.message];
        console.error('Error detallado de autenticación:', error);
        return error.message || 'Ocurrió un error inesperado. Inténtalo de nuevo.';
    }

    function appError(message, extra) {
        const e = new Error(message);
        e.userMessage = message;
        Object.assign(e, extra || {});
        return e;
    }

    async function postJson(url, payload) {
        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const res = await fetch(url, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf || ''
            },
            body: JSON.stringify(payload)
        });
        let data = {};
        try { data = await res.json(); } catch (e) { /* respuesta no JSON */ }
        return { ok: res.ok, status: res.status, data };
    }

    function serverError(r) {
        if (r.data && r.data.errors) {
            return appError(Object.values(r.data.errors).flat().join(' '));
        }
        return appError((r.data && r.data.message) || 'Error al comunicarse con el servidor.', { code: r.data && r.data.code });
    }

    /** Registro opcional en Cloud Firestore (solo si el SDK y las reglas lo permiten). */
    async function registrarEventoNoSQL(data) {
        if (typeof firebase === 'undefined' || typeof firebase.firestore !== 'function') return;
        try {
            const write = firebase.firestore().collection('auditoria_accesos').add(data);
            await Promise.race([write, new Promise(r => setTimeout(r, 1500))]);
        } catch (e) {
            console.warn('No se pudo registrar evento en Firestore (auditoría opcional):', e.message);
        }
    }

    /**
     * Guarda o actualiza el perfil del usuario en Cloud Firestore.
     * No almacena contraseñas ni sustituye la autenticación de Laravel.
     */
    async function registrarPerfilFirestore(user, nombre, proveedor) {
        if (
            typeof firebase === 'undefined' ||
            typeof firebase.firestore !== 'function' ||
            !user ||
            !user.uid
        ) {
            return false;
        }

        try {
            const db = firebase.firestore();
            const referencia = db.collection('usuarios').doc(user.uid);
            const documento = await referencia.get();

            const perfil = {
                uid: user.uid,
                nombre: nombre || user.displayName || '',
                correo: user.email || '',
                proveedor: proveedor || 'password',
                actualizadoEn: firebase.firestore.FieldValue.serverTimestamp()
            };

            // Conserva la fecha de creación original si el perfil ya existe.
            if (!documento.exists || !documento.data().creadoEn) {
                perfil.creadoEn =
                    firebase.firestore.FieldValue.serverTimestamp();
            }

            await referencia.set(perfil, { merge: true });

            return true;
        } catch (error) {
            // Un error de Firestore no debe impedir el inicio de sesión.
            console.warn(
                'No se pudo guardar el perfil en Firestore:',
                error.message
            );
            return false;
        }
        try { await user.updateProfile({ displayName: names }); } catch (e) { /* no crítico */ }
        await user.sendEmailVerification();
        return r.data;
    }


    /* ---------- Operaciones Principales ---------- */

    async function loginWithPassword(email, password, remember) {
        if (!firebaseReady || !auth) {
            throw appError('Firebase Authentication no está configurado en el servidor. Completa FIREBASE_* en el archivo .env.');
        }
        await readyPromise;
        const cred = await auth.signInWithEmailAndPassword(email, password);
        let user = cred.user;
        await user.reload();
        user = auth.currentUser || user;

        if (!user.emailVerified) {
            pendingUser = user;
            throw appError('Debes verificar tu correo antes de ingresar. Revisa tu bandeja (y carpeta de spam).', { needsVerification: true });
        }
        return finishLogin(user, remember, 'password');
    }

    async function finishLogin(user, remember, provider) {
        showAlert('info', 'Verificando credenciales con el servidor...');
        const idToken = await user.getIdToken(true);
        const r = await postJson(routes.login, { idToken, remember: !!remember });
        if (!r.ok) throw serverError(r);
        await registrarPerfilFirestore(
            user,
            user.displayName || '',
            provider
        );

        await registrarEventoNoSQL({
            tipo: 'LOGIN_' + provider.toUpperCase(),
            uid: user.uid,
            email: user.email,
            proveedor: provider,
            fecha: new Date().toISOString()
        });

        showAlert('success', 'Sesión iniciada correctamente. Redirigiendo...');
        window.location.href = r.data.redirect || '/';
    }

    async function providerSignIn(fallbackToRedirect = true) {
        if (!firebaseReady || !auth) {
            throw appError('Firebase Authentication no está configurado. Completa las credenciales FIREBASE_* en tu archivo .env.');
        }

        const provider = new firebase.auth.GoogleAuthProvider();
        provider.setCustomParameters({ prompt: 'select_account' });

        try {
            const cred = await auth.signInWithPopup(provider);
            if (!cred || !cred.user) {
                throw appError('No se recibió la información de la cuenta seleccionada.');
            }
            return await finishLogin(cred.user, true, 'google');
        } catch (err) {
            // Si la ventana emergente fue bloqueada por el navegador, intentar redirección automática
            if (err && err.code === 'auth/popup-blocked' && fallbackToRedirect) {
                showAlert('info', 'Ventana emergente bloqueada. Redirigiendo al proveedor de autenticación...');
                await auth.signInWithRedirect(provider);
                return;
            }
            throw err;
        }
    }

    async function register({ names, email, userName, password }) {
        if (!firebaseReady || !auth) {
            throw appError('Firebase Authentication no está configurado. Completa las credenciales FIREBASE_* en tu archivo .env.');
        }

        await readyPromise;
        const cred = await auth.createUserWithEmailAndPassword(email, password);
        const user = cred.user;

        const idToken = await user.getIdToken();
        const r = await postJson(routes.register, { idToken, names, user_name: userName });

        if (!r.ok) {
            // Evita dejar una cuenta huérfana en Firebase si Laravel rechazó los datos.
            try { await user.delete(); } catch (e) { console.warn('No se pudo revertir la cuenta en Firebase', e); }
            throw serverError(r);
        }

        try { await user.updateProfile({ displayName: names }); } catch (e) { /* no crítico */ }
        await user.sendEmailVerification();
        return r.data;
    }

    async function resendVerification() {
        if (!pendingUser) throw appError('Inicia sesión primero para reenviar el correo.');
        await pendingUser.sendEmailVerification();
    }

    /* ---------- Interfaz de Usuario ---------- */

    function showAlert(type, message, withResend) {
        const box = document.getElementById('firebase-alert');
        if (!box) { alert(message); return; }
        box.className = 'alert alert-' + type + ' rounded-2 py-2 px-3 small mb-3';
        box.classList.remove('d-none');
        box.textContent = message;

        if (withResend) {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'btn btn-link btn-sm p-0 ms-1 align-baseline';
            btn.textContent = 'Reenviar correo';
            btn.addEventListener('click', async () => {
                btn.disabled = true;
                try {
                    await resendVerification();
                    showAlert('success', 'Te reenviamos el enlace de verificación. Revisa tu correo.');
                } catch (e) {
                    showAlert('danger', friendly(e));
                }
            });
            box.appendChild(btn);
        }
    }

    function clearAlert() {
        const box = document.getElementById('firebase-alert');
        if (box) {
            box.textContent = '';
            box.className = 'd-none';
        }
    }

    function setBusy(button, busy, loadingText) {
        if (!button) return;
        button.disabled = busy;
        button.style.opacity = busy ? '0.7' : '';
        button.style.pointerEvents = busy ? 'none' : '';
        if (busy && loadingText) {
            button.dataset.originalText = button.innerHTML;
            button.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>' + loadingText;
        } else if (!busy && button.dataset.originalText) {
            button.innerHTML = button.dataset.originalText;
            delete button.dataset.originalText;
        }
    }

    function initLoginForm() {
        const form = document.getElementById('loginForm');
        if (!form) return;
        const idInput = form.querySelector('[name="user_name"]');
        const pwdInput = form.querySelector('[name="password"]');
        const remember = form.querySelector('[name="remember"]');
        const submit = form.querySelector('[type="submit"]');

        form.addEventListener('submit', async (e) => {
            const identifier = idInput ? idInput.value.trim() : '';

            // Si es un nombre de usuario tradicional (sin @), se procesa directamente por Laravel.
            if (!identifier.includes('@')) {
                return;
            }

            // Si Firebase no está configurado, permitir que Laravel procese la solicitud nativa tradicional.
            if (!firebaseReady) {
                return;
            }

            e.preventDefault();
            clearAlert();
            setBusy(submit, true, 'Iniciando sesión...');
            try {
                await loginWithPassword(identifier, pwdInput.value, remember && remember.checked);
            } catch (err) {
                showAlert('danger', friendly(err), err.needsVerification);
                setBusy(submit, false);
            }
        });

        const flash = new URLSearchParams(window.location.search).get('verificacion');
        if (flash === 'enviada') {
            showAlert('success', 'Cuenta creada exitosamente. Revisa tu correo y verifica tu cuenta antes de iniciar sesión.');
        }
    }

    function initRegisterForm() {
        const form = document.getElementById('registerForm');
        if (!form) return;
        const submit = form.querySelector('[type="submit"]');

        form.addEventListener('submit', async (e) => {
            // Si Firebase no está configurado, permitir el flujo de registro nativo de Laravel
            if (!firebaseReady) {
                return;
            }

            e.preventDefault();
            clearAlert();

            const names = form.querySelector('[name="names"]')?.value.trim() || '';
            const email = form.querySelector('[name="email"]')?.value.trim().toLowerCase() || '';
            const userName = form.querySelector('[name="user_name"]')?.value.trim() || '';
            const password = form.querySelector('[name="password"]')?.value || '';
            const confirm = form.querySelector('[name="password_confirmation"]')?.value || '';

            const problem = validatePassword(password, confirm);
            if (!names || !email || !userName) return showAlert('danger', 'Por favor completa todos los campos requeridos.');
            if (problem) return showAlert('danger', problem);

            setBusy(submit, true, 'Creando cuenta...');
            try {
                await register({ names, email, userName, password });
                window.location.href = (routes.loginPage || '/login') + '?verificacion=enviada';
            } catch (err) {
                showAlert('danger', friendly(err));
                setBusy(submit, false);
            }
        });
    }

    function validatePassword(p, confirm) {
        if (p.length < 8) return 'La contraseña debe tener al menos 8 caracteres.';
        if (!/[a-z]/.test(p) || !/[A-Z]/.test(p)) return 'La contraseña debe incluir letras mayúsculas y minúsculas.';
        if (!/\d/.test(p)) return 'La contraseña debe incluir al menos un número.';
        if (!/[^A-Za-z0-9]/.test(p)) return 'La contraseña debe incluir al menos un carácter especial (@, $, !, %, *, #, ?, &).';
        if (p !== confirm) return 'La confirmación de la contraseña no coincide.';
        return null;
    }

    // Botones de Google (login y registro)
    function initSsoButtons() {
        const button = document.getElementById('btn-sso-google');
        if (!button || button.dataset.bound) return;

        button.dataset.bound = '1';
        button.addEventListener('click', async (e) => {
            e.preventDefault();
            clearAlert();
            setBusy(button, true);
            try {
                await providerSignIn();
            } catch (err) {
                showAlert('danger', friendly(err));
                setBusy(button, false);
            }
        });
    }

    // Manejo del resultado de signInWithRedirect al regresar a la aplicación
    async function checkRedirectResult() {
        if (!firebaseReady || !auth) return;
        try {
            const result = await auth.getRedirectResult();
            if (result && result.user) {
                showAlert('info', 'Autenticación completada. Iniciando sesión en la plataforma...');
                await finishLogin(result.user, true, 'google');
            }
        } catch (err) {
            showAlert('danger', friendly(err));
        }
    }

    function initAll() {
        initLoginForm();
        initRegisterForm();
        initSsoButtons();
        checkRedirectResult();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAll);
    } else {
        initAll();
    }
})();

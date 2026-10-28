{{-- Configuración pública de Firebase --}}
<script>
    window.FIREBASE_CONFIG = {{ Illuminate\Support\Js::from([
        'apiKey' => config('services.firebase.api_key'),
        'authDomain' => config('services.firebase.auth_domain'),
        'projectId' => config('services.firebase.project_id'),
        'storageBucket' => config('services.firebase.storage_bucket'),
        'messagingSenderId' => config('services.firebase.messaging_sender_id'),
        'appId' => config('services.firebase.app_id'),
    ]) }};

    window.FIREBASE_ROUTES = {
        login: {{ Illuminate\Support\Js::from(route('auth.firebase.login')) }},
        register: {{ Illuminate\Support\Js::from(route('auth.firebase.register')) }},
        loginPage: {{ Illuminate\Support\Js::from(route('login')) }}
    };
</script>

<script src="https://www.gstatic.com/firebasejs/9.22.0/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.22.0/firebase-auth-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.22.0/firebase-firestore-compat.js"></script>
<script src="{{ asset('js/firebase-auth.js') }}"></script>
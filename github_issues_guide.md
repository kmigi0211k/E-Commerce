# Guía de Registro de Issues en GitHub

Para cumplir con la trazabilidad de tu **Sprint 1**, copia y pega la siguiente información en la pestaña **Issues** de tu repositorio en GitHub. Cada Issue corresponde a una **Historia de Usuario (HU)**.

---

### Issue 1: Registro de Usuarios (HU01)
**Título:** HU01 - Registro de Usuarios con Nombre de Usuario
**Descripción:** 
Como nuevo usuario del sistema, quiero registrarme usando un nombre de usuario único (user_name) para poder acceder a la gestión de productos de forma segura.

**Criterios de Aceptación:**
- El sistema debe validar que el `user_name` sea único.
- No se requiere una cuenta de correo electrónico.
- La contraseña debe confirmarse para evitar errores.

---

### Issue 2: Inicio de Sesión (HU02)
**Título:** HU02 - Autenticación de Usuarios Registrados
**Descripción:** 
Como usuario registrado, quiero iniciar sesión con mi `user_name` y contraseña para acceder a mis herramientas de gestión.

**Criterios de Aceptación:**
- El sistema debe permitir el acceso solo a usuarios con credenciales válidas.
- Redireccionar automáticamente al catálogo de productos tras el éxito.

---

### Issue 3: Gestión de Productos (HU03)
**Título:** HU03 - Creación de Productos con Imagen
**Descripción:** 
Como administrador de inventario, quiero crear un nuevo producto subiendo una imagen desde mi computadora para facilitar su identificación visual.

**Criterios de Aceptación:**
- Permitir subir formatos JPEG, PNG, JPG y GIF.
- Validar que el precio sea un número positivo.
- La imagen debe guardarse correctamente y mostrarse en el catálogo.

---

### Issue 4: Catálogo Visual de Productos (HU04)
**Título:** HU04 - Visualización de Inventario con Miniaturas
**Descripción:** 
Como usuario del sistema, quiero ver una lista elegante de todos mis productos con sus respectivas fotos para tener un control visual del stock actual.

**Criterios de Aceptación:**
- El catálogo debe ser responsivo.
- Las imágenes deben estar alineadas y con un tamaño uniforme.

---

### Issue 5: Funcionalidad del Carrito (HU05)
**Título:** HU05 - Añadir Productos al Carrito
**Descripción:** 
Como comprador, quiero poder añadir productos a un carrito de compras temporal para seleccionar mis artículos antes de finalizar el pedido.

**Criterios de Aceptación:**
- Los productos deben persistir en la sesión.
- El contador de la barra de navegación debe incrementarse.

---

### Issue 6: Gestión de Cantidades (HU06)
**Título:** HU06 - Actualizar Cantidades en el Carrito
**Descripción:** 
Como comprador, quiero cambiar las cantidades de los productos en mi carrito para ajustar mi pedido antes de la compra.

**Criterios de Aceptación:**
- Las cantidades deben actualizarse vía AJAX (sin recargar).
- El total de la compra debe recalcularse al instante.

---

### Issue 7: Perfil de Usuario (HU07)
**Título:** HU07 - Edición de Datos de Perfil
**Descripción:** 
Como usuario registrado, quiero cambiar mis datos personales o mi contraseña para mantener mi cuenta segura y actualizada.

**Criterios de Aceptación:**
- Los cambios deben validarse antes de guardarse en la base de datos.
- Confirmación visual tras actualizar con éxito.

---
*Instrucciones:* En GitHub, haz clic en **"New Issue"**, pega el Título y la Descripción, y haz clic en **"Submit new issue"** para cada punto.

# Historias de Usuario - Inventario

Este documento detalla las funcionalidades de la aplicación desde la perspectiva del usuario final.

## 🔑 Autenticación y Seguridad

### HU01: Registro de Usuario Personalizado
**Como** nuevo usuario del sistema,
**Quiero** registrarme con mis datos personales, un correo electrónico y un nombre de usuario único,
**Para** acceder a la tienda y realizar compras de forma segura.
- **Criterios de Aceptación:**
    - El sistema debe validar que el correo y el `user_name` sean únicos; el nombre de usuario admite hasta 15 caracteres.
    - El correo debe verificarse mediante un código de seis dígitos.
    - La contraseña debe confirmarse y cumplir la política de seguridad indicada en el formulario.

### HU02: Inicio de Sesión
**Como** usuario registrado,
**Quiero** iniciar sesión con mi `user_name` y contraseña,
**Para** acceder a la tienda y a las funciones permitidas por mi rol.

---

## 📦 Gestión de Productos (CRUD)

### HU03: Creación de Productos con Imagen
**Como** administrador de inventario,
**Quiero** crear un nuevo producto subiendo una imagen desde mi computadora,
**Para** que sea más fácil de identificar visualmente en la tienda.
- **Criterios de Aceptación:**
    - Permitir subir formatos JPEG, PNG, JPG y GIF.
    - Validar que el precio sea un número positivo.
    - La imagen debe guardarse correctamente en el servidor.

### HU04: Visualización de Inventario
**Como** usuario del sistema,
**Quiero** consultar el catálogo de productos disponibles con sus imágenes, precios y detalles,
**Para** encontrar los productos que me interesan.

---

## 🛒 Carrito de Compras

### HU05: Añadir al Carrito
**Como** comprador,
**Quiero** hacer clic en "Añadir" desde la lista de productos,
**Para** ir seleccionando los artículos que deseo comprar.
- **Criterios de Aceptación:**
    - El carrito debe persistir durante mi sesión.
    - El contador en la barra de navegación debe actualizarse al instante.

### HU06: Gestión del Carrito
**Como** comprador,
**Quiero** ver el total de mi compra y poder cambiar las cantidades,
**Para** ajustar mi pedido antes de finalizar.

---

## 👤 Perfil y Cuenta

### HU07: Edición de Perfil
**Como** usuario registrado,
**Quiero** cambiar mi nombre de usuario o mi contraseña,
**Para** mantener mis datos actualizados o por seguridad.

---

## 🧾 Proceso de Venta (Solución Sprint 2)

### HU08: Finalización de Compra y Descuento de Inventario
**Como** comprador,
**Quiero** confirmar mi pedido desde una pantalla de checkout,
**Para** concretar mi compra y que el sistema descuente automáticamente los productos del stock.
- **Criterios de Aceptación:**
    - Se debe generar un registro de pedido en la base de datos (Orders/OrderItems).
    - El stock de cada producto debe disminuir según la cantidad comprada.
    - Se debe mostrar un resumen final (recibo) tras una compra exitosa.
    - El carrito debe vaciarse automáticamente al terminar el proceso.

---

## 🤝 Mercado Solidario (Solución Sprint 4)

### HU09: Registro de Voluntariado por Producto
**Como** miembro de la comunidad "12 de Octubre",
**Quiero** postularme a un producto indicando el tipo de ayuda, las horas y mis datos de contacto,
**Para** que el administrador revise mi solicitud de voluntariado.
- **Criterios de Aceptación:**
    - El usuario debe especificar el tipo de ayuda, las horas comprometidas y un teléfono de contacto.
    - La solicitud se registra en estado "pendiente" y el usuario puede consultar su estado.
    - El administrador puede revisar y actualizar el estado de la solicitud.

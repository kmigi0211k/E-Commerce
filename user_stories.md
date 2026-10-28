# Historias de Usuario - ProductosPro Laravel

Este documento detalla las funcionalidades de la aplicación desde la perspectiva del usuario final.

## 🔑 Autenticación y Seguridad

### HU01: Registro de Usuario Personalizado
**Como** nuevo usuario del sistema,
**Quiero** registrarme usando un nombre de usuario único de máximo 15 caracteres,
**Para** poder acceder a la gestión de productos de forma segura.
- **Criterios de Aceptación:**
    - El sistema debe validar que el `user_name` sea único.
    - No se requiere una cuenta de correo electrónico.
    - La contraseña debe confirmarse para evitar errores.

### HU02: Inicio de Sesión
**Como** usuario registrado,
**Quiero** iniciar sesión con mi `user_name` y contraseña,
**Para** acceder a mis herramientas de gestión.

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
**Quiero** ver una lista elegante de todos mis productos con miniaturas de sus fotos,
**Para** tener un control visual de mi stock actual.

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
**Quiero** comprometerme a realizar horas de servicio social,
**Para** obtener un producto sin costo monetario y contribuir al barrio.
- **Criterios de Aceptación:**
    - El usuario debe especificar el tipo de ayuda y las horas comprometidas.
    - El sistema debe redirigir al usuario a WhatsApp para coordinar con el administrador.
    - Se debe crear un registro de voluntariado en estado "pendiente".



# Documentación de Páginas y Componentes

Este documento explica de manera clara y concisa la función y el funcionamiento de las páginas y componentes principales del proyecto "proyectoFinalManolo". Está pensado para presentar el proyecto a un público técnico no necesariamente experto.

---

## `index.php`

<div align="center">index.php</div>

- Propósito: Página principal del sitio que muestra el listado de productos y un carrusel (hero) con productos destacados.
- Flujo principal:
  - Carga `controllers/ProductoController.php` para obtener la lista de productos.
  - Incluye `includes/header.php` y `includes/footer.php`.
  - Si el usuario realiza una búsqueda (`?buscar=...`), muestra resultados filtrados.
  - Cada producto enlaza a `views/producto.php?id=...`.
- Notas: El markup contiene secciones para búsqueda, grid de productos y hero/carousel. Usa `assets/css/style.css` y `assets/js/main.js`.

---

## `includes/header.php`

<div align="center">header.php</div>

- Propósito: Cabecera y navegación global del sitio.
- Contenido:
  - Meta tags, título dinámico (`$page_title`) y enlace al CSS `assets/css/style.css`.
  - Menú con enlaces a `index.php`, `views/quienes_somos.php`, `views/contacto.php`, y enlaces a `login/register` o perfil/administración según sesión.
- Comportamiento:
  - Inicia sesión si es necesario (`session_start()`), determina si el usuario es admin y muestra enlaces acorde.

---

## `includes/footer.php`

<div align="center">footer.php</div>

- Propósito: Pie de página común y carga de scripts.
- Contenido:
  - Texto de copyright y carga de `assets/js/main.js`.

---

## `views/carrito.php`

<div align="center">carrito.php</div>

- Propósito: Mostrar los productos que el usuario agregó al carrito.
- Flujo:
  - Requiere `controllers/CarritoController.php` para obtener el carrito del usuario logueado.
  - Muestra lista de items con cantidad, subtotal y opciones para actualizar/eliminar.
  - Muestra el total y un botón para "Proceder al Pago" que ahora apunta a `views/checkout.php`.
- Notas:
  - Si el carrito está vacío, muestra un link para volver a `index.php`.

---

## `views/checkout.php`

<div align="center">checkout.php</div>

- Propósito: Capturar la dirección de envío antes de ir al pago.
- Campos capturados:
  - `ciudad`, `departamento` (o estado), `municipio`, `direccion` (completa), `descripcion` (referencia del lugar).
- Flujo:
  - Si el usuario envía el formulario (`POST`), los datos se guardan en `$_SESSION['envio']` y redirige a `views/pago.php`.
  - Si el carrito está vacío, redirige a `views/carrito.php`.
- Relevancia: Permite que los datos de envío estén asociados al pago y, posteriormente, se guarden en la tabla `pagos`.

---

## `views/pago.php`

<div align="center">pago.php</div>

- Propósito: Formulario de pago (básico) donde el usuario ingresa datos de tarjeta y elige método de pago.
- Funcionalidad importante:
  - Muestra resumen del pedido (items y total).
  - Formulario con campos: `nombre_titular`, `numero_tarjeta`, `fecha_expiracion`, `cvv`, y selección de método mediante botones (tarjeta crédito / débito).
  - Formatea automáticamente número de tarjeta (grupos de 4) y fecha (MM/YY) con `assets/js/main.js`.
  - Envía `POST` al mismo archivo; `controllers/PagoController.php` procesa la petición.
  - Antes de crear el pago, `PagoController` toma los datos de envío desde `$_SESSION['envio']` (llenados en `checkout.php`) y los añade al registro del pago.
  - Tras procesar (en este entorno de demo), redirige a `views/pago_no_disponible.php` y limpia el carrito.
- Notas de seguridad: En este demo, se enmascara el número de tarjeta y no se guarda el CVV real.

---

## `views/pago_no_disponible.php`

<div align="center">pago_no_disponible.php</div>

- Propósito: Mostrar un mensaje decorado indicando que el sistema de pago no está disponible (usado en demo).
- Contenido:
  - Mensaje agradable con icono, explicación, botones para volver al carrito o al inicio, y un código de estado.
- Uso: Redirección desde el controlador de pago después de simular el procesamiento.

---

## `views/cambiar_contrasena.php` y `admin/cambiar_contrasena.php`

<div align="center">views/cambiar_contrasena.php / admin/cambiar_contrasena.php</div>

- Propósito: Permitir al usuario (o admin en su vista) cambiar su contraseña.
- Flujo:
  - Formulario con `contrasena_actual`, `contrasena_nueva`, `confirmar_contrasena`.
  - `controllers/CambiarContrasenaController.php` valida los datos, verifica la contraseña actual con `Usuario::obtenerPorId()` y usa `password_hash()` para guardar la nueva.
  - Muestra mensajes de error o éxito según corresponda.

---

## `views/perfil.php`

<div align="center">perfil.php</div>

- Propósito: Mostrar información del usuario logueado (nombre, email y tipo) y acciones rápidas.
- Acciones disponibles:
  - Botones para `Cambiar Contraseña` y `Historial de Pagos`.

---

## `views/historial_pagos.php`

<div align="center">historial_pagos.php</div>

- Propósito: Mostrar al usuario su historial de pagos (transacciones realizadas).
- Flujo:
  - Llama a `controllers/PagoController.php::obtenerHistorial($usuario_id)` para obtener los registros.
  - Muestra tabla con ID, fecha, monto, método, tarjeta (enmascarada) y estado.

---

## `views/producto.php`

<div align="center">producto.php</div>

- Propósito: Mostrar la página de detalle de un producto específico.
- Flujo:
  - Recibe `?id=...`, obtiene los datos del producto desde `controllers/ProductoController.php` y muestra información, imagen y botón para agregar al carrito.
  - Si el usuario está logueado, permite agregar cantidad al carrito (`controllers/carrito_action.php`).

---

## `views/login.php` / `views/register.php`

<div align="center">login.php / register.php</div>

- Propósito: Permitir autenticación y registro de usuarios.
- `controllers/AuthController.php` maneja **login**, **registro** y **logout**.
- Notas: Las contraseñas se guardan con `password_hash()` y la verificación con `password_verify()`.

---

## `admin/index.php` y `admin/pedidos.php`

<div align="center">admin/index.php</div>

- `admin/index.php` - Panel de administración con gestión de productos y acceso a acciones: añadir producto, editar misión/visión, cambiar contraseña, ver pedidos.

<div align="center">admin/pedidos.php</div>

- `admin/pedidos.php` - Muestra todos los pedidos (pagos) en secciones separadas por estado: "Pagos Realizados", "Pagos Pendientes" y "Pagos Rechazados".
- Flujo:
  - Usa `controllers/PagoController.php::obtenerHistorial(null)` para obtener todos los pagos (en admin se listan todos los usuarios).
  - Divide y muestra por estado.
- Complemento: Se puede ampliar para permitir acciones (marcar como enviado, rechazar, ver detalles).

---

## Controladores (carpeta `controllers/`)

- `AuthController.php`:
  - Login, registro y logout. Setea `$_SESSION` con `usuario_id`, `usuario_nombre`, `usuario_tipo`.

- `ProductoController.php`:
  - Funciones para listar productos, buscar por nombre/ID, crear/editar/eliminar (usado por admin).

- `CarritoController.php`:
  - Maneja obtención del carrito del usuario, agregar/eliminar/actualizar cantidades. Interactúa con `models/Carrito.php`.

- `PagoController.php`:
  - Procesa los pagos: valida formulario, toma datos de envío desde `$_SESSION['envio']`, enmascara tarjeta, crea registro en `pagos` usando `models/Pago.php`.
  - Incluye `verificarTabla()` que crea la tabla `pagos` si no existe y añade columnas de envío si hacen falta.
  - `obtenerHistorial($usuario_id)` devuelve pagos para un usuario (o todos si `null` en admin).

- `CambiarContrasenaController.php`:
  - Valida y solicita a `models/Usuario.php` cambiar la contraseña del usuario autenticado.

---

## Modelos (carpeta `models/`)

- `Usuario.php`:
  - `registrar()`, `login()`, `existeEmail()`, `obtenerPorId()` y la lógica para cambiar contraseña (`cambiarContrasena()`).
  - Trabaja con `config/database.php` para obtener la conexión PDO.

- `Producto.php`:
  - Métodos CRUD para productos.

- `Carrito.php`:
  - `agregarProducto()`, `obtenerCarritoUsuario()`, `eliminarProducto()`, `actualizarCantidad()`, `limpiar()`.

- `Pago.php`:
  - Almacena la estructura de pago y el método `crear()` guarda el pago junto con la dirección de envío en la tabla `pagos`.

---

## Configuración y Migraciones

- `config/database.php`:
  - Encapsula la conexión PDO a la base de datos.

- `install.php`:
  - Script de instalación que crea las tablas iniciales (usuarios, productos, carrito, mision_vision, etc.) y crea un admin por defecto.

- `config/migrate_pagos.sql` y `create_pagos_table.php`:
  - SQL y script PHP para crear la tabla `pagos` (incluye campos de envío). Útil si la tabla no existe.

---

## Assets

- `assets/css/style.css`:
  - Estilos globales del sitio, incluyendo estilos para perfil, carrito, página de pago, botones de método de pago y la página de pago no disponible.

- `assets/js/main.js`:
  - Lógica del carousel, interactions y formateo: formatea número de tarjeta (grupos de 4), fecha de expiración (MM/YY) y sanitiza CVV.

---

## Flujo completo de compra (resumen para presentación)

1. Usuario navega `index.php` y añade productos al carrito.
2. Desde `carrito.php`, clic en "Proceder al Pago" → va a `checkout.php` para ingresar dirección de envío.
3. `checkout.php` guarda la dirección en `$_SESSION['envio']` y redirige a `pago.php`.
4. En `pago.php` el usuario ingresa datos de tarjeta (formato automático) y selecciona método (botones).
5. `PagoController` valida el formulario, recoge `$_SESSION['envio']`, enmascara tarjeta, guarda registro en `pagos` y limpia el carrito.
6. En este demo, el flujo termina mostrando `pago_no_disponible.php` (mensaje estético). En producción, aquí se integraría con un gateway (Stripe, PayPal, etc.).

---

## Consideraciones de seguridad y mejoras sugeridas (para la presentación)

- Nunca guardar CVV en producción; en el demo se enmascara y se almacena `***`.
- Integrar un gateway de pago real y usar HTTPS en producción.
- Validación y sanitización adicional del lado servidor para todos los campos.
- Añadir logs y herramientas de auditoría para pagos.
- Añadir gestión de estados en `admin/pedidos.php` para marcar como "enviado", "reembolsado", "fallido".

---

Si quieres, puedo también:
- Generar una diapositiva tipo resumen con los puntos clave.
- Preparar ejemplos de comandos para crear la base de datos y correr la demo localmente.

> Archivo generado: `DOCUMENTACION_PAGINAS.md` en la raíz del proyecto.


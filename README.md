# Tienda de Dispositivos Móviles

Sitio web para venta de dispositivos móviles desarrollado con PHP, MySQL, HTML, CSS y JavaScript siguiendo el patrón MVC (Modelo-Vista-Controlador).

## Características

- ✅ Sistema de autenticación (Login y Registro)
- ✅ Login especial para administradores
- ✅ Panel de administración para gestionar productos (CRUD)
- ✅ Carrocel automático en la página principal
- ✅ Página de detalles de productos
- ✅ Sistema de carrito de compras por usuario
- ✅ Sección "Quiénes Somos" (Misión y Visión)
- ✅ Formulario de contacto que guarda en archivo TXT
- ✅ Diseño oscuro con colores negro y dorado

## Instalación

1. **Configurar la base de datos:**
   - Importa el archivo `config/db_setup.sql` en MySQL/phpMyAdmin
   - O ejecuta el script SQL manualmente

2. **Configurar la conexión:**
   - Edita `config/database.php` si necesitas cambiar las credenciales de la base de datos

3. **Crear directorios necesarios:**
   - `assets/img/` - Para las imágenes de productos
   - `data/` - Para el archivo de contactos

4. **Permisos:**
   - Asegúrate de que los directorios `assets/img/` y `data/` tengan permisos de escritura

## Credenciales por defecto

**Administrador:**
- Email: admin@admin.com
- Contraseña: admin123

## Estructura del Proyecto

```
IA/
├── admin/              # Panel de administración
├── assets/
│   ├── css/           # Estilos
│   ├── js/            # JavaScript
│   └── img/           # Imágenes de productos
├── config/            # Configuración (base de datos)
├── controllers/       # Controladores MVC
├── data/              # Archivos de datos (contactos.txt)
├── includes/          # Header y Footer
├── models/            # Modelos MVC
└── views/             # Vistas MVC
```

## Tecnologías Utilizadas

- PHP 7.4+
- MySQL
- HTML5
- CSS3
- JavaScript (Vanilla)
- Patrón MVC

## Funcionalidades del Administrador

- Añadir nuevos productos
- Editar productos existentes
- Eliminar productos
- Subir imágenes de productos
- Ver todos los productos en una tabla

## Funcionalidades del Usuario

- Navegar productos sin necesidad de registro
- Registrarse e iniciar sesión
- Agregar productos al carrito
- Ver detalles de productos
- Contactar a través del formulario

## Notas

- El carrito se guarda en la base de datos y está asociado a cada usuario
- Los contactos se guardan en `data/contactos.txt`
- Las imágenes se suben a `assets/img/`



# tiendaCelulares

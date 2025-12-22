# Guía Completa: Subir el Proyecto a Internet

Este documento explica paso a paso cómo publicar tu tienda de dispositivos móviles en internet.

---

## 📋 Requisitos Previos

Antes de empezar, necesitas:

1. **Dominio** - Contrata un dominio (ej: mitienda.com)
2. **Hosting** - Contrata un servicio de hosting con soporte para:
   - PHP 7.4 o superior
   - MySQL 5.7 o superior
   - Acceso a base de datos
   - Panel de control (cPanel, Plesk, etc.)
3. **FTP/SFTP** - Cliente para subir archivos (FileZilla, WinSCP)
4. **Credenciales** - Usuario y contraseña del hosting

---

## 🔍 Paso 1: Preparar el Proyecto Localmente

### 1.1 Limpiar y optimizar

Elimina archivos innecesarios antes de subir:

```bash
# Elimina archivos de desarrollo
- create_all_tables.php (mantén una copia de seguridad)
- DOCUMENTACION_PAGINAS.md (opcional)
- GUIA_DEPLOYMENT.md (este archivo, opcional)
```

### 1.2 Actualizar configuración

Edita `config/database.php` para que sea flexible:

```php
<?php
class Database {
    private $host;
    private $username;
    private $password;
    private $db_name;
    private $conn;

    public function __construct() {
        // Detectar entorno (local o producción)
        if($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === 'localhost:80') {
            // Configuración local
            $this->host = 'localhost';
            $this->username = 'root';
            $this->password = '';
            $this->db_name = 'tienda_moviles';
        } else {
            // Configuración producción (edita con tus credenciales del hosting)
            $this->host = 'tu_host_mysql';
            $this->username = 'tu_usuario_db';
            $this->password = 'tu_contraseña_db';
            $this->db_name = 'tu_base_datos';
        }
        $this->connect();
    }

    private function connect() {
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4",
                $this->username,
                $this->password,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        } catch(PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->conn;
    }
}
?>
```

### 1.3 Verificar directorios

Asegúrate que estos directorios existan y tengan permisos:

```
/uploads/          # Para logos subidos
/assets/img/       # Para imágenes de productos
/data/             # Para contactos.txt
```

### 1.4 Crear `.htaccess` (opcional pero recomendado)

Crea un archivo `.htaccess` en la raíz del proyecto para mejorar seguridad:

```apache
# Deniega acceso a archivos sensibles
<FilesMatch "\.(env|sql|md|txt)$">
    Deny from all
</FilesMatch>

# Permite que las solicitudes vayan a index.php (si usas URL rewriting)
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]
</IfModule>

# Habilita GZIP
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript application/javascript
</IfModule>

# Añade headers de seguridad
<IfModule mod_headers.c>
    Header set X-UA-Compatible "IE=edge"
    Header set X-Content-Type-Options "nosniff"
    Header set X-Frame-Options "SAMEORIGIN"
</IfModule>
```

---

## 🚀 TUTORIAL RÁPIDO: USAR INFINITYFREE (100% GRATIS)

### ¿Por qué InfinityFree?

- **Totalmente gratis** - Sin tarjeta de crédito
- **Sin anuncios** - Si sigues sus términos
- **cPanel incluido** - Interfaz fácil
- **MySQL gratis** - Base de datos incluida
- **Dominio gratis** - Tu sitio en mitienda.infinityfree.app

### Pasos detallados

#### 1. Registrarse en InfinityFree

1. Ve a https://www.infinityfree.net/
2. Haz clic en **"Sign Up"**
3. Completa el formulario:
   - **Email:** Tu correo
   - **Contraseña:** Crea una segura
4. Haz clic en **"Create Account"**
5. Confirma tu email

#### 2. Crear cuenta de hosting

1. Inicia sesión en https://www.infinityfree.net/
2. Ve a **"Create Account"**
3. Elige tu nombre de dominio (ej: `mitienda`)
   - Verás: `mitienda.infinityfree.app`
4. Selecciona el plan **FREE**
5. Haz clic en **"Create Account"**

#### 3. Acceder a cPanel

1. Después de crear la cuenta, ve a tu panel principal
2. Haz clic en **"Go to Account"** o **"cPanel"**
3. ¡Estás dentro de cPanel!

#### 4. Crear base de datos MySQL

1. En cPanel, busca **"MySQL Databases"** (o **"Bases de datos MySQL"**)
2. Haz clic en él
3. En **"Create New Database"**, escribe:
   - **Database Name:** `tienda_moviles`
   - Haz clic en **"Create Database"**
4. Verás un mensaje de éxito

#### 5. Crear usuario MySQL

1. En la misma página, ve a **"MySQL Users"** (o **"Usuarios MySQL"**)
2. Crea un nuevo usuario:
   - **Username:** `tienda_user`
   - **Password:** Crea una contraseña fuerte
   - Haz clic en **"Create User"**
3. Ahora ve a **"Add User To Database"**
4. Selecciona:
   - **User:** `tienda_user`
   - **Database:** `tienda_moviles`
   - Marca todas las opciones de permisos
   - Haz clic en **"Add User"**

#### 6. Subir archivos

**Opción A: Usar File Manager (más fácil)**

1. En cPanel, busca **"File Manager"**
2. Haz clic en él
3. Navega a la carpeta `public_html`
4. Haz clic derecho en el área vacía > **"Upload"**
5. Selecciona TODOS los archivos de tu carpeta `proyectoFinalManolo`
6. Sube los archivos
7. Descomprime si es necesario

**Opción B: Usar FTP (más rápido para muchos archivos)**

1. En cPanel, busca **"FTP Accounts"** (o **"Cuentas FTP"**)
2. Crea una nueva cuenta FTP:
   - **FTP Account:** `upload`
   - **Password:** Contraseña fuerte
3. Haz clic en **"Create FTP Account"**
4. Descarga FileZilla: https://filezilla-project.org/
5. En FileZilla:
   - **Host:** Tu dominio de FTP (ej: `ftp.mitienda.infinityfree.app`)
   - **Username:** `upload`
   - **Password:** La que creaste
   - **Puerto:** 21
6. Haz clic en **"Quickconnect"**
7. En la derecha, navega a `public_html`
8. En la izquierda, navega a tu carpeta `proyectoFinalManolo`
9. Selecciona todos los archivos y carpetas
10. Arrastra hacia la derecha para subir

#### 7. Configurar la aplicación

1. En cPanel, ve a **"File Manager"**
2. Navega a `/public_html/config/`
3. Haz clic derecho en `database.php` > **"Edit"**
4. Busca estas líneas y actualiza:

```php
$this->host = 'localhost';
$this->username = 'tu_usuario_infinityfree_usuario_tienda_user'; // Así te lo da InfinityFree
$this->password = 'tu_contraseña_mysql';
$this->db_name = 'tu_usuario_infinityfree_tienda_moviles'; // Así te lo da InfinityFree
```

> **Nota:** InfinityFree añade prefijos a las bases de datos. Verifica los nombres exactos en cPanel.

5. Haz clic en **"Save"**

#### 8. Crear las tablas

1. Abre en tu navegador: `https://mitienda.infinityfree.app/create_all_tables.php`
2. Espera a que se ejecute
3. Verás mensajes de éxito
4. **IMPORTANTE:** Elimina `create_all_tables.php` después de ejecutarlo
   - Ve a File Manager > public_html
   - Haz clic derecho en `create_all_tables.php` > **"Delete"**

#### 9. Prueba tu sitio

Abre en el navegador:

- **Inicio:** https://mitienda.infinityfree.app/
- **Login:** https://mitienda.infinityfree.app/views/login.php
- **Admin:** https://mitienda.infinityfree.app/admin/index.php
  - Email: `admin@admin.com`
  - Contraseña: `admin123`

#### 10. Medidas de seguridad finales

1. Cambia la contraseña del admin:
   - Inicia sesión > Perfil > Cambiar Contraseña
2. Elimina archivos innecesarios en File Manager:
   - `create_all_tables.php`
   - `create_pagos_table.php`
   - `DOCUMENTACION_PAGINAS.md`
   - `GUIA_DEPLOYMENT.md`
   - `install.php` (si existe)

---

### 🆓 OPCIONES COMPLETAMENTE GRATUITAS (Recomendado)

#### **1. Render + Railway (Mejor opción)**

**Render:** https://render.com/

- ✅ **Hosting PHP/MySQL gratis**
- ✅ **Base de datos gratis**
- ✅ **SSL automático**
- ✅ **Dominio gratuito** (ej: mitienda.onrender.com)
- ⚠️ Se detiene después de 15 minutos de inactividad (sin problema para testing)

**Ventajas:**
- Muy fácil de usar
- Sin tarjeta de crédito necesaria
- Perfecto para aprender

**Desventajas:**
- Limitaciones de almacenamiento
- Se detiene si no hay actividad

**Pasos con Render:**
1. Crea cuenta en https://render.com/
2. Crea un nuevo Web Service
3. Conecta tu repositorio GitHub
4. Selecciona PHP como lenguaje
5. La base de datos se crea automáticamente
6. Despliega con un clic

#### **2. InfinityFree (Alternativa popular)**

**InfinityFree:** https://www.infinityfree.net/

- ✅ **Hosting PHP/MySQL completamente gratis**
- ✅ **1 GB almacenamiento**
- ✅ **Dominio gratis** (ej: mitienda.infinityfree.app)
- ✅ **Sin anuncios**
- ✅ **cPanel incluido**

**Pasos:**
1. Regístrate en https://www.infinityfree.net/
2. Crea una nueva cuenta de hosting
3. Acepta los términos (no hay publicidad si usas correctamente)
4. Accede a cPanel
5. Crea base de datos MySQL
6. Sube archivos vía FTP

#### **3. 000webhost (Por Hostinger)**

**000webhost:** https://www.000webhost.com/

- ✅ **Hosting PHP/MySQL gratis**
- ✅ **Dominio gratis** (ej: mitienda.000webhostapp.com)
- ✅ **cPanel**
- ✅ **Soporte 24/7**

#### **4. Heroku (Para Python/Node, NO recomendado para PHP)**

> ⚠️ Nota: Heroku descontinuó el plan gratuito en noviembre 2022

---

### 💰 Opciones PAGADAS (Si prefieres estable)

#### **Hosting Compartido Barato**
- **Bluehost** - $2.95/mes, soporte 24/7
- **SiteGround** - $3.99/mes, excelente soporte
- **HostGator** - $2.75/mes, muy popular
- **GoDaddy** - Incluye dominio gratis primer año
- **Hostinger** - Muy económico, buena velocidad

**Ventajas:**
- Muy barato
- Manejo automático de actualizaciones
- Panel de control fácil (cPanel)

**Desventajas:**
- Recursos compartidos
- Menos control

#### **VPS (Virtual Private Server)**
- **DigitalOcean** - $5/mes
- **Linode** - $5/mes
- **AWS** - Gratis el primer año

**Ventajas:**
- Más control y velocidad
- Escalable

**Desventajas:**
- Requiere conocimientos de Linux
- Más caro

---

### 🎯 RECOMENDACIÓN

**👉 Para comenzar SIN GASTAR:**
- **InfinityFree** o **000webhost** (Más simple, cPanel incluido)
- **Render** (Más moderno, integración con GitHub)

**👉 Para producción (si crece):**
- **Bluehost** ($2.95/mes) - Mejor relación precio/calidad
- **SiteGround** ($3.99/mes) - Mejor soporte

---

### Registrar dominio personalizado (Opcional)

Si quieres usar `mitienda.com` en lugar del dominio gratis:

- **Namecheap** - Desde $0.98/año (muy barato)
- **GoDaddy** - Primer año gratis si compras hosting
- **DuckDNS** - Totalmente gratis (para testing)

> **Consejo:** Los dominios gratuitos del hosting son suficientes para empezar.

---

## 📦 Paso 3: Subir Archivos al Hosting

### 3.1 Conectar vía FTP/SFTP

**Usando FileZilla (gratuito):**

1. Descarga FileZilla: https://filezilla-project.org/
2. Abre FileZilla
3. Ve a `Archivo > Gestor de sitios`
4. Crea un nuevo sitio:
   - **Host:** `tu_dominio.com` o `ftp.tu_dominio.com`
   - **Puerto:** 21 (FTP) o 22 (SFTP)
   - **Usuario:** Tu usuario FTP del hosting
   - **Contraseña:** Tu contraseña FTP
5. Haz clic en "Conectar"

### 3.2 Subir archivos

1. En el lado izquierdo (local), navega a tu carpeta `proyectoFinalManolo`
2. En el lado derecho (servidor), navega a:
   - Si es hosting compartido: `/public_html/` o `/www/`
3. Selecciona TODOS los archivos y carpetas de tu proyecto
4. Haz clic derecho y selecciona "Subir"
5. **Espera a que finalice** (puede tardar varios minutos)

**Estructura esperada en el servidor:**

```
/public_html/
├── admin/
├── assets/
├── config/
├── controllers/
├── data/
├── includes/
├── models/
├── uploads/
├── views/
├── index.php
├── .htaccess
└── ...otros archivos
```

---

## 🗄️ Paso 4: Crear Base de Datos en el Hosting

### 4.1 Acceder a phpMyAdmin

1. Ingresa al panel de control de tu hosting (cPanel, Plesk, etc.)
2. Busca **"phpMyAdmin"** o **"Bases de Datos MySQL"**
3. Abre phpMyAdmin

### 4.2 Crear base de datos

1. Haz clic en **"Nueva"** o **"Crear base de datos"**
2. Nombre: `tienda_moviles` (o el que prefieras)
3. Colación: `utf8mb4_unicode_ci`
4. Haz clic en **"Crear"**

### 4.3 Crear usuario de base de datos

1. Ve a **"Cuentas de usuario"** o **"Usuarios"**
2. Haz clic en **"Agregar usuario"**
3. **Usuario:** `tienda_user` (ej)
4. **Contraseña:** Genera una contraseña fuerte
5. Haz clic en **"Crear"**

### 4.4 Asignar permisos

1. En la tabla de usuarios, busca el usuario que creaste
2. Haz clic en **"Cambiar permisos"**
3. Selecciona la base de datos `tienda_moviles`
4. Marca **"Seleccionar todo"**
5. Haz clic en **"Continuar"**

### 4.5 Crear tablas

**Opción A: Subir script PHP (recomendado)**

1. Sube `create_all_tables.php` a `/public_html/`
2. Abre en el navegador: `https://tu_dominio.com/create_all_tables.php`
3. Ejecuta el script
4. **Elimina `create_all_tables.php` después de ejecutarlo**

**Opción B: Importar SQL manualmente**

1. En phpMyAdmin, selecciona la base de datos `tienda_moviles`
2. Ve a la pestaña **"Importar"**
3. Copia el contenido de `config/db_setup.sql` (o usa `create_all_tables.php`)
4. Pega en el editor de SQL
5. Haz clic en **"Continuar"**

---

## ⚙️ Paso 5: Configurar la Aplicación

### 5.1 Actualizar `config/database.php`

En tu panel de hosting, edita `config/database.php`:

1. Ve a **"Administrador de archivos"** en cPanel
2. Navega a `/public_html/config/`
3. Haz clic derecho en `database.php` y selecciona **"Editar"**
4. Actualiza las credenciales:

```php
$this->host = 'localhost'; // O el servidor MySQL del hosting
$this->username = 'tienda_user'; // Tu usuario MySQL
$this->password = 'tu_contraseña_segura'; // Tu contraseña
$this->db_name = 'tienda_moviles'; // Tu base de datos
```

5. Haz clic en **"Guardar"**

### 5.2 Crear directorios si no existen

En el Administrador de archivos:

```
Crea si no existen:
- /data/           (para contactos.txt)
- /uploads/        (para logos)
```

### 5.3 Configurar permisos

Para cada directorio, haz clic derecho y selecciona **"Permisos":**

```
/uploads/      → 755
/data/         → 755
/assets/img/   → 755
```

---

## 🔒 Paso 6: Medidas de Seguridad

### 6.1 Cambiar contraseña del admin

1. Abre `https://tu_dominio.com/views/login.php`
2. Inicia sesión con: `admin@admin.com` / `admin123`
3. Ve a tu perfil y cambia la contraseña inmediatamente

### 6.2 Eliminar archivos de instalación

En el Administrador de archivos, **elimina:**

```
- create_all_tables.php
- create_pagos_table.php (si existe)
- DOCUMENTACION_PAGINAS.md
- GUIA_DEPLOYMENT.md
- install.php (si existe)
```

### 6.3 Proteger directorios sensibles

Crea un archivo `.htaccess` en `/config/`:

```apache
<FilesMatch "\.(env|sql)$">
    Deny from all
</FilesMatch>
```

### 6.4 Activar HTTPS (SSL)

Casi todos los hosting ofrecen SSL gratuito (Let's Encrypt):

1. En cPanel, busca **"SSL/TLS"**
2. Haz clic en **"Instalar certificado SSL"**
3. Selecciona **"Let's Encrypt"**
4. Confirma la instalación

---

## 🧪 Paso 7: Pruebas Finales

Abre tu navegador y verifica:

```
✅ Página principal carga correctamente
   https://tu_dominio.com/

✅ Login funciona
   https://tu_dominio.com/views/login.php

✅ Registro funciona
   https://tu_dominio.com/views/register.php

✅ Panel admin funciona
   https://tu_dominio.com/admin/index.php
   (Inicia sesión como admin)

✅ Carrito funciona
   https://tu_dominio.com/views/carrito.php
   (Después de agregar productos)

✅ Contacto funciona
   https://tu_dominio.com/views/contacto.php

✅ Logo personalizado aparece
   En header y footer
```

Si encuentras errores, revisa los logs:

1. Ve a cPanel > **"Error Log"**
2. Busca mensajes de error recientes
3. Corrige los problemas

---

## 🚀 Paso 8: Optimizaciones Recomendadas

### 8.1 Caché

Añade a `.htaccess`:

```apache
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/png "access plus 30 days"
    ExpiresByType image/jpg "access plus 30 days"
    ExpiresByType text/css "access plus 7 days"
    ExpiresByType text/javascript "access plus 7 days"
</IfModule>
```

### 8.2 Comprimir imágenes

Las imágenes grandes ralentizan el sitio. Usa:

- **TinyPNG** - https://tinypng.com/
- **ImageOptim** - https://imageoptim.com/
- **Compressor.io** - https://compressor.io/

### 8.3 Usar CDN (opcional)

Para servir archivos estáticos más rápido:

- **Cloudflare** - Gratuito, muy popular
- **AWS CloudFront** - Pago, muy potente

---

## 🔧 Solución de Problemas Comunes

### Problemas en InfinityFree específicamente

#### Error: "Base de datos no encontrada"

**Causa:** Los nombres de usuario y base de datos tienen prefijos en InfinityFree

**Solución:**
1. En cPanel de InfinityFree, ve a **"MySQL Databases"**
2. Copia el nombre exacto de tu base de datos (ej: `tuusuario_tienda_moviles`)
3. Copia el nombre exacto del usuario (ej: `tuusuario_tienda_user`)
4. Actualiza `config/database.php` con estos nombres exactos
5. Verifica que la contraseña sea la que creaste

#### Error: "No se puede conectar a la base de datos"

**Soluciones en orden:**
1. Verifica que hayas añadido el usuario a la base de datos en cPanel
2. Comprueba que las credenciales en `database.php` sean exactas
3. Verifica que MySQL esté activo en cPanel
4. Intenta acceder a phpMyAdmin desde cPanel para confirmar que funciona

#### Error: "No se puede guardar archivo" (contactos.txt)

**Causa:** Permisos incorrectos en `/data/`

**Solución:**
1. Ve a cPanel > **"File Manager"**
2. Navega a la carpeta `/data/`
3. Haz clic derecho > **"Permissions"**
4. Establece a `755` (o `rwxr-xr-x`)
5. Marca **"Apply to all"** si existe

#### El sitio está muy lento

**Causa:** InfinityFree tiene limitaciones de recursos

**Soluciones:**
1. Optimiza las imágenes (máximo 500KB cada una)
2. Elimina archivos innecesarios
3. No subas más de 100 productos al principio

#### El logo no aparece después de subir

**Causa:** Permisos en `/uploads/`

**Solución:**
1. En cPanel > **"File Manager"**
2. Haz clic derecho en `/uploads/` > **"Permissions"**
3. Establece a `755`

### Error: "No se puede conectar a la base de datos"

**Causas posibles:**
1. Credenciales incorrectas en `config/database.php`
2. La base de datos no fue creada
3. El usuario de MySQL no tiene permisos

**Solución:**
1. Verifica las credenciales en phpMyAdmin
2. Asegúrate que la base de datos y el usuario existan
3. Verifica los permisos del usuario

### Error: "No se puede guardar archivo" (contactos.txt)

**Causa:** Permisos incorrectos en `/data/`

**Solución:**
1. Ve a cPanel > Administrador de archivos
2. Haz clic derecho en `/data/` > Permisos
3. Establece a `755`

### Imágenes no cargan

**Causa:** Ruta incorrecta o permisos incorrectos en `/uploads/` o `/assets/img/`

**Solución:**
1. Verifica que los directorios existan
2. Establece permisos a `755`
3. Verifica que la ruta en `src=""` sea correcta (ej: `/proyectoFinalManolo/assets/img/...`)

### "Acceso denegado" al subir logo

**Causa:** Permisos en `/uploads/`

**Solución:**
1. En cPanel, establece `/uploads/` a `755`

### Sitio muy lento

**Causas:**
1. Hosting sobrecargado
2. Base de datos sin índices
3. Imágenes muy grandes
4. Sin caché

**Soluciones:**
1. Considera un hosting mejor o VPS
2. Optimiza imágenes
3. Activa caché en `.htaccess`
4. Usa un CDN como Cloudflare

---

## 📞 Contacto y Soporte

Si tienes problemas:

1. **Hosting:** Contacta al soporte técnico de tu proveedor
2. **Dominio:** Contacta al registrador de dominio
3. **Proyecto:** Revisa el README.md o DOCUMENTACION_PAGINAS.md

---

## 📚 Recursos Útiles

- **PHP Manual:** https://www.php.net/manual/
- **MySQL Tutorial:** https://www.mysqltutorial.org/
- **Bluehost Support:** https://www.bluehost.com/support
- **FileZilla Manual:** https://wiki.filezilla-project.org/
- **cPanel Documentation:** https://docs.cpanel.net/

---

**¡Felicidades! Tu proyecto está en internet. 🎉**

---

*Última actualización: 27 de noviembre de 2025*

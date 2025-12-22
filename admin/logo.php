<?php
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}
if(!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'admin') {
    header("Location: /proyectoFinalManolo/views/login.php");
    exit();
}

// Manejo de subida de logo
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['logo'])) {
    $file = $_FILES['logo'];
    $allowed = array('image/png' => '.png', 'image/jpeg' => '.jpg', 'image/svg+xml' => '.svg');
    
    if($file['error'] === UPLOAD_ERR_OK) {
        if($file['size'] > 2 * 1024 * 1024) {
            $_SESSION['flash_logo'] = array('type' => 'error', 'message' => 'El archivo es demasiado grande (máx 2MB).');
        } else {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);
            
            if(!array_key_exists($mime, $allowed)) {
                $_SESSION['flash_logo'] = array('type' => 'error', 'message' => 'Formato no permitido. Usa PNG, JPG o SVG.');
            } else {
                $ext = $allowed[$mime];
                $uploadDir = __DIR__ . '/../uploads/';
                if(!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                // Eliminar logos anteriores
                $exts = array('.png', '.jpg', '.jpeg', '.svg');
                foreach($exts as $e) {
                    $p = $uploadDir . 'logo' . $e;
                    if(file_exists($p)) @unlink($p);
                }
                $target = $uploadDir . 'logo' . $ext;
                if(move_uploaded_file($file['tmp_name'], $target)) {
                    $_SESSION['flash_logo'] = array('type' => 'success', 'message' => 'Logo actualizado correctamente.');
                } else {
                    $_SESSION['flash_logo'] = array('type' => 'error', 'message' => 'Error al mover el archivo subido.');
                }
            }
        }
    } else {
        $_SESSION['flash_logo'] = array('type' => 'error', 'message' => 'Error en la subida de archivo.');
    }
    header('Location: /proyectoFinalManolo/admin/logo.php');
    exit();
}

// Obtener el logo actual
$currentLogo = null;
$uploadsDir = __DIR__ . '/../uploads/';
if(is_dir($uploadsDir)) {
    foreach(['logo.png','logo.jpg','logo.jpeg','logo.svg'] as $lf) {
        if(file_exists($uploadsDir . $lf)) {
            $currentLogo = '/proyectoFinalManolo/uploads/' . $lf;
            break;
        }
    }
}

$page_title = "Gestionar Logo";
include __DIR__ . '/../includes/header.php';
?>

<main class="main-content">
    <div class="container">
        <h1 class="page-title">Gestionar Logo del Sitio</h1>

        <?php if(isset($_SESSION['flash_logo'])): $f = $_SESSION['flash_logo']; unset($_SESSION['flash_logo']); ?>
            <div class="flash-message flash-<?php echo $f['type']; ?>">
                <?php echo htmlspecialchars($f['message']); ?>
            </div>
        <?php endif; ?>

        <div class="logo-management-container">
            <!-- Sección actual del logo -->
            <section class="logo-current">
                <h2>Logo Actual</h2>
                <?php if($currentLogo): ?>
                    <div class="current-logo-display">
                        <img src="<?php echo $currentLogo; ?>?t=<?php echo time(); ?>" alt="Logo actual" class="logo-preview-large">
                        <p class="logo-info">Tamaño actual: <strong>Variable</strong> | Escalará automáticamente en header y footer</p>
                    </div>
                <?php else: ?>
                    <div class="no-logo">
                        <p>No hay logo personalizado. El sitio mostrará el texto por defecto: <strong>📱 MobileStore</strong></p>
                    </div>
                <?php endif; ?>
            </section>

            <!-- Formulario de subida -->
            <section class="logo-upload-section">
                <h2>Subir o Actualizar Logo</h2>
                <form method="POST" enctype="multipart/form-data" class="logo-upload-form">
                    <div class="form-group">
                        <label for="logo-input">Selecciona una imagen</label>
                        <input type="file" 
                               id="logo-input"
                               name="logo" 
                               accept="image/png, image/jpeg, image/svg+xml" 
                               required
                               class="file-input">
                        <p class="form-help">
                            <strong>Formatos permitidos:</strong> PNG, JPG, SVG<br>
                            <strong>Tamaño máximo:</strong> 2MB<br>
                            <strong>Recomendación:</strong> Usa imágenes cuadradas (1:1) para mejor visualización
                        </p>
                    </div>
                    <button type="submit" class="btn btn-primary btn-large">Subir Logo</button>
                </form>
            </section>

            <!-- Vista previa antes de subir -->
            <section class="logo-preview-section" id="preview-section" style="display:none;">
                <h2>Vista Previa</h2>
                <div class="preview-container">
                    <div class="preview-header">
                        <p style="font-size: 0.85rem; color: #999; margin-bottom: 0.5rem;">En la cabecera:</p>
                        <div class="preview-navbar">
                            <img id="preview-logo-header" src="" alt="Preview" class="site-logo">
                            <span style="color: #d4af37;">MobileStore</span>
                        </div>
                    </div>
                    <div class="preview-footer" style="margin-top: 1rem;">
                        <p style="font-size: 0.85rem; color: #999; margin-bottom: 0.5rem;">En el pie de página:</p>
                        <div class="preview-footer-content">
                            <img id="preview-logo-footer" src="" alt="Preview" class="footer-logo">
                            <span style="color: #888;">© 2025 MobileStore</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Información útil -->
            <section class="logo-info-section">
                <h2>Información Técnica</h2>
                <div class="info-box">
                    <h3>¿Cómo funciona?</h3>
                    <ul>
                        <li>El logo se escala automáticamente al tamaño del texto en la cabecera y pie de página.</li>
                        <li>En pantallas grandes (>=1024px), el logo será más prominente.</li>
                        <li>En pantallas pequeñas, el logo se reducirá ligeramente.</li>
                        <li>Cada vez que subes un nuevo logo, el anterior se reemplaza automáticamente.</li>
                    </ul>
                </div>
                <div class="info-box">
                    <h3>Recomendaciones de diseño</h3>
                    <ul>
                        <li><strong>Formato:</strong> PNG con transparencia es ideal para adaptarse al fondo.</li>
                        <li><strong>Proporción:</strong> Cuadrada (1:1) o rectangular (2:1) funcionan mejor.</li>
                        <li><strong>Resolución:</strong> Mínimo 200x200px para que se vea nítido en pantallas grandes.</li>
                        <li><strong>Colores:</strong> Considera usar colores que contrasten con los fondos dorado (#d4af37) y oscuro (#1a1a1a).</li>
                    </ul>
                </div>
            </section>
        </div>
    </div>
</main>

<script>
// Vista previa en tiempo real antes de subir
document.getElementById('logo-input').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if(file) {
        const reader = new FileReader();
        reader.onload = function(event) {
            document.getElementById('preview-logo-header').src = event.target.result;
            document.getElementById('preview-logo-footer').src = event.target.result;
            document.getElementById('preview-section').style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
});
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>

<?php
require_once __DIR__ . '/../controllers/MisionVisionController.php';

$page_title = "Quiénes Somos";
include __DIR__ . '/../includes/header.php';

$controller = new MisionVisionController();
$datos = $controller->obtener();
?>

<main class="main-content">
    <div class="container">
        <div class="about-section">
            <h1 class="page-title">Quiénes Somos</h1>
            
            <div class="about-content">
                <div class="mission-section">
                    <h2>Misión</h2>
                    <p><?php echo nl2br(htmlspecialchars($datos['mision'] ?? 'Misión no definida')); ?></p>
                </div>

                <div class="vision-section">
                    <h2>Visión</h2>
                    <p><?php echo nl2br(htmlspecialchars($datos['vision'] ?? 'Visión no definida')); ?></p>
                </div>

                <?php if(!empty($datos['valores'])): ?>
                <div class="values-section">
                    <h2>Nuestros Valores</h2>
                    <ul>
                        <?php 
                        $valores = explode("\n", $datos['valores']);
                        foreach($valores as $valor):
                            $valor = trim($valor);
                            if(!empty($valor)):
                                // Si contiene ":", separar en título y descripción
                                if(strpos($valor, ':') !== false):
                                    list($titulo, $descripcion) = explode(':', $valor, 2);
                                    echo '<li><strong>' . htmlspecialchars(trim($titulo)) . ':</strong> ' . htmlspecialchars(trim($descripcion)) . '</li>';
                                else:
                                    echo '<li>' . htmlspecialchars($valor) . '</li>';
                                endif;
                            endif;
                        endforeach;
                        ?>
                    </ul>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>


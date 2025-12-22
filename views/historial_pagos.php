<?php
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['usuario_id'])) {
    header("Location: /proyectoFinalManolo/views/login.php");
    exit();
}

require_once __DIR__ . '/../controllers/PagoController.php';

$page_title = "Historial de Pagos";
include __DIR__ . '/../includes/header.php';

$pagoController = new PagoController();
$pagos = $pagoController->obtenerHistorial($_SESSION['usuario_id']);
?>

<main class="main-content">
    <div class="container">
        <h1 class="page-title">Historial de Pagos</h1>

        <?php if(empty($pagos)): ?>
            <div class="empty-cart">
                <p>No tienes pagos registrados</p>
                <a href="/proyectoFinalManolo/index.php" class="btn btn-primary">Ir a Comprar</a>
            </div>
        <?php else: ?>
            <div class="payments-table-container">
                <table class="payments-table">
                    <thead>
                        <tr>
                            <th>ID Transacción</th>
                            <th>Fecha</th>
                            <th>Monto</th>
                            <th>Método de Pago</th>
                            <th>Tarjeta</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($pagos as $pago): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($pago['id']); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($pago['fecha_creacion'])); ?></td>
                                <td>$<?php echo number_format($pago['total'], 2); ?></td>
                                <td><?php echo htmlspecialchars(str_replace('_', ' ', ucfirst($pago['metodo_pago']))); ?></td>
                                <td><?php echo htmlspecialchars($pago['numero_tarjeta']); ?></td>
                                <td><span class="status-badge status-<?php echo strtolower($pago['estado']); ?>"><?php echo htmlspecialchars(ucfirst($pago['estado'])); ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="back-link">
                <a href="/proyectoFinalManolo/views/perfil.php">← Volver al Perfil</a>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>

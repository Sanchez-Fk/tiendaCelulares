<?php
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}
if(!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'admin') {
    header("Location: /proyectoFinalManolo/views/login.php");
    exit();
}

require_once __DIR__ . '/../controllers/PagoController.php';

$page_title = "Pedidos";
include __DIR__ . '/../includes/header.php';

$pagoController = new PagoController();
$pagos_completados = [];
$pagos_pendientes = [];
$pagos_rechazados = [];

// Obtener todos los pagos y dividir por estado
$all = $pagoController->obtenerHistorial(null);
foreach($all as $p) {
    $estado = strtolower($p['estado'] ?? 'completado');
    if($estado === 'completado') $pagos_completados[] = $p;
    elseif($estado === 'pendiente') $pagos_pendientes[] = $p;
    else $pagos_rechazados[] = $p;
}
?>

<main class="main-content">
    <div class="container">
        <h1 class="page-title">Pedidos</h1>

        <section class="orders-section">
            <h2>Pagos Realizados</h2>
            <?php if(empty($pagos_completados)): ?>
                <p>No hay pagos realizados.</p>
            <?php else: ?>
                <div class="payments-table-container">
                    <table class="payments-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Usuario ID</th>
                                <th>Fecha</th>
                                <th>Monto</th>
                                <th>Dirección</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($pagos_completados as $pago): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($pago['id']); ?></td>
                                    <td><?php echo htmlspecialchars($pago['usuario_id']); ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($pago['fecha_creacion'])); ?></td>
                                    <td>$<?php echo number_format($pago['total'],2); ?></td>
                                    <td><?php echo htmlspecialchars($pago['direccion'] . ' - ' . $pago['ciudad']); ?></td>
                                    <td><?php echo htmlspecialchars($pago['estado']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </section>

        <section class="orders-section">
            <h2>Pagos Pendientes</h2>
            <?php if(empty($pagos_pendientes)): ?>
                <p>No hay pagos pendientes.</p>
            <?php else: ?>
                <!-- Similar tabla -->
            <?php endif; ?>
        </section>

        <section class="orders-section">
            <h2>Pagos Rechazados</h2>
            <?php if(empty($pagos_rechazados)): ?>
                <p>No hay pagos rechazados.</p>
            <?php else: ?>
                <!-- Similar tabla -->
            <?php endif; ?>
        </section>

        <div class="back-link">
            <a href="/proyectoFinalManolo/admin/index.php">← Volver al Panel</a>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>
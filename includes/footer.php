    <footer class="footer">
        <div class="container footer-inner">
            <?php
            // Buscar logo personalizado en uploads/
            $logoUrl = null;
            $uploadsRel = '/proyectoFinalManolo/uploads/';
            $uploadsDir = __DIR__ . '/../uploads/';
            foreach(['logo.png','logo.jpg','logo.jpeg','logo.svg'] as $lf) {
                if(file_exists($uploadsDir . $lf)) {
                    $logoUrl = $uploadsRel . $lf;
                    break;
                }
            }
            ?>
            <?php if($logoUrl): ?>
                <div class="footer-brand">
                    <a href="/proyectoFinalManolo/index.php"><img src="<?php echo $logoUrl; ?>" alt="Logo" class="footer-logo"></a>
                </div>
            <?php endif; ?>
            <p>&copy; <?php echo date('Y'); ?> MobileStore. Todos los derechos reservados.</p>
        </div>
    </footer>
    <script src="/proyectoFinalManolo/assets/js/main.js"></script>
</body>
</html>




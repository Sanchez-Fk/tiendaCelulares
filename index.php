<?php
require_once __DIR__ . '/controllers/ProductoController.php';

$page_title = "Inicio - Tienda de Dispositivos Móviles";
include __DIR__ . '/includes/header.php';

$productoController = new ProductoController();

// Manejar búsqueda
$busqueda = $_GET['buscar'] ?? '';
$productos = !empty($busqueda) ? $productoController->buscar($busqueda) : $productoController->listar();
?>

<main class="main-content">
    <!-- Barra de búsqueda para usuarios -->
    <section class="search-section-user">
        <div class="container">
            <form method="GET" action="" class="search-form-user">
                <div class="search-input-group-user">
                    <input type="text" name="buscar" 
                           placeholder="🔍 Buscar productos por nombre..." 
                           value="<?php echo htmlspecialchars($busqueda); ?>"
                           class="search-input-user">
                    <button type="submit" class="btn btn-primary">Buscar</button>
                    <?php if(!empty($busqueda)): ?>
                        <a href="/proyectoFinalManolo/index.php" class="btn btn-secondary">Ver Todos</a>
                    <?php endif; ?>
                </div>
            </form>
            <?php if(!empty($busqueda)): ?>
                <p class="search-results-info-user">
                    Resultados para: <strong>"<?php echo htmlspecialchars($busqueda); ?>"</strong> 
                    (<?php echo count($productos); ?> producto(s) encontrado(s))
                </p>
            <?php endif; ?>
        </div>
    </section>

    <?php if(empty($busqueda)): ?>
    <section class="hero">
        <div class="carousel-container">
            <div class="carousel" id="productCarousel">
                <?php foreach($productos as $index => $producto): ?>
                    <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>" data-product-id="<?php echo $producto['id']; ?>">
                        <img src="/proyectoFinalManolo/<?php echo htmlspecialchars($producto['imagen'] ?? 'assets/img/placeholder.svg'); ?>" 
                             alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                        <div class="carousel-overlay">
                            <h2><?php echo htmlspecialchars($producto['nombre']); ?></h2>
                            <p class="price">$<?php echo number_format($producto['precio'], 2); ?></p>
                            <a href="/proyectoFinalManolo/views/producto.php?id=<?php echo $producto['id']; ?>" class="btn btn-primary">Ver Detalles</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="carousel-controls">
                <button class="carousel-btn prev" onclick="moveCarousel(-1)">‹</button>
                <button class="carousel-btn next" onclick="moveCarousel(1)">›</button>
            </div>
            <div class="carousel-indicators">
                <?php foreach($productos as $index => $producto): ?>
                    <span class="indicator <?php echo $index === 0 ? 'active' : ''; ?>" onclick="goToSlide(<?php echo $index; ?>)"></span>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <section class="products-section">
        <div class="container">
            <h2 class="section-title"><?php echo !empty($busqueda) ? 'Resultados de Búsqueda' : 'Nuestros Productos'; ?></h2>
            <?php if(empty($productos)): ?>
                <div class="no-products">
                    <p>No se encontraron productos<?php echo !empty($busqueda) ? ' que coincidan con "' . htmlspecialchars($busqueda) . '"' : ''; ?>.</p>
                    <?php if(!empty($busqueda)): ?>
                        <a href="/proyectoFinalManolo/index.php" class="btn btn-primary">Ver Todos los Productos</a>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="products-grid">
                    <?php foreach($productos as $producto): ?>
                        <div class="product-card">
                            <img src="/proyectoFinalManolo/<?php echo htmlspecialchars($producto['imagen'] ?? 'assets/img/placeholder.svg'); ?>" 
                                 alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                            <div class="product-info">
                                <h3><?php echo htmlspecialchars($producto['nombre']); ?></h3>
                                <p class="product-price">$<?php echo number_format($producto['precio'], 2); ?></p>
                                <a href="/proyectoFinalManolo/views/producto.php?id=<?php echo $producto['id']; ?>" class="btn btn-secondary">Ver Detalles</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>


<?php
$pageTitle = 'Home';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/config/database.php';

// Fetch featured products
try {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE status = 'active' ORDER BY created_at DESC LIMIT 6");
    $stmt->execute();
    $featuredProducts = $stmt->fetchAll();
} catch (PDOException $e) {
    $featuredProducts = [];
}
?>

<!-- Hero Section -->
<section class="hero">
    <div class="container hero-container">
        <div class="hero-content">
            <h1>Customized Packaged Drinking Water Bottle for Your Success</h1>
            <p>Discover high-quality Bottles, Labels, Packaging, and more to accelerate your Hotels, Restaurants, and Businesses.</p>
            <div class="hero-buttons">
                <a href="<?php echo SITE_URL; ?>/pages/products.php" class="btn btn-lg btn-primary">EXPLORE PRODUCTS</a>
                <a href="<?php echo SITE_URL; ?>/pages/about.php" class="btn btn-lg btn-outline">LEARN MORE</a>
            </div>
        </div>
       <div class="hero-image">
    <img 
        src="<?php echo SITE_URL; ?>/assets/images/bottle.png"
        alt="Abhi AquaLeaf Water Bottle"
        class="hero-bottle"
    >
</div>
</section>

<!-- Featured Products Section -->
<section class="container">
    <h2 class="text-center mt-4 mb-4">Featured Products</h2>
    <div class="products-grid">
        <?php foreach ($featuredProducts as $product): ?>
            <div class="card">
                <?php if ($product['image_url']): ?>
                    <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>" class="card-image">
                <?php else: ?>
                    <img src="<?php echo SITE_URL; ?>/assets/images/placeholder.jpg" alt="Product Image" class="card-image">
                <?php endif; ?>
                <div class="card-body">
                    <h3 class="card-title"><?php echo htmlspecialchars($product['title']); ?></h3>
                    <p class="card-text"><?php echo substr(htmlspecialchars($product['description']), 0, 100); ?>...</p>
                    <p class="card-price"><?php echo CURRENCY . number_format($product['price'], 2); ?></p>
                    <div class="d-flex gap-2">
                        <a href="<?php echo SITE_URL; ?>/pages/product-detail.php?id=<?php echo $product['id']; ?>" class="btn btn-primary w-100">View Details</a>
                        <button onclick="addToCart(<?php echo $product['id']; ?>, <?php echo $product['price']; ?>)" class="btn btn-success w-100">
                            <i class="fas fa-cart-plus"></i> Add
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="text-center mb-4">
        <a href="<?php echo SITE_URL; ?>/pages/products.php" class="btn btn-outline btn-lg">View All Products</a>
    </div>
</section>

<!-- Why Choose Us Section -->
<section class="container mt-4 mb-4">
    <h2 class="text-center mb-4">Why Choose Us?</h2>
    <div class="products-grid">
        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-tint fa-3x mb-2" style="color: var(--primary-color);"></i>
                <h3 class="card-title">Premium Quality</h3>
                <p class="card-text">We offer only the highest quality drinking water bottles, tested and certified.</p>
            </div>
        </div>
        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-truck fa-3x mb-2" style="color: var(--primary-color);"></i>
                <h3 class="card-title">Fast Delivery</h3>
                <p class="card-text">Quick and reliable delivery to your doorstep. Order today, receive tomorrow.</p>
            </div>
        </div>
        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-shield-alt fa-3x mb-2" style="color: var(--primary-color);"></i>
                <h3 class="card-title">Secure Payments</h3>
                <p class="card-text">Multiple payment options with secure transactions. Your data is safe with us.</p>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="container mt-4 mb-4">
    <h2 class="text-center mb-4">What Our Customers Say</h2>
    <div class="products-grid">
        <div class="card">
            <div class="card-body">
                <p class="card-text">"Excellent quality water bottles! The delivery was fast and the packaging was perfect. Highly recommended!"</p>
                <p class="mt-2"><strong>- Rahul Sharma</strong></p>
                <div style="color: var(--warning-color);">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <p class="card-text">"Great prices and amazing customer service. I've been ordering from AquaLeaf for months now. Never disappointed!"</p>
                <p class="mt-2"><strong>- Priya Patel</strong></p>
                <div style="color: var(--warning-color);">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <p class="card-text">"The water quality is top-notch. Perfect for my office and home use. Will definitely order again!"</p>
                <p class="mt-2"><strong>- Amit Kumar</strong></p>
                <div style="color: var(--warning-color);">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

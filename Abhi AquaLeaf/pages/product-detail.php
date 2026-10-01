<?php
$pageTitle = 'Product Detail';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../config/database.php';

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$product_id) {
    header('Location: ' . SITE_URL . '/pages/products.php');
    exit;
}

// Get product details
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? AND status = 'active'");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    header('Location: ' . SITE_URL . '/pages/products.php');
    exit;
}

// Get product screenshots
$stmt = $pdo->prepare("SELECT * FROM product_screenshots WHERE product_id = ? ORDER BY display_order");
$stmt->execute([$product_id]);
$screenshots = $stmt->fetchAll();

// Get related products (same category, excluding current)
try {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE category = ? AND id != ? AND status = 'active' ORDER BY created_at DESC LIMIT 4");
    $stmt->execute([$product['category'], $product_id]);
    $related_products = $stmt->fetchAll();
} catch (PDOException $e) {
    // If category column doesn't exist, get random products
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id != ? AND status = 'active' ORDER BY created_at DESC LIMIT 4");
    $stmt->execute([$product_id]);
    $related_products = $stmt->fetchAll();
}
?>

<div class="container mt-4 mb-4">
    <!-- Breadcrumb -->
    <nav class="mb-3">
        <a href="<?php echo SITE_URL; ?>/">Home</a> &gt;
        <a href="<?php echo SITE_URL; ?>/pages/products.php">Products</a> &gt;
        <span><?php echo htmlspecialchars($product['title']); ?></span>
    </nav>

    <div class="d-flex gap-3" style="flex-wrap: wrap;">
        <!-- Product Image -->
        <div style="flex: 1; min-width: 300px; max-width: 500px;">
            <?php if ($product['image_url']): ?>
                <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>" style="width: 100%; border-radius: var(--radius);">
            <?php else: ?>
                <img src="<?php echo SITE_URL; ?>/assets/images/placeholder.jpg" alt="Product Image" style="width: 100%; border-radius: var(--radius);">
            <?php endif; ?>

            <?php if (count($screenshots) > 0): ?>
                <div class="mt-2" style="display: flex; gap: 10px; overflow-x: auto;">
                    <?php foreach ($screenshots as $screenshot): ?>
                        <img src="<?php echo htmlspecialchars($screenshot['image_url']); ?>" alt="Screenshot" style="width: 80px; height: 80px; object-fit: cover; border-radius: var(--radius); cursor: pointer;">
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Product Info -->
        <div style="flex: 1; min-width: 300px;">
            <h1 class="mb-2"><?php echo htmlspecialchars($product['title']); ?></h1>
            <?php if ($product['category']): ?>
                <span class="badge badge-info mb-2"><?php echo htmlspecialchars($product['category']); ?></span>
            <?php endif; ?>
            <p class="card-price" style="font-size: 32px;"><?php echo CURRENCY . number_format($product['price'], 2); ?></p>

            <p class="mb-3"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>

            <div class="d-flex gap-2 mb-3">
                <a href="<?php echo SITE_URL; ?>/pages/cart.php" onclick="addToCart(<?php echo $product['id']; ?>, <?php echo $product['price']; ?>); return false;" class="btn btn-lg btn-primary w-100">
                    <i class="fas fa-cart-plus"></i> Add to Cart
                </a>
                <a href="<?php echo SITE_URL; ?>/pages/checkout.php" onclick="buyNow(<?php echo $product['id']; ?>); return false;" class="btn btn-lg btn-success w-100">
                    <i class="fas fa-bolt"></i> Buy Now
                </a>
            </div>

            <div class="alert alert-info">
                <i class="fas fa-shield-alt"></i> Secure payment & fast delivery
            </div>
        </div>
    </div>

    <!-- Related Products -->
    <?php if (count($related_products) > 0): ?>
        <h2 class="mt-4 mb-3">Related Products</h2>
        <div class="products-grid">
            <?php foreach ($related_products as $related): ?>
                <div class="card">
                    <?php if ($related['image_url']): ?>
                        <img src="<?php echo htmlspecialchars($related['image_url']); ?>" alt="<?php echo htmlspecialchars($related['title']); ?>" class="card-image">
                    <?php else: ?>
                        <img src="<?php echo SITE_URL; ?>/assets/images/placeholder.jpg" alt="Product Image" class="card-image">
                    <?php endif; ?>
                    <div class="card-body">
                        <h3 class="card-title"><?php echo htmlspecialchars($related['title']); ?></h3>
                        <p class="card-price"><?php echo CURRENCY . number_format($related['price'], 2); ?></p>
                        <div class="d-flex gap-2">
                            <a href="<?php echo SITE_URL; ?>/pages/product-detail.php?id=<?php echo $related['id']; ?>" class="btn btn-primary w-100">View Details</a>
                            <button onclick="addToCart(<?php echo $related['id']; ?>, <?php echo $related['price']; ?>)" class="btn btn-success w-100">
                                <i class="fas fa-cart-plus"></i> Add
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script>
function addToCart(productId, price) {
    fetch('<?php echo SITE_URL; ?>/api/add-to-cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ productId, price, quantity: 1 })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateCartCount();
            showNotification('Product added to cart!', 'success');
        } else {
            showNotification(data.message || 'Error adding to cart', 'danger');
        }
    })
    .catch(error => {
        showNotification('Error adding to cart', 'danger');
    });
}

function buyNow(productId) {
    addToCart(productId, <?php echo $product['price']; ?>);
    setTimeout(() => {
        window.location.href = '<?php echo SITE_URL; ?>/pages/checkout.php';
    }, 500);
}
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

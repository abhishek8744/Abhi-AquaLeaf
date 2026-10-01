<?php
$pageTitle = 'Products';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../config/database.php';

// Get filters
$category = $_GET['category'] ?? '';
$search = $_GET['search'] ?? '';
$sort = $_GET['sort'] ?? 'newest';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * PRODUCTS_PER_PAGE;

// Build query
$where = "WHERE status = 'active'";
$params = [];

if ($category) {
    $where .= " AND category = ?";
    $params[] = $category;
}

if ($search) {
    $where .= " AND (title LIKE ? OR description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

// Check if category column exists
try {
    $stmt = $pdo->query("SHOW COLUMNS FROM products LIKE 'category'");
    $has_category = $stmt->fetch() !== false;
} catch (PDOException $e) {
    $has_category = false;
}

// If category column doesn't exist, remove category filter
if (!$has_category && $category) {
    $where = "WHERE status = 'active'";
    $params = [];
}

// Sorting
$order_by = match($sort) {
    'price_low' => 'price ASC',
    'price_high' => 'price DESC',
    'popular' => 'id DESC',
    default => 'created_at DESC'
};

// Get total count
$count_sql = "SELECT COUNT(*) FROM products $where";
$stmt = $pdo->prepare($count_sql);
$stmt->execute($params);
$total_products = $stmt->fetchColumn();
$total_pages = ceil($total_products / PRODUCTS_PER_PAGE);

// Get products
$sql = "SELECT * FROM products $where ORDER BY $order_by LIMIT " . PRODUCTS_PER_PAGE . " OFFSET $offset";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

// Get categories
try {
    $stmt = $pdo->query("SELECT DISTINCT category FROM products WHERE category IS NOT NULL AND category != '' ORDER BY category");
    $categories = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    $categories = [];
}
?>

<div class="container mt-4 mb-4">
    <h1 class="text-center mb-4">Our Products</h1>

    <!-- Search and Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="" class="d-flex gap-2 flex-wrap">
                <input type="text" name="search" class="form-control" placeholder="Search products..."
                       value="<?php echo htmlspecialchars($search); ?>" style="flex: 1; min-width: 200px;">

                <select name="category" class="form-select" style="min-width: 150px;">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo htmlspecialchars($cat); ?>" <?php echo $category === $cat ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <select name="sort" class="form-select" style="min-width: 150px;">
                    <option value="newest" <?php echo $sort === 'newest' ? 'selected' : ''; ?>>Newest</option>
                    <option value="price_low" <?php echo $sort === 'price_low' ? 'selected' : ''; ?>>Price: Low to High</option>
                    <option value="price_high" <?php echo $sort === 'price_high' ? 'selected' : ''; ?>>Price: High to Low</option>
                    <option value="popular" <?php echo $sort === 'popular' ? 'selected' : ''; ?>>Popular</option>
                </select>

                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="<?php echo SITE_URL; ?>/pages/products.php" class="btn btn-outline">Clear</a>
            </form>
        </div>
    </div>

    <!-- Products Grid -->
    <?php if (count($products) > 0): ?>
        <div class="products-grid">
            <?php foreach ($products as $product): ?>
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

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page - 1; ?>&category=<?php echo urlencode($category); ?>&search=<?php echo urlencode($search); ?>&sort=<?php echo $sort; ?>">Previous</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <?php if ($i == $page): ?>
                        <span class="active"><?php echo $i; ?></span>
                    <?php else: ?>
                        <a href="?page=<?php echo $i; ?>&category=<?php echo urlencode($category); ?>&search=<?php echo urlencode($search); ?>&sort=<?php echo $sort; ?>"><?php echo $i; ?></a>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if ($page < $total_pages): ?>
                    <a href="?page=<?php echo $page + 1; ?>&category=<?php echo urlencode($category); ?>&search=<?php echo urlencode($search); ?>&sort=<?php echo $sort; ?>">Next</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="alert alert-info text-center">
            <h3>No products found</h3>
            <p>Try adjusting your filters or search terms.</p>
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
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

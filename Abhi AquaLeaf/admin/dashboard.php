<?php
$pageTitle = 'Dashboard';
require_once __DIR__ . '/includes/header.php';

// Get stats
$stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'user'");
$user_count = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM products WHERE status = 'active'");
$product_count = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM orders");
$order_count = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COALESCE(SUM(final_amount), 0) FROM orders WHERE payment_status = 'completed'");
$revenue = $stmt->fetchColumn();

// Get recent orders
$stmt = $pdo->query("SELECT o.*, u.name FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC LIMIT 5");
$recent_orders = $stmt->fetchAll();

// Get recent users
$stmt = $pdo->query("SELECT * FROM users WHERE role = 'user' ORDER BY created_at DESC LIMIT 5");
$recent_users = $stmt->fetchAll();

// Get top products
$stmt = $pdo->query("SELECT p.*, COUNT(oi.id) as order_count FROM products p LEFT JOIN order_items oi ON p.id = oi.product_id GROUP BY p.id ORDER BY order_count DESC LIMIT 5");
$top_products = $stmt->fetchAll();
?>

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <h3>Total Users</h3>
        <div class="value"><?php echo $user_count; ?></div>
    </div>
    <div class="stat-card" style="border-left-color: var(--success-color);">
        <h3>Total Products</h3>
        <div class="value"><?php echo $product_count; ?></div>
    </div>
    <div class="stat-card" style="border-left-color: var(--warning-color);">
        <h3>Total Orders</h3>
        <div class="value"><?php echo $order_count; ?></div>
    </div>
    <div class="stat-card" style="border-left-color: var(--info-color);">
        <h3>Total Revenue</h3>
        <div class="value"><?php echo CURRENCY . number_format($revenue, 2); ?></div>
    </div>
</div>

<div class="d-flex gap-3" style="flex-wrap: wrap;">
    <!-- Recent Orders -->
    <div style="flex: 1; min-width: 300px;">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title mb-3">Recent Orders</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_orders as $order): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($order['order_number']); ?></td>
                                <td><?php echo htmlspecialchars($order['name']); ?></td>
                                <td><?php echo CURRENCY . number_format($order['final_amount'], 2); ?></td>
                                <td>
                                    <span class="badge badge-<?php echo $order['order_status'] === 'completed' ? 'success' : 'info'; ?>">
                                        <?php echo ucfirst($order['order_status']); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <a href="<?php echo SITE_URL; ?>/admin/orders/index.php" class="btn btn-outline btn-block">View All Orders</a>
            </div>
        </div>
    </div>

    <!-- Recent Users -->
    <div style="flex: 1; min-width: 300px;">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title mb-3">Recent Users</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_users as $user): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user['name']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td>
                                    <span class="badge badge-<?php echo $user['status'] === 'active' ? 'success' : 'danger'; ?>">
                                        <?php echo ucfirst($user['status']); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <a href="<?php echo SITE_URL; ?>/admin/users/index.php" class="btn btn-outline btn-block">View All Users</a>
            </div>
        </div>
    </div>
</div>

<!-- Top Products -->
<div class="card mt-3">
    <div class="card-body">
        <h3 class="card-title mb-3">Top Selling Products</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Orders</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($top_products as $product): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['title']); ?></td>
                        <td><?php echo CURRENCY . number_format($product['price'], 2); ?></td>
                        <td><?php echo $product['order_count']; ?></td>
                        <td>
                            <span class="badge badge-<?php echo $product['status'] === 'active' ? 'success' : 'danger'; ?>">
                                <?php echo ucfirst($product['status']); ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="<?php echo SITE_URL; ?>/admin/products/index.php" class="btn btn-outline btn-block">View All Products</a>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

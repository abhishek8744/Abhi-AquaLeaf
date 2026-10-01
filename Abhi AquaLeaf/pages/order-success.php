<?php
$pageTitle = 'Order Successful';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../config/database.php';

$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;

if (!$order_id) {
    header('Location: ' . SITE_URL . '/pages/products.php');
    exit;
}

// Get order details
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->execute([$order_id, $_SESSION['user_id']]);
$order = $stmt->fetch();

if (!$order) {
    header('Location: ' . SITE_URL . '/pages/products.php');
    exit;
}

// Get order items
$stmt = $pdo->prepare("SELECT oi.*, p.title, p.image_url FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
$stmt->execute([$order_id]);
$order_items = $stmt->fetchAll();
?>

<div class="container mt-4 mb-4">
    <div class="card" style="max-width: 600px; margin: 0 auto;">
        <div class="card-body text-center">
            <div style="font-size: 80px; color: var(--success-color); margin-bottom: 20px;">
                <i class="fas fa-check-circle"></i>
            </div>
            <h1 class="mb-3">Order Successful!</h1>
            <p class="mb-4">Thank you for your purchase. Your order has been received and is being processed.</p>

            <div class="alert alert-info">
                <p><strong>Order Number:</strong> <?php echo htmlspecialchars($order['order_number']); ?></p>
                <p><strong>Total Amount:</strong> <?php echo CURRENCY . number_format($order['final_amount'], 2); ?></p>
            </div>

            <h3 class="mb-3">Order Details</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($order_items as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['title']); ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td><?php echo CURRENCY . number_format($item['price'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="d-flex gap-2 justify-content-center mt-4">
                <a href="<?php echo SITE_URL; ?>/pages/orders.php" class="btn btn-primary">View Orders</a>
                <a href="<?php echo SITE_URL; ?>/pages/products.php" class="btn btn-outline">Continue Shopping</a>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

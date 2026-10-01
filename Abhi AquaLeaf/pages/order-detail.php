<?php
$pageTitle = 'Order Details';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . SITE_URL . '/pages/login.php');
    exit;
}

$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$order_id) {
    header('Location: ' . SITE_URL . '/pages/orders.php');
    exit;
}

// Get order details
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->execute([$order_id, $_SESSION['user_id']]);
$order = $stmt->fetch();

if (!$order) {
    header('Location: ' . SITE_URL . '/pages/orders.php');
    exit;
}

// Get order items
$stmt = $pdo->prepare("SELECT oi.*, p.title, p.image_url, p.file_url FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
$stmt->execute([$order_id]);
$order_items = $stmt->fetchAll();
?>

<div class="container mt-4 mb-4">
    <div class="mb-3">
        <a href="<?php echo SITE_URL; ?>/pages/orders.php" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Back to Orders
        </a>
    </div>

    <h1 class="mb-4">Order #<?php echo htmlspecialchars($order['order_number']); ?></h1>

    <div class="d-flex gap-3" style="flex-wrap: wrap;">
        <!-- Order Info -->
        <div style="flex: 1; min-width: 300px;">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title mb-3">Order Information</h3>

                    <table class="table">
                        <tr>
                            <td><strong>Order Date:</strong></td>
                            <td><?php echo date('F j, Y g:i A', strtotime($order['created_at'])); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Order Status:</strong></td>
                            <td>
                                <span class="badge badge-<?php echo $order['order_status'] === 'completed' ? 'success' : ($order['order_status'] === 'cancelled' ? 'danger' : 'info'); ?>">
                                    <?php echo ucfirst($order['order_status']); ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Payment Status:</strong></td>
                            <td>
                                <span class="badge badge-<?php echo $order['payment_status'] === 'completed' ? 'success' : 'warning'; ?>">
                                    <?php echo ucfirst($order['payment_status']); ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Payment Method:</strong></td>
                            <td><?php echo ucfirst($order['payment_method']); ?></td>
                        </tr>
                        <?php if ($order['transaction_id']): ?>
                            <tr>
                                <td><strong>Transaction ID:</strong></td>
                                <td><?php echo htmlspecialchars($order['transaction_id']); ?></td>
                            </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div style="flex: 1; min-width: 300px;">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title mb-3">Order Items</h3>

                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($order_items as $item): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if ($item['image_url']): ?>
                                                <img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: var(--radius);">
                                            <?php endif; ?>
                                            <span><?php echo htmlspecialchars($item['title']); ?></span>
                                        </div>
                                    </td>
                                    <td><?php echo $item['quantity']; ?></td>
                                    <td><?php echo CURRENCY . number_format($item['price'], 2); ?></td>
                                    <td><?php echo CURRENCY . number_format($item['subtotal'], 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <div class="mt-3">
                        <p><strong>Subtotal:</strong> <?php echo CURRENCY . number_format($order['total_amount'], 2); ?></p>
                        <?php if ($order['discount_amount'] > 0): ?>
                            <p style="color: var(--success-color);"><strong>Discount:</strong> -<?php echo CURRENCY . number_format($order['discount_amount'], 2); ?></p>
                        <?php endif; ?>
                        <p><strong>Tax:</strong> <?php echo CURRENCY . number_format($order['tax_amount'], 2); ?></p>
                        <p class="card-price"><strong>Total:</strong> <?php echo CURRENCY . number_format($order['final_amount'], 2); ?></p>
                    </div>

                    <?php if ($order['order_status'] === 'completed'): ?>
                        <div class="mt-3">
                            <button onclick="downloadInvoice(<?php echo $order['id']; ?>)" class="btn btn-success w-100">
                                <i class="fas fa-download"></i> Download Invoice
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function downloadInvoice(orderId) {
    window.location.href = '<?php echo SITE_URL; ?>/api/download-invoice.php?order_id=' + orderId;
}
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

<?php
$pageTitle = 'Order Details';
require_once __DIR__ . '/../includes/header.php';

$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$order_id) {
    header('Location: index.php');
    exit;
}

// Get order details
$stmt = $pdo->prepare("SELECT o.*, u.name, u.email FROM orders o JOIN users u ON o.user_id = u.id WHERE o.id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch();

if (!$order) {
    header('Location: index.php');
    exit;
}

// Get order items
$stmt = $pdo->prepare("SELECT oi.*, p.title FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
$stmt->execute([$order_id]);
$order_items = $stmt->fetchAll();

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $new_status = $_POST['order_status'];
    $stmt = $pdo->prepare("UPDATE orders SET order_status = ? WHERE id = ?");
    $stmt->execute([$new_status, $order_id]);
    $order['order_status'] = $new_status;
}
?>

<div class="mb-3">
    <a href="index.php" class="btn btn-outline">
        <i class="fas fa-arrow-left"></i> Back to Orders
    </a>
</div>

<div class="d-flex gap-3" style="flex-wrap: wrap;">
    <!-- Order Info -->
    <div style="flex: 1; min-width: 300px;">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title mb-3">Order Information</h3>

                <table class="table">
                    <tr>
                        <td><strong>Order #:</strong></td>
                        <td><?php echo htmlspecialchars($order['order_number']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Customer:</strong></td>
                        <td><?php echo htmlspecialchars($order['name']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Email:</strong></td>
                        <td><?php echo htmlspecialchars($order['email']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Date:</strong></td>
                        <td><?php echo date('F j, Y g:i A', strtotime($order['created_at'])); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Payment Method:</strong></td>
                        <td><?php echo ucfirst($order['payment_method']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Payment Status:</strong></td>
                        <td>
                            <span class="badge badge-<?php echo $order['payment_status'] === 'completed' ? 'success' : 'warning'; ?>">
                                <?php echo ucfirst($order['payment_status']); ?>
                            </span>
                        </td>
                    </tr>
                </table>

                <form method="POST" action="">
                    <div class="form-group">
                        <label class="form-label">Order Status</label>
                        <select name="order_status" class="form-select">
                            <option value="pending" <?php echo $order['order_status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="processing" <?php echo $order['order_status'] === 'processing' ? 'selected' : ''; ?>>Processing</option>
                            <option value="completed" <?php echo $order['order_status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                            <option value="cancelled" <?php echo $order['order_status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                        </select>
                    </div>
                    <button type="submit" name="update_status" class="btn btn-primary">Update Status</button>
                </form>
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
                                <td><?php echo htmlspecialchars($item['title']); ?></td>
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
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

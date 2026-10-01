<?php
$pageTitle = 'Shopping Cart';
require_once __DIR__ . '/../includes/header.php';

$cart = $_SESSION['cart'] ?? [];
$total = 0;
?>

<div class="container mt-4 mb-4">
    <h1 class="mb-4">Shopping Cart</h1>

    <?php if (empty($cart)): ?>
        <div class="alert alert-info text-center">
            <h3>Your cart is empty</h3>
            <p class="mt-2">
                <a href="<?php echo SITE_URL; ?>/pages/products.php" class="btn btn-primary">Continue Shopping</a>
            </p>
        </div>
    <?php else: ?>
        <div class="card">
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart as $index => $item): ?>
                            <?php
                            $subtotal = $item['price'] * $item['quantity'];
                            $total += $subtotal;
                            ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <?php if ($item['image']): ?>
                                            <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" style="width: 60px; height: 60px; object-fit: cover; border-radius: var(--radius);">
                                        <?php else: ?>
                                            <img src="<?php echo SITE_URL; ?>/assets/images/placeholder.jpg" alt="Product" style="width: 60px; height: 60px; object-fit: cover; border-radius: var(--radius);">
                                        <?php endif; ?>
                                        <span><?php echo htmlspecialchars($item['title']); ?></span>
                                    </div>
                                </td>
                                <td><?php echo CURRENCY . number_format($item['price'], 2); ?></td>
                                <td>
                                    <input type="number" value="<?php echo $item['quantity']; ?>" min="1"
                                           onchange="updateQuantity(<?php echo $index; ?>, this.value)"
                                           style="width: 70px; padding: 8px; border: 1px solid var(--border-color); border-radius: var(--radius);">
                                </td>
                                <td><?php echo CURRENCY . number_format($subtotal, 2); ?></td>
                                <td>
                                    <button onclick="removeFromCart(<?php echo $index; ?>)" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="d-flex justify-content-between align-items-center mt-4" style="flex-wrap: wrap; gap: 20px;">
                    <div>
                        <a href="<?php echo SITE_URL; ?>/pages/products.php" class="btn btn-outline">
                            <i class="fas fa-arrow-left"></i> Continue Shopping
                        </a>
                    </div>
                    <div class="text-right">
                        <h3>Total: <?php echo CURRENCY . number_format($total, 2); ?></h3>
                        <p class="text-muted">+ GST (<?php echo GST_PERCENTAGE; ?>%): <?php echo CURRENCY . number_format($total * (GST_PERCENTAGE / 100), 2); ?></p>
                        <p class="card-price">Grand Total: <?php echo CURRENCY . number_format($total * (1 + GST_PERCENTAGE / 100), 2); ?></p>
                        <a href="<?php echo SITE_URL; ?>/pages/checkout.php" class="btn btn-lg btn-primary">
                            Proceed to Checkout <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
function updateQuantity(index, quantity) {
    if (quantity < 1) return;

    fetch('<?php echo SITE_URL; ?>/api/update-cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ index, quantity })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            showNotification(data.message || 'Error updating cart', 'danger');
        }
    })
    .catch(error => {
        showNotification('Error updating cart', 'danger');
    });
}

function removeFromCart(index) {
    if (!confirm('Are you sure you want to remove this item?')) return;

    fetch('<?php echo SITE_URL; ?>/api/remove-from-cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ index })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            showNotification(data.message || 'Error removing item', 'danger');
        }
    })
    .catch(error => {
        showNotification('Error removing item', 'danger');
    });
}
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

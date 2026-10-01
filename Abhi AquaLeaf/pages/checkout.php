<?php
$pageTitle = 'Checkout';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . SITE_URL . '/pages/login.php?redirect=checkout');
    exit;
}

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';
$coupon_discount = 0;
$coupon_code = '';

// Handle coupon application
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apply_coupon'])) {
    $coupon_code = trim($_POST['coupon_code'] ?? '');

    $stmt = $pdo->prepare("SELECT * FROM coupons WHERE code = ? AND status = 'active' AND (expiry_date IS NULL OR expiry_date >= CURDATE())");
    $stmt->execute([$coupon_code]);
    $coupon = $stmt->fetch();

    if (!$coupon) {
        $error = 'Invalid or expired coupon code.';
    } elseif ($coupon['usage_limit'] > 0 && $coupon['used_count'] >= $coupon['usage_limit']) {
        $error = 'Coupon usage limit reached.';
    } else {
        // Calculate discount
        $subtotal = array_sum(array_map(function($item) { return $item['price'] * $item['quantity']; }, $cart));

        if ($coupon['min_order_amount'] > 0 && $subtotal < $coupon['min_order_amount']) {
            $error = 'Minimum order amount for this coupon is ' . CURRENCY . number_format($coupon['min_order_amount'], 2);
        } else {
            if ($coupon['discount_type'] === 'flat') {
                $coupon_discount = $coupon['discount_value'];
            } else {
                $coupon_discount = $subtotal * ($coupon['discount_value'] / 100);
            }

            if ($coupon['max_discount_amount'] > 0 && $coupon_discount > $coupon['max_discount_amount']) {
                $coupon_discount = $coupon['max_discount_amount'];
            }

            $_SESSION['coupon'] = [
                'code' => $coupon['code'],
                'discount' => $coupon_discount,
                'coupon_id' => $coupon['id']
            ];
            $success = 'Coupon applied successfully!';
        }
    }
}

// Calculate totals
$cart = $_SESSION['cart'] ?? [];
$subtotal = array_sum(array_map(function($item) { return $item['price'] * $item['quantity']; }, $cart));
$tax = $subtotal * (GST_PERCENTAGE / 100);

if (isset($_SESSION['coupon'])) {
    $coupon_discount = $_SESSION['coupon']['discount'];
    $coupon_code = $_SESSION['coupon']['code'];
}

$discounted_subtotal = $subtotal - $coupon_discount;
$tax = $discounted_subtotal * (GST_PERCENTAGE / 100);
$total = $discounted_subtotal + $tax;

// Get user details
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
?>

<div class="container mt-4 mb-4">
    <h1 class="mb-4">Checkout</h1>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <div class="d-flex gap-3" style="flex-wrap: wrap;">
        <!-- Order Summary -->
        <div style="flex: 1; min-width: 300px;">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title mb-3">Order Summary</h3>

                    <table class="table">
                        <tbody>
                            <?php foreach ($cart as $item): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($item['title']); ?> x <?php echo $item['quantity']; ?></td>
                                    <td class="text-right"><?php echo CURRENCY . number_format($item['price'] * $item['quantity'], 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <tr>
                                <td><strong>Subtotal</strong></td>
                                <td class="text-right"><strong><?php echo CURRENCY . number_format($subtotal, 2); ?></strong></td>
                            </tr>
                            <?php if ($coupon_discount > 0): ?>
                                <tr>
                                    <td>Discount (<?php echo htmlspecialchars($coupon_code); ?>)</td>
                                    <td class="text-right" style="color: var(--success-color);">-<?php echo CURRENCY . number_format($coupon_discount, 2); ?></td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <td>GST (<?php echo GST_PERCENTAGE; ?>%)</td>
                                <td class="text-right"><?php echo CURRENCY . number_format($tax, 2); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Total</strong></td>
                                <td class="text-right"><strong style="font-size: 24px; color: var(--primary-color);"><?php echo CURRENCY . number_format($total, 2); ?></strong></td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Coupon Form -->
                    <form method="POST" action="" class="mt-3">
                        <div class="d-flex gap-2">
                            <input type="text" name="coupon_code" class="form-control" placeholder="Enter coupon code"
                                   value="<?php echo htmlspecialchars($coupon_code); ?>">
                            <button type="submit" name="apply_coupon" class="btn btn-secondary">Apply</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Payment Form -->
        <div style="flex: 1; min-width: 300px;">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title mb-3">Payment Details</h3>

                    <form method="POST" action="<?php echo SITE_URL; ?>/pages/process-payment.php">
                        <div class="form-group">
                            <label class="form-label">Full Name</label>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['name']); ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Payment Method</label>
                            <select name="payment_method" class="form-select" required>
                                <option value="card">Credit/Debit Card</option>
                                <option value="upi">UPI</option>
                                <option value="netbanking">Net Banking</option>
                                <option value="wallet">Wallet (Paytm, PhonePe)</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Card Number</label>
                            <input type="text" name="card_number" class="form-control" placeholder="1234 5678 9012 3456" maxlength="19">
                        </div>

                        <div class="d-flex gap-2">
                            <div class="form-group" style="flex: 1;">
                                <label class="form-label">Expiry Date</label>
                                <input type="text" name="expiry" class="form-control" placeholder="MM/YY" maxlength="5">
                            </div>
                            <div class="form-group" style="flex: 1;">
                                <label class="form-label">CVV</label>
                                <input type="text" name="cvv" class="form-control" placeholder="123" maxlength="3">
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-lock"></i> Your payment information is secure and encrypted.
                        </div>

                        <button type="submit" class="btn btn-lg btn-primary btn-block">
                            Pay <?php echo CURRENCY . number_format($total, 2); ?>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

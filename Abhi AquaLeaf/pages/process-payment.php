<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . SITE_URL . '/pages/login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
    header('Location: ' . SITE_URL . '/pages/products.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . SITE_URL . '/pages/checkout.php');
    exit;
}

$payment_method = $_POST['payment_method'] ?? 'card';

// Calculate totals
$subtotal = array_sum(array_map(function($item) { return $item['price'] * $item['quantity']; }, $cart));
$coupon_discount = 0;

if (isset($_SESSION['coupon'])) {
    $coupon_discount = $_SESSION['coupon']['discount'];
}

$discounted_subtotal = $subtotal - $coupon_discount;
$tax = $discounted_subtotal * (GST_PERCENTAGE / 100);
$total = $discounted_subtotal + $tax;

try {
    $pdo->beginTransaction();

    // Generate order number
    $order_number = 'ORD' . date('Ymd') . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);

    // Create order
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, order_number, total_amount, discount_amount, tax_amount, final_amount, payment_method, payment_status, order_status) VALUES (?, ?, ?, ?, ?, ?, ?, 'completed', 'processing')");
    $stmt->execute([$user_id, $order_number, $subtotal, $coupon_discount, $tax, $total, $payment_method]);
    $order_id = $pdo->lastInsertId();

    // Add order items
    foreach ($cart as $item) {
        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price, subtotal) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$order_id, $item['productId'], $item['quantity'], $item['price'], $item['price'] * $item['quantity']]);
    }

    // Update coupon usage if applied
    if (isset($_SESSION['coupon'])) {
        $stmt = $pdo->prepare("UPDATE coupons SET used_count = used_count + 1 WHERE id = ?");
        $stmt->execute([$_SESSION['coupon']['coupon_id']]);

        $stmt = $pdo->prepare("INSERT INTO coupon_usage (coupon_id, user_id, order_id) VALUES (?, ?, ?)");
        $stmt->execute([$_SESSION['coupon']['coupon_id'], $user_id, $order_id]);

        unset($_SESSION['coupon']);
    }

    $pdo->commit();

    // Clear cart
    unset($_SESSION['cart']);

    // Redirect to order success page
    header('Location: ' . SITE_URL . '/pages/order-success.php?order_id=' . $order_id);
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    $error = 'Payment processing failed. Please try again.';
    $_SESSION['error'] = $error;
    header('Location: ' . SITE_URL . '/pages/checkout.php');
    exit;
}
?>

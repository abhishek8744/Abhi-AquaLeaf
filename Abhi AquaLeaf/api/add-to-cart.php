<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/config.php';

session_start();

$data = json_decode(file_get_contents('php://input'), true);
$productId = $data['productId'] ?? 0;
$quantity = $data['quantity'] ?? 1;

if (!$productId) {
    echo json_encode(['success' => false, 'message' => 'Invalid product']);
    exit;
}

// Check if product exists and is active
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? AND status = 'active'");
$stmt->execute([$productId]);
$product = $stmt->fetch();

if (!$product) {
    echo json_encode(['success' => false, 'message' => 'Product not found']);
    exit;
}

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add or update cart item
$found = false;
foreach ($_SESSION['cart'] as &$item) {
    if ($item['productId'] == $productId) {
        $item['quantity'] += $quantity;
        $found = true;
        break;
    }
}

if (!$found) {
    $_SESSION['cart'][] = [
        'productId' => $productId,
        'quantity' => $quantity,
        'price' => $product['price'],
        'title' => $product['title'],
        'image' => $product['image_url']
    ];
}

echo json_encode(['success' => true, 'message' => 'Product added to cart']);
?>

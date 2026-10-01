<?php
header('Content-Type: application/json');
session_start();

$data = json_decode(file_get_contents('php://input'), true);
$index = $data['index'] ?? 0;
$quantity = $data['quantity'] ?? 1;

if (!isset($_SESSION['cart'][$index])) {
    echo json_encode(['success' => false, 'message' => 'Item not found in cart']);
    exit;
}

$_SESSION['cart'][$index]['quantity'] = $quantity;

echo json_encode(['success' => true, 'message' => 'Cart updated']);
?>

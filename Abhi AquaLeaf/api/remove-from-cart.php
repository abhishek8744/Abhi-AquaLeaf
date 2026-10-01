<?php
header('Content-Type: application/json');
session_start();

$data = json_decode(file_get_contents('php://input'), true);
$index = $data['index'] ?? 0;

if (!isset($_SESSION['cart'][$index])) {
    echo json_encode(['success' => false, 'message' => 'Item not found in cart']);
    exit;
}

array_splice($_SESSION['cart'], $index, 1);

echo json_encode(['success' => true, 'message' => 'Item removed from cart']);
?>

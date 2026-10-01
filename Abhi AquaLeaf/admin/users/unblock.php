<?php
require_once __DIR__ . '/../includes/header.php';

$user_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$user_id) {
    header('Location: index.php');
    exit;
}

// Unblock user
$stmt = $pdo->prepare("UPDATE users SET status = 'active' WHERE id = ?");
$stmt->execute([$user_id]);

header('Location: index.php');
exit;
?>

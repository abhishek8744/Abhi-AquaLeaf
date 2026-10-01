<?php
$pageTitle = 'User Details';
require_once __DIR__ . '/../includes/header.php';

$user_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$user_id) {
    header('Location: index.php');
    exit;
}

// Get user details
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    header('Location: index.php');
    exit;
}

// Get user's orders
$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll();
?>

<div class="mb-3">
    <a href="index.php" class="btn btn-outline">
        <i class="fas fa-arrow-left"></i> Back to Users
    </a>
</div>

<div class="d-flex gap-3" style="flex-wrap: wrap;">
    <!-- User Info -->
    <div style="flex: 1; min-width: 300px;">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title mb-3">User Information</h3>

                <table class="table">
                    <tr>
                        <td><strong>Name:</strong></td>
                        <td><?php echo htmlspecialchars($user['name']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Email:</strong></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Role:</strong></td>
                        <td><?php echo ucfirst($user['role']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td>
                            <span class="badge badge-<?php echo $user['status'] === 'active' ? 'success' : 'danger'; ?>">
                                <?php echo ucfirst($user['status']); ?>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Joined:</strong></td>
                        <td><?php echo date('F j, Y', strtotime($user['created_at'])); ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- User Orders -->
    <div style="flex: 1; min-width: 300px;">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title mb-3">Order History</h3>

                <?php if (count($orders) > 0): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($order['order_number']); ?></td>
                                    <td><?php echo date('M j, Y', strtotime($order['created_at'])); ?></td>
                                    <td><?php echo CURRENCY . number_format($order['final_amount'], 2); ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo $order['order_status'] === 'completed' ? 'success' : 'info'; ?>">
                                            <?php echo ucfirst($order['order_status']); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-muted">No orders yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

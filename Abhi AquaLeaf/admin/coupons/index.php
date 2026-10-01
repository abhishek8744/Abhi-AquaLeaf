<?php
$pageTitle = 'Coupons';
require_once __DIR__ . '/../includes/header.php';

// Get all coupons
$stmt = $pdo->query("SELECT * FROM coupons ORDER BY created_at DESC");
$coupons = $stmt->fetchAll();
?>

<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="card-title mb-0">All Coupons</h3>
            <a href="add.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Coupon
            </a>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Type</th>
                    <th>Value</th>
                    <th>Usage</th>
                    <th>Expiry</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($coupons as $coupon): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($coupon['code']); ?></strong></td>
                        <td><?php echo ucfirst($coupon['discount_type']); ?></td>
                        <td>
                            <?php echo $coupon['discount_type'] === 'flat' ? CURRENCY . number_format($coupon['discount_value'], 2) : $coupon['discount_value'] . '%'; ?>
                        </td>
                        <td><?php echo $coupon['used_count']; ?><?php echo $coupon['usage_limit'] ? '/' . $coupon['usage_limit'] : ''; ?></td>
                        <td><?php echo $coupon['expiry_date'] ? date('M j, Y', strtotime($coupon['expiry_date'])) : 'No expiry'; ?></td>
                        <td>
                            <span class="badge badge-<?php echo $coupon['status'] === 'active' ? 'success' : 'danger'; ?>">
                                <?php echo ucfirst($coupon['status']); ?>
                            </span>
                        </td>
                        <td>
                            <a href="edit.php?id=<?php echo $coupon['id']; ?>" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

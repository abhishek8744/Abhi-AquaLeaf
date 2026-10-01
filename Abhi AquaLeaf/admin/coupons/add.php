<?php
$pageTitle = 'Add Coupon';
require_once __DIR__ . '/../includes/header.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = strtoupper(trim($_POST['code'] ?? ''));
    $discount_type = $_POST['discount_type'] ?? 'flat';
    $discount_value = $_POST['discount_value'] ?? 0;
    $min_order_amount = $_POST['min_order_amount'] ?? 0;
    $max_discount_amount = $_POST['max_discount_amount'] ?? 0;
    $usage_limit = $_POST['usage_limit'] ?? '';
    $expiry_date = $_POST['expiry_date'] ?? '';
    $status = $_POST['status'] ?? 'active';

    if (empty($code) || empty($discount_value)) {
        $error = 'Code and discount value are required.';
    } else {
        $usage_limit = $usage_limit ? (int)$usage_limit : null;
        $expiry_date = $expiry_date ? $expiry_date : null;

        $stmt = $pdo->prepare("INSERT INTO coupons (code, discount_type, discount_value, min_order_amount, max_discount_amount, usage_limit, expiry_date, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        try {
            $stmt->execute([$code, $discount_type, $discount_value, $min_order_amount, $max_discount_amount, $usage_limit, $expiry_date, $status]);
            $success = 'Coupon added successfully!';
            echo "<script>setTimeout(() => window.location.href = 'index.php', 1500);</script>";
        } catch (PDOException $e) {
            $error = 'Failed to add coupon. Code may already exist.';
        }
    }
}
?>

<div class="card">
    <div class="card-body">
        <h3 class="card-title mb-3">Add New Coupon</h3>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label class="form-label">Coupon Code</label>
                <input type="text" name="code" class="form-control" required
                       value="<?php echo htmlspecialchars($_POST['code'] ?? ''); ?>" style="text-transform: uppercase;">
            </div>

            <div class="d-flex gap-2">
                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Discount Type</label>
                    <select name="discount_type" class="form-select">
                        <option value="flat" <?php echo ($_POST['discount_type'] ?? '') === 'flat' ? 'selected' : ''; ?>>Flat Amount</option>
                        <option value="percentage" <?php echo ($_POST['discount_type'] ?? '') === 'percentage' ? 'selected' : ''; ?>>Percentage</option>
                    </select>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Discount Value</label>
                    <input type="number" name="discount_value" class="form-control" required step="0.01"
                           value="<?php echo htmlspecialchars($_POST['discount_value'] ?? ''); ?>">
                </div>
            </div>

            <div class="d-flex gap-2">
                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Min Order Amount</label>
                    <input type="number" name="min_order_amount" class="form-control" step="0.01"
                           value="<?php echo htmlspecialchars($_POST['min_order_amount'] ?? ''); ?>">
                </div>
                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Max Discount Amount</label>
                    <input type="number" name="max_discount_amount" class="form-control" step="0.01"
                           value="<?php echo htmlspecialchars($_POST['max_discount_amount'] ?? ''); ?>">
                </div>
            </div>

            <div class="d-flex gap-2">
                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Usage Limit (Optional)</label>
                    <input type="number" name="usage_limit" class="form-control"
                           value="<?php echo htmlspecialchars($_POST['usage_limit'] ?? ''); ?>">
                </div>
                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Expiry Date (Optional)</label>
                    <input type="date" name="expiry_date" class="form-control"
                           value="<?php echo htmlspecialchars($_POST['expiry_date'] ?? ''); ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="active" <?php echo ($_POST['status'] ?? '') === 'active' ? 'selected' : ''; ?>>Active</option>
                    <option value="inactive" <?php echo ($_POST['status'] ?? '') === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Add Coupon</button>
            <a href="index.php" class="btn btn-outline">Cancel</a>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

<?php
$pageTitle = 'Edit Coupon';
require_once __DIR__ . '/../includes/header.php';

$coupon_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$coupon_id) {
    header('Location: index.php');
    exit;
}

// Get coupon details
$stmt = $pdo->prepare("SELECT * FROM coupons WHERE id = ?");
$stmt->execute([$coupon_id]);
$coupon = $stmt->fetch();

if (!$coupon) {
    header('Location: index.php');
    exit;
}

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

        $stmt = $pdo->prepare("UPDATE coupons SET code = ?, discount_type = ?, discount_value = ?, min_order_amount = ?, max_discount_amount = ?, usage_limit = ?, expiry_date = ?, status = ? WHERE id = ?");
        try {
            $stmt->execute([$code, $discount_type, $discount_value, $min_order_amount, $max_discount_amount, $usage_limit, $expiry_date, $status, $coupon_id]);
            $success = 'Coupon updated successfully!';
        } catch (PDOException $e) {
            $error = 'Failed to update coupon. Code may already exist.';
        }
    }
}
?>

<div class="card">
    <div class="card-body">
        <h3 class="card-title mb-3">Edit Coupon</h3>

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
                       value="<?php echo htmlspecialchars($_POST['code'] ?? $coupon['code']); ?>" style="text-transform: uppercase;">
            </div>

            <div class="d-flex gap-2">
                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Discount Type</label>
                    <select name="discount_type" class="form-select">
                        <option value="flat" <?php echo ($_POST['discount_type'] ?? $coupon['discount_type']) === 'flat' ? 'selected' : ''; ?>>Flat Amount</option>
                        <option value="percentage" <?php echo ($_POST['discount_type'] ?? $coupon['discount_type']) === 'percentage' ? 'selected' : ''; ?>>Percentage</option>
                    </select>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Discount Value</label>
                    <input type="number" name="discount_value" class="form-control" required step="0.01"
                           value="<?php echo htmlspecialchars($_POST['discount_value'] ?? $coupon['discount_value']); ?>">
                </div>
            </div>

            <div class="d-flex gap-2">
                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Min Order Amount</label>
                    <input type="number" name="min_order_amount" class="form-control" step="0.01"
                           value="<?php echo htmlspecialchars($_POST['min_order_amount'] ?? $coupon['min_order_amount']); ?>">
                </div>
                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Max Discount Amount</label>
                    <input type="number" name="max_discount_amount" class="form-control" step="0.01"
                           value="<?php echo htmlspecialchars($_POST['max_discount_amount'] ?? $coupon['max_discount_amount']); ?>">
                </div>
            </div>

            <div class="d-flex gap-2">
                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Usage Limit (Optional)</label>
                    <input type="number" name="usage_limit" class="form-control"
                           value="<?php echo htmlspecialchars($_POST['usage_limit'] ?? $coupon['usage_limit']); ?>">
                </div>
                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Expiry Date (Optional)</label>
                    <input type="date" name="expiry_date" class="form-control"
                           value="<?php echo htmlspecialchars($_POST['expiry_date'] ?? $coupon['expiry_date']); ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="active" <?php echo ($_POST['status'] ?? $coupon['status']) === 'active' ? 'selected' : ''; ?>>Active</option>
                    <option value="inactive" <?php echo ($_POST['status'] ?? $coupon['status']) === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Update Coupon</button>
            <a href="index.php" class="btn btn-outline">Cancel</a>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

<?php
$pageTitle = 'Settings';
require_once __DIR__ . '/../includes/header.php';

$error = '';
$success = '';

// Handle settings update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $settings = [
        'site_name' => $_POST['site_name'] ?? SITE_NAME,
        'site_logo' => $_POST['site_logo'] ?? '',
        'footer_text' => $_POST['footer_text'] ?? '',
        'contact_email' => $_POST['contact_email'] ?? '',
        'contact_phone' => $_POST['contact_phone'] ?? '',
        'stripe_public_key' => $_POST['stripe_public_key'] ?? '',
        'stripe_secret_key' => $_POST['stripe_secret_key'] ?? '',
        'razorpay_key_id' => $_POST['razorpay_key_id'] ?? '',
        'razorpay_key_secret' => $_POST['razorpay_key_secret'] ?? '',
        'paypal_client_id' => $_POST['paypal_client_id'] ?? '',
        'paypal_secret' => $_POST['paypal_secret'] ?? '',
        'gst_percentage' => $_POST['gst_percentage'] ?? GST_PERCENTAGE,
        'currency' => $_POST['currency'] ?? CURRENCY_CODE
    ];

    foreach ($settings as $key => $value) {
        $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
        $stmt->execute([$key, $value, $value]);
    }

    $success = 'Settings updated successfully!';
}

// Get current settings
$stmt = $pdo->query("SELECT * FROM settings");
$settings_list = $stmt->fetchAll();
$current_settings = [];
foreach ($settings_list as $setting) {
    $current_settings[$setting['setting_key']] = $setting['setting_value'];
}
?>

<div class="card">
    <div class="card-body">
        <h3 class="card-title mb-3">Website Settings</h3>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <h4 class="mb-3">General Settings</h4>
            <div class="form-group">
                <label class="form-label">Site Name</label>
                <input type="text" name="site_name" class="form-control"
                       value="<?php echo htmlspecialchars($current_settings['site_name'] ?? SITE_NAME); ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Footer Text</label>
                <input type="text" name="footer_text" class="form-control"
                       value="<?php echo htmlspecialchars($current_settings['footer_text'] ?? ''); ?>">
            </div>

            <div class="d-flex gap-2">
                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Contact Email</label>
                    <input type="email" name="contact_email" class="form-control"
                           value="<?php echo htmlspecialchars($current_settings['contact_email'] ?? ''); ?>">
                </div>
                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Contact Phone</label>
                    <input type="text" name="contact_phone" class="form-control"
                           value="<?php echo htmlspecialchars($current_settings['contact_phone'] ?? ''); ?>">
                </div>
            </div>

            <h4 class="mb-3 mt-4">Payment Gateway Settings</h4>
            <div class="form-group">
                <label class="form-label">Stripe Public Key</label>
                <input type="text" name="stripe_public_key" class="form-control"
                       value="<?php echo htmlspecialchars($current_settings['stripe_public_key'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Stripe Secret Key</label>
                <input type="password" name="stripe_secret_key" class="form-control"
                       value="<?php echo htmlspecialchars($current_settings['stripe_secret_key'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Razorpay Key ID</label>
                <input type="text" name="razorpay_key_id" class="form-control"
                       value="<?php echo htmlspecialchars($current_settings['razorpay_key_id'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Razorpay Key Secret</label>
                <input type="password" name="razorpay_key_secret" class="form-control"
                       value="<?php echo htmlspecialchars($current_settings['razorpay_key_secret'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label class="form-label">PayPal Client ID</label>
                <input type="text" name="paypal_client_id" class="form-control"
                       value="<?php echo htmlspecialchars($current_settings['paypal_client_id'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label class="form-label">PayPal Secret</label>
                <input type="password" name="paypal_secret" class="form-control"
                       value="<?php echo htmlspecialchars($current_settings['paypal_secret'] ?? ''); ?>">
            </div>

            <h4 class="mb-3 mt-4">Tax & Currency Settings</h4>
            <div class="d-flex gap-2">
                <div class="form-group" style="flex: 1;">
                    <label class="form-label">GST Percentage (%)</label>
                    <input type="number" name="gst_percentage" class="form-control" step="0.01"
                           value="<?php echo htmlspecialchars($current_settings['gst_percentage'] ?? GST_PERCENTAGE); ?>">
                </div>
                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Currency Code</label>
                    <input type="text" name="currency" class="form-control"
                           value="<?php echo htmlspecialchars($current_settings['currency'] ?? CURRENCY_CODE); ?>">
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Save Settings</button>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

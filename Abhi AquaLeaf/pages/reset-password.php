<?php
$pageTitle = 'Reset Password';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../config/database.php';

$error = '';
$success = '';
$token = $_GET['token'] ?? '';

// Validate token
$stmt = $pdo->prepare("SELECT pr.*, u.id as user_id FROM password_resets pr JOIN users u ON pr.user_id = u.id WHERE pr.token = ? AND pr.expires_at > NOW()");
$stmt->execute([$token]);
$reset = $stmt->fetch();

if (!$reset) {
    $error = 'Invalid or expired reset token. Please request a new password reset.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $reset) {
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($password) || empty($confirm_password)) {
        $error = 'Please enter both password fields.';
    } elseif (strlen($password) < MIN_PASSWORD_LENGTH) {
        $error = 'Password must be at least ' . MIN_PASSWORD_LENGTH . ' characters.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } else {
        // Update password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        try {
            $stmt->execute([$hashed_password, $reset['user_id']]);

            // Delete the token
            $stmt = $pdo->prepare("DELETE FROM password_resets WHERE token = ?");
            $stmt->execute([$token]);

            $success = 'Password reset successfully! You can now login with your new password.';
            echo "<script>setTimeout(() => window.location.href = '" . SITE_URL . "/pages/login.php', 2000);</script>";
        } catch (PDOException $e) {
            $error = 'Password reset failed. Please try again.';
        }
    }
}
?>

<div class="container mt-4 mb-4">
    <div class="card" style="max-width: 500px; margin: 0 auto;">
        <div class="card-body">
            <h2 class="card-title text-center mb-4">Reset Password</h2>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <?php if ($reset && !$success): ?>
                <form method="POST" action="">
                    <div class="form-group">
                        <label class="form-label">New Password</label>
                        <input type="password" name="password" class="form-control" required
                               minlength="<?php echo MIN_PASSWORD_LENGTH; ?>">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="confirm_password" class="form-control" required
                               minlength="<?php echo MIN_PASSWORD_LENGTH; ?>">
                    </div>

                    <button type="submit" class="btn btn-primary btn-block btn-lg">Reset Password</button>
                </form>
            <?php endif; ?>

            <p class="text-center mt-3">
                <a href="<?php echo SITE_URL; ?>/pages/login.php">Back to Login</a>
            </p>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

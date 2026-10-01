<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' . SITE_NAME : SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Desktop Navbar -->
    <nav class="navbar">
        <div class="container navbar-container">
            <a href="<?php echo SITE_URL; ?>/" class="navbar-brand">
    <img src="<?php echo SITE_URL; ?>/assets/images/logo.png"
         alt="<?php echo SITE_NAME; ?> Logo"
         class="company-logo">
    <span><?php echo SITE_NAME; ?></span>
</a>
            <ul class="navbar-nav">
                <li><a href="<?php echo SITE_URL; ?>/">Home</a></li>
                <li><a href="<?php echo SITE_URL; ?>/pages/products.php">Products</a></li>
                <li><a href="<?php echo SITE_URL; ?>/pages/about.php">About</a></li>
                <li><a href="<?php echo SITE_URL; ?>/pages/contact.php">Contact</a></li>
            </ul>
            <div class="navbar-actions">
                <button class="theme-toggle" onclick="toggleTheme()">
                    <i class="fas fa-moon"></i>
                </button>
                <a href="<?php echo SITE_URL; ?>/pages/cart.php" class="cart-badge">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-count">0</span>
                </a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="<?php echo SITE_URL; ?>/pages/profile.php" class="btn btn-sm btn-outline">
                        <i class="fas fa-user"></i> Profile
                    </a>
                    <a href="<?php echo SITE_URL; ?>/pages/logout.php" class="btn btn-sm btn-danger">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                <?php else: ?>
                    <a href="<?php echo SITE_URL; ?>/pages/login.php" class="btn btn-sm btn-outline">Login</a>
                    <a href="<?php echo SITE_URL; ?>/pages/signup.php" class="btn btn-sm btn-primary">Sign Up</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Mobile Bottom Navigation -->
    <nav class="bottom-nav">
        <ul class="bottom-nav-items">
            <li class="bottom-nav-item">
                <a href="<?php echo SITE_URL; ?>/" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                    <i class="fas fa-home bottom-nav-icon"></i>
                    <span>Home</span>
                </a>
            </li>
            <li class="bottom-nav-item">
                <a href="<?php echo SITE_URL; ?>/pages/products.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'products.php' ? 'active' : ''; ?>">
                    <i class="fas fa-box bottom-nav-icon"></i>
                    <span>Products</span>
                </a>
            </li>
            <li class="bottom-nav-item">
                <a href="<?php echo SITE_URL; ?>/pages/cart.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'cart.php' ? 'active' : ''; ?>">
                    <i class="fas fa-shopping-cart bottom-nav-icon"></i>
                    <span>Cart</span>
                </a>
            </li>
            <li class="bottom-nav-item">
                <a href="<?php echo isset($_SESSION['user_id']) ? SITE_URL . '/pages/profile.php' : SITE_URL . '/pages/login.php'; ?>" class="<?php echo basename($_SERVER['PHP_SELF']) == 'profile.php' || basename($_SERVER['PHP_SELF']) == 'login.php' ? 'active' : ''; ?>">
                    <i class="fas fa-user bottom-nav-icon"></i>
                    <span>Profile</span>
                </a>
            </li>
        </ul>
    </nav>

    <main>

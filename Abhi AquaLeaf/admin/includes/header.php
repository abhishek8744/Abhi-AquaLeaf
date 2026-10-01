<?php
session_start();
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/database.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ' . SITE_URL . '/admin/login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - Admin' : 'Admin Panel'; ?> - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .admin-layout {
            display: flex;
            min-height: 100vh;
        }
        .admin-sidebar {
            width: 250px;
            background: var(--dark-color);
            color: white;
            padding: 20px 0;
            position: fixed;
            height: 100%;
            overflow-y: auto;
        }
        .admin-sidebar .brand {
            padding: 0 20px 20px;
            border-bottom: 1px solid #495057;
            margin-bottom: 20px;
        }
        .admin-sidebar .brand h2 {
            font-size: 20px;
            margin: 0;
        }
        .admin-nav {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .admin-nav li {
            padding: 0;
        }
        .admin-nav a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #adb5bd;
            text-decoration: none;
            transition: var(--transition);
        }
        .admin-nav a:hover,
        .admin-nav a.active {
            background: rgba(255,255,255,0.1);
            color: white;
        }
        .admin-nav a i {
            width: 25px;
            margin-right: 10px;
        }
        .admin-content {
            flex: 1;
            margin-left: 250px;
            padding: 20px;
        }
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border-color);
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border-left: 4px solid var(--primary-color);
        }
        .stat-card h3 {
            font-size: 14px;
            color: var(--text-muted);
            margin: 0 0 10px;
        }
        .stat-card .value {
            font-size: 32px;
            font-weight: 700;
            color: var(--text-color);
        }
        @media (max-width: 768px) {
            .admin-sidebar {
                width: 100%;
                position: relative;
                height: auto;
            }
            .admin-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="brand">
                <a class="navbar-brand d-flex align-items-center" href="index.php">
    <img src="images/logo.png" alt="Abhi AquaLeaf Logo" class="site-logo">
    <span>Abhi AquaLeaf</span>
</a>
                <small>Admin Panel</small>
            </div>
            <ul class="admin-nav">
                <li><a href="<?php echo SITE_URL; ?>/admin/dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="<?php echo SITE_URL; ?>/admin/products/index.php" class="<?php echo strpos($_SERVER['PHP_SELF'], 'products') !== false ? 'active' : ''; ?>"><i class="fas fa-box"></i> Products</a></li>
                <li><a href="<?php echo SITE_URL; ?>/admin/orders/index.php" class="<?php echo strpos($_SERVER['PHP_SELF'], 'orders') !== false ? 'active' : ''; ?>"><i class="fas fa-shopping-cart"></i> Orders</a></li>
                <li><a href="<?php echo SITE_URL; ?>/admin/users/index.php" class="<?php echo strpos($_SERVER['PHP_SELF'], 'users') !== false ? 'active' : ''; ?>"><i class="fas fa-users"></i> Users</a></li>
                <li><a href="<?php echo SITE_URL; ?>/admin/coupons/index.php" class="<?php echo strpos($_SERVER['PHP_SELF'], 'coupons') !== false ? 'active' : ''; ?>"><i class="fas fa-ticket-alt"></i> Coupons</a></li>
                <li><a href="<?php echo SITE_URL; ?>/admin/reports/index.php" class="<?php echo strpos($_SERVER['PHP_SELF'], 'reports') !== false ? 'active' : ''; ?>"><i class="fas fa-chart-bar"></i> Reports</a></li>
                <li><a href="<?php echo SITE_URL; ?>/admin/support/index.php" class="<?php echo strpos($_SERVER['PHP_SELF'], 'support') !== false ? 'active' : ''; ?>"><i class="fas fa-headset"></i> Support</a></li>
                <li><a href="<?php echo SITE_URL; ?>/admin/settings/index.php" class="<?php echo strpos($_SERVER['PHP_SELF'], 'settings') !== false ? 'active' : ''; ?>"><i class="fas fa-cog"></i> Settings</a></li>
                <li><a href="<?php echo SITE_URL; ?>/admin/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="admin-content">
            <div class="admin-header">
                <h1><?php echo isset($pageTitle) ? $pageTitle : 'Dashboard'; ?></h1>
                <div class="d-flex align-items-center gap-2">
                    <span>Welcome, <?php echo htmlspecialchars($_SESSION['admin_name']); ?></span>
                    <a href="<?php echo SITE_URL; ?>/" target="_blank" class="btn btn-sm btn-outline">
                        <i class="fas fa-external-link-alt"></i> View Site
                    </a>
                </div>
            </div>

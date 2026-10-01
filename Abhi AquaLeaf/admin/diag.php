<?php
echo "<h1>Admin Panel Diagnostics</h1>";
echo "<style>body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; } .success { color: green; } .error { color: red; } .info { color: blue; } pre { background: #f5f5f5; padding: 10px; border-radius: 5px; }</style>";

// Check if config files exist
echo "<h2>File Checks</h2>";

if (file_exists(__DIR__ . '/../config/config.php')) {
    echo "<p class='success'>✓ config/config.php exists</p>";
} else {
    echo "<p class='error'>✗ config/config.php missing</p>";
}

if (file_exists(__DIR__ . '/../config/database.php')) {
    echo "<p class='success'>✓ config/database.php exists</p>";
} else {
    echo "<p class='error'>✗ config/database.php missing</p>";
}

if (file_exists(__DIR__ . '/login.php')) {
    echo "<p class='success'>✓ admin/login.php exists</p>";
} else {
    echo "<p class='error'>✗ admin/login.php missing</p>";
}

if (file_exists(__DIR__ . '/dashboard.php')) {
    echo "<p class='success'>✓ admin/dashboard.php exists</p>";
} else {
    echo "<p class='error'>✗ admin/dashboard.php missing</p>";
}

// Check database connection
echo "<h2>Database Connection</h2>";
try {
    require_once __DIR__ . '/../config/database.php';
    echo "<p class='success'>✓ Database connection successful</p>";

    // Check if database exists
    $stmt = $pdo->query("SELECT DATABASE()");
    $db_name = $stmt->fetchColumn();
    echo "<p class='info'>Current database: " . htmlspecialchars($db_name) . "</p>";

    // Check if users table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    if ($stmt->fetch()) {
        echo "<p class='success'>✓ users table exists</p>";

        // Check admin user
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = 'admin@aqualeaf.com'");
        $stmt->execute();
        $admin = $stmt->fetch();

        if ($admin) {
            echo "<p class='success'>✓ Admin user exists</p>";
            echo "<pre>";
            echo "Name: " . htmlspecialchars($admin['name']) . "\n";
            echo "Email: " . htmlspecialchars($admin['email']) . "\n";
            echo "Role: " . htmlspecialchars($admin['role']) . "\n";
            echo "Status: " . htmlspecialchars($admin['status']) . "\n";
            echo "</pre>";
        } else {
            echo "<p class='error'>✗ Admin user not found</p>";
        }
    } else {
        echo "<p class='error'>✗ users table missing</p>";
    }
} catch (PDOException $e) {
    echo "<p class='error'>✗ Database connection failed: " . htmlspecialchars($e->getMessage()) . "</p>";
}

// Check session
echo "<h2>Session Check</h2>";
session_start();
if (isset($_SESSION['admin_logged_in'])) {
    echo "<p class='success'>✓ Admin logged in</p>";
} else {
    echo "<p class='info'>Admin not logged in</p>";
}

// PHP errors
echo "<h2>PHP Configuration</h2>";
echo "<p>PHP Version: " . phpversion() . "</p>";
echo "<p>Error Reporting: " . error_reporting() . "</p>";
echo "<p>Display Errors: " . (ini_get('display_errors') ? 'On' : 'Off') . "</p>";

echo "<h2>Quick Links</h2>";
echo "<p><a href='login.php' class='info'>Go to Admin Login</a></p>";
echo "<p><a href='../reset-admin.php' class='info'>Reset Admin Password</a></p>";
echo "<p><a href='../install.php' class='info'>Run Database Installer</a></p>";
?>

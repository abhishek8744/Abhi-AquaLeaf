<?php
echo "<h1>Admin Login Diagnostic</h1>";
echo "<style>body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; } .success { color: green; } .error { color: red; } .info { color: blue; } pre { background: #f5f5f5; padding: 10px; border-radius: 5px; overflow-x: auto; }</style>";

// Check configuration
echo "<h2>Configuration Check</h2>";

if (file_exists(__DIR__ . '/../config/config.php')) {
    echo "<p class='success'>✓ config/config.php exists</p>";
    require_once __DIR__ . '/../config/config.php';
    echo "<p class='info'>SITE_URL: " . SITE_URL . "</p>";
} else {
    echo "<p class='error'>✗ config/config.php missing</p>";
}

if (file_exists(__DIR__ . '/../config/database.php')) {
    echo "<p class='success'>✓ config/database.php exists</p>";
    require_once __DIR__ . '/../config/database.php';
} else {
    echo "<p class='error'>✗ config/database.php missing</p>";
}

// Check database connection
echo "<h2>Database Connection</h2>";
try {
    echo "<p class='success'>✓ Database connection successful</p>";

    // Check current database
    $stmt = $pdo->query("SELECT DATABASE()");
    $db_name = $stmt->fetchColumn();
    echo "<p class='info'>Current database: " . htmlspecialchars($db_name) . "</p>";

    // Check if users table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    if ($stmt->fetch()) {
        echo "<p class='success'>✓ users table exists</p>";

        // Check all users
        $stmt = $pdo->query("SELECT * FROM users");
        $users = $stmt->fetchAll();

        echo "<h3>All Users in Database:</h3>";
        echo "<pre>";
        foreach ($users as $user) {
            echo "ID: " . $user['id'] . "\n";
            echo "Name: " . htmlspecialchars($user['name']) . "\n";
            echo "Email: " . htmlspecialchars($user['email']) . "\n";
            echo "Role: " . htmlspecialchars($user['role']) . "\n";
            echo "Status: " . htmlspecialchars($user['status']) . "\n";
            echo "---\n";
        }
        echo "</pre>";

        // Test password verification for each user
        echo "<h3>Password Verification Test:</h3>";
        foreach ($users as $user) {
            $test_passwords = ['admin123', 'Komal@182804'];
            foreach ($test_passwords as $test_pass) {
                if (password_verify($test_pass, $user['password'])) {
                    echo "<p class='success'>✓ User " . htmlspecialchars($user['email']) . " password matches: " . $test_pass . "</p>";
                }
            }
        }

        // Check for admin users
        $stmt = $pdo->prepare("SELECT * FROM users WHERE role IN ('admin', 'super_admin')");
        $stmt->execute();
        $admins = $stmt->fetchAll();

        if (count($admins) > 0) {
            echo "<p class='success'>✓ Found " . count($admins) . " admin user(s)</p>";
        } else {
            echo "<p class='error'>✗ No admin users found</p>";
        }

    } else {
        echo "<p class='error'>✗ users table missing</p>";
        echo "<p class='info'>Run installer to create tables</p>";
    }

} catch (PDOException $e) {
    echo "<p class='error'>✗ Database connection failed: " . htmlspecialchars($e->getMessage()) . "</p>";
}

// Check session
echo "<h2>Session Check</h2>";
session_start();
if (isset($_SESSION['admin_logged_in'])) {
    echo "<p class='success'>✓ Admin logged in</p>";
    echo "<p>Admin ID: " . $_SESSION['admin_id'] . "</p>";
    echo "<p>Admin Name: " . htmlspecialchars($_SESSION['admin_name']) . "</p>";
} else {
    echo "<p class='info'>Admin not logged in</p>";
}

// Test login with custom credentials
echo "<h2>Test Login with Custom Credentials</h2>";
$email = 'abhiaqualeaf@gmail.com';
$password = 'Komal@182804';

try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND role IN ('admin', 'super_admin')");
    $stmt->execute([$email]);
    $admin = $stmt->fetch();

    if ($admin) {
        echo "<p class='success'>✓ User found: " . htmlspecialchars($email) . "</p>";

        if (password_verify($password, $admin['password'])) {
            echo "<p class='success'>✓ Password correct for: " . htmlspecialchars($email) . "</p>";
        } else {
            echo "<p class='error'>✗ Password incorrect for: " . htmlspecialchars($email) . "</p>";
            echo "<p class='info'>Try resetting the password</p>";
        }

        if ($admin['status'] === 'blocked') {
            echo "<p class='error'>✗ Account is blocked</p>";
        }
    } else {
        echo "<p class='error'>✗ User not found: " . htmlspecialchars($email) . "</p>";
        echo "<p class='info'>User needs to be created in database</p>";
    }
} catch (PDOException $e) {
    echo "<p class='error'>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}

// Quick links
echo "<h2>Quick Actions</h2>";
echo "<p><a href='fix-password.php' class='info'>Reset Admin Password</a></p>";
echo "<p><a href='../install.php' class='info'>Run Database Installer</a></p>";
echo "<p><a href='login.php' class='info'>Go to Admin Login</a></p>";
?>

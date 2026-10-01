<?php
echo "<h1>Database Check for Admin Login</h1>";
echo "<style>body { font-family: Arial, sans-serif; max-width: 700px; margin: 50px auto; padding: 20px; } .success { color: green; } .error { color: red; } .info { color: blue; } pre { background: #f5f5f5; padding: 10px; border-radius: 5px; }</style>";

$host = 'localhost';
$dbname = 'aqualeaf_db';
$username = 'root';
$password = '';

try {
    // Connect to MySQL server
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p class='success'>✓ Connected to MySQL server</p>";

    // Check if database exists
    $stmt = $pdo->query("SHOW DATABASES LIKE 'aqualeaf_db'");
    if ($stmt->fetch()) {
        echo "<p class='success'>✓ Database 'aqualeaf_db' exists</p>";

        // Connect to database
        $pdo->exec("USE aqualeaf_db");
        echo "<p class='success'>✓ Connected to database</p>";

        // Check if users table exists
        $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
        if ($stmt->fetch()) {
            echo "<p class='success'>✓ users table exists</p>";

            // Get all users
            $stmt = $pdo->query("SELECT * FROM users");
            $users = $stmt->fetchAll();

            echo "<h3>All Users in Database:</h3>";
            if (count($users) > 0) {
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

                // Check for admin users
                $stmt = $pdo->prepare("SELECT * FROM users WHERE role IN ('admin', 'super_admin')");
                $stmt->execute();
                $admins = $stmt->fetchAll();

                if (count($admins) > 0) {
                    echo "<p class='success'>✓ Found " . count($admins) . " admin user(s)</p>";

                    // Test password for abhiaqualeaf@gmail.com
                    $email = 'abhiaqualeaf@gmail.com';
                    $pass = 'Komal@182804';
                    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
                    $stmt->execute([$email]);
                    $user = $stmt->fetch();

                    if ($user) {
                        echo "<p class='info'>✓ User found: " . htmlspecialchars($email) . "</p>";
                        if (password_verify($pass, $user['password'])) {
                            echo "<p class='success'>✓ Password CORRECT for " . htmlspecialchars($email) . "</p>";
                        } else {
                            echo "<p class='error'>✗ Password INCORRECT for " . htmlspecialchars($email) . "</p>";
                            echo "<p class='info'>Need to reset password</p>";
                        }
                    } else {
                        echo "<p class='error'>✗ User NOT found: " . htmlspecialchars($email) . "</p>";
                        echo "<p class='info'>Need to create user</p>";
                    }
                } else {
                    echo "<p class='error'>✗ NO admin users found in database</p>";
                    echo "<p class='info'>Need to create admin user</p>";
                }
            } else {
                echo "<p class='error'>✗ NO users in database</p>";
                echo "<p class='info'>Need to create admin user</p>";
            }
        } else {
            echo "<p class='error'>✗ users table does NOT exist</p>";
            echo "<p class='info'>Need to create table and user</p>";
        }
    } else {
        echo "<p class='error'>✗ Database 'aqualeaf_db' does NOT exist</p>";
        echo "<p class='info'>Need to create database, table, and user</p>";
    }

} catch (PDOException $e) {
    echo "<p class='error'>Error: " . $e->getMessage() . "</p>";
}

echo "<h2>Quick Actions</h2>";
echo "<p><a href='repair-login.php' class='info'>Run Repair Script (Recommended)</a></p>";
echo "<p><a href='../install.php' class='info'>Run Full Installer</a></p>";
?>

<?php
echo "<h1>Repair Admin Login</h1>";
echo "<style>body { font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; } .success { color: green; } .error { color: red; } .info { color: blue; } h2 { margin-top: 30px; }</style>";

try {
    // Connect to MySQL server first
    $host = 'localhost';
    $username = 'root';
    $password = '';

    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "<p class='success'>✓ Connected to MySQL server</p>";

    // Create database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS aqualeaf_db");
    echo "<p class='success'>✓ Database 'aqualeaf_db' exists</p>";

    // Connect to the database
    $pdo->exec("USE aqualeaf_db");
    echo "<p class='success'>✓ Connected to database</p>";

    // Create users table if it doesn't exist
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        role ENUM('user', 'admin', 'super_admin') DEFAULT 'user',
        status ENUM('active', 'blocked') DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )");
    echo "<p class='success'>✓ users table exists</p>";

    // Delete existing admin user with your email
    $stmt = $pdo->prepare("DELETE FROM users WHERE email = 'abhiaqualeaf@gmail.com'");
    $stmt->execute();
    echo "<p class='info'>✓ Removed old admin user (if existed)</p>";

    // Also delete default admin
    $stmt = $pdo->prepare("DELETE FROM users WHERE email = 'admin@aqualeaf.com'");
    $stmt->execute();
    echo "<p class='info'>✓ Removed default admin user (if existed)</p>";

    // Create new admin user with your credentials
    $name = 'Super Admin';
    $email = 'abhiaqualeaf@gmail.com';
    $pass = 'Komal@182804';
    $password_hash = password_hash($pass, PASSWORD_DEFAULT);
    $role = 'super_admin';
    $status = 'active';

    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$name, $email, $password_hash, $role, $status]);

    echo "<p class='success'>✓ Admin user created successfully</p>";

    // Verify the user was created
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        echo "<p class='success'>✓ User verified in database</p>";
        echo "<pre>";
        echo "ID: " . $user['id'] . "\n";
        echo "Name: " . htmlspecialchars($user['name']) . "\n";
        echo "Email: " . htmlspecialchars($user['email']) . "\n";
        echo "Role: " . htmlspecialchars($user['role']) . "\n";
        echo "Status: " . htmlspecialchars($user['status']) . "\n";
        echo "</pre>";

        // Test password verification
        if (password_verify($pass, $user['password'])) {
            echo "<p class='success'>✓ Password verification test PASSED</p>";
        } else {
            echo "<p class='error'>✗ Password verification test FAILED</p>";
        }
    } else {
        echo "<p class='error'>✗ User not found after creation</p>";
    }

    echo "<h2>✅ Admin Login Repaired!</h2>";
    echo "<div style='background: #d4edda; padding: 20px; border-radius: 8px; margin: 20px 0;'>";
    echo "<h3 style='color: #155724;'>Login Credentials:</h3>";
    echo "<p><strong>Email:</strong> abhiaqualeaf@gmail.com</p>";
    echo "<p><strong>Password:</strong> Komal@182804</p>";
    echo "</div>";

    echo "<p><a href='login.php' style='display: inline-block; background: #007bff; color: white; padding: 15px 30px; text-decoration: none; border-radius: 8px; font-size: 18px;'>Go to Admin Login →</a></p>";

} catch (PDOException $e) {
    echo "<p class='error'>Error: " . $e->getMessage() . "</p>";
    echo "<p class='error'>Please ensure XAMPP/MySQL is running</p>";
}
?>

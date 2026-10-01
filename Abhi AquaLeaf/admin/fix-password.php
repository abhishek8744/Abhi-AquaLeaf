<?php
echo "<h1>Fix Admin Password</h1>";
echo "<style>body { font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; } .success { color: green; } .error { color: red; }</style>";

try {
    // Connect to MySQL server first
    $host = 'localhost';
    $username = 'root';
    $password = '';

    $pdo = new PDO("mysql:host=$host;dbname=aqualeaf_db;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "<p class='success'>✓ Connected to database</p>";

    // Delete existing admin user to avoid conflicts
    $stmt = $pdo->prepare("DELETE FROM users WHERE email = 'admin@aqualeaf.com'");
    $stmt->execute();
    echo "<p class='info'>✓ Removed old admin user (if existed)</p>";

    // Create new admin user with fresh password hash
    $name = 'Super Admin';
    $email = 'admin@aqualeaf.com';
    $new_password = 'admin123';
    $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
    $role = 'super_admin';
    $status = 'active';

    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$name, $email, $password_hash, $role, $status]);

    echo "<p class='success'>✓ Admin user created successfully</p>";

    // Verify the password hash works
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($new_password, $user['password'])) {
        echo "<p class='success'>✓ Password verification test passed</p>";
    } else {
        echo "<p class='error'>✗ Password verification test failed</p>";
    }

    echo "<h2>New Login Credentials:</h2>";
    echo "<p><strong>Email:</strong> admin@aqualeaf.com</p>";
    echo "<p><strong>Password:</strong> admin123</p>";
    echo "<p><a href='login.php' style='color: blue; font-size: 18px;'>Click here to Login →</a></p>";

} catch (PDOException $e) {
    echo "<p class='error'>Error: " . $e->getMessage() . "</p>";
    echo "<p class='error'>Make sure database 'aqualeaf_db' exists. Run install.php if needed.</p>";
    echo "<p><a href='../install.php' style='color: blue;'>Run Installer</a></p>";
}
?>

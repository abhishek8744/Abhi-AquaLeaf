<?php
echo "<h1>Reset Admin Password</h1>";
echo "<style>body { font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; } .success { color: green; } .error { color: red; }</style>";

try {
    // Connect to database
    $host = 'localhost';
    $dbname = 'aqualeaf_db';
    $username = 'root';
    $password = '';

    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "<p class='success'>✓ Connected to database.</p>";

    // Check if admin user exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = 'admin@aqualeaf.com'");
    $stmt->execute();
    $admin = $stmt->fetch();

    if ($admin) {
        echo "<p class='success'>✓ Admin user found.</p>";
        echo "<p>Email: " . htmlspecialchars($admin['email']) . "</p>";
        echo "<p>Role: " . htmlspecialchars($admin['role']) . "</p>";
        echo "<p>Status: " . htmlspecialchars($admin['status']) . "</p>";

        // Update password to admin123
        $new_password = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = 'admin@aqualeaf.com'");
        $stmt->execute([$new_password]);

        echo "<p class='success'>✓ Password reset to: admin123</p>";
    } else {
        echo "<p class='error'>✗ Admin user not found. Creating...</p>";

        // Create admin user
        $name = 'Super Admin';
        $email = 'abhiaqualeaf@gmail.com';
        $password = password_hash('Komal@182804', PASSWORD_DEFAULT);
        $role = 'super_admin';
        $status = 'active';

        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $email, $password, $role, $status]);

        echo "<p class='success'>✓ Admin user created.</p>";
    }

    echo "<h2>Login Credentials:</h2>";
    echo "<p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>";
    echo "<p><strong>Password:</strong> Komal@182804</p>";
    echo "<p><a href='/admin/login.php' style='color: blue;'>Go to Admin Login</a></p>";

} catch (PDOException $e) {
    echo "<p class='error'>Error: " . $e->getMessage() . "</p>";
    echo "<p class='error'>Please run install.php first to set up the database.</p>";
    echo "<p><a href='/install.php' style='color: blue;'>Run Installer</a></p>";
}
?>

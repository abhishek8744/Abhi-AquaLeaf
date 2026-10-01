<?php
echo "<h1>Reset Admin Password (Production)</h1>";
echo "<style>body { font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; } .success { color: green; } .error { color: red; } .info { color: blue; }</style>";

// Production database credentials - UPDATE THESE WITH YOUR HOSTINGER CREDENTIALS
$host = 'localhost';
$dbname = 'YOUR_DATABASE_NAME'; // e.g., u123456789_aqualeaf_db
$username = 'YOUR_DATABASE_USERNAME'; // e.g., u123456789_user
$password = 'YOUR_DATABASE_PASSWORD'; // Your password from Hostinger

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "<p class='success'>✓ Connected to database.</p>";

    // Check if admin user exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = 'abhiaqualeaf@gmail.com'");
    $stmt->execute();
    $admin = $stmt->fetch();

    if ($admin) {
        echo "<p class='success'>✓ Admin user found.</p>";
        echo "<p>Email: " . htmlspecialchars($admin['email']) . "</p>";
        echo "<p>Role: " . htmlspecialchars($admin['role']) . "</p>";
        echo "<p>Status: " . htmlspecialchars($admin['status']) . "</p>";

        // Update password
        $new_password = password_hash('Komal@182804', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = 'abhiaqualeaf@gmail.com'");
        $stmt->execute([$new_password]);

        echo "<p class='success'>✓ Password reset to: Komal@182804</p>";
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
    echo "<p><strong>Email:</strong> abhiaqualeaf@gmail.com</p>";
    echo "<p><strong>Password:</strong> Komal@182804</p>";
    echo "<p><a href='/admin/login.php' class='info'>Go to Admin Login</a></p>";

} catch (PDOException $e) {
    echo "<p class='error'>Error: " . $e->getMessage() . "</p>";
    echo "<p class='error'>Please update the database credentials at the top of this file with your Hostinger credentials.</p>";
    echo "<p><a href='/install.php' class='info'>Run Installer Instead</a></p>";
}
?>

<?php
echo "<h1>Create Admin User</h1>";
echo "<style>body { font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; } .success { color: green; } .error { color: red; } .info { color: blue; }</style>";

// Configuration - UPDATE THESE FOR HOSTINGER
$host = 'localhost';
$dbname = 'aqualeaf_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "<p class='success'>✓ Connected to database</p>";

    // Delete existing admin user (to avoid conflicts)
    $stmt = $pdo->prepare("DELETE FROM users WHERE email = 'abhiaqualeaf@gmail.com'");
    $stmt->execute();
    echo "<p class='info'>✓ Removed old admin user (if existed)</p>";

    // Create new admin user
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
        echo "Name: " . htmlspecialchars($user['name']) . "\n";
        echo "Email: " . htmlspecialchars($user['email']) . "\n";
        echo "Role: " . htmlspecialchars($user['role']) . "\n";
        echo "Status: " . htmlspecialchars($user['status']) . "\n";
        echo "</pre>";

        // Test password verification
        if (password_verify($pass, $user['password'])) {
            echo "<p class='success'>✓ Password verification test passed</p>";
        } else {
            echo "<p class='error'>✗ Password verification test failed</p>";
        }
    }

    echo "<h2>Login Credentials:</h2>";
    echo "<p><strong>Email:</strong> abhiaqualeaf@gmail.com</p>";
    echo "<p><strong>Password:</strong> Komal@182804</p>";
    echo "<p><a href='login.php' class='info' style='font-size: 18px;'>Click here to Login →</a></p>";

} catch (PDOException $e) {
    echo "<p class='error'>Error: " . $e->getMessage() . "</p>";
    echo "<p class='error'>For Hostinger, update the database credentials at the top of this file</p>";
    echo "<p><a href='../install.php' class='info'>Run Installer Instead</a></p>";
}
?>

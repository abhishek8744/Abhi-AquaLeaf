<?php
echo "<h1>Abhi AquaLeaf - Database Installation</h1>";
echo "<style>body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; } p { margin: 10px 0; } .success { color: green; } .error { color: red; } .info { color: blue; }</style>";

try {
    // Connect to MySQL server
    $host = 'localhost';
    $username = 'root';
    $password = '';

    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "<p class='success'>✓ Connected to MySQL server.</p>";

    // Drop existing database if it exists (for clean install)
    $pdo->exec("DROP DATABASE IF EXISTS aqualeaf_db");
    echo "<p class='info'>✓ Dropped existing database (if any).</p>";

    // Create database
    $pdo->exec("CREATE DATABASE aqualeaf_db");
    echo "<p class='success'>✓ Database 'aqualeaf_db' created.</p>";

    // Connect to the database
    $pdo->exec("USE aqualeaf_db");
    echo "<p class='success'>✓ Connected to database.</p>";

    // Read and execute schema
    $schema = file_get_contents(__DIR__ . '/database/schema.sql');
    $pdo->exec($schema);
    echo "<p class='success'>✓ Database schema imported successfully.</p>";

    // Verify tables
    $tables = ['users', 'products', 'orders', 'order_items', 'coupons', 'support_tickets', 'faqs', 'settings', 'product_screenshots', 'coupon_usage', 'ticket_messages', 'password_resets', 'cart'];
    foreach ($tables as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($stmt->fetch()) {
            echo "<p class='success'>✓ Table '$table' exists.</p>";
        } else {
            echo "<p class='error'>✗ Table '$table' missing.</p>";
        }
    }

    // Verify admin user
    $stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE email = 'admin@aqualeaf.com'");
    if ($stmt->fetchColumn() > 0) {
        echo "<p class='success'>✓ Default admin user exists.</p>";
    } else {
        echo "<p class='error'>✗ Default admin user missing.</p>";
    }

    // Verify products table has category column
    $stmt = $pdo->query("SHOW COLUMNS FROM products LIKE 'category'");
    if ($stmt->fetch()) {
        echo "<p class='success'>✓ Products table has 'category' column.</p>";
    } else {
        echo "<p class='error'>✗ Products table missing 'category' column.</p>";
    }

    echo "<h2 class='success'>Installation Complete!</h2>";
    echo "<p><strong>Admin Login:</strong></p>";
    echo "<ul>";
    echo "<li>Email: admin@aqualeaf.com</li>";
    echo "<li>Password: admin123</li>";
    echo "</ul>";
    echo "<p><a href='/' class='info'>Go to Website</a> | <a href='/admin/' class='info'>Go to Admin Panel</a></p>";

} catch (PDOException $e) {
    echo "<p class='error'>Error: " . $e->getMessage() . "</p>";
    echo "<p class='error'>Please ensure:</p>";
    echo "<ul>";
    echo "<li>XAMPP/MySQL server is running</li>";
    echo "<li>Database credentials are correct (root with no password)</li>";
    echo "<li>You have permission to create databases</li>";
    echo "</ul>";
}
?>

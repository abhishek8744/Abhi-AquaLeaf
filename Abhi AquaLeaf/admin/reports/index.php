<?php
$pageTitle = 'Reports';
require_once __DIR__ . '/../includes/header.php';

// Get sales data
$stmt = $pdo->query("SELECT DATE(created_at) as date, COUNT(*) as orders, SUM(final_amount) as revenue FROM orders WHERE payment_status = 'completed' GROUP BY DATE(created_at) ORDER BY date DESC LIMIT 30");
$daily_sales = $stmt->fetchAll();

// Get monthly sales
$stmt = $pdo->query("SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as orders, SUM(final_amount) as revenue FROM orders WHERE payment_status = 'completed' GROUP BY DATE_FORMAT(created_at, '%Y-%m') ORDER BY month DESC LIMIT 12");
$monthly_sales = $stmt->fetchAll();

// Get top products
$stmt = $pdo->query("SELECT p.*, COUNT(oi.id) as order_count, SUM(oi.quantity) as total_sold FROM products p LEFT JOIN order_items oi ON p.id = oi.product_id GROUP BY p.id ORDER BY total_sold DESC LIMIT 10");
$top_products = $stmt->fetchAll();

// Calculate totals
$total_revenue = array_sum(array_column($daily_sales, 'revenue'));
$total_orders = array_sum(array_column($daily_sales, 'orders'));
?>

<div class="stats-grid">
    <div class="stat-card">
        <h3>Total Revenue</h3>
        <div class="value"><?php echo CURRENCY . number_format($total_revenue, 2); ?></div>
    </div>
    <div class="stat-card" style="border-left-color: var(--success-color);">
        <h3>Total Orders</h3>
        <div class="value"><?php echo $total_orders; ?></div>
    </div>
    <div class="stat-card" style="border-left-color: var(--warning-color);">
        <h3>Average Order Value</h3>
        <div class="value"><?php echo $total_orders > 0 ? CURRENCY . number_format($total_revenue / $total_orders, 2) : CURRENCY . '0.00'; ?></div>
    </div>
    <div class="stat-card" style="border-left-color: var(--info-color);">
        <h3>Tax Collected</h3>
        <div class="value"><?php echo CURRENCY . number_format($total_revenue * (GST_PERCENTAGE / 100), 2); ?></div>
    </div>
</div>

<div class="d-flex gap-3" style="flex-wrap: wrap;">
    <!-- Daily Sales -->
    <div style="flex: 1; min-width: 300px;">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title mb-3">Daily Sales (Last 30 Days)</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Orders</th>
                            <th>Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($daily_sales as $sale): ?>
                            <tr>
                                <td><?php echo date('M j, Y', strtotime($sale['date'])); ?></td>
                                <td><?php echo $sale['orders']; ?></td>
                                <td><?php echo CURRENCY . number_format($sale['revenue'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Monthly Sales -->
    <div style="flex: 1; min-width: 300px;">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title mb-3">Monthly Sales (Last 12 Months)</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th>Orders</th>
                            <th>Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($monthly_sales as $sale): ?>
                            <tr>
                                <td><?php echo date('F Y', strtotime($sale['month'] . '-01')); ?></td>
                                <td><?php echo $sale['orders']; ?></td>
                                <td><?php echo CURRENCY . number_format($sale['revenue'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Top Products -->
<div class="card mt-3">
    <div class="card-body">
        <h3 class="card-title mb-3">Top Selling Products</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Total Sold</th>
                    <th>Revenue</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($top_products as $product): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['title']); ?></td>
                        <td><?php echo CURRENCY . number_format($product['price'], 2); ?></td>
                        <td><?php echo $product['total_sold']; ?></td>
                        <td><?php echo CURRENCY . number_format($product['price'] * $product['total_sold'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

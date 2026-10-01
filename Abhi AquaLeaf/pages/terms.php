<?php
$pageTitle = 'Terms & Conditions';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container mt-4 mb-4">
    <h1 class="text-center mb-4">Terms & Conditions</h1>

    <div class="card" style="max-width: 800px; margin: 0 auto;">
        <div class="card-body">
            <h2 class="card-title mb-3">Acceptance of Terms</h2>
            <p class="card-text mb-3">
                By accessing and using <?php echo SITE_NAME; ?>, you accept and agree to be bound by the terms and provisions of this agreement.
            </p>

            <h2 class="card-title mb-3">Products and Services</h2>
            <p class="card-text mb-3">
                We reserve the right to modify, suspend, or discontinue any product or service at any time without prior notice. We are not liable to you or any third party for any modification, suspension, or discontinuation.
            </p>

            <h2 class="card-title mb-3">Pricing and Payment</h2>
            <p class="card-text mb-3">
                All prices are listed in Indian Rupees (INR) and are subject to change without notice. We accept various payment methods including credit/debit cards, UPI, net banking, and digital wallets.
            </p>

            <h2 class="card-title mb-3">Orders and Delivery</h2>
            <p class="card-text mb-3">
                We strive to deliver all orders within the estimated time frames. However, we are not responsible for delays caused by circumstances beyond our control.
            </p>

            <h2 class="card-title mb-3">Returns and Refunds</h2>
            <p class="card-text mb-3">
                Due to the nature of our products, we do not accept returns unless the product is damaged or defective. Please contact us within 48 hours of delivery for any issues.
            </p>

            <h2 class="card-title mb-3">User Accounts</h2>
            <p class="card-text mb-3">
                You are responsible for maintaining the confidentiality of your account information and for all activities that occur under your account. You agree to notify us immediately of any unauthorized use.
            </p>

            <h2 class="card-title mb-3">Intellectual Property</h2>
            <p class="card-text mb-3">
                All content on this website, including text, graphics, logos, images, and software, is the property of <?php echo SITE_NAME; ?> or its content suppliers and is protected by intellectual property laws.
            </p>

            <h2 class="card-title mb-3">Limitation of Liability</h2>
            <p class="card-text mb-3">
                In no event shall <?php echo SITE_NAME; ?> be liable for any indirect, incidental, special, consequential, or punitive damages arising out of or related to your use of our products or services.
            </p>

            <h2 class="card-title mb-3">Governing Law</h2>
            <p class="card-text mb-3">
                These terms shall be governed by and construed in accordance with the laws of India. Any disputes shall be subject to the exclusive jurisdiction of the courts in India.
            </p>

            <h2 class="card-title mb-3">Changes to Terms</h2>
            <p class="card-text">
                We reserve the right to modify these terms at any time. Your continued use of the website after any changes constitutes acceptance of the new terms.
            </p>

            <p class="text-muted mt-4">Last updated: <?php echo date('F j, Y'); ?></p>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

<?php
$pageTitle = 'About Us';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container mt-4 mb-4">
    <h1 class="text-center mb-4">About <?php echo SITE_NAME; ?></h1>

    <div class="card mb-4">
        <div class="card-body">
            <h2 class="card-title mb-3">Our Story</h2>
            <p class="card-text">
                <?php echo SITE_NAME; ?> was founded with a simple mission: to provide clean, safe, and refreshing drinking water to everyone. We believe that access to clean water is a fundamental right, and we're committed to making it accessible to all.
            </p>
            <p class="card-text">
                Since our inception, we have been dedicated to delivering the highest quality packaged drinking water. Our state-of-the-art purification process ensures that every bottle meets the strictest quality standards.
            </p>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h2 class="card-title mb-3">Our Mission</h2>
            <p class="card-text">
                To be the most trusted provider of packaged drinking water, delivering quality products with exceptional service, while promoting health and wellness in our communities.
            </p>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h2 class="card-title mb-3">Our Values</h2>
            <div class="products-grid">
                <div class="card p-3">
                    <h3><i class="fas fa-tint" style="color: var(--primary-color);"></i> Quality</h3>
                    <p>We never compromise on quality. Every bottle undergoes rigorous testing.</p>
                </div>
                <div class="card p-3">
                    <h3><i class="fas fa-heart" style="color: var(--primary-color);"></i> Health</h3>
                    <p>We are committed to promoting health and wellness through clean water.</p>
                </div>
                <div class="card p-3">
                    <h3><i class="fas fa-leaf" style="color: var(--primary-color);"></i> Sustainability</h3>
                    <p>We are committed to environmentally responsible practices.</p>
                </div>
                <div class="card p-3">
                    <h3><i class="fas fa-users" style="color: var(--primary-color);"></i> Community</h3>
                    <p>We believe in giving back to the communities we serve.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h2 class="card-title mb-3">Why Choose Us?</h2>
            <ul class="card-text">
                <li class="mb-2"><i class="fas fa-check" style="color: var(--success-color);"></i> Multi-stage purification process</li>
                <li class="mb-2"><i class="fas fa-check" style="color: var(--success-color);"></i> Certified by relevant authorities</li>
                <li class="mb-2"><i class="fas fa-check" style="color: var(--success-color);"></i> Fast and reliable delivery</li>
                <li class="mb-2"><i class="fas fa-check" style="color: var(--success-color);"></i> Competitive pricing</li>
                <li class="mb-2"><i class="fas fa-check" style="color: var(--success-color);"></i> Excellent customer service</li>
            </ul>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

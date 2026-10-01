<?php
$pageTitle = 'Contact Us';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../config/database.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format.';
    } else {
        // In production, send email or save to database
        $success = 'Thank you for contacting us! We will get back to you soon.';
    }
}
?>

<div class="container mt-4 mb-4">
    <h1 class="text-center mb-4">Contact Us</h1>

    <div class="d-flex gap-3" style="flex-wrap: wrap;">
        <!-- Contact Form -->
        <div style="flex: 1; min-width: 300px;">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title mb-3">Send us a Message</h3>

                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>

                    <?php if ($success): ?>
                        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="form-group">
                            <label class="form-label">Your Name</label>
                            <input type="text" name="name" class="form-control" required
                                   value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" required
                                   value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Subject</label>
                            <input type="text" name="subject" class="form-control" required
                                   value="<?php echo htmlspecialchars($_POST['subject'] ?? ''); ?>">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Message</label>
                            <textarea name="message" class="form-control" rows="5" required><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">Send Message</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Contact Info -->
        <div style="flex: 1; min-width: 300px;">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title mb-3">Contact Information</h3>

                    <div class="mb-3">
                        <h4><i class="fas fa-map-marker-alt" style="color: var(--primary-color);"></i> Address</h4>
                        <p>123 Water Street, Aqua City, India</p>
                    </div>

                    <div class="mb-3">
                        <h4><i class="fas fa-phone" style="color: var(--primary-color);"></i> Phone</h4>
                        <p>+91 9876543210</p>
                    </div>

                    <div class="mb-3">
                        <h4><i class="fas fa-envelope" style="color: var(--primary-color);"></i> Email</h4>
                        <p>contact@aqualeaf.com</p>
                    </div>

                    <div class="mb-3">
                        <h4><i class="fas fa-clock" style="color: var(--primary-color);"></i> Business Hours</h4>
                        <p>Monday - Friday: 9:00 AM - 6:00 PM</p>
                        <p>Saturday: 10:00 AM - 4:00 PM</p>
                        <p>Sunday: Closed</p>
                    </div>

                    <div class="mt-4">
                        <h4>Follow Us</h4>
                        <div class="d-flex gap-2 mt-2">
                            <a href="#" class="btn btn-outline"><i class="fab fa-facebook"></i></a>
                            <a href="#" class="btn btn-outline"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="btn btn-outline"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="btn btn-outline"><i class="fab fa-linkedin"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

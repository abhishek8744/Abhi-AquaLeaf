<?php
$pageTitle = 'FAQ';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../config/database.php';

// Get FAQs
$stmt = $pdo->query("SELECT * FROM faqs WHERE status = 'active' ORDER BY display_order");
$faqs = $stmt->fetchAll();
?>

<div class="container mt-4 mb-4">
    <h1 class="text-center mb-4">Frequently Asked Questions</h1>

    <div class="card" style="max-width: 800px; margin: 0 auto;">
        <div class="card-body">
            <?php foreach ($faqs as $index => $faq): ?>
                <div class="mb-3" style="border-bottom: 1px solid var(--border-color); padding-bottom: 20px;">
                    <h3 style="cursor: pointer;" onclick="toggleFaq(<?php echo $index; ?>)">
                        <i class="fas fa-chevron-down" id="faq-icon-<?php echo $index; ?>"></i>
                        <?php echo htmlspecialchars($faq['question']); ?>
                    </h3>
                    <div id="faq-answer-<?php echo $index; ?>" style="display: none; margin-top: 10px; color: var(--text-muted);">
                        <?php echo nl2br(htmlspecialchars($faq['answer'])); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="text-center mt-4">
        <p>Still have questions? <a href="<?php echo SITE_URL; ?>/pages/contact.php">Contact us</a> or <a href="<?php echo SITE_URL; ?>/pages/support.php">Submit a support ticket</a></p>
    </div>
</div>

<script>
function toggleFaq(index) {
    const answer = document.getElementById('faq-answer-' + index);
    const icon = document.getElementById('faq-icon-' + index);

    if (answer.style.display === 'none') {
        answer.style.display = 'block';
        icon.classList.remove('fa-chevron-down');
        icon.classList.add('fa-chevron-up');
    } else {
        answer.style.display = 'none';
        icon.classList.remove('fa-chevron-up');
        icon.classList.add('fa-chevron-down');
    }
}
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

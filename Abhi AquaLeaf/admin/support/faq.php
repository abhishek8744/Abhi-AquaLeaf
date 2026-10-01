<?php
$pageTitle = 'FAQ Management';
require_once __DIR__ . '/../includes/header.php';

$error = '';
$success = '';

// Handle add/edit FAQ
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_faq'])) {
    $question = trim($_POST['question'] ?? '');
    $answer = trim($_POST['answer'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $display_order = $_POST['display_order'] ?? 0;
    $status = $_POST['status'] ?? 'active';

    if (empty($question) || empty($answer)) {
        $error = 'Question and answer are required.';
    } else {
        $stmt = $pdo->prepare("INSERT INTO faqs (question, answer, category, display_order, status) VALUES (?, ?, ?, ?, ?)");
        try {
            $stmt->execute([$question, $answer, $category, $display_order, $status]);
            $success = 'FAQ added successfully!';
        } catch (PDOException $e) {
            $error = 'Failed to add FAQ.';
        }
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $faq_id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM faqs WHERE id = ?");
    $stmt->execute([$faq_id]);
    header('Location: faq.php');
    exit;
}

// Get all FAQs
$stmt = $pdo->query("SELECT * FROM faqs ORDER BY display_order, created_at DESC");
$faqs = $stmt->fetchAll();
?>

<div class="card">
    <div class="card-body">
        <h3 class="card-title mb-3">FAQ Management</h3>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <!-- Add FAQ Form -->
        <form method="POST" action="" class="mb-4" style="background: var(--light-color); padding: 20px; border-radius: var(--radius);">
            <h4 class="mb-3">Add New FAQ</h4>
            <div class="form-group">
                <label class="form-label">Question</label>
                <input type="text" name="question" class="form-control" required
                       value="<?php echo htmlspecialchars($_POST['question'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Answer</label>
                <textarea name="answer" class="form-control" rows="3" required><?php echo htmlspecialchars($_POST['answer'] ?? ''); ?></textarea>
            </div>

            <div class="d-flex gap-2">
                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Category</label>
                    <input type="text" name="category" class="form-control"
                           value="<?php echo htmlspecialchars($_POST['category'] ?? ''); ?>">
                </div>
                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Display Order</label>
                    <input type="number" name="display_order" class="form-control"
                           value="<?php echo htmlspecialchars($_POST['display_order'] ?? '0'); ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="active" <?php echo ($_POST['status'] ?? '') === 'active' ? 'selected' : ''; ?>>Active</option>
                    <option value="inactive" <?php echo ($_POST['status'] ?? '') === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                </select>
            </div>

            <button type="submit" name="save_faq" class="btn btn-primary">Add FAQ</button>
        </form>

        <!-- FAQ List -->
        <table class="table">
            <thead>
                <tr>
                    <th>Question</th>
                    <th>Category</th>
                    <th>Order</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($faqs as $faq): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($faq['question']); ?></td>
                        <td><?php echo htmlspecialchars($faq['category'] ?? '-'); ?></td>
                        <td><?php echo $faq['display_order']; ?></td>
                        <td>
                            <span class="badge badge-<?php echo $faq['status'] === 'active' ? 'success' : 'danger'; ?>">
                                <?php echo ucfirst($faq['status']); ?>
                            </span>
                        </td>
                        <td>
                            <a href="faq.php?delete=<?php echo $faq['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this FAQ?');">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

<?php
$pageTitle = 'Add Product';
require_once __DIR__ . '/../includes/header.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
    $description = trim($_POST['description'] ?? '');
    $price = $_POST['price'] ?? 0;
    $category = trim($_POST['category'] ?? '');
    $status = $_POST['status'] ?? 'active';

    if (empty($title) || empty($price)) {
        $error = 'Title and price are required.';
    } else {
        // Handle image upload
        $image_url = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = __DIR__ . '/../../assets/images/uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $ext;
            $filepath = $upload_dir . $filename;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $filepath)) {
                $image_url = SITE_URL . '/assets/images/uploads/' . $filename;
            }
        }

        $stmt = $pdo->prepare("INSERT INTO products (title, slug, description, price, category, image_url, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        try {
            $stmt->execute([$title, $slug, $description, $price, $category, $image_url, $status]);
            $success = 'Product added successfully!';
            echo "<script>setTimeout(() => window.location.href = 'index.php', 1500);</script>";
        } catch (PDOException $e) {
            $error = 'Failed to add product. Please try again.';
        }
    }
}
?>

<div class="card">
    <div class="card-body">
        <h3 class="card-title mb-3">Add New Product</h3>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form method="POST" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label class="form-label">Product Title</label>
                <input type="text" name="title" class="form-control" required
                       value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="5"><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
            </div>

            <div class="d-flex gap-2">
                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Price (₹)</label>
                    <input type="number" name="price" class="form-control" required step="0.01"
                           value="<?php echo htmlspecialchars($_POST['price'] ?? ''); ?>">
                </div>
                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Category</label>
                    <input type="text" name="category" class="form-control"
                           value="<?php echo htmlspecialchars($_POST['category'] ?? ''); ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Product Image</label>
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>

            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="active" <?php echo ($_POST['status'] ?? '') === 'active' ? 'selected' : ''; ?>>Active</option>
                    <option value="inactive" <?php echo ($_POST['status'] ?? '') === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Add Product</button>
            <a href="index.php" class="btn btn-outline">Cancel</a>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

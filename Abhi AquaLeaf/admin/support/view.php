<?php
$pageTitle = 'Ticket Details';
require_once __DIR__ . '/../includes/header.php';

$ticket_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$ticket_id) {
    header('Location: index.php');
    exit;
}

// Get ticket details
$stmt = $pdo->prepare("SELECT st.*, u.name, u.email FROM support_tickets st JOIN users u ON st.user_id = u.id WHERE st.id = ?");
$stmt->execute([$ticket_id]);
$ticket = $stmt->fetch();

if (!$ticket) {
    header('Location: index.php');
    exit;
}

// Get ticket messages
$stmt = $pdo->prepare("SELECT tm.*, u.name FROM ticket_messages tm LEFT JOIN users u ON tm.user_id = u.id WHERE tm.ticket_id = ? ORDER BY tm.created_at ASC");
$stmt->execute([$ticket_id]);
$messages = $stmt->fetchAll();

// Handle reply
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reply'])) {
    $message = trim($_POST['message'] ?? '');

    if (!empty($message)) {
        $stmt = $pdo->prepare("INSERT INTO ticket_messages (ticket_id, message, is_admin_reply) VALUES (?, ?, TRUE)");
        $stmt->execute([$ticket_id, $message]);

        // Update ticket status to in_progress
        $stmt = $pdo->prepare("UPDATE support_tickets SET status = 'in_progress' WHERE id = ?");
        $stmt->execute([$ticket_id]);

        header('Location: view.php?id=' . $ticket_id);
        exit;
    }
}

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $status = $_POST['status'];
    $stmt = $pdo->prepare("UPDATE support_tickets SET status = ? WHERE id = ?");
    $stmt->execute([$status, $ticket_id]);
    $ticket['status'] = $status;
}
?>

<div class="mb-3">
    <a href="index.php" class="btn btn-outline">
        <i class="fas fa-arrow-left"></i> Back to Tickets
    </a>
</div>

<div class="d-flex gap-3" style="flex-wrap: wrap;">
    <!-- Ticket Info -->
    <div style="flex: 1; min-width: 300px;">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title mb-3">Ticket Information</h3>

                <table class="table">
                    <tr>
                        <td><strong>Ticket ID:</strong></td>
                        <td>#<?php echo $ticket['id']; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Customer:</strong></td>
                        <td><?php echo htmlspecialchars($ticket['name']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Email:</strong></td>
                        <td><?php echo htmlspecialchars($ticket['email']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Subject:</strong></td>
                        <td><?php echo htmlspecialchars($ticket['subject']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td>
                            <span class="badge badge-<?php echo $ticket['status'] === 'resolved' ? 'success' : ($ticket['status'] === 'in_progress' ? 'warning' : 'info'); ?>">
                                <?php echo ucfirst(str_replace('_', ' ', $ticket['status'])); ?>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Created:</strong></td>
                        <td><?php echo date('F j, Y g:i A', strtotime($ticket['created_at'])); ?></td>
                    </tr>
                </table>

                <form method="POST" action="">
                    <div class="form-group">
                        <label class="form-label">Update Status</label>
                        <select name="status" class="form-select">
                            <option value="open" <?php echo $ticket['status'] === 'open' ? 'selected' : ''; ?>>Open</option>
                            <option value="in_progress" <?php echo $ticket['status'] === 'in_progress' ? 'selected' : ''; ?>>In Progress</option>
                            <option value="resolved" <?php echo $ticket['status'] === 'resolved' ? 'selected' : ''; ?>>Resolved</option>
                            <option value="closed" <?php echo $ticket['status'] === 'closed' ? 'selected' : ''; ?>>Closed</option>
                        </select>
                    </div>
                    <button type="submit" name="update_status" class="btn btn-primary">Update Status</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Messages -->
    <div style="flex: 1; min-width: 300px;">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title mb-3">Messages</h3>

                <div style="max-height: 400px; overflow-y: auto; margin-bottom: 20px;">
                    <?php foreach ($messages as $msg): ?>
                        <div class="card p-2 mb-2" style="background: <?php echo $msg['is_admin_reply'] ? 'var(--primary-color)' : 'var(--light-color)'; ?>; color: <?php echo $msg['is_admin_reply'] ? 'white' : 'var(--text-color)'; ?>;">
                            <small><?php echo $msg['is_admin_reply'] ? 'Admin' : htmlspecialchars($msg['name'] ?? 'Customer'); ?> - <?php echo date('M j, g:i A', strtotime($msg['created_at'])); ?></small>
                            <p class="mt-1 mb-0"><?php echo nl2br(htmlspecialchars($msg['message'])); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>

                <form method="POST" action="">
                    <div class="form-group">
                        <label class="form-label">Reply</label>
                        <textarea name="message" class="form-control" rows="3" required></textarea>
                    </div>
                    <button type="submit" name="reply" class="btn btn-primary">Send Reply</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

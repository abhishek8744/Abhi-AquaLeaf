<?php
$pageTitle = 'Support Tickets';
require_once __DIR__ . '/../includes/header.php';

// Get all tickets
$stmt = $pdo->query("SELECT st.*, u.name FROM support_tickets st JOIN users u ON st.user_id = u.id ORDER BY st.created_at DESC");
$tickets = $stmt->fetchAll();
?>

<div class="card">
    <div class="card-body">
        <h3 class="card-title mb-3">All Support Tickets</h3>

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tickets as $ticket): ?>
                    <tr>
                        <td>#<?php echo $ticket['id']; ?></td>
                        <td><?php echo htmlspecialchars($ticket['name']); ?></td>
                        <td><?php echo htmlspecialchars($ticket['subject']); ?></td>
                        <td>
                            <span class="badge badge-<?php echo $ticket['status'] === 'resolved' ? 'success' : ($ticket['status'] === 'in_progress' ? 'warning' : 'info'); ?>">
                                <?php echo ucfirst(str_replace('_', ' ', $ticket['status'])); ?>
                            </span>
                        </td>
                        <td><?php echo date('M j, Y', strtotime($ticket['created_at'])); ?></td>
                        <td>
                            <a href="view.php?id=<?php echo $ticket['id']; ?>" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

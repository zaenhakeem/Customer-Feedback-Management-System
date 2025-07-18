<?php
require_once '../db.php';


include 'includes/header.php';
include 'includes/sidebar.php';

$all = $conn->query("SELECT * FROM notifications ORDER BY created_at DESC");
?>

<h4 class="mb-4">All Notifications</h4>

<table class="table table-hover">
  <thead class="table-dark">
    <tr>
      <th>Title</th>
      <th>Status</th>
      <th>Date</th>
    </tr>
  </thead>
  <tbody>
    <?php while ($n = $all->fetch_assoc()): ?>
      <tr>
        <td>
          <a href="mark_notification.php?id=<?= $n['id'] ?>&link=<?= urlencode($n['link']) ?>">
            <?= htmlspecialchars($n['title']) ?>
          </a>
        </td>
        <td><?= $n['is_read'] ? '<span class="badge bg-secondary">Read</span>' : '<span class="badge bg-primary">Unread</span>' ?></td>
        <td><?= date('M j, Y H:i', strtotime($n['created_at'])) ?></td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>

<?php include 'includes/footer.php'; ?>

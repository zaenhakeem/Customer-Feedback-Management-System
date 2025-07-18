<?php
require_once '../db.php';
include 'includes/header.php';
include 'includes/sidebar.php';

// Fetch all contact messages
$messages = $conn->query("SELECT * FROM contact_messages ORDER BY created_at DESC");

?>

<h3 class="mb-4">View Contact Messages</h3>

<table class="table table-bordered table-hover bg-white shadow-sm">
  <thead class="table-dark">
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Email</th>
      <th>Subject</th>
      <th>Submitted At</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php while ($row = $messages->fetch_assoc()): ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>
        <td><?= htmlspecialchars($row['subject']) ?></td>
        <td><?= date("M d, Y H:i", strtotime($row['created_at'])) ?></td>
        <td>
          <a href="view_message.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-info">View</a>
        </td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>

<?php include 'includes/footer.php'; ?>

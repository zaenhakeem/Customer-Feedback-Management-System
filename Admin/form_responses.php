<?php
require_once '../db.php';
include 'includes/header.php';
include 'includes/sidebar.php';

$form_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$form = $conn->query("SELECT * FROM feedback_forms WHERE id = $form_id")->fetch_assoc();
if (!$form) {
  echo "<div class='alert alert-danger'>Form not found.</div>";
  exit;
}

$responses = $conn->query("SELECT * FROM feedback_form_responses WHERE form_id = $form_id ORDER BY submitted_at DESC");
?>

<h3 class="mb-4">Responses for Form: <?= htmlspecialchars($form['title']) ?></h3>

<table class="table table-bordered table-hover bg-white shadow-sm">
  <thead class="table-dark">
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Email</th>
      <th>Submitted At</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php while ($row = $responses->fetch_assoc()): ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>
        <td><?= date("M d, Y H:i", strtotime($row['submitted_at'])) ?></td>
        <td>
          <a href="view_response.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-info">View Details</a>
        </td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>

<?php include 'includes/footer.php'; ?>

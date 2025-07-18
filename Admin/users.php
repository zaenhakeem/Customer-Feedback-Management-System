<?php
require_once '../db.php';

include 'includes/header.php';
include 'includes/sidebar.php';

$users = $conn->query("SELECT * FROM users ORDER BY id DESC");
?>

<h3 class="mb-4">All Users</h3>

<a href="create_user.php" class="btn btn-success mb-3">
  <i class="bi bi-person-plus-fill me-1"></i> Add New User
</a>

<table class="table table-bordered table-striped bg-white shadow-sm">
  <thead class="table-dark">
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Email</th>
      <th>Role</th>
      <th>Created</th>
    </tr>
  </thead>
  <tbody>
    <?php while ($row = $users->fetch_assoc()): ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>
        <td>
          <span class="badge bg-<?= $row['role'] === 'admin' ? 'primary' : 'secondary' ?>">
            <?= ucfirst($row['role']) ?>
          </span>
        </td>
        <td><?= $row['created_at'] ?></td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>

<?php include 'includes/footer.php'; ?>

<?php
require_once '../db.php';
include 'includes/header.php';
include 'includes/sidebar.php';

// Handle deletion
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $deleteId = intval($_GET['delete']);
    $conn->query("DELETE FROM users WHERE id = $deleteId");
    header("Location: users.php");
    exit;
}

// Fetch users
$users = $conn->query("SELECT * FROM users ORDER BY id DESC");
?>

<div class="container mt-4">
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
                <th style="width: 160px;">Actions</th>
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
                    <td>
                        <div class="d-flex gap-2">
                            <a href="edit_user.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">
                                <i class="bi bi-pencil-fill"></i> Edit
                            </a>
                            <a href="users.php?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger"
                               onclick="return confirm('Are you sure you want to delete this user?');">
                                <i class="bi bi-trash-fill"></i> Delete
                            </a>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>

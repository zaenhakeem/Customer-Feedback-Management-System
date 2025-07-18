<?php
require_once '../db.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('User ID is required.');
}

$id = intval($_GET['id']);
$user = $conn->query("SELECT * FROM users WHERE id = $id")->fetch_assoc();

if (!$user) {
    die('User not found.');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $role = $_POST['role'];
    $password = $_POST['password'];

    if (empty($name) || empty($email) || empty($role)) {
        $errors[] = "Name, Email, and Role are required.";
    }

    if (empty($errors)) {
        if (!empty($password)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET name=?, email=?, role=?, password=? WHERE id=?");
            $stmt->bind_param("ssssi", $name, $email, $role, $hashedPassword, $id);
        } else {
            $stmt = $conn->prepare("UPDATE users SET name=?, email=?, role=? WHERE id=?");
            $stmt->bind_param("sssi", $name, $email, $role, $id);
        }

        if ($stmt->execute()) {
            header("Location: users.php");
            exit;
        } else {
            $errors[] = "Error updating user.";
        }
    }
}
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>

<div class="container">
    <h3>Edit User</h3>

    <?php if ($errors): ?>
        <div class="alert alert-danger">
            <?= implode('<br>', $errors) ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Role</label>
            <select name="role" class="form-control" required>
                <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>User</option>
                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
            </select>
        </div>

        <div class="mb-3">
            <label>New Password (leave blank to keep current)</label>
            <input type="password" name="password" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Update User</button>
        <a href="users.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php include 'includes/footer.php'; ?>

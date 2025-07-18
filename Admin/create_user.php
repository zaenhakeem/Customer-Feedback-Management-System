<?php
require_once '../db.php';

include 'includes/header.php';
include 'includes/sidebar.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    if (empty($name) || empty($email) || empty($password) || empty($role)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check_result = $check->get_result();
        if ($check_result->num_rows > 0) {
            $error = "Email already exists.";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $hashed, $role);
            if ($stmt->execute()) {
                $success = "User created successfully!";
            } else {
                $error = "Error creating user.";
            }
        }
    }
}
?>

<h3 class="mb-4">Create New User</h3>

<?php if ($success): ?>
  <div class="alert alert-success"><?= $success ?></div>
<?php elseif ($error): ?>
  <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<form method="POST" class="bg-white p-4 shadow-sm rounded">
  <div class="mb-3">
    <label>Name</label>
    <input type="text" name="name" class="form-control" required>
  </div>

  <div class="mb-3">
    <label>Email</label>
    <input type="email" name="email" class="form-control" required>
  </div>

  <div class="mb-3">
    <label>Password</label>
    <input type="password" name="password" class="form-control" required>
  </div>

  <div class="mb-3">
    <label>Role</label>
    <select name="role" class="form-select" required>
      <option value="">-- Select Role --</option>
      <option value="admin">Admin</option>
      <option value="staff">Staff</option>
    </select>
  </div>

  <button type="submit" class="btn btn-success">Create User</button>
</form>

<?php include 'includes/footer.php';
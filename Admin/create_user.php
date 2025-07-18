<?php
require_once '../db.php';

include 'includes/header.php';  // session started here
include 'includes/sidebar.php';

// Access Denied styles and animation
$accessDeniedHtml = '
<style>
  .access-denied {
    max-width: 400px;
    margin: 3rem auto;
    padding: 1.5rem 2rem;
    border-radius: 8px;
    background-color: #ffe6e6;
    color: #b30000;
    font-weight: 600;
    text-align: center;
    box-shadow: 0 0 15px rgba(179, 0, 0, 0.3);
    opacity: 0;
    animation: fadeIn 0.8s forwards;
    font-family: Arial, sans-serif;
  }
  @keyframes fadeIn {
    to { opacity: 1; }
  }
  .access-denied svg {
    width: 50px;
    height: 50px;
    margin-bottom: 0.7rem;
  }
</style>
<div class="access-denied">
  <svg fill="none" stroke="#b30000" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
    <circle cx="12" cy="12" r="10" stroke-linecap="round" stroke-linejoin="round"></circle>
    <line x1="15" y1="9" x2="9" y2="15" stroke-linecap="round" stroke-linejoin="round"></line>
    <line x1="9" y1="9" x2="15" y2="15" stroke-linecap="round" stroke-linejoin="round"></line>
  </svg>
  Access Denied<br>
  <small>Only Admins can Add new users.</small>
</div>';

// Check role and show Access Denied if not admin or no role
if (!isset($_SESSION['user_role'])) {
    echo $accessDeniedHtml;
    include 'includes/footer.php';
    exit;
} elseif ($_SESSION['user_role'] !== 'admin') {
    echo $accessDeniedHtml;
    include 'includes/footer.php';
    exit;
}

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
  <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php elseif ($error): ?>
  <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
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

<?php include 'includes/footer.php'; ?>

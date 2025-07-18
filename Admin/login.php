<?php
require_once '../db.php';
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];
            header("Location: dashboard.php");
            exit;
        }
    }

    $error = "Invalid email or password.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background: linear-gradient(135deg, #1a1f36, #2b3455);
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #fff;
      font-family: 'Segoe UI', sans-serif;
    }

    .login-box {
      background: #1f273a;
      padding: 40px 30px;
      border-radius: 12px;
      box-shadow: 0 8px 24px rgba(0,0,0,0.3);
      width: 100%;
      max-width: 420px;
      animation: fadeIn 0.4s ease;
    }

    .login-box h3 {
      font-weight: 600;
      margin-bottom: 10px;
      color: #ffffff;
    }

    .login-box .subtitle {
      font-size: 0.95rem;
      color: #adb5bd;
      margin-bottom: 25px;
    }

    .form-control {
      background-color: #2d3648;
      border: 1px solid #444c5c;
      color: #fff;
    }

    .form-control:focus {
      box-shadow: none;
      border-color: #0d6efd;
      background-color: #2d3648;
      color: #fff;
    }

    .btn-primary {
      background-color: #0d6efd;
      border: none;
      width: 100%;
    }

    .btn-primary:hover {
      background-color: #0b5ed7;
    }

    .alert {
      background-color: #dc3545;
      color: #fff;
      border: none;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(15px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
  </style>
</head>
<body>

<div class="login-box">
  <h3 class="text-center">Admin Panel</h3>
  <p class="text-center subtitle">Staff & Admin Access Only</p>

  <?php if (!empty($error)): ?>
    <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="POST" action="">
    <div class="mb-3">
      <label for="email" class="form-label">Email Address</label>
      <input type="email" name="email" id="email" class="form-control" required autofocus>
    </div>

    <div class="mb-3">
      <label for="password" class="form-label">Password</label>
      <input type="password" name="password" id="password" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-primary mt-2">Login</button>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

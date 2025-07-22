<?php
require_once 'db.php';
session_start();

$loginError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, name, email, password, status FROM customertbl WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $name, $email, $hashedPassword, $status);
        $stmt->fetch();

        if (password_verify($password, $hashedPassword)) {
            if ($status === 'active') {
                $_SESSION['user_id'] = $id;
                $_SESSION['user_name'] = $name;
                $_SESSION['user_email'] = $email;
                header("Location: index.php");
                exit;
            } else {
                $loginError = 'Your account is inactive. Please contact support.';
            }
        } else {
            $loginError = 'Invalid password. Please try again.';
        }
    } else {
        $loginError = 'No account found with that email. Please register.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Customer Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <style>
    body {
      background: linear-gradient(135deg, #e0f0ff, #f8f9fa);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Segoe UI', sans-serif;
    }

    .login-card {
      width: 100%;
      max-width: 420px;
      background: #ffffff;
      border: none;
      border-radius: 12px;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
      padding: 30px;
      animation: fadeIn 0.4s ease-in-out;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .login-card h3 {
      font-weight: 600;
    }

    .form-control:focus {
      box-shadow: none;
      border-color: #0d6efd;
    }

    .btn-primary {
      width: 100%;
    }

    .footer-text {
      font-size: 0.9rem;
    }

    .alert {
      margin-bottom: 20px;
    }
  </style>
</head>
<body>

<div class="login-card">
  <h3 class="text-center mb-4">Customer Login</h3>

  <!-- Display error if any -->
  <?php if ($loginError): ?>
    <div class="alert alert-danger" role="alert">
      <?= htmlspecialchars($loginError) ?>
    </div>
  <?php endif; ?>

  <form method="POST" action="customer_login.php">
    <div class="mb-3">
      <label for="email" class="form-label">Email Address</label>
      <input type="email" name="email" class="form-control" id="email" required>
    </div>

    <div class="mb-3">
      <label for="password" class="form-label">Password</label>
      <input type="password" name="password" class="form-control" id="password" required>
    </div>

    <button type="submit" class="btn btn-primary">Login</button>
  </form>

  <p class="text-center mt-3 footer-text">
    Don't have an account? <a href="signup.php">Register here</a>
  </p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
require_once 'db.php';  // Include DB connection
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: customer_login.php");  // If not logged in, redirect to login page
    exit;
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$user_email = $_SESSION['user_email'];

// Fetch the user's status and account creation date
$stmt = $conn->prepare("SELECT status, created_at FROM customertbl WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($status, $created_at);
$stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile - Customer Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f7f9fc;
    }

    .card {
      border-radius: 15px;
      box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
    }

    .card-header {
      background-color: #4c6ef5;
      color: white;
    }

    .card-body {
      padding: 2rem;
    }

    .btn-custom {
      background-color: #4c6ef5;
      color: white;
      border: none;
      border-radius: 20px;
      padding: 10px 20px;
      font-weight: bold;
      transition: background-color 0.3s;
    }

    .btn-custom:hover {
      background-color: #4057e6;
    }

    .form-control {
      border-radius: 10px;
    }
  </style>
</head>
<body>

<!-- Navbar (same as index.php) -->
<nav class="navbar navbar-expand-lg navbar-custom mb-5">
  <div class="container">
    <a class="navbar-brand text-white" href="#">Feedback Dashboard</a>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <?php if (isset($_SESSION['user_id'])): ?>
          <li class="nav-item dropdown user-menu">
            <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <?= htmlspecialchars($_SESSION['user_name']) ?>
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
              <li><a class="dropdown-item" href="profile.php">Profile</a></li>
              <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
            </ul>
          </li>
        <?php else: ?>
          <li class="nav-item">
            <a class="nav-link text-white" href="signup.php">Sign Up</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white" href="customer_login.php">Login</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-5">
  <!-- Profile Details -->
  <div class="card shadow-sm mb-4">
    <div class="card-header text-center">
      <h4 class="card-title">Your Profile</h4>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-6 mb-3">
          <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
              <h5 class="mb-0">Account Information</h5>
            </div>
            <div class="card-body">
              <p><strong>Name:</strong> <?= htmlspecialchars($user_name) ?></p>
              <p><strong>Email:</strong> <?= htmlspecialchars($user_email) ?></p>
              <p><strong>Status:</strong> <?= ucfirst($status) ?></p>
              <p><strong>Account Created:</strong> <?= date("M d, Y", strtotime($created_at)) ?></p>
            </div>
          </div>
        </div>
        <!-- Change Password Section -->
        <div class="col-md-6">
          <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
              <h5 class="mb-0">Change Password</h5>
            </div>
            <div class="card-body">
              <form method="POST" action="change_password.php">
                <div class="mb-3">
                  <label class="form-label">New Password</label>
                  <input type="password" name="new_password" class="form-control" required>
                </div>
                <div class="mb-3">
                  <label class="form-label">Confirm New Password</label>
                  <input type="password" name="confirm_password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-custom">Change Password</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

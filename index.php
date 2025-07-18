<?php
require_once 'db.php';  // Include DB connection
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #f7f9fc;
    }

    /* Navbar */
    .navbar-custom {
      background-color: #4c6ef5;
    }
    .navbar-custom .navbar-nav .nav-link {
      color: white;
    }
    .navbar-custom .navbar-nav .nav-link:hover {
      color: #f8f9fa;
    }

    /* User Menu Dropdown */
    .user-menu .dropdown-menu {
      min-width: 200px;
    }

    .card-body {
      padding: 2rem;
    }

    .list-group-item {
      background-color: #ffffff;
      border: none;
      border-radius: 8px;
      margin-bottom: 1rem;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      transition: transform 0.2s ease-in-out;
    }

    .list-group-item:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
    }

    .list-group-item i {
      color: #4c6ef5;
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

    .section-header {
      font-size: 1.5rem;
      font-weight: bold;
      color: #333;
      margin-bottom: 1rem;
    }

    .card {
      border-radius: 15px;
      box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
    }

    .card-title {
      font-size: 1.5rem;
      font-weight: 600;
      color: #333;
    }

    .card-text {
      font-size: 1rem;
      color: #555;
    }

    .list-group {
      margin-top: 2rem;
    }

    .card-footer {
      background-color: #f8f9fa;
      border-top: none;
      padding: 1rem 2rem;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-custom mb-5">
  <div class="container">
    <a class="navbar-brand text-white" href="#">Feedback Dashboard</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <?php if (isset($_SESSION['user_id'])): ?>
          <!-- User is logged in, display user info -->
          <li class="nav-item dropdown user-menu">
            <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <?= htmlspecialchars($_SESSION['user_name']) ?>
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
              <li><a class="dropdown-item" href="customer_profile.php">Profile</a></li>
              <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
            </ul>
          </li>
        <?php else: ?>
          <!-- User is not logged in, show login/signup links -->
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
  <div class="card shadow-sm">
    <div class="card-header text-center">
      <h2 class="card-title">Welcome to Your Feedback Dashboard</h2>
    </div>
    <div class="card-body">
      <p class="card-text">Navigate through various sections related to feedback management and user actions.</p>

      <!-- Main Navigation Links -->
      <div class="list-group">
        <a href="my_feedback.php" class="list-group-item">
          <i class="bi bi-chat-dots-fill me-2"></i> My Feedback
        </a>
        <a href="feedback_form.php" class="list-group-item">
          <i class="bi bi-file-earmark-plus me-2"></i> Submit New Feedback
        </a>
        <a href="forms.php" class="list-group-item">
          <i class="bi bi-file-earmark-text me-2"></i> View Forms
        </a>
        <a href="profile.php" class="list-group-item">
          <i class="bi bi-person-circle me-2"></i> Profile
        </a>
        <a href="contact.php" class="list-group-item">
          <i class="bi bi-envelope me-2"></i> Contact Support
        </a>
      </div>

      <!-- Button to go to another page or functionality -->
      <div class="text-center mt-4">
        <a href="user_dashboard.php" class="btn btn-custom">Explore More</a>
      </div>
    </div>
    <div class="card-footer text-center">
      <small class="text-muted">&copy; <?= date('Y') ?> Feedback System. All rights reserved.</small>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

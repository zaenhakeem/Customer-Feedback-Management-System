<?php
require_once '../db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: ../login.php");
  exit;
}

// Fetch notifications
$notifications = $conn->query("SELECT * FROM notifications ORDER BY created_at DESC LIMIT 5");
$unreadCount = $conn->query("SELECT COUNT(*) AS count FROM notifications WHERE is_read = 0")->fetch_assoc()['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin Panel</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Raleway:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">


  <style>
    * {
        font-family: 'Lato', sans-serif;

    }
    body {
      margin: 0;
      padding: 0;
      display: flex;
      flex-direction: column;
    }

    .topbar {
      background-color: #343a40;
      color: white;
      height: 56px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 20px;
      position: fixed;
      width: 100%;
      top: 0;
      z-index: 1000;
    }

    .sidebar {
      width: 220px;
      background-color: #343a40;
      color: white;
      flex-shrink: 0;
      height: 100vh;
      position: fixed;
      top: 56px;
      left: 0;
      overflow-y: auto;
    }

    .sidebar a {
      color: white;
      text-decoration: none;
      display: block;
      padding: 15px;
      transition: background 0.3s;
    }

    .sidebar a:hover,
    .sidebar a.active {
      background-color: #495057;
    }

    .main-content {
      margin-left: 220px;
      margin-top: 56px;
      padding: 30px;
      flex: 1;
      background-color: #f8f9fa;
    }

    .dropdown-menu {
      right: 0;
      left: auto;
    }

    .dropdown-item small {
      font-size: 0.75rem;
    }

    .notification-title {
      font-weight: 500;
    }

    .dropdown-scroll {
      max-height: 300px;
      overflow-y: auto;
    }

    .dropdown-item.unread {
      background-color: #f1f1f1;
    }

    .notification-bell {
      cursor: pointer;
    }

    .notification-bell:hover {
      color: #ffc107;
    }
  </style>
</head>
<body class="d-flex flex-column min-vh-100">

<!-- Topbar -->
<!-- Topbar -->
<div class="topbar">
  <div class="fw-bold text-white">Customer Feedback Admin</div>
  <div class="d-flex align-items-center gap-4">

    <!-- Messages -->
    <a class="text-white position-relative" href="messages.php" title="Messages">
      <i class="bi bi-envelope fs-5"></i>
    </a>

    <!-- Notifications -->
    <div class="dropdown">
      <a class="text-white position-relative notification-bell" href="#" data-bs-toggle="dropdown" title="Notifications">
        <i class="bi bi-bell fs-5"></i>
        <?php if ($unreadCount > 0): ?>
          <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            <?= $unreadCount ?>
          </span>
        <?php endif; ?>
      </a>
      <ul class="dropdown-menu dropdown-menu-end shadow dropdown-scroll" style="width: 320px;">
        <li class="dropdown-header fw-semibold text-dark">Notifications</li>
        <li><hr class="dropdown-divider"></li>

        <?php if ($notifications->num_rows > 0): ?>
          <?php while ($n = $notifications->fetch_assoc()): ?>
            <li>
              <a class="dropdown-item <?= $n['is_read'] ? '' : 'fw-bold unread' ?>"
                 href="mark_notification.php?id=<?= $n['id'] ?>&redirect=<?= urlencode($n['link']) ?>">
                <div class="notification-title"><?= htmlspecialchars($n['title']) ?></div>
                <small class="text-muted"><?= date("M j, Y H:i", strtotime($n['created_at'])) ?></small>
              </a>
            </li>
          <?php endwhile; ?>
        <?php else: ?>
          <li><span class="dropdown-item text-muted small">No notifications</span></li>
        <?php endif; ?>

        <li><hr class="dropdown-divider"></li>
        <li>
          <div class="d-flex justify-content-between px-3 py-2">
            <a href="notifications.php" class="small text-primary text-decoration-none">View All</a>
            <a href="mark_all_read.php" class="small text-secondary text-decoration-none">Mark All Read</a>
          </div>
        </li>
      </ul>
    </div>

    <!-- User -->
    <div class="dropdown">
      <a class="text-white text-decoration-none dropdown-toggle d-flex align-items-center" href="#" data-bs-toggle="dropdown">
        <i class="bi bi-person-circle me-1"></i> <?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?>
      </a>
      <ul class="dropdown-menu dropdown-menu-end">
        <li>
          <span class="dropdown-item disabled">
            <i class="bi bi-person-badge me-2"></i> Role: <?= $_SESSION['user_role'] ?>
          </span>
        </li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item" href="profile.php"><i class="bi bi-gear me-2"></i>Profile</a></li>
        <li><a class="dropdown-item text-danger" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
      </ul>
    </div>

  </div>
</div>


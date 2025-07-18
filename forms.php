<?php
require_once 'db.php';  // Include your DB connection or header

$forms = $conn->query("SELECT * FROM feedback_forms ORDER BY created_at DESC");

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Available Feedback Forms</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="card shadow-sm">
    <div class="card-body">
      <h3 class="card-title mb-4">Available Feedback Forms</h3>
      <p class="card-text">Below is a list of available forms that you can fill out. Simply click on the form title to start providing your feedback.</p>

      <!-- List of Feedback Forms -->
      <div class="list-group">
        <?php while ($row = $forms->fetch_assoc()): ?>
          <a href="form.php?id=<?= $row['id'] ?>" class="list-group-item list-group-item-action">
            <h5 class="mb-1"><?= htmlspecialchars($row['title']) ?></h5>
            <p class="mb-1"><?= nl2br(htmlspecialchars($row['description'])) ?></p>
            <small>Created on <?= date("M d, Y", strtotime($row['created_at'])) ?></small>
          </a>
        <?php endwhile; ?>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>


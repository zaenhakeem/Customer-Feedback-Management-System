<?php require_once 'db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Submit Feedback</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container mt-5">
    <h2 class="mb-4">Customer Feedback Form</h2>

    <?php if (isset($_GET['success'])): ?>
      <div class="alert alert-success">Thank you! Your feedback was submitted.</div>
    <?php endif; ?>

    <form action="process_feedback.php" method="POST" enctype="multipart/form-data" class="bg-white p-4 shadow-sm rounded">
      <div class="mb-3">
        <label>Name (optional)</label>
        <input type="text" name="name" class="form-control">
      </div>

      <div class="mb-3">
        <label>Email (optional)</label>
        <input type="email" name="email" class="form-control">
      </div>

      <div class="mb-3">
        <label>Category</label>
        <select name="category_id" class="form-select" required>
          <option value="">-- Select Category --</option>
          <?php
          $categories = $conn->query("SELECT * FROM categories");
          while ($row = $categories->fetch_assoc()):
          ?>
            <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
          <?php endwhile; ?>
        </select>
      </div>

      <div class="mb-3">
        <label>Rating</label>
        <select name="rating" class="form-select" required>
          <option value="">-- Select Rating --</option>
          <?php for ($i = 1; $i <= 5; $i++): ?>
            <option value="<?= $i ?>"><?= $i ?> Star<?= $i > 1 ? 's' : '' ?></option>
          <?php endfor; ?>
        </select>
      </div>

      <div class="mb-3">
        <label>Message</label>
        <textarea name="message" class="form-control" rows="4" required></textarea>
      </div>

      <div class="mb-3">
        <label>Attach File (optional)</label>
        <input type="file" name="attachment" class="form-control">
      </div>

      <button type="submit" class="btn btn-primary">Submit Feedback</button>
    </form>
  </div>
</body>
</html>

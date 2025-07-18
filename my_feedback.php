<?php
require_once 'db.php';

$email = $_GET['email'] ?? '';
$feedbacks = [];

if ($email) {
  $stmt = $conn->prepare("SELECT f.*, c.name AS category_name FROM feedback f 
                          LEFT JOIN categories c ON f.category_id = c.id 
                          WHERE f.email = ? ORDER BY f.created_at DESC");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $feedbacks = $stmt->get_result();
}

// Handle customer replies
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reply_message'], $_POST['feedback_id'], $_POST['email'])) {
  $reply = trim($_POST['reply_message']);
  $feedback_id = (int) $_POST['feedback_id'];
  $user_email = $_POST['email'];

  if (!empty($reply)) {
    // Insert as anonymous "customer" user (user_id = NULL)
    $stmt = $conn->prepare("INSERT INTO feedback_responses (feedback_id, user_id, message, created_at) VALUES (?, NULL, ?, NOW())");
    $stmt->bind_param("is", $feedback_id, $reply);
    $stmt->execute();

    // Optional: update feedback.updated_at
    $conn->query("UPDATE feedback SET updated_at = NOW() WHERE id = $feedback_id");

    header("Location: my_feedback.php?email=" . urlencode($user_email));
    exit;
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Feedback</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f8f9fa;
    }
    .form-card {
      background: white;
      border-radius: 12px;
      padding: 2rem;
      box-shadow: 0 0 12px rgba(0,0,0,0.05);
    }
    .badge-new { background-color: #0d6efd; }
    .badge-in_progress { background-color: #ffc107; color: #000; }
    .badge-resolved { background-color: #198754; }
    .chat-bubble {
      padding: 10px 15px;
      border-radius: 12px;
      margin-bottom: 8px;
      background: #f1f1f1;
    }
    .chat-admin {
      background: #e2f0ff;
    }
    .chat-customer {
      background: #d1e7dd;
    }
  </style>
</head>
<body>

<div class="container py-5">
  <div class="row justify-content-center mb-4">
    <div class="col-md-8">
      <div class="form-card">
        <h4 class="mb-3">🔍 View Your Feedback</h4>
        <form method="GET" class="row g-3">
          <div class="col-md-9">
            <input type="email" name="email" placeholder="Enter your email address" value="<?= htmlspecialchars($email) ?>" class="form-control" required>
          </div>
          <div class="col-md-3 d-grid">
            <button type="submit" class="btn btn-primary">View Feedback</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <?php if ($email): ?>
    <div class="row justify-content-center">
      <div class="col-md-10">
        <h5 class="mb-4">Results for <strong><?= htmlspecialchars($email) ?></strong></h5>

        <?php if ($feedbacks->num_rows === 0): ?>
          <div class="alert alert-warning">No feedback found for this email.</div>
        <?php else: ?>
          <?php while ($f = $feedbacks->fetch_assoc()): ?>
            <div class="card mb-4 shadow-sm">
              <div class="card-body">
                <h5 class="card-title d-flex justify-content-between align-items-center">
                  <?= htmlspecialchars($f['category_name']) ?>
                  <span class="badge <?= 'badge-' . strtolower($f['status']) ?>">
                    <?= ucfirst(str_replace('_', ' ', $f['status'])) ?>
                  </span>
                </h5>

                <p class="mb-2"><strong>Your Message:</strong><br><?= nl2br(htmlspecialchars($f['message'])) ?></p>

                <?php
                  $fid = $f['id'];
                  $res = $conn->query("SELECT fr.*, u.name, u.role 
                                       FROM feedback_responses fr 
                                       LEFT JOIN users u ON fr.user_id = u.id 
                                       WHERE fr.feedback_id = $fid 
                                       ORDER BY fr.created_at ASC");
                ?>

                <div class="mb-3">
                  <strong>Conversation:</strong><br>
                  <?php if ($res && $res->num_rows > 0): ?>
                    <?php while ($r = $res->fetch_assoc()): ?>
                      <div class="chat-bubble <?= $r['user_id'] ? 'chat-admin' : 'chat-customer' ?>">
                        <small class="text-muted">
                          <?= $r['user_id'] ? "<strong>{$r['name']} ({$r['role']})</strong>" : "<strong>You</strong>" ?> — <?= $r['created_at'] ?>
                        </small><br>
                        <?= nl2br(htmlspecialchars($r['message'])) ?>
                      </div>
                    <?php endwhile; ?>
                  <?php else: ?>
                    <p class="text-muted">No responses yet.</p>
                  <?php endif; ?>
                </div>

                <!-- Customer reply form -->
                <form method="POST" class="mt-3">
                  <input type="hidden" name="feedback_id" value="<?= $fid ?>">
                  <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
                  <div class="mb-2">
                    <textarea name="reply_message" rows="2" class="form-control" placeholder="Type a reply to the team..." required></textarea>
                  </div>
                  <div class="d-grid d-md-block text-end">
                    <button type="submit" class="btn btn-outline-primary btn-sm">Send Reply</button>
                  </div>
                </form>

                <p class="text-muted mb-0 mt-2"><small>Last Updated: <?= $f['updated_at'] ?: $f['created_at'] ?></small></p>
              </div>
            </div>
          <?php endwhile; ?>
        <?php endif; ?>
      </div>
    </div>
  <?php endif; ?>
</div>

</body>
</html>

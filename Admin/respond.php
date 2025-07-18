
<?php
ob_start();
require_once '../db.php';
include 'includes/header.php';
include 'includes/sidebar.php';


$id = $_GET['id'] ?? 0;
$id = (int)$id;

$stmt = $conn->prepare("SELECT f.*, c.name AS category_name FROM feedback f 
                        LEFT JOIN categories c ON f.category_id = c.id 
                        WHERE f.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$feedback = $stmt->get_result()->fetch_assoc();

if (!$feedback) {
  echo "<div class='alert alert-danger'>Feedback not found.</div>";
  include 'includes/footer.php';
  exit;
}



// Handle admin/staff response
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['response_message'])) {
  $message = trim($_POST['response_message']);
  $user_id = $_SESSION['user_id'];

  if (!empty($message)) {
    $stmt = $conn->prepare("INSERT INTO feedback_responses (feedback_id, user_id, message, created_at)
                            VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iis", $id, $user_id, $message);
    $stmt->execute();

    $conn->query("UPDATE feedback SET status = 'in_progress', updated_at = NOW() WHERE id = $id");

    header("Location: respond.php?id=$id");
    exit;
  }
}

// Handle internal notes
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['internal_note'])) {
  $note = trim($_POST['internal_note']);
  $user_id = $_SESSION['user_id'];

  if (!empty($note)) {
    $stmt = $conn->prepare("INSERT INTO internal_notes (feedback_id, user_id, note, created_at)
                            VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iis", $id, $user_id, $note);
    $stmt->execute();

    header("Location: respond.php?id=$id");
    exit;
  }
}
?>

<div class="container mt-4">
  <h4 class="mb-3">Respond to Feedback #<?= $id ?></h4>

  <!-- Feedback Details -->
  <div class="card mb-4">
    <div class="card-body">
      <p><strong>Name:</strong> <?= htmlspecialchars($feedback['name']) ?></p>
      <p><strong>Email:</strong> <?= htmlspecialchars($feedback['email']) ?></p>
      <p><strong>Category:</strong> <?= htmlspecialchars($feedback['category_name']) ?></p>
      <p><strong>Rating:</strong> <?= $feedback['rating'] ?> ⭐</p>
      <p><strong>Status:</strong>
        <span class="badge bg-<?= $feedback['status'] === 'resolved' ? 'success' : ($feedback['status'] === 'in_progress' ? 'warning text-dark' : 'primary') ?>">
          <?= ucfirst(str_replace('_', ' ', $feedback['status'])) ?>
        </span>
      </p>
      <p><strong>Message:</strong><br><?= nl2br(htmlspecialchars($feedback['message'])) ?></p>
      <?php if (!empty($feedback['file_path'])): ?>
        <p><strong>Attachment:</strong> <a href="../<?= $feedback['file_path'] ?>" target="_blank">View File</a></p>
      <?php endif; ?>
    </div>
  </div>

  <!-- Conversation Thread -->
  <div class="card mb-4">
    <div class="card-header bg-light"><strong>Conversation</strong></div>
    <div class="card-body">
      <?php
        $res = $conn->query("SELECT fr.*, u.name AS user_name, u.role 
                             FROM feedback_responses fr
                             LEFT JOIN users u ON fr.user_id = u.id
                             WHERE fr.feedback_id = $id 
                             ORDER BY fr.created_at ASC");

        if ($res->num_rows > 0):
          while ($r = $res->fetch_assoc()):
      ?>
        <div class="mb-3 p-3 border rounded <?= $r['user_id'] ? 'bg-light' : 'bg-success-subtle' ?>">
          <div class="small text-muted mb-1">
            <?php if ($r['user_id']): ?>
              <strong><?= htmlspecialchars($r['user_name']) ?> (<?= htmlspecialchars($r['role']) ?>)</strong>
            <?php else: ?>
              <strong><?= htmlspecialchars($feedback['name']) ?> (<?= htmlspecialchars($feedback['email']) ?>)</strong>
            <?php endif; ?>
            — <?= $r['created_at'] ?>
          </div>
          <?= nl2br(htmlspecialchars($r['message'])) ?>
        </div>
      <?php endwhile; else: ?>
        <p class="text-muted">No responses yet.</p>
      <?php endif; ?>
    </div>
  </div>

  <!-- Update Status -->
<form method="POST" class="mb-4">
  <div class="mb-3">
    <label for="status" class="form-label">Update Status</label>
    <select name="status" id="status" class="form-select" required>
      <option value="new" <?= $feedback['status'] == 'new' ? 'selected' : '' ?>>New</option>
      <option value="in_progress" <?= $feedback['status'] == 'in_progress' ? 'selected' : '' ?>>In Progress</option>
      <option value="resolved" <?= $feedback['status'] == 'resolved' ? 'selected' : '' ?>>Resolved</option>
    </select>
  </div>
  <button type="submit" name="update_status" class="btn btn-outline-primary">Update Status</button>
</form>
<?php
  // Handle manual status update
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $new_status = $_POST['status'];
    $stmt = $conn->prepare("UPDATE feedback SET status = ?, updated_at = NOW() WHERE id = ?");
    $stmt->bind_param("si", $new_status, $id);
    $stmt->execute();

    header("Location: respond.php?id=$id");
    exit;
  }

  ?>

  <!-- Send Response -->
  <form method="POST" class="mb-4">
    <div class="mb-3">
      <label class="form-label">Send Response</label>
      <textarea name="response_message" class="form-control" rows="3" required></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Send Response</button>
  </form>

  <!-- Internal Notes -->
  <div class="card mb-4">
    <div class="card-header bg-dark text-white"><strong>Internal Notes</strong></div>
    <div class="card-body">
      <?php
        $notes = $conn->query("SELECT n.note, n.created_at, u.name FROM internal_notes n
                               LEFT JOIN users u ON n.user_id = u.id
                               WHERE n.feedback_id = $id ORDER BY n.created_at DESC");
        if ($notes->num_rows > 0):
          while ($note = $notes->fetch_assoc()):
      ?>
        <div class="mb-3">
          <small class="text-muted"><strong><?= htmlspecialchars($note['name']) ?></strong> - <?= $note['created_at'] ?></small><br>
          <?= nl2br(htmlspecialchars($note['note'])) ?>
        </div>
      <?php endwhile; else: ?>
        <p class="text-muted">No internal notes yet.</p>
      <?php endif; ?>
    </div>
    <div class="card-footer">
      <form method="POST">
        <textarea name="internal_note" class="form-control mb-2" rows="2" placeholder="Add internal note..." required></textarea>
        <button type="submit" class="btn btn-outline-secondary btn-sm">Add Note</button>
      </form>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
<?php ob_end_flush(); ?>


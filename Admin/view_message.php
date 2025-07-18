<?php
require_once '../db.php';
include 'includes/header.php';
include 'includes/sidebar.php';

$message_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch message details
$message = $conn->query("SELECT * FROM contact_messages WHERE id = $message_id")->fetch_assoc();
if (!$message) {
  echo "<div class='alert alert-danger'>Message not found.</div>";
  exit;
}

?>

<h3 class="mb-4">View Contact Message</h3>

<div class="card">
  <div class="card-body">
    <h5 class="card-title"><?= htmlspecialchars($message['subject']) ?></h5>
    <p><strong>Name:</strong> <?= htmlspecialchars($message['name']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($message['email']) ?></p>
    <p><strong>Message:</strong><br><?= nl2br(htmlspecialchars($message['message'])) ?></p>
    <p><strong>Submitted At:</strong> <?= date("M d, Y H:i", strtotime($message['created_at'])) ?></p>
  </div>
</div>

<?php include 'includes/footer.php'; ?>

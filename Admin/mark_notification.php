<?php
require_once '../db.php';

if (!isset($_GET['id']) || !isset($_GET['redirect'])) {
  header("Location: dashboard.php");
  exit;
}

$id = intval($_GET['id']);
$redirect = urldecode($_GET['redirect']);

// Mark as read
$stmt = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

// Redirect
header("Location: ../$redirect"); // prepend ../ to reach Admin/respond.php from root
exit;

<?php
require_once 'db.php';

// Collect form data
$name = $_POST['name'] ?? null;
$email = $_POST['email'] ?? null;
$category_id = $_POST['category_id'];
$rating = $_POST['rating'];
$message = $_POST['message'];
$file_path = null;

// Handle file upload
if (!empty($_FILES['attachment']['name'])) {
    $upload_dir = "uploads/";
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

    $file_name = time() . "_" . basename($_FILES['attachment']['name']);
    $file_path = $upload_dir . $file_name;

    move_uploaded_file($_FILES['attachment']['tmp_name'], $file_path);
}

// Insert into feedback table
$stmt = $conn->prepare("INSERT INTO feedback (name, email, category_id, rating, message, file_path) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssiiss", $name, $email, $category_id, $rating, $message, $file_path);
$stmt->execute();

// Get last inserted feedback ID
$feedback_id = $conn->insert_id;

// Insert into notifications table
$type = 'feedback';
$title = "📝 New feedback submitted by $name";
$link = "Admin/respond.php?id=" . $feedback_id;

$stmtNotif = $conn->prepare("INSERT INTO notifications (type, title, link) VALUES (?, ?, ?)");
$stmtNotif->bind_param("sss", $type, $title, $link);
$stmtNotif->execute();

// Redirect back to feedback form
header("Location: feedback_form.php?success=1");
exit;

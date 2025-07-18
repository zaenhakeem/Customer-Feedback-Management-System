<?php
require_once 'db.php';  // Include DB connection
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: customer_login.php");  // If not logged in, redirect to login page
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if the passwords match
    if ($new_password !== $confirm_password) {
        echo "<script>alert('Passwords do not match. Please try again.'); window.location.href = 'customer_profile.php';</script>";
        exit;
    }

    // Hash the new password
    $hashedPassword = password_hash($new_password, PASSWORD_BCRYPT);

    // Update the password in the database
    $stmt = $conn->prepare("UPDATE customertbl SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $hashedPassword, $user_id);
    $stmt->execute();

    echo "<script>alert('Password updated successfully.'); window.location.href = 'customer_profile.php';</script>";
    exit;
}
?>

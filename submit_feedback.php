<?php
require_once 'db.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form id and user details
    $form_id = $_POST['form_id'] ?? null;
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');

    // Ensure form_id, name, and email are provided
    if (empty($name) || empty($email) || !$form_id) {
        echo "<div class='alert alert-danger'>Name, email, and form ID are required.</div>";
        exit;
    }

    // Insert user data into the feedback_form_responses table
    $stmt = $conn->prepare("INSERT INTO feedback_form_responses (form_id, name, email, submitted_at) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iss", $form_id, $name, $email);
    $stmt->execute();
    $response_id = $conn->insert_id; // Get the ID of the inserted response

    // Loop through each dynamic field and insert answers into feedback_response_answers table
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'field_') === 0) {  // Check if key is like "field_1"
            $field_id = substr($key, 6);  // Extract field ID from key
            if (is_array($value)) {
                // For checkboxes
                foreach ($value as $checkbox_value) {
                    $stmt = $conn->prepare("INSERT INTO feedback_response_answers (response_id, field_id, answer) VALUES (?, ?, ?)");
                    $stmt->bind_param("iis", $response_id, $field_id, $checkbox_value);
                    $stmt->execute();
                }
            } else {
                // For text, textarea, radio, select
                $stmt = $conn->prepare("INSERT INTO feedback_response_answers (response_id, field_id, answer) VALUES (?, ?, ?)");
                $stmt->bind_param("iis", $response_id, $field_id, $value);
                $stmt->execute();
            }
        }
    }

    // Show a local alert (no redirect)
    echo "<script>alert('Thank you for your feedback!');</script>";
    exit;
} else {
    echo "<div class='alert alert-danger'>Invalid request method.</div>";
    exit;
}
?>

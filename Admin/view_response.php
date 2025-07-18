<?php
require_once '../db.php';
include 'includes/header.php';
include 'includes/sidebar.php';

$response_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch the response data
$response = $conn->query("SELECT * FROM feedback_form_responses WHERE id = $response_id")->fetch_assoc();
if (!$response) {
  echo "<div class='alert alert-danger'>Response not found.</div>";
  exit;
}

$form_id = $response['form_id'];

// Fetch the form details
$form = $conn->query("SELECT * FROM feedback_forms WHERE id = $form_id")->fetch_assoc();
if (!$form) {
  echo "<div class='alert alert-danger'>Form not found.</div>";
  exit;
}

// Fetch the fields for this form
$fields = $conn->query("SELECT * FROM feedback_form_fields WHERE form_id = $form_id ORDER BY `order` ASC");

$answers = [];
while ($field = $fields->fetch_assoc()) {
  // Get the answer for the current field
  $answer_result = $conn->query("SELECT answer FROM feedback_response_answers WHERE response_id = $response_id AND field_id = {$field['id']}")->fetch_assoc();
  if ($answer_result) {
    $answers[$field['id']] = $answer_result['answer'];
  } else {
    $answers[$field['id']] = 'No answer provided';
  }
}
?>

<h3 class="mb-4">Response Details for Form: <?= htmlspecialchars($form['title']) ?></h3>

<!-- Display Response Answers -->
<table class="table table-bordered table-hover bg-white shadow-sm">
  <thead class="table-dark">
    <tr>
      <th>Field</th>
      <th>Answer</th>
    </tr>
  </thead>
  <tbody>
    <?php 
    // Reset fields cursor and iterate again to show the answers
    $fields->data_seek(0); 
    while ($field = $fields->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($field['label']) ?></td>
        <td><?= nl2br(htmlspecialchars($answers[$field['id']])) ?></td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>

<?php include 'includes/footer.php'; ?>

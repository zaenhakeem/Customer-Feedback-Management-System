<?php
require_once 'db.php';

$form_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$form = $conn->query("SELECT * FROM feedback_forms WHERE id = $form_id")->fetch_assoc();
$fields = $conn->query("SELECT * FROM feedback_form_fields WHERE form_id = $form_id ORDER BY `order` ASC");

if (!$form) {
  echo "<div class='alert alert-danger'>Form not found.</div>";
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($form['title']) ?> - Feedback</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
  <div class="card shadow-sm">
    <div class="card-body">
      <h4 class="card-title"><?= htmlspecialchars($form['title']) ?></h4>
      <p class="card-text"><?= htmlspecialchars($form['description']) ?></p>

      <!-- User information fields (name and email) -->
      <form id="feedbackForm" action="submit_feedback.php" method="POST">
        <input type="hidden" name="form_id" value="<?= $form_id ?>">

        <!-- Name Field -->
        <div class="mb-3">
          <label class="form-label">Your Name <span class="text-danger">*</span></label>
          <input type="text" name="name" class="form-control" required>
        </div>

        <!-- Email Field -->
        <div class="mb-3">
          <label class="form-label">Email Address <span class="text-danger">*</span></label>
          <input type="email" name="email" class="form-control" required>
        </div>

        <!-- Dynamically Render Form Fields -->
        <?php while ($field = $fields->fetch_assoc()): ?>
          <div class="mb-3">
            <label class="form-label"><?= htmlspecialchars($field['label']) ?> <?= $field['required'] ? '<span class="text-danger">*</span>' : '' ?></label>

            <?php if ($field['field_type'] === 'text'): ?>
              <input type="text" name="field_<?= $field['id'] ?>" class="form-control" <?= $field['required'] ? 'required' : '' ?>>

            <?php elseif ($field['field_type'] === 'textarea'): ?>
              <textarea name="field_<?= $field['id'] ?>" class="form-control" rows="3" <?= $field['required'] ? 'required' : '' ?>></textarea>

            <?php elseif ($field['field_type'] === 'radio'): ?>
              <?php foreach (explode(',', $field['options']) as $option): ?>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="field_<?= $field['id'] ?>" value="<?= trim($option) ?>" <?= $field['required'] ? 'required' : '' ?>>
                  <label class="form-check-label"><?= trim($option) ?></label>
                </div>
              <?php endforeach; ?>

            <?php elseif ($field['field_type'] === 'checkbox'): ?>
              <?php foreach (explode(',', $field['options']) as $option): ?>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="field_<?= $field['id'] ?>[]" value="<?= trim($option) ?>">
                  <label class="form-check-label"><?= trim($option) ?></label>
                </div>
              <?php endforeach; ?>

            <?php elseif ($field['field_type'] === 'select'): ?>
              <select name="field_<?= $field['id'] ?>" class="form-select" <?= $field['required'] ? 'required' : '' ?>>
                <option value="">-- Select --</option>
                <?php foreach (explode(',', $field['options']) as $option): ?>
                  <option value="<?= trim($option) ?>"><?= trim($option) ?></option>
                <?php endforeach; ?>
              </select>

            <?php endif; ?>
          </div>
        <?php endwhile; ?>

        <button type="submit" class="btn btn-primary">Submit Feedback</button>
      </form>

      <!-- Success Message (Hidden Initially) -->
      <div id="thankYouMessage" class="alert alert-success mt-3" style="display:none;">
        <h5>Thank you for your feedback!</h5>
        <p>Your feedback has been submitted successfully.</p>
      </div>
    </div>
  </div>
</div>

<!-- JavaScript to show Thank You Message -->
<script>
  document.getElementById('feedbackForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the default form submission

    var form = event.target;
    var formData = new FormData(form);

    // Send the form data using AJAX (fetch or XMLHttpRequest)
    fetch(form.action, {
      method: 'POST',
      body: formData
    }).then(response => response.text()).then(result => {
      // Show the Thank You message upon success
      document.getElementById('thankYouMessage').style.display = 'block';
      form.reset(); // Reset form fields
    }).catch(error => {
      alert('There was an error submitting the form. Please try again later.');
    });
  });
</script>
</body>
</html>

<?php
require_once '../db.php';
include 'includes/header.php';
include 'includes/sidebar.php';

$form_id = null;
$form_created = false;

// Handle new form creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_title'])) {
    $title = trim($_POST['form_title']);
    $desc = trim($_POST['form_description']);

    if (!empty($title)) {
        $stmt = $conn->prepare("INSERT INTO feedback_forms (title, description) VALUES (?, ?)");
        $stmt->bind_param("ss", $title, $desc);
        $stmt->execute();
        $form_id = $conn->insert_id;
        $form_created = true;
    }
}

// Handle adding fields
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_field'])) {
    $form_id = (int)$_POST['form_id'];
    $label = trim($_POST['field_label']);
    $type = $_POST['field_type'];
    $options = $_POST['field_options'] ?? '';
    $required = isset($_POST['field_required']) ? 1 : 0;

    if (!empty($label) && !empty($type)) {
        $stmt = $conn->prepare("INSERT INTO feedback_form_fields (form_id, label, field_type, options, required) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("isssi", $form_id, $label, $type, $options, $required);
        $stmt->execute();
    }
}

// Load fields if form exists
$fields = [];
if (!empty($form_id)) {
    $stmt = $conn->prepare("SELECT * FROM feedback_form_fields WHERE form_id = ? ORDER BY `order` ASC, id ASC");
    $stmt->bind_param("i", $form_id);
    $stmt->execute();
    $fields = $stmt->get_result();
}
?>

<div class="container mt-4">
  <h3>Create New Feedback Form</h3>

  <?php if (!$form_id): ?>
  <form method="POST" class="card p-4 mb-4 shadow-sm">
    <div class="mb-3">
      <label class="form-label">Form Title</label>
      <input type="text" name="form_title" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Form Description</label>
      <textarea name="form_description" class="form-control" rows="2"></textarea>
    </div>
    <button type="submit" class="btn btn-success">Create Form</button>
  </form>
  <?php else: ?>
  <div class="alert alert-success">Form created. Add fields below.</div>

  <form method="POST" class="card p-4 mb-4 shadow-sm">
    <input type="hidden" name="form_id" value="<?= $form_id ?>">
    <div class="row g-3 align-items-end">
      <div class="col-md-4">
        <label class="form-label">Field Label</label>
        <input type="text" name="field_label" class="form-control" required>
      </div>
      <div class="col-md-3">
        <label class="form-label">Field Type</label>
        <select name="field_type" class="form-select" required>
          <option value="text">Text Input</option>
          <option value="textarea">Textarea</option>
          <option value="radio">Radio Buttons</option>
          <option value="checkbox">Checkboxes</option>
          <option value="select">Dropdown</option>
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label">Options <small>(comma separated)</small></label>
        <input type="text" name="field_options" class="form-control">
      </div>
      <div class="col-md-1 form-check">
        <input type="checkbox" name="field_required" class="form-check-input" id="required">
        <label for="required" class="form-check-label">Required</label>
      </div>
      <div class="col-md-1">
        <button type="submit" name="add_field" class="btn btn-primary w-100">Add</button>
      </div>
    </div>
  </form>

  <h5 class="mt-4">Form Fields</h5>
  <table class="table table-bordered">
    <thead class="table-dark">
      <tr>
        <th>#</th>
        <th>Label</th>
        <th>Type</th>
        <th>Options</th>
        <th>Required</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($fields->num_rows > 0): ?>
        <?php while ($field = $fields->fetch_assoc()): ?>
          <tr>
            <td><?= $field['id'] ?></td>
            <td><?= htmlspecialchars($field['label']) ?></td>
            <td><?= $field['field_type'] ?></td>
            <td><?= htmlspecialchars($field['options']) ?></td>
            <td><?= $field['required'] ? 'Yes' : 'No' ?></td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="5" class="text-center">No fields added yet.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
  <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>

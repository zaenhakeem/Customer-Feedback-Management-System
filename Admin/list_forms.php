<?php
require_once '../db.php';
include 'includes/header.php';
include 'includes/sidebar.php';

$forms = $conn->query("SELECT * FROM feedback_forms ORDER BY created_at DESC");

?>

<h2 class="mb-4">Manage Feedback Forms</h2>

<!-- Table for listing the forms -->
<table class="table table-bordered table-hover bg-white shadow-sm">
  <thead class="table-dark">
    <tr>
      <th>ID</th>
      <th>Title</th>
      <th>Description</th>
      <th>Created At</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php while ($row = $forms->fetch_assoc()): ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['title']) ?></td>
        <td><?= nl2br(htmlspecialchars($row['description'])) ?></td>
        <td><?= date("M d, Y", strtotime($row['created_at'])) ?></td>
        <td>
          <!-- View Responses Button -->
          <a href="form_responses.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-info">View Responses</a>

          <!-- Share Button (dropdown) -->
          <div class="dropdown d-inline-block">
            <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
              Share
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
              <li><a class="dropdown-item" href="https://wa.me/?text=Fill%20out%20the%20feedback%20form%3A%20<?= urlencode("http://localhost/Customer_Feedback_system/form.php?id=" . $row['id']) ?>" target="_blank"><i class="bi bi-whatsapp"></i> Share on WhatsApp</a></li>
              <li><a class="dropdown-item" href="#" onclick="copyToClipboard('http://localhost/Customer_Feedback_system/form.php?id=<?= $row['id'] ?>')"><i class="bi bi-clipboard"></i> Copy Link</a></li>
              <li><a class="dropdown-item" href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode("http://localhost/Customer_Feedback_system/form.php?id=" . $row['id']) ?>" target="_blank"><i class="bi bi-facebook"></i> Share on Facebook</a></li>
            </ul>
          </div>
        </td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>

<?php include 'includes/footer.php'; ?>

<script>
  // Function to copy link to clipboard
  function copyToClipboard(text) {
    var tempInput = document.createElement("input");
    document.body.appendChild(tempInput);
    tempInput.value = text;
    tempInput.select();
    document.execCommand("copy");
    document.body.removeChild(tempInput);
    alert("Link copied to clipboard!");
  }
</script>

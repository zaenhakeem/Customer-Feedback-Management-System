<?php
require_once '../db.php';
include 'includes/header.php';
include 'includes/sidebar.php';

// Handle new category
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['category_name'])) {
    $name = trim($_POST['category_name']);
    if (!empty($name)) {
        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->bind_param("s", $name);
        $stmt->execute();
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM categories WHERE id = $id");
}

// Search
$search = $_GET['search'] ?? '';
$search_sql = '';
if (!empty($search)) {
    $searchEsc = $conn->real_escape_string($search);
    $search_sql = "WHERE name LIKE '%$searchEsc%'";
}

// Pagination setup
$limit = 10;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$total = $conn->query("SELECT COUNT(*) AS count FROM categories $search_sql")->fetch_assoc()['count'];
$total_pages = ceil($total / $limit);

// Get categories
$query = "SELECT * FROM categories $search_sql ORDER BY id DESC LIMIT $limit OFFSET $offset";
$categories = $conn->query($query);
?>

<!-- Custom inline search style -->
<style>
  .search-bar {
    max-width: 220px;
    height: 38px;
    font-size: 14px;
    padding: 0 8px;
  }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h3 class="mb-0">Manage Feedback Categories</h3>
  <form method="GET" class="d-flex">
    <input type="text" name="search" class="form-control search-bar me-2" placeholder="Search..." value="<?= htmlspecialchars($search) ?>">
    <button type="submit" class="btn btn-outline-dark btn-sm"><i class="bi bi-search"></i></button>
  </form>
</div>

<form action="" method="POST" class="mb-4 d-flex gap-3">
  <input type="text" name="category_name" class="form-control" placeholder="New category name" required>
  <button type="submit" class="btn btn-success">Add</button>
</form>

<table class="table table-bordered bg-white shadow-sm">
  <thead class="table-dark">
    <tr>
      <th>ID</th>
      <th>Category</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php if ($categories->num_rows > 0): ?>
      <?php while ($row = $categories->fetch_assoc()): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= htmlspecialchars($row['name']) ?></td>
          <td>
            <a href="?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm"
               onclick="return confirm('Are you sure you want to delete this category?')">Delete</a>
          </td>
        </tr>
      <?php endwhile; ?>
    <?php else: ?>
      <tr><td colspan="3" class="text-center py-3">No categories found.</td></tr>
    <?php endif; ?>
  </tbody>
</table>

<!-- Pagination -->
<?php if ($total_pages > 1): ?>
  <nav>
    <ul class="pagination justify-content-center">
      <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <li class="page-item <?= $i === $page ? 'active' : '' ?>">
          <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
        </li>
      <?php endfor; ?>
    </ul>
  </nav>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>

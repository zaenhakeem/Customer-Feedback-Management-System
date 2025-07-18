<?php
require_once '../db.php';
include 'includes/header.php';
include 'includes/sidebar.php';

// Pagination setup
$limit = 10;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Search filter
$search = $_GET['search'] ?? '';
$searchSql = '';
if (!empty($search)) {
    $searchLike = '%' . $conn->real_escape_string($search) . '%';
    $searchSql = "WHERE f.name LIKE '$searchLike' OR f.email LIKE '$searchLike' OR f.message LIKE '$searchLike'";
}

// Count total results
$countRes = $conn->query("SELECT COUNT(*) as total FROM feedback f 
                          JOIN categories c ON f.category_id = c.id 
                          $searchSql");
$totalRows = $countRes->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);

// Fetch paginated results
$result = $conn->query("SELECT f.*, c.name AS category_name FROM feedback f 
                        JOIN categories c ON f.category_id = c.id 
                        $searchSql 
                        ORDER BY f.created_at DESC 
                        LIMIT $limit OFFSET $offset");
?>

<h2 class="mb-4">All Customer Feedback</h2>

<form class="mb-3" method="GET">
  <div class="input-group">
    <input type="text" class="form-control" name="search" placeholder="Search by name, email or message..." value="<?= htmlspecialchars($search) ?>">
    <button class="btn btn-dark" type="submit">Search</button>
  </div>
</form>

<div class="table-responsive">
  <table class="table table-bordered table-hover bg-white shadow-sm">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Category</th>
        <th>Rating</th>
        <th>Message</th>
        <th>Status</th>
        <th>Submitted</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= htmlspecialchars($row['name'] ?? 'Anonymous') ?></td>
          <td><?= htmlspecialchars($row['email'] ?? '—') ?></td>
          <td><?= htmlspecialchars($row['category_name']) ?></td>
          <td>
            <?= $row['rating'] ?>
            <i class="bi bi-star-fill text-warning"></i>
          </td>
          <td><?= htmlspecialchars(mb_strimwidth($row['message'], 0, 40, '...')) ?></td>
          <td>
            <?php
              $statusClass = match ($row['status']) {
                'resolved' => 'success',
                'in_progress' => 'warning',
                default => 'secondary'
              };
            ?>
            <span class="badge bg-<?= $statusClass ?>"><?= ucfirst($row['status']) ?></span>
          </td>
          <td><?= date("M d, Y", strtotime($row['created_at'])) ?></td>
          <td>
            <a href="respond.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Respond</a>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<!-- Pagination -->
<?php if ($totalPages > 1): ?>
<nav>
  <ul class="pagination justify-content-center">
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
      <li class="page-item <?= $i === $page ? 'active' : '' ?>">
        <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
      </li>
    <?php endfor; ?>
  </ul>
</nav>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>

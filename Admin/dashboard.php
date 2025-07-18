<?php 
require_once '../db.php';

include 'includes/header.php';
include 'includes/sidebar.php';

// Stats
$total = $conn->query("SELECT COUNT(*) AS count FROM feedback")->fetch_assoc()['count'];
$new = $conn->query("SELECT COUNT(*) AS count FROM feedback WHERE status = 'new'")->fetch_assoc()['count'];
$in_progress = $conn->query("SELECT COUNT(*) AS count FROM feedback WHERE status = 'in_progress'")->fetch_assoc()['count'];
$resolved = $conn->query("SELECT COUNT(*) AS count FROM feedback WHERE status = 'resolved'")->fetch_assoc()['count'];
?>

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<!-- Compact Glassmorphism Styling -->
<style>
  .glass-card {
    backdrop-filter: blur(10px);
    background: rgba(255, 255, 255, 0.7);
    border: 1px solid rgba(255, 255, 255, 0.4);
    border-radius: 16px;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    min-height: 110px;
    padding: 1rem 0.75rem;
  }

  .glass-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
  }

  .icon-circle {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: #6c63ff;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 18px;
    margin: 0 auto 6px auto;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  }

  .stat-title {
    font-size: 0.9rem;
    font-weight: 600;
    color: #444;
    margin-bottom: 0.25rem;
  }

  .stat-number {
    font-size: 1.75rem;
    font-weight: 700;
    color: #222;
    margin-bottom: 0;
  }

  .card-body {
    padding: 0.5rem 0.25rem;
    text-align: center;
  }

  canvas {
    max-width: 100%;
    height: 300px !important;
  }

  h2 {
    font-size: 1.5rem;
  }
</style>

<?php
// Get monthly status counts for last 6 months
$months = [];
$status_data = ['new' => [], 'in_progress' => [], 'resolved' => []];

for ($i = 5; $i >= 0; $i--) {
  $monthLabel = date('M Y', strtotime("-$i months"));
  $monthStart = date('Y-m-01', strtotime("-$i months"));
  $monthEnd = date('Y-m-t', strtotime("-$i months"));
  $months[] = $monthLabel;

  foreach (['new', 'in_progress', 'resolved'] as $status) {
    $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM feedback WHERE status = ? AND created_at BETWEEN ? AND ?");
    $stmt->bind_param("sss", $status, $monthStart, $monthEnd);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    $status_data[$status][] = (int)$res['count'];
  }
}

// Monthly feedback
$monthlyData = $conn->query("SELECT MONTH(created_at) as month, COUNT(*) as total FROM feedback GROUP BY MONTH(created_at)");
$months = []; $totals = [];
while ($row = $monthlyData->fetch_assoc()) {
    $months[] = date("F", mktime(0, 0, 0, $row['month'], 10));
    $totals[] = $row['total'];
}

// Feedback by category
$catData = $conn->query("SELECT c.name, COUNT(*) as total FROM feedback f LEFT JOIN categories c ON f.category_id = c.id GROUP BY c.name");
$catLabels = []; $catCounts = [];
while ($row = $catData->fetch_assoc()) {
    $catLabels[] = $row['name'] ?? 'Uncategorized';
    $catCounts[] = $row['total'];
}

// Rating
$ratingData = $conn->query("SELECT rating, COUNT(*) as total FROM feedback GROUP BY rating");
$ratings = []; $ratingCounts = [];
while ($row = $ratingData->fetch_assoc()) {
    $ratings[] = $row['rating'] . "★";
    $ratingCounts[] = $row['total'];
}

// Status
$statusData = $conn->query("SELECT status, COUNT(*) as total FROM feedback GROUP BY status");
$statusLabels = []; $statusCounts = [];
while ($row = $statusData->fetch_assoc()) {
    $statusLabels[] = ucfirst(str_replace('_', ' ', $row['status']));
    $statusCounts[] = $row['total'];
}
?>

<h2 class="mb-4">Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?></h2>

<div class="row g-3">
  <div class="col-6 col-md-3">
    <div class="glass-card">
      <div class="card-body">
        <div class="icon-circle bg-dark"><i class="bi bi-chat-dots-fill"></i></div>
        <div class="stat-title">Total Feedback</div>
        <div class="stat-number"><?= $total ?></div>
      </div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="glass-card">
      <div class="card-body">
        <div class="icon-circle bg-primary"><i class="bi bi-inbox-fill"></i></div>
        <div class="stat-title">New</div>
        <div class="stat-number"><?= $new ?></div>
      </div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="glass-card">
      <div class="card-body">
        <div class="icon-circle bg-warning text-dark"><i class="bi bi-clock-history"></i></div>
        <div class="stat-title">In Progress</div>
        <div class="stat-number"><?= $in_progress ?></div>
      </div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="glass-card">
      <div class="card-body">
        <div class="icon-circle bg-success"><i class="bi bi-check-circle-fill"></i></div>
        <div class="stat-title">Resolved</div>
        <div class="stat-number"><?= $resolved ?></div>
      </div>
    </div>
  </div>
</div>

<!-- Full-width Feedback Trends Chart -->
<div class="row mt-5">
  <div class="col-12">
    <div class="card shadow-sm">
      <div class="card-header bg-white">
        <h6 class="mb-0">Feedback Trends (Last 6 Months)</h6>
      </div>
      <div class="card-body">
        <canvas id="feedbackChart"></canvas>
      </div>
    </div>
  </div>
</div>

<!-- Monthly and Category Charts -->
<div class="row mt-4">
  <div class="col-md-6">
    <div class="card shadow-sm mb-3">
      <div class="card-header bg-white">
        <h6 class="mb-0">Monthly Feedback Volume</h6>
      </div>
      <div class="card-body">
        <canvas id="monthlyChart"></canvas>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card shadow-sm mb-3">
      <div class="card-header bg-white">
        <h6 class="mb-0">Feedback by Category</h6>
      </div>
      <div class="card-body">
        <canvas id="categoryChart"></canvas>
      </div>
    </div>
  </div>
</div>

<!-- Ratings and Status Charts -->
<div class="row mt-4">
  <div class="col-md-6">
    <div class="card shadow-sm mb-3">
      <div class="card-header bg-white">
        <h6 class="mb-0">Feedback Ratings</h6>
      </div>
      <div class="card-body">
        <canvas id="ratingChart"></canvas>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card shadow-sm mb-3">
      <div class="card-header bg-white">
        <h6 class="mb-0">Feedback by Status</h6>
      </div>
      <div class="card-body">
        <canvas id="statusChart"></canvas>
      </div>
    </div>
  </div>
</div>

<!-- Recent Feedback Table -->
<div class="card mt-5 shadow-sm">
  <div class="card-header bg-white">
    <h6 class="mb-0">Recent Feedback</h6>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover table-sm mb-0 align-middle">
        <thead class="table-light">
          <tr>
            <th><i class="bi bi-hash"></i></th>
            <th><i class="bi bi-person-circle me-1"></i> Name</th>
            <th><i class="bi bi-tags-fill me-1"></i> Category</th>
            <th><i class="bi bi-calendar-date me-1"></i> Date</th>
            <th><i class="bi bi-info-circle me-1"></i> Status</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $query = "SELECT f.*, c.name AS category_name FROM feedback f 
                      LEFT JOIN categories c ON f.category_id = c.id 
                      ORDER BY f.created_at DESC LIMIT 5";
            $result = $conn->query($query);
            if ($result->num_rows > 0):
              while ($row = $result->fetch_assoc()):
          ?>
          <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['category_name'] ?? 'Uncategorized') ?></td>
            <td><?= date("M d, Y", strtotime($row['created_at'])) ?></td>
            <td>
              <?php if ($row['status'] == 'new'): ?>
                <span class="badge bg-primary">New</span>
              <?php elseif ($row['status'] == 'in_progress'): ?>
                <span class="badge bg-warning text-dark">In Progress</span>
              <?php elseif ($row['status'] == 'resolved'): ?>
                <span class="badge bg-success">Resolved</span>
              <?php endif; ?>
            </td>
          </tr>
          <?php endwhile; else: ?>
            <tr><td colspan="5" class="text-center py-3">No recent feedback available.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>

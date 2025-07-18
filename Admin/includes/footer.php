</div> <!-- End of .main-content -->

<footer class="text-center py-3 bg-white border-top mt-auto" style="margin-left: 220px;">
  <small>&copy; <?= date('Y') ?> Customer Feedback System. All rights reserved.</small>
</footer>

<!-- Bootstrap & Icons JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const ctx = document.getElementById('feedbackChart').getContext('2d');
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: <?= json_encode($months) ?>,
      datasets: [
        {
          label: 'New',
          data: <?= json_encode($status_data['new']) ?>,
          borderColor: '#0d6efd',
          backgroundColor: 'rgba(13, 110, 253, 0.1)',
          tension: 0.3
        },
        {
          label: 'In Progress',
          data: <?= json_encode($status_data['in_progress']) ?>,
          borderColor: '#ffc107',
          backgroundColor: 'rgba(255, 193, 7, 0.1)',
          tension: 0.3
        },
        {
          label: 'Resolved',
          data: <?= json_encode($status_data['resolved']) ?>,
          borderColor: '#198754',
          backgroundColor: 'rgba(25, 135, 84, 0.1)',
          tension: 0.3
        }
      ]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { position: 'top' },
        tooltip: { mode: 'index', intersect: false }
      },
      scales: {
        y: { beginAtZero: true, ticks: { stepSize: 1 } }
      }
    }
  });
</script>

<script>
const monthLabels = <?= json_encode($months) ?>;
const monthCounts = <?= json_encode($totals) ?>;
const categoryLabels = <?= json_encode($catLabels) ?>;
const categoryCounts = <?= json_encode($catCounts) ?>;
const ratingLabels = <?= json_encode($ratings) ?>;
const ratingCounts = <?= json_encode($ratingCounts) ?>;
const statusLabels = <?= json_encode($statusLabels) ?>;
const statusCounts = <?= json_encode($statusCounts) ?>;

new Chart(document.getElementById('monthlyChart'), {
  type: 'bar',
  data: {
    labels: monthLabels,
    datasets: [{
      label: 'Feedback per Month',
      backgroundColor: '#4e73df',
      data: monthCounts,
    }]
  }
});

new Chart(document.getElementById('categoryChart'), {
  type: 'pie',
  data: {
    labels: categoryLabels,
    datasets: [{
      label: 'Feedback by Category',
      data: categoryCounts,
      backgroundColor: ['#36b9cc', '#f6c23e', '#1cc88a', '#e74a3b', '#858796']
    }]
  }
});

new Chart(document.getElementById('ratingChart'), {
  type: 'doughnut',
  data: {
    labels: ratingLabels,
    datasets: [{
      label: 'Ratings',
      data: ratingCounts,
      backgroundColor: ['#f1c40f', '#e67e22', '#e74c3c', '#3498db', '#2ecc71']
    }]
  }
});

new Chart(document.getElementById('statusChart'), {
  type: 'doughnut',
  data: {
    labels: statusLabels,
    datasets: [{
      label: 'Status',
      data: statusCounts,
      backgroundColor: ['#007bff', '#ffc107', '#28a745']
    }]
  }
});
</script>


<script>
  document.querySelectorAll('.stat-number').forEach(el => {
    const target = +el.textContent;
    let count = 0;

    // Make this smaller to slow down the count speed
    const step = Math.max(1, Math.floor(target / 200)); 
    const interval = setInterval(() => {
      count += step;
      if (count >= target) {
        el.textContent = target;
        clearInterval(interval);
      } else {
        el.textContent = count;
      }
    }, 200); // Increase this delay to slow it further (e.g., 30 or 40)
  });
</script>


</body>
</html>

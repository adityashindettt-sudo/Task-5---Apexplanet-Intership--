<?php
require_once __DIR__ . '/../src/db.php';
session_start();
if (!isset($_SESSION['user_id'])) { 
    header('Location: login.php'); 
    exit; 
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Dashboard</title>
</head>
<body>

<h2>Dashboard</h2>
<p>Welcome, user ID: <?php echo intval($_SESSION['user_id']); ?> | 
    <a href="logout.php">Logout</a>
</p>

<h3>Enrollments (Last 30 days)</h3>

<canvas id="enrollChart" width="600" height="250"></canvas>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Fetch chart data -->
<script>
fetch('api/enrollments_chart.php')
    .then(r => r.json())
    .then(data => {
        const ctx = document.getElementById('enrollChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Enrollments',
                    data: data.values,
                    borderWidth: 2,
                    fill: false,
                    tension: 0.2
                }]
            }
        });
    });
</script>

</body>
</html>

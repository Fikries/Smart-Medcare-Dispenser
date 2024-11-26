<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Database connection
$conn = new mysqli("localhost", "root", "", "elderainfik");

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$monthlyQuery = $conn->prepare("SELECT DATE_FORMAT(date_dispensed, '%Y-%m') AS month, COUNT(*) as total_dispensed FROM motorspin GROUP BY month ORDER BY month");
$monthlyQuery->execute();
$monthlyQuery->store_result();
$monthlyQuery->bind_result($month, $total_dispensed);

$months = [];
$totals = [];
while ($monthlyQuery->fetch()) {
    $months[] = $month;
    $totals[] = $total_dispensed;
}

$monthlyQuery->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicine Frequency Chart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; color: #333; }
        .container { max-width: 800px; margin: 50px auto; text-align: center; }
        .chart-container { width: 100%; height: 400px; margin-top: 30px; }
    </style>
</head>
<body>

<div class="container">
    <h1>Medcare Frequency Chart</h1>
    <p>This chart shows the frequency of each medicine dispensed.</p>

    <div class="chart-container">
        <canvas id="medicineBarChart"></canvas>
    </div>
</div>

<script>
const months = <?php echo json_encode($months); ?>;
const totals = <?php echo json_encode($totals); ?>;

const ctx = document.getElementById('medicineBarChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: months,
        datasets: [{
            label: 'Total Medicines Dispensed',
            data: totals,
            backgroundColor: '#36A2EB',
            borderColor: '#1D7A99',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: { beginAtZero: true, title: { display: true, text: 'Total Medicines' } },
            x: { title: { display: true, text: 'Month' } }
        }
    }
});
</script>

</body>
</html>


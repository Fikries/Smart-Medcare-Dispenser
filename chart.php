<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

// Check if the admin is logged in, if not redirect to login page
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

// Prevent back button from accessing a cached page after logout
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Database connection
$conn = new mysqli("localhost", "root", "", "elderainfik");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to get medicine frequency and type
$medicineFreqQuery = $conn->prepare("SELECT medicinename, medicinetype, COUNT(*) as frequency FROM motorspin GROUP BY medicinename, medicinetype");
$medicineFreqQuery->execute();
$medicineFreqQuery->store_result();
$medicineFreqQuery->bind_result($medicinename, $medicinetype, $frequency);

$medicineNames = [];
$frequencies = [];
$medicineTypes = [];
while ($medicineFreqQuery->fetch()) {
    $medicineNames[] = $medicinename;
    $frequencies[] = $frequency;
    $medicineTypes[] = $medicinetype;
}
$medicineFreqQuery->close();

// Query for bar chart data (medicines dispensed by month)
$monthlyQuery = $conn->prepare("SELECT DATE_FORMAT(datetime, '%Y-%m') AS month, COUNT(*) as total_count FROM motorspin GROUP BY month ORDER BY month");
$monthlyQuery->execute();
$monthlyQuery->store_result();
$monthlyQuery->bind_result($month, $total_count);

$months = [];
$totals = [];
while ($monthlyQuery->fetch()) {
    $months[] = $month;
    $totals[] = $total_count;
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
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            text-align: center;
        }
        .chart-container {
            width: 100%;
            height: 400px;
            margin-top: 30px;
        }
        /* Navbar styling */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #ffffff;
            padding: 10px 20px;
            box-shadow: none;
            border-bottom: 1px solid #e6e6e6;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .navbar .logo {
            font-size: 24px;
            font-weight: bold;
            color: #1877f2;
            text-decoration: none;
        }
        .navbar .nav-links {
            display: flex;
            align-items: center;
            list-style: none;
            margin-left: auto;
            margin-right: 10px;
            gap: 20px;
        }
        .navbar .nav-links li {
            margin: 0;
        }
        .navbar .nav-links a {
            text-decoration: none;
            color: #333;
            font-size: 16px;
            font-weight: 500;
            padding: 10px 15px;
            transition: background-color 0.2s ease, color 0.2s ease;
        }
        .navbar .nav-links a:hover,
        .navbar .nav-links a.active {
            color: #1877f2;
        }
        .logout-btn {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #333;
            background-color: #AEC6CF;
            padding: 8px 15px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .logout-btn:hover {
            background-color: #98b3c3;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar">
    <a href="interface.php" class="logo">Medicine Dispenser</a>
    <ul class="nav-links">
        <li><a href="register.php">Register</a></li>
        <li><a href="list.php">Record</a></li>
        <li><a href="interface.php">Admin</a></li>
        <li><a href="monitoring.php">Monitor</a></li>
        <li><a href="chart.php" class="active">Chart</a></li>
        <li><a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i>Logout</a></li>
    </ul>
</nav>

<div class="container">
    <h1>Medcare Frequency Chart</h1>
    <p>This chart shows the frequency of each medicine dispensed.</p>

    <!-- Table for displaying charts -->
    <table class="table table-bordered">
        <tr>
            <td>
                <!-- Pie Chart -->
                <div class="chart-container">
                    <canvas id="medicinePieChart"></canvas>
                </div>
            </td>
            <td>
                <!-- Bar Chart -->
                <div class="chart-container">
                    <canvas id="medicineBarChart"></canvas>
                </div>
            </td>
        </tr>
    </table>
</div>


<script>
const medicineNames = <?php echo json_encode($medicineNames); ?>;
const frequencies = <?php echo json_encode($frequencies); ?>;
const medicineTypes = <?php echo json_encode($medicineTypes); ?>;
const months = <?php echo json_encode($months); ?>;
const totals = <?php echo json_encode($totals); ?>;

// Calculate total frequency for percentage calculation
const totalFrequencies = frequencies.reduce((a, b) => a + b, 0);

// Pie Chart
const ctx = document.getElementById('medicinePieChart').getContext('2d');
new Chart(ctx, {
    type: 'pie',
    data: {
        labels: medicineNames,
        datasets: [{
            label: 'Medicine Frequency',
            data: frequencies,
            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'],
            hoverBackgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40']
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'top' },
            tooltip: {
                callbacks: {
                    label: function(tooltipItem) {
                        const currentIndex = tooltipItem.dataIndex;
                        const medicineName = medicineNames[currentIndex];
                        const frequency = frequencies[currentIndex];
                        const medicineType = medicineTypes[currentIndex];
                        const percentage = ((frequency / totalFrequencies) * 100).toFixed(2) + '%';
                        return `${medicineName} (${medicineType}): ${frequency} (${percentage})`;
                    }
                }
            }
        }
    }
});

// Bar Chart
const ctxBar = document.getElementById('medicineBarChart').getContext('2d');
new Chart(ctxBar, {
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


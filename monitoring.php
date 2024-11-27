<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Start session
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
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <title>Medicine Dispenser</title>
    <style>
        /* Basic reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* General body styling */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }

        /* Minimalist Navbar like Facebook */
.navbar {
    display: flex;
    justify-content: space-between; /* Adjust this to center the items */
    align-items: center;
    background-color: #ffffff;
    padding: 10px 20px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    position: sticky;
    top: 0;
    z-index: 100;
}

/* Logo or brand */
.navbar .logo {
    font-size: 24px;
    font-weight: bold;
    color: #1877f2;
    text-decoration: none;
    margin-right: auto; /* Ensures the logo is aligned to the left */
}

/* Navigation links */
.navbar .nav-links {
    display: flex;
    align-items: center;
    list-style: none;
    margin-left: auto; /* Aligns the links to the right */
}

.navbar .nav-links li {
    margin-left: 20px;
}

.navbar .nav-links a {
    text-decoration: none;
    color: #333;
    font-size: 16px;
    font-weight: 500;
    padding: 10px 15px;
    border-radius: 5px;
    transition: background-color 0.2s ease, color 0.2s ease;
}

/* Hover effect on nav items */
.navbar .nav-links a:hover {
    background-color: #e4e6eb;
    color: #1877f2;
}
        .nav-links .active {
            font-weight: bold;
            text-decoration: underline;
        }

        /* Modern Logout Button */
        .logout-btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: none;
            color: white;
            background-color: #AEC6CF; /* Navy blue */
            border: none;
            border-radius: 30px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .logout-btn i {
            margin-right: 8px;
        }

        .logout-btn:hover {
            background-color: #e84118; /* Darker red on hover */
            transform: translateY(-2px); /* Lifting effect */
        }

        .logout-btn:active {
            background-color: #c23616; /* Even darker red when clicked */
            transform: translateY(0); /* Normal position when clicked */
        }
        
        /* Dropdown Container */
.dropdown {
    position: relative;
    display: inline-block;
}

/* Dropdown Button */
.dropbtn {
    color: white;
    padding: 16px;
    font-size: 16px;
    border: none;
    cursor: pointer;
}

/* Dropdown Content (Hidden by Default) */
.dropdown-content {
    display: none;
    position: absolute;
    background-color: #f9f9f9;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
}

/* Links inside the dropdown */
.dropdown-content a {
    color: black;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
}

/* Change color of dropdown links on hover */
.dropdown-content a:hover {
    background-color: #f1f1f1;
}

/* Show the dropdown menu on hover */
.dropdown:hover .dropdown-content {
    display: block;
}

        /* Main Heading */
        h1 {
            text-align: center;
            font-size: 1.8em;
            margin: 20px 0;
            color: #333;
            font-weight: 600;
        }

        /* Elder's Name */
        h3 {
            font-family: sans-serif;
            font-size: 1.6em;
            text-align: center;
            color: #333;
            margin-bottom: 10px;
        }

        /* Table styling */
        .table-container {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        table {
            background-image: url('asset/nursing.png');
            background-repeat: no-repeat;
            background-position: center;
            background-size: contain;
            margin: 20px auto;
            border: 1px solid #ddd;
            border-collapse: collapse;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }

        th {
            background-color: #007bff;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        td {
            font-size: 1em;
        }

        td:nth-child(5) {
            text-align: center;
            width: 20px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                align-items: flex-start;
            }

            .nav-links {
                flex-direction: column;
                width: 100%;
            }

            .nav-links li {
                margin: 5px 0;
            }

            h1 {
                font-size: 1.5em;
            }
        }
    </style>
</head>

<body>
    <!-- Minimalist Navbar -->
    <nav class="navbar">
        <a href="interface.php" class="logo">Medicine Dispenser</a>
        <ul class="nav-links">
            <li><a href="register.php" class="active">Register</a></li>
            <li><a href="list.php">Record</a></li>
            <li><a href="interface.php">Admin</a></li>
            <li class="dropdown">
            <a href="javascript:void(0)" class="dropbtn">Monitor</a>
            <div class="dropdown-content">
                <a href="successful.php?status=successful">Successful</a>
                <a href="unsuccessful.php?status=unsuccessful">Unsuccessful</a>
            </div>
        </li>
        <li><a href="chart.php">Chart</a></li>
            <!-- Modern Logout Button -->
            <li><a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i>Logout</a></li>
        </ul>
    </nav>

    <h1>Medcare Medication Log</h1>

    <?php
    $conn = new mysqli("localhost", "root", "", "elderainfik");

    $medicinestatus = $conn->prepare("SELECT `id`, `date` FROM `pushtime` WHERE `date` >= ? AND HOUR(`date`) = HOUR(?) AND DATE(`date`) = DATE(?)");
    $medicinestatus->bind_param("sss", $eachdatetime, $eachdatetime, $eachdatetime);
    ?>

    <table id="motorTableBody">
        <thead>
            <tr>
                <th>ID</th>
                <th>Elder name</th>
                <th>Medicine name</th>
                <th>Medicine type</th>
                <th>Rotation Datetime</th>
                <th>Rotated status</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $displaymotorspinsql = $conn->prepare("SELECT `id`, `eldername`, `elderemail`, `medicinename`, `medicinetype`, `datetime`, `spinstate` FROM `motorspin`");
            $displaymotorspinsql->execute();
            $displaymotorspinsql->store_result();
            $displaymotorspinsql->bind_result($idmotor, $eldername, $elderemail, $medicinename, $medicinetype, $datetimespinmotor, $statespinmotor);

            while ($displaymotorspinsql->fetch()) {
            // Set the color based on the spinstate value
            $color = ($statespinmotor === 'true') ? 'green' : 'red';
              ?>
        <tr>
        <td><?php echo $idmotor ?></td>
        <td><?php echo $eldername ?></td>
        <td><?php echo $medicinename ?></td>
        <td><?php echo $medicinetype ?></td>
        <td><?php echo $datetimespinmotor ?></td>
        <td><?php echo $statespinmotor ?></td>
        <td style="background-color: <?php echo $color ?>;"></td>
        </tr>
            <?php } ?>
        </tbody>
    </table>

    <script>
        function refresh() {
            window.location.reload();
        }
        setTimeout(refresh, 5000);
    </script>
</body>
</html>

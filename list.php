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
    <link rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        /* Basic reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }

        .table-container {
            width: 100%;
            background-image: url('asset/nursing.png');
            background-repeat: no-repeat; /* Prevents multiple logos */
            background-position: center; /* Centers the logo */
            background-size: contain; /* Adjusts the logo size to fit within the container */
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden; /* Ensure border-radius is applied */
        }

        table {
            width: 100%;
            border: 1px solid #ddd; /* Define the border style */
            border-collapse: collapse; /* Ensure borders work properly */
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }

        th {
            background-color: #007bff; /* Bootstrap primary color */
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        tr:hover {
            background-color: #f1f1f1; /* Light gray on hover */
        }

        tr:last-child td {
            border-bottom: none; /* Remove border for the last row */
        }

        /* Responsive design */
        @media (max-width: 768px) {
            th, td {
                padding: 10px;
            }
        }

        /* Style for editable input */
        .editable-input {
            border: none;
            background-color: transparent;
            width: 100%;
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

        /* Style for pagination links */
        .pagination {
            display: flex;
            justify-content: center;
            padding: 10px 0;
        }

        .pagination a {
            color: black;
            padding: 8px 16px;
            text-decoration: none;
            border: 1px solid #ddd;
            margin: 0 2px;
        }

        .pagination a.active {
            background-color: #007bff;
            color: white;
            border: 1px solid #007bff;
        }

        .pagination a:hover:not(.active) {
            background-color: #ddd;
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

        /* Style for search input */
        .search-input {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 200px; /* Adjust width as needed */
            transition: border-color 0.3s;
        }

        /* Style for search button */
        .search-button {
            padding: 10px 15px;
            border: 1px solid #007bff;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            margin-left: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s, border-color 0.3s;
        }

        /* Hover effect for search button */
        .search-button:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        /* Focus effect for search input */
        .search-input:focus {
            border-color: #007bff;
            outline: none; /* Remove default outline */
        }
    </style>
</head>
<body>
<nav class="navbar">
    <a href="interface.php" class="logo">Medicine Dispenser</a>
    <ul class="nav-links">
        <li><a href="register.php" class="active">Register</a></li>
        <li><a href="list.php">Record</a></li>
        <li><a href="interface.php">Admin</a></li>
        <li><a href="monitoring.php">Monitor</a></li>
        <li><a href="chart.php">Chart</a></li>
        <!-- Modern Logout Button -->
            <li><a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i>Logout</a></li>
    </ul>
</nav>
<h1></h1><br><br>
<h1 align="center">RECORD ELDER</h1><br><br>
<div class="filter-container">
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <p style="text-align: center; font-family: Arial, sans-serif;">Elder Name:
        <input type="text" id="name" name="name" value="<?php echo isset($_POST['name']) ? $_POST['name'] : ''; ?>" class="search-input">
        <button type="submit" class="search-button">Search</button></p>
    </form>
</div>

<?php
// Establish connection to the database
<<<<<<< HEAD
$connect = mysqli_connect("localhost", "root", "", "elderainfik");
=======
$connect = mysqli_connect("localhost", "root", "", "project2");
>>>>>>> 18efd40711513c6e1deef57c6c172f4aa5f268e5

// Check the connection
if (mysqli_connect_errno()) {
    die("Failed to connect to MySQL: " . mysqli_connect_error());
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $searchName = mysqli_real_escape_string($connect, $_POST['name']);
} else {
    $searchName = '';
}

// Pagination logic
$records_per_page = 10; // Number of records to display per page
$current_page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($current_page - 1) * $records_per_page;

// Query to count total records
$count_query = "SELECT COUNT(*) FROM patient WHERE name LIKE '%$searchName%'";
$count_result = mysqli_query($connect, $count_query);
$total_records = mysqli_fetch_array($count_result)[0];
$total_pages = ceil($total_records / $records_per_page);

// Query to fetch filtered resident data with pagination
$query = "SELECT id, name, email, address, datein, timein, illness FROM patient WHERE name LIKE '%$searchName%' ORDER BY id LIMIT $offset, $records_per_page";
$result = mysqli_query($connect, $query);

if ($result) {
    echo '<div class="table-container">';
    echo '<div align="center">';
    echo '<table>
    <tr>
        <th>Resident ID</th>
        <th>Elder Name</th>
        <th>Email</th>
        <th>Address</th>
        <th>Date In</th>
        <th>Time In</th>
        <th>Illness</th>
    </tr>';

    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        echo '<tr>
        <td>' . $row['id'] . '</td>
        <td class="editable-cell" data-field="name" data-id="' . $row['id'] . '">' . $row['name'] . '</td>
        <td class="editable-cell" data-field="email" data-id="' . $row['id'] . '">' . $row['email'] . '</td>
        <td class="editable-cell" data-field="address" data-id="' . $row['id'] . '">' . $row['address'] . '</td>
        <td class="editable-cell editable-date" data-field="datein" data-id="' . $row['id'] . '">' . $row['datein'] . '</td>
        <td class="editable-cell editable-time" data-field="timein" data-id="' . $row['id'] . '">' . $row['timein'] . '</td>
        <td class="editable-cell" data-field="illness" data-id="' . $row['id'] . '">' . $row['illness'] . '</td>
        </tr>';
    }

    echo '</table>';
    echo '</div>';
    echo '</div>';

    // Pagination links
    echo '<div class="pagination">';
    for ($page = 1; $page <= $total_pages; $page++) {
        echo '<a href="?page=' . $page . '"';
        if ($page == $current_page) {
            echo ' class="active"';
        }
        echo '>' . $page . '</a> ';
    }
    echo '</div>';
} else {
    echo "Error: " . mysqli_error($connect);
}

// Close the database connection
mysqli_close($connect);
?>

<!-- JavaScript for making table cells editable on double-click -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cells = document.querySelectorAll('.editable-cell');

        cells.forEach(cell => {
            cell.addEventListener('dblclick', () => {
                const text = cell.innerText.trim();
                let inputType = 'text';
                if (cell.classList.contains('editable-date')) {
                    inputType = 'date';
                } else if (cell.classList.contains('editable-time')) {
                    inputType = 'time';
                }
                cell.innerHTML = `<input type="${inputType}" class="editable-input" value="${text}">`;
                const input = cell.querySelector('.editable-input');
                input.focus();

                input.addEventListener('blur', () => {
                    const newValue = input.value.trim();
                    const field = cell.getAttribute('data-field');
                    const id = cell.getAttribute('data-id');

                    // Update the database with the new value
                    updateCellValue(field, id, newValue);

                    cell.innerHTML = newValue;
                });

                input.addEventListener('keydown', (event) => {
                    if (event.key === 'Enter') {
                        input.blur();
                    }
                });
            });
        });

        // Function to update cell value in the database via AJAX
        function updateCellValue(field, id, value) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'update_cell.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.send(`field=${field}&id=${id}&value=${encodeURIComponent(value)}`);
        }
    });
</script>
</body>
</html>

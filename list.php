<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        .navbar {
            overflow: hidden;
            background-color: #333;
        }

        .navbar a {
            float: left;
            display: block;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }

        .navbar a:hover {
            background-color: #ddd;
            color: black;
        }

        .dropdown {
            float: left;
            overflow: hidden;
        }

        .dropdown .dropbtn {
            font-size: 16px;
            border: none;
            outline: none;
            color: white;
            padding: 14px 16px;
            background-color: inherit;
            font-family: inherit;
            margin: 0;
        }

        .navbar a:hover, .dropdown:hover .dropbtn {
            background-color: #ddd;
            color: black;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }

        .dropdown-content a {
            float: none;
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            text-align: left;
        }

        .dropdown-content a:hover {
            background-color: #ddd;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .navbar a.active {
            background-color: #04AA6D;
            color: white;
        }

        .table-container {
            width: 100%;
            margin: 20px auto;
            border-collapse: collapse;
        }

        .table-container th, .table-container td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .table-container th {
            background-color: #f2f2f2;
        }

        .table-container tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .table-container tr:hover {
            background-color: #ddd;
        }

        /* Style for editable input */
        .editable-input {
            border: none;
            background-color: transparent;
            width: 100%;
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
            background-color: #04AA6D;
            color: white;
            border: 1px solid #04AA6D;
        }

        .pagination a:hover:not(.active) {
            background-color: #ddd;
        }
    </style>
</head>
<body>
<div class="navbar">
    <a href="register.php">Register</a>
    <div class="dropdown">
    <button class="dropbtn">Record</button>
    <div class="dropdown-content">
      <a href="list.php">Date In</a>
      <a href="dateout.php">Date Out</a>
    </div>
  </div> 
    <a href="interface.php">Admin</a>
    <a href="monitoring.php">Monitor</a>
</div>
<h1><span class="highlighted">PERSONAL MEDICINE DISPENSER WITH NOTIFICATION FOR ELDERLY CARE IN NURSING HOME USING ESP32 INTERGRATED WITH MYSQL DATABASE</h1></span>
<div class="filter-container">
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="name"><div align=center>Elder Name:</label>
        <input type="text" id="name" name="name" value="<?php echo isset($_POST['name']) ? $_POST['name'] : ''; ?>">
        <button type="submit" class="filter">Search</button></div>
    </form>
</div>

<?php
// Establish connection to the database
$connect = mysqli_connect("localhost", "root", "", "project2");

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
    echo '<table border="2">
    <tr>
        <td><b>Resident ID</b></td>
        <td><b>Elder Name</b></td>
        <td><b>Email</b></td>
        <td><b>Address</b></td>
        <td><b>Date In</b></td>
        <td><b>Time In</b></td>
        <td><b>Illness</b></td>
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

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
    </style>
</head>
<body>
<div class="navbar">
    <a href="register.php">Register</a>
    <a href="list.php">List</a>
    <a href="interface.php">Admin</a>
    <a href="monitoring.php">Monitor</a>
</div>
<h1><span class="highlighted">PERSONAL MEDICINE DISPENSER WITH NOTIFICATION FOR ELDERLY CARE IN
            NURSING HOME USING ESP32 INTERGRATED WITH MYSQL DATABASE</h1></span>
            <div class="filter-container">
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="name">Elder Name:</label>
        <input type="text" id="name" name="name" value="<?php echo isset($_POST['name']) ? $_POST['name'] : ''; ?>">
        <button type="submit" class="filter">Search</button>
    </form>
</div>

<?php
// Establish connection to the database
$connect = mysqli_connect("localhost", "root", "", "project");

// Check the connection
if (mysqli_connect_errno()) {
    die("Failed to connect to MySQL: " . mysqli_connect_error());
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $searchName = mysqli_real_escape_string($connect, $_POST['name']);

    // Query to fetch filtered resident data with pagination
    $query = "SELECT id, name, email, address, datein, illness FROM patient WHERE name LIKE '%$searchName%' ORDER BY id";
    $result = mysqli_query($connect, $query);

    if ($result) {
        echo '<div class="table-container">';
        echo '<table border="2">
        <tr>
            <td><b>Resident ID</b></td>
            <td><b>Elder Name</b></td>
            <td><b>Email</b></td>
            <td><b>Address</b></td>
            <td><b>Date In</b></td>
            <td><b>Illness</b></td>
        </tr>';

        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            echo '<tr>
            <td>' . $row['id'] . '</td>
            <td>' . $row['name'] . '</td>
            <td>' . $row['email'] . '</td>
            <td>' . $row['address'] . '</td>
            <td>' . $row['datein'] . '</td>
            <td>' . $row['illness'] . '</td>
            </tr>';
        }

        echo '</table>';
        echo '</div>';
    } else {
        echo "Error: " . mysqli_error($connect);
    }

    // Close the database connection
    mysqli_close($connect);
}
?>

<!-- JavaScript for making table cells editable on double-click -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cells = document.querySelectorAll('.editable-cell');

        cells.forEach(cell => {
            cell.addEventListener('dblclick', () => {
                const text = cell.innerText.trim();
                cell.innerHTML = `<input type="text" class="editable-input" value="${text}">`;
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

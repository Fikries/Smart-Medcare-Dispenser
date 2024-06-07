<?php
// Start session
session_start();

// Check if the admin is logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    // Display logout button
    echo '<form action="logout.php" method="post">';
    echo '<button class="button" type="submit">Logout</button>';
    echo '</form>';
}

if (
    isset($_POST['name']) &&
    isset($_POST['email']) &&
    isset($_POST['address']) &&
    isset($_POST['date_in']) &&
    isset($_POST['illness']) // Check if the payment status is set
) {
    // Establish connection to the database
    $conn = new mysqli("localhost", "root", "", "project");

    // Prepare SQL statement to insert data into the database
    $sql = $conn->prepare("INSERT INTO patient (id, name, email, address, datein,  illness) VALUES (NULL, ?, ?, ?, ?, ?)");

    // Bind parameters to the prepared statement
    $sql->bind_param("sssss", $_POST['name'], $_POST['email'], $_POST['address'], $_POST['date_in'], $_POST['illness']);

    // Execute the prepared statement
    $sql->execute();

    // Notify the user that insertion was successful
    echo "<script>alert('Insert successfully')</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
<style>
        body {
            background-image: url('asset/background.jpg');
            /* Specify the path to your image */
            background-size: cover;
            /* Cover the entire background */
            background-position: center;
            /* Center the background image */
            background-repeat: no-repeat;
            /* Do not repeat the background image */
            color: rgb(9, 67, 4);

        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            background-image: linear-gradient(to right, #ff007f, #ffcc00);
            color: white;
            border-radius: 9999px;
            /* Large value to make it look like full rounded */
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: none;
        }

        .highlighted {
            background-color: yellow;
            /* Set background color to yellow for highlighted text */
            color: black;
            /* Set font color to black for highlighted text */

        }

        .font {
            background-color: white;
            /* Set background color to yellow for highlighted text */
            color: black;
            /* Set font color to black for highlighted text */
            border-radius: 20px;
            /* Set border radius to create a curved highlight effect */
            padding: 5px 10px;
            /* Add padding for better appearance */
        }

        #remark {
            width: 300px;
            /* Adjust width as needed */
            height: 100px;
            /* Adjust height as needed */
        }

        table {
            width: 500px;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        td {
            background-color: #f2f2f2;
        }
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
    </style>
    <title>Medicine Dispenser</title>
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
        <form action="" method="post">
        <h2><span class="highlighted">PATIENT Information</h2></span>
        <label class="font" for="name">Patient Name:</label>
        <input type="text" id="name" name="name" required>
        <br><br>

        <label class="font" for="email">Patient Email:</label>
        <input type="text" id="email" name="email" required>
        <br><br>

        <label class="font" for="address">Address:</label>
        <input type="text" id="address" name="address" required>
        <br><br>

        <label class="font" for="date_in">Time In Date:</label>
        <input type="date" id="date_in" name="date_in" required>
        <br><br>

        <label class="font" for="illness">Illness:</label>
        <input type="text" id="illness" name="illness" placeholder="Optional">
        <br><br>

        <input type="submit" value="Submit">
    </form>
    <br>
</body>
</html>
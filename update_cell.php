<?php
// Establish connection to the database
$connect = mysqli_connect("localhost", "root", "", "project");

// Check the connection
if (mysqli_connect_errno()) {
    die("Failed to connect to MySQL: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $field = mysqli_real_escape_string($connect, $_POST['field']);
    $id = mysqli_real_escape_string($connect, $_POST['id']);
    $value = mysqli_real_escape_string($connect, $_POST['value']);

    // Update the database
    $updateQuery = "UPDATE patient SET $field = '$value' WHERE id = '$id'";
    if (mysqli_query($connect, $updateQuery)) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . mysqli_error($connect);
    }

    // Close the database connection
    mysqli_close($connect);
    exit(); // Stop further execution
}
?>

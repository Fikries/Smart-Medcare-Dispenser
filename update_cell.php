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

<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $elderName = $_POST['elder_name'];
    $caretakerEmail = $_POST['caretaker_email'];
    $medicineName = $_POST['medicine_name'];
    $medicineType = $_POST['medicine_type'];
    $consumptionDate = $_POST['consumption_date'];
    $consumptionTime = $_POST['consumption_time'];
    $remark = $_POST['remark'];

    // Database connection
    $conn = new mysqli("localhost", "root", "", "project2");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL statement
    $sql = $conn->prepare("INSERT INTO medicine (eldername, caretakeremail, medicinename, medicinetype, consumptiondate, consumptiontime, remark) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $sql->bind_param("sssssss", $elderName, $caretakerEmail, $medicineName, $medicineType, $consumptionDate, $consumptionTime, $remark);

    // Execute SQL statement
    if ($sql->execute() === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close connection
    $conn->close();
}
?>

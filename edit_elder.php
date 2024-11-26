<?php
// Establish connection to the database
$connect = mysqli_connect("localhost", "root", "", "elderainfik");

// Check the connection
if (mysqli_connect_errno()) {
    die("Failed to connect to MySQL: " . mysqli_connect_error());
}

// Check if resident ID is provided in the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $elder_id = $_GET['id'];

    // Retrieve resident information
    $query = "SELECT * FROM patient WHERE id = $elder_id";
    $result = mysqli_query($connect, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $elder_id = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html>
<head>
    <title>UPDATE ELDER</title>
</head>
<body>
    <h2>UPDATE ELDER</h2>
    <form action="update_elder.php" method="post">
        <input type="hidden" name="elder_id" value="<?php echo $elder['id']; ?>">
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

        <input type="submit" value="Update">
        <button type="button" onclick="cancelEdit()">Cancel</button>
    </form>
    <!-- JavaScript function to handle cancellation -->
    <script>
        function cancelEdit() {
            // Redirect the user to the previous page
            window.history.back();
        }
    </script>
</body>
</html>
<?php
    } else {
        echo "Resident not found.";
    }
} else {
    echo "Invalid request.";
}

// Close the database connection
mysqli_close($connect);
?>
<?php
session_start();

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate username and password (you may replace this with a database query)
    $valid_username = "admin";
    $valid_password = "admin123";

    // Retrieve username and password from form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if username and password match
    if ($username === $valid_username && $password === $valid_password) {
        // Admin credentials are valid, set session variables or redirect to admin dashboard
        $_SESSION['admin_logged_in'] = true;
        header("Location: interface.php");
        exit();
    } else {
        // Invalid credentials, show error message
        echo "Invalid username or password. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
</head>
<body>
    <h2>Admin Login</h2>
    <form action="login.php" method="post">
        Username: <input type="text" name="username" required><br><br>
        Password: <input type="password" name="password" required><br><br>
        <input type="submit" value="Login">
    </form>
</body>
</html>
<?php
session_start();

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate username and password (replace with a database query in a real scenario)
    $valid_username = "admin";
    $valid_password = "admin123";

    // Retrieve username and password from form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if username and password match
    if ($username === $valid_username && $password === $valid_password) {
        // Admin credentials are valid, set session variable
        $_SESSION['admin_logged_in'] = true;
        header("Location: monitoring.php");
        exit();
    } else {
        // Invalid credentials, show error message
        $error_message = "Invalid username or password. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome Icons -->
    <style>
        /* General reset and styling */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body, html {
            height: 100%;
            font-family: 'Poppins', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            background: url('asset/nursing.png') no-repeat center center fixed;
            background-size: cover;
            overflow: hidden;
        }

        /* Add a subtle animation to the background */
        @keyframes backgroundShift {
            0% { background-position: center; }
            50% { background-position: center right; }
            100% { background-position: center; }
        }

        body {
            animation: backgroundShift 10s ease-in-out infinite;
        }

        /* Minimalist login container with fade-in effect */
        .login-container {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 100%;
            text-align: center;
            animation: fadeIn 1.2s ease-in-out both;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-container h2 {
            font-size: 2.2rem;
            margin-bottom: 20px;
            font-weight: 600;
            color: #333;
        }

        .login-container form {
            display: flex;
            flex-direction: column;
        }

        /* Input fields with icons and hover focus shadow */
        .input-container {
            position: relative;
            margin-bottom: 20px;
            transition: transform 0.3s ease;
        }

        .login-container input[type="text"], 
        .login-container input[type="password"] {
            padding: 15px 15px 15px 45px;
            border: none;
            border-radius: 5px;
            background-color: #f7f7f7;
            font-size: 1rem;
            width: 100%;
            outline: none;
            transition: box-shadow 0.3s ease;
        }

        /* Focus effect on input fields */
        .login-container input[type="text"]:focus,
        .login-container input[type="password"]:focus {
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.3);
        }

        /* Icon styling with slight color transition */
        .input-container i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
            transition: color 0.3s ease;
        }

        .input-container:hover i {
            color: #007bff;
        }

        /* Login button with subtle animation */
        .login-container input[type="submit"] {
            padding: 15px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            outline: none;
        }

        .login-container input[type="submit"]:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }

        .login-container input[type="submit"]:active {
            transform: translateY(1px);
        }

        /* Error message with smooth fade-in */
        .error-message {
            color: #e74c3c;
            margin-bottom: 15px;
            font-size: 0.9rem;
            animation: fadeInError 0.5s ease-in-out both;
        }

        @keyframes fadeInError {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Responsive design for smaller screens */
        @media (max-width: 768px) {
            .login-container {
                padding: 30px;
                max-width: 90%;
            }
        }
    </style>
</head>
<body>
  <h1>SMART MEDCARE DISPENSER</h1>

<div class="login-container">
    <h2>Admin Login</h2>
    
    <?php if (isset($error_message)) { ?>
        <div class="error-message"><?php echo $error_message; ?></div>
    <?php } ?>
    
    <form action="login.php" method="post">
        <div class="input-container">
            <i class="fas fa-user"></i> <!-- Username icon -->
            <input type="text" name="username" placeholder="Username" required>
        </div>
        <div class="input-container">
            <i class="fas fa-lock"></i> <!-- Password icon -->
            <input type="password" name="password" placeholder="Password" required>
        </div>
        <input type="submit" value="Login">
    </form>
</div>

</body>
</html>


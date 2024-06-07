<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate username and password
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
        $error_message = "Invalid username or password. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
        }

        .container {
            text-align: center;
        }

        .login {
            width: 340px;
            height: 380px;
            background: #2c2c2c;
            padding: 47px;
            padding-bottom: 30px;
            color: #fff;
            border-radius: 17px;
            font-size: 1.3em;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            display: inline-block;
        }

        .login input[type="text"],
        .login input[type="password"] {
            opacity: 1;
            display: block;
            border: none;
            outline: none;
            width: calc(100% - 36px);
            padding: 13px 18px;
            margin: 20px 0 0 0;
            font-size: 0.8em;
            border-radius: 100px;
            background: #3c3c3c;
            color: #fff;
        }

        .login input:focus {
            animation: bounce 1s;
            -webkit-appearance: none;
        }

        .login input[type=submit],
        .login input[type=button],
        .h1 {
            border: 0;
            outline: 0;
            width: 100%;
            padding: 13px;
            margin: 40px 0 0 0;
            border-radius: 500px;
            font-weight: 600;
            animation: bounce2 1.6s;
        }

        .h1 {
            padding: 0;
            position: relative;
            top: -35px;
            display: block;
            margin-bottom: -0px;
            font-size: 1.3em;
        }

        .btn {
            background: linear-gradient(144deg, #af40ff, #5b42f3 50%, #00ddeb);
            color: #fff;
            padding: 16px !important;
        }

        .btn:hover {
            background: linear-gradient(144deg, #1e1e1e, 20%, #1e1e1e 50%, #1e1e1e);
            color: rgb(255, 255, 255);
            padding: 16px !important;
            cursor: pointer;
            transition: all 0.4s ease;
        }

        .login input[type=text] {
            animation: bounce 1s;
            -webkit-appearance: none;
        }

        .login input[type=password] {
            animation: bounce1 1.3s;
        }

        .ui {
            font-weight: bolder;
            background: -webkit-linear-gradient(#B563FF, #535EFC, #0EC8EE);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            border-bottom: 4px solid transparent;
            border-image: linear-gradient(0.25turn, #535EFC, #0EC8EE, #0EC8EE);
            border-image-slice: 1;
            display: inline;
        }

        @media only screen and (max-width: 600px) {
            .login {
                width: 70%;
                padding: 3em;
            }
        }

        @keyframes bounce {
            0% {
                transform: translateY(-250px);
                opacity: 0;
            }
        }

        @keyframes bounce1 {
            0% {
                opacity: 0;
            }

            40% {
                transform: translateY(-100px);
                opacity: 0;
            }
        }

        @keyframes bounce2 {
            0% {
                opacity: 0;
            }

            70% {
                transform: translateY(-20px);
                opacity: 0;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="login wrap">
            <div class="h1">Login</div>
            <form method="post" action="">
                <input placeholder="Username" id="username" name="username" type="text" required>
                <input placeholder="Password" id="password" name="password" type="password" required>
                <input value="Login" class="btn" type="submit">
            </form>
            <?php
            if (isset($error_message)) {
                echo "<p style='color:red;'>$error_message</p>";
            }
            ?>
        </div>
    </div>
</body>

</html>

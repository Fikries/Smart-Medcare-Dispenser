<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
// Start session
session_start();

// Check if the admin is logged in, if not redirect to login page
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

// Prevent back button from accessing a cached page after logout
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require 'vendor/autoload.php';

if (
    isset($_POST['name']) &&
    isset($_POST['email']) &&
    isset($_POST['address']) &&
    isset($_POST['date_in']) &&
    isset($_POST['time_in']) &&
    isset($_POST['illness']) // Check if the payment status is set
) {
    // Establish connection to the database
    $conn = new mysqli("localhost", "root", "", "elderainfik");

    // Check if the email already exists in the database
    $email = $_POST['email'];
    $checkEmail = $conn->prepare("SELECT email FROM patient WHERE email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $checkEmail->store_result();

    if ($checkEmail->num_rows > 0) {
        // If the email exists, show an alert and stop further execution
        echo "<script>alert('This email is already registered. Please use a different email.'); window.location='register.php';</script>";
    } else {
        // Prepare SQL statement to insert data into the database
        $sql = $conn->prepare("INSERT INTO patient (id, name, email, address, datein, timein, illness, usename, password) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?)");

        // Bind parameters to the prepared statement
        $sql->bind_param("ssssssss", $_POST['name'], $_POST['email'], $_POST['address'], $_POST['date_in'], $_POST['time_in'], $_POST['illness'], $username, $password);
        $username = '';
        $password = '';

        // Execute the prepared statement
        $sql->execute();

        // PHPMailer setup for sending the email
        $mail = new PHPMailer(true);
        try {
            $receiveremail = $_POST['email'];
            $receivername = $_POST['name'];
            // Server settings
            $mail->isSMTP();                                            // Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                     // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = 'fikries184@gmail.com';                     // SMTP username
            $mail->Password   = 'fdvhejgdxfddoapo';                               // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            // Enable implicit TLS encryption
            $mail->Port       = 465;                                    // TCP port to connect to

            // Recipients
            $mail->setFrom('fikries184@gmail.com', 'Fikri Medical');
            $mail->addAddress($receiveremail, $receivername);     // Add a recipient

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Registration';
            $mail->Body    = '<html><body style="font-family: \'Times New Roman\', Times, serif;">
                Your registration is successful. Here are the details submitted:</body></html>' . '<br><br>' .
                'Name: ' . $_POST['name'] . '<br>' .
                'Email: ' . $_POST['email'] . '<br>' .
                'Address: ' . $_POST['address'] . '<br>' .
                'Date In: ' . $_POST['date_in'] . '<br>' .
                'Time In: ' . $_POST['time_in'] . '<br>' .
                'Illness: ' . $_POST['illness'] . '<br><br>' .
                'Thank you.';

            $mail->AltBody = $_POST['name'] . ' Thank you for your registration. Here are the details submitted. ' .
                'Email: ' . $_POST['email'] . '. Address: ' . $_POST['address'] .
                '. Date In: ' . $_POST['date_in'] . '. Illness: ' . $_POST['illness'];

            $mail->send();
            // echo 'Message has been sent';
        } catch (Exception $e) {
            // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

        echo "<script>alert('Insert successfully, and notification is sent through email'); window.location='list.php';</script>";
    }

    $checkEmail->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
        /* Basic reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            color: rgb(9, 67, 4);
        }

        /* Minimalist Navbar like Facebook */
.navbar {
    display: flex;
    justify-content: space-between; /* Adjust this to center the items */
    align-items: center;
    background-color: #ffffff;
    padding: 10px 20px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    position: sticky;
    top: 0;
    z-index: 100;
}

/* Logo or brand */
.navbar .logo {
    font-size: 24px;
    font-weight: bold;
    color: #1877f2;
    text-decoration: none;
    margin-right: auto; /* Ensures the logo is aligned to the left */
}

/* Navigation links */
.navbar .nav-links {
    display: flex;
    align-items: center;
    list-style: none;
    margin-left: auto; /* Aligns the links to the right */
}

.navbar .nav-links li {
    margin-left: 20px;
}

.navbar .nav-links a {
    text-decoration: none;
    color: #333;
    font-size: 16px;
    font-weight: 500;
    padding: 10px 15px;
    border-radius: 5px;
    transition: background-color 0.2s ease, color 0.2s ease;
}

/* Hover effect on nav items */
.navbar .nav-links a:hover {
    background-color: #e4e6eb;
    color: #1877f2;
}

        /* Main container for both profile and patient info */
        .main-container {
            display: flex;
            justify-content: space-between;
            margin: 50px auto;
            width: 90%;
            max-width: 1200px;
        }

        /* Admin profile container */
        .profile-container {
            flex: 1;
            background-image: url('asset/mara.png');
            margin-right: 20px;
            background-position: center;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 8px;
            max-height: 400px;
           box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
            background-size: 50%; /* Adjust this value as needed */
            background-repeat: no-repeat; /* To prevent tiling */
        }
        
        /* Modern Logout Button */
        .logout-btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: none;
            color: white;
            background-color: #AEC6CF; /* Navy blue */
            border: none;
            border-radius: 30px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .logout-btn i {
            margin-right: 8px;
        }

        .logout-btn:hover {
            background-color: #e84118; /* Darker red on hover */
            transform: translateY(-2px); /* Lifting effect */
        }

        .logout-btn:active {
            background-color: #c23616; /* Even darker red when clicked */
            transform: translateY(0); /* Normal position when clicked */
        }


        /* Form container */
        .patient-container {
            flex: 2;
            background-image: url('asset/nursing.png');
            background-size: cover;
            background-position: center;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.8);
           box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
            border-radius: 8px;
        }

        h1, h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }

        input[type="text"],
        input[type="email"],
        input[type="date"],
        input[type="time"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            background-color: rgba(255, 255, 255, 0.5);
            color: #333;
        }

        input[type="submit"] {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #218838;
        }

        .marquee {
            width: 100%;
            overflow: hidden;
            white-space: nowrap;
            box-sizing: border-box;
        }

        .marquee span {
            display: inline-block;
            padding-left: 100%;
            animation: marquee 8s linear infinite;
        }

        @keyframes marquee {
            0% {
                transform: translateX(100%);
            }
            100% {
                transform: translateX(-100%);
            }
        }

        /* Profile Picture Styling */
        #profile-picture {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 3px solid #ccc;
            object-fit: cover;
            margin-bottom: 10px;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .navbar .nav-links {
                display: none;
            }

            .main-container {
                flex-direction: column;
            }

            .profile-container, .patient-container {
                margin: 100px;
            }   
        }
    </style>
    <title>Medicine Dispenser</title>
</head>
<body>
<!-- Minimalist Navbar -->
<nav class="navbar">
    <a href="interface.php" class="logo">Medicine Dispenser</a>
    <ul class="nav-links">
        <li><a href="register.php" class="active">Register</a></li>
        <li><a href="list.php">Record</a></li>
        <li><a href="interface.php">Admin</a></li>
        <li><a href="monitoring.php">Monitor</a></li>
        <li><a href="chart.php">Chart</a></li>
        <!-- Modern Logout Button -->
            <li><a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i>Logout</a></li>
    </ul>
</nav>
<div class="main-container">
    <!-- Admin Profile Container -->
    <div class="profile-container">
        <p><b>Admin Profile</b></p>
        <div class="camera-container">
            <img id="profile-picture" src="" alt="Profile Picture" />
            <input type="file" id="file-input" style="display:none;" accept="image/*" />
        </div>
        <form action="" method="post" onsubmit="return showProfileAlert();">
            <div class="form-group">
                <label for="admin_name">Admin Name:</label>
                <p>MUHAMMAD FIKRI BIN ZAHARUDIN</p>
            </div>

            <div class="form-group">
                <label for="admin_sv">Supervisor Name:</label>
                <p>PN AZLIN BINTI RAMLI</p>
            </div>

            <div class="form-group">
                <label for="admin_class">Class:</label>
                <p>FUJITSU</p>
            </div>

            <div class="form-group">
                <label for="admin_email">Admin Email:</label>
                <p>fikries184@gmail.com</p>
            </div>
        </form>
    </div>

    <!-- Patient Information Container -->
    <div class="patient-container">
        <h1>A D M I N</h1>
        <h2>Patient Information</h2>
        <div class="marquee">
            <span>Please fill elder's information and submit the form.</span>
        </div>
        <form action="" method="post">
            <div class="form-group">
                <label for="name">Patient Name:</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="email">Patient Email:</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" required>
            </div>

            <div class="form-group">
                <label for="date_in">Date In:</label>
                <input type="date" id="date_in" name="date_in" required>
            </div>

            <div class="form-group">
                <label for="time_in">Time In:</label>
                <input type="time" id="time_in" name="time_in" required>
            </div>

            <div class="form-group">
                <label for="illness">Illness:</label>
                <input type="text" id="illness" name="illness" placeholder="Optional">
            </div>

            <input type="submit" value="Submit">
        </form>
    </div>
</div>
    <br>
<script>
document.getElementById('profile-picture').addEventListener('click', () => {
    document.getElementById('file-input').click();
});

document.getElementById('file-input').addEventListener('change', (event) => {
    const file = event.target.files[0];
    const reader = new FileReader();

    reader.onload = (e) => {
        document.getElementById('profile-picture').src = e.target.result;
        localStorage.setItem('profilePicture', e.target.result);
    };

    if (file) {
        reader.readAsDataURL(file);
    }
});

window.onload = function() {
    const savedImage = localStorage.getItem('profilePicture');
    if (savedImage) {
        document.getElementById('profile-picture').src = savedImage;
    }
};
</script>

</body>
</html>


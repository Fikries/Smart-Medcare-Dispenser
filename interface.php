<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
// Start session
session_start();

// Check if the admin is logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    // Display logout button
    echo '<form action="logout.php" method="post">';
    echo '<button class="button" type="submit"><span class="glyphicon glyphicon-log-out"></span> Logout</button>';
    echo '</form>';
}

require 'vendor/autoload.php';
if (
    isset($_POST['elder_name']) &&
    isset($_POST['elder_email']) &&
    isset($_POST['medicine_name']) &&
    isset($_POST['medicine_type']) &&
    isset($_POST['consumption_date']) &&
    isset($_POST['consumption_time']) &&
    isset($_POST['remark'])
) {
    $conn = new mysqli("localhost", "root", "", "project");
    $sql = $conn->prepare("INSERT INTO medicine(id, eldername, email, medicinename, medicinetype, consumptiondate, consumptiontime, remark) VALUES (NULL,?,?,?,?,?,?,?)");
    $sql->bind_param("sssssss", $_POST['elder_name'], $_POST['elder_email'], $_POST['medicine_name'], $_POST['medicine_type'], $_POST['consumption_date'], $_POST['consumption_time'], $_POST['remark']);
    $sql->execute();
    $mail = new PHPMailer(true);
    try {
        $receiveremail = $_POST['elder_email'];
        $receivername = $_POST['elder_name'];
        //Server settings
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'fikries184@gmail.com';                     //SMTP username
        $mail->Password   = 'xorbcraxrcpbmang';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('fikries184@gmail.com', 'Fikri Medical');
        $mail->addAddress($receiveremail, $receivername);     //Add a recipient

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Medical Submission';
        $mail->Body    = '<h3>'.$_POST['elder_name'].'</h3>Thank you for your submission. Here is your details submitted<br>Email: '.$_POST['elder_email'].'<br>Medicine name: '.$_POST['medicine_name'].'<br>Medicine type: '.$_POST['medicine_type'].'<br>Comsumption date: '.$_POST['consumption_date'].'<br>Comsumption time: '.$_POST['consumption_time'].'<br>Remark: '.$_POST['remark'];
        $mail->AltBody = $_POST['elder_name'].' Thank you for your submission. Here is your details submitted. Email: '.$_POST['elder_email'].'. Medicine name: '.$_POST['medicine_name'].'. Medicine type: '.$_POST['medicine_type'].'. Comsumption date: '.$_POST['consumption_date'].'. Comsumption time: '.$_POST['consumption_time'].'. Remark: '.$_POST['remark'];


        $mail->send();
        // echo 'Message has been sent';
    } catch (Exception $e) {
        // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

    echo "<script>alert('Insert successfully, and notification is sent through email')</script>";
}
?>

<!DOCTYPE html>
<html>

<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <title>Medicine Dispenser</title>
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
    </style>
</head>

<body class="body">

    <h1><span class="highlighted">PERSONAL MEDICINE DISPENSER WITH NOTIFICATION FOR ELDERLY CARE IN
            NURSING HOME USING ESP32 INTERGRATED WITH MYSQL DATABASE</h1></span>

    <label class="font" for="rotation_time">Enter Rotation Time:</label>
    <input type="datetime-local" id="rotation_time">
    <button class="button" onclick="executeRotation()">Set Rotation Time</button>

    <form action="" method="post">
        <h2><span class="highlighted">Elder Information</h2></span>
        <label class="font" for="elder_name">Elder Name:</label>
        <input type="text" id="elder_name" name="elder_name" required>
        <br><br>

        <label class="font" for="elder_email">Elder email:</label>
        <input type="text" id="elder_email" name="elder_email" required>
        <br><br>

        <label class="font" for="medicine_name">Medicine Name:</label>
        <input type="text" id="medicine_name" name="medicine_name" required>
        <br><br>

        <label class="font" for="medicine_type">Medicine Type:</label>
        <input type="text" id="medicine_type" name="medicine_type" required>
        <br><br>

        <label class="font" for="consumption_date">Consumption Date:</label>
        <input type="date" id="consumption_date" name="consumption_date" required>
        <br><br>

        <label class="font" for="consumption_time">Consumption Time:</label>
        <input type="time" id="consumption_time" name="consumption_time" required>
        <br><br>

        <label class="font" for="remark">Remarks:</label>
        <textarea id="remark" name="remark" rows="4" cols="50" required></textarea>
        <br><br>

        <button class="button" type="submit">Submit Medicine Info</button>
    </form>

    <script>
        function getCurrentDateTime() {
            // Get the current datetime
            let currentDate = new Date();

            // Format the date components
            let year = currentDate.getFullYear();
            let month = ('0' + (currentDate.getMonth() + 1)).slice(-2); // Months are zero based
            let day = ('0' + currentDate.getDate()).slice(-2);
            let hours = ('0' + currentDate.getHours()).slice(-2);
            let minutes = ('0' + currentDate.getMinutes()).slice(-2);
            let seconds = ('0' + currentDate.getSeconds()).slice(-2);

            // Return the formatted datetime string
            return year + '-' + month + '-' + day + 'T' + hours + ':' + minutes + ':' + seconds;
        }

        function calculateDelay(targetDatetime1, targetDatetime2) {
            // Convert targetDatetime1 and targetDatetime2 to Date objects
            let targetDate1 = new Date(targetDatetime1);
            let targetDate2 = new Date(targetDatetime2);

            if (targetDate1 > targetDate2) {
                alert('Entered date time is less than current time');
                return 0;
            }

            // Calculate the difference in milliseconds between targetDatetime1 and targetDatetime2
            let delayMilliseconds = Math.abs(targetDate2.getTime() - targetDate1.getTime());

            // Return the delay in milliseconds
            return delayMilliseconds;
        }

        function executeRotation() {
            let targetDatetime1 = getCurrentDateTime();
            let targetDatetime2 = document.getElementById("rotation_time").value;
            let delayMilliseconds = calculateDelay(targetDatetime1, targetDatetime2);
            if (isNaN(delayMilliseconds)) {
                alert('Please enter datetime');
                return;
            }
            const xhttp = new XMLHttpRequest();
            xhttp.onload = function() {
                alert("Request from ESP: " + this.responseText);
            }
            xhttp.open("GET", "http://192.168.147.145/index.html?delay=" + delayMilliseconds, true);
            xhttp.send();
            alert('Successfully scheduled');
            console.log("Delay in milliseconds:", delayMilliseconds);
        }
    </script>

</body>

</html>

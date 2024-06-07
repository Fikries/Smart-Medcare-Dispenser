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
    isset($_POST['medicine']) &&
    isset($_POST['consumption_date']) &&
    isset($_POST['consumption_time']) &&
    isset($_POST['remark'])
) {
    $medicineData = json_decode($_POST['medicine'], true);
    $tableHtml = '<table>';
    $tableHtml .= '<thead><tr><th>Medicine Name</th><th>Medicine Type</th><th>Date</th><th>Time</th></tr></thead>';
    $tableHtml .= '<tbody>';

    foreach ($medicineData as $medicine) {
        $tableHtml .= '<tr>';
        $tableHtml .= '<td>' . $medicine['name'] . '</td>';
        $tableHtml .= '<td>' . $medicine['type'] . '</td>';
        $tableHtml .= '<td>' . $medicine['date'] . '</td>';
        $tableHtml .= '<td>' . $medicine['time'] . '</td>';
        $tableHtml .= '</tr>';
    }

    $tableHtml .= '</tbody></table>';

    $conn = new mysqli("localhost", "root", "", "project");

    // Check if elder is registered
    $checkSql = $conn->prepare("SELECT * FROM patient WHERE email = ?");
    $checkSql->bind_param("s", $_POST['elder_email']);
    $checkSql->execute();
    $result = $checkSql->get_result();

    if ($result->num_rows == 0) {
    echo "<script>alert('Person is not registered yet'); window.location='register.php';</script>";
    exit;
    }

    // If registered, proceed with the insertion of medicine information

    //DELETE DATA YANG DAH ADA
    $delete = $conn->prepare("DELETE FROM medicine");
    $delete->execute();
    $sql = $conn->prepare("INSERT INTO medicine(id, eldername, email, medicine, consumptiondate, consumptiontime, remark) VALUES (NULL,?,?,?,?,?,?)");
    $sql->bind_param("ssssss", $_POST['elder_name'], $_POST['elder_email'], $_POST['medicine'], $_POST['consumption_date'], $_POST['consumption_time'], $_POST['remark']);
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
        $mail->Body = '<h3>' . $_POST['elder_name'] . '</h3>Thank you for your submission. Here are the details submitted:<br>' .
            'Email: ' . $_POST['elder_email'] . '<br>' .
            'Medicine details:<br>' . $tableHtml . '<br>' .
            'Consumption date: ' . $_POST['consumption_date'] . '<br>' .
            'Consumption time: ' . $_POST['consumption_time'] . '<br>' .
            'Remark: ' . $_POST['remark'];

        $mail->AltBody = $_POST['elder_name'] . ' Thank you for your submission. Here are the details submitted. ' .
            'Email: ' . $_POST['elder_email'] . '. Medicine details: ' . $_POST['medicine'] . '. Consumption date: ' . $_POST['consumption_date'] .
            '. Consumption time: ' . $_POST['consumption_time'] . '. Remark: ' . $_POST['remark'];


        $mail->send();
        // echo 'Message has been sent';
    } catch (Exception $e) {
        // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

    echo "<script>alert('Insert successfully, and notification is sent through email'); window.location='monitoring.php';</script>";
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
</head>

<body class="body">
<div class="navbar">
    <a href="register.php">Register</a>
    <a href="list.php">List</a>
    <a href="interface.php">Admin</a>
    <a href="monitoring.php">Monitor</a>
</div>
    <h1><span class="highlighted">PERSONAL MEDICINE DISPENSER WITH NOTIFICATION FOR ELDERLY CARE IN
            NURSING HOME USING ESP32 INTERGRATED WITH MYSQL DATABASE</h1></span>

    <label class="font" for="rotation_time">Enter Rotation Time:</label>

    <form id="timeForm">
        <div id="timeInputs">
            <input type="date" name="date[]" required>
            <input type="time" name="time[]" required>
        </div>
        <button type="button" class="button" id="addTime">Add More</button>
        <button type="button" class="button" id="saveTime">Set rotation time</button>
    </form>

    <form action="" method="post">
        <input type="hidden" name="medicine" id="medicine">
        <h2><span class="highlighted">Elder Information</h2></span>
        <label class="font" for="elder_name">Elder Name:</label>
        <input type="text" id="elder_name" name="elder_name" required>
        <br><br>

        <label class="font" for="elder_email">Elder email:</label>
        <input type="text" id="elder_email" name="elder_email" required>
        <br><br>

        <table id="medicineTable">
            <thead>
                <tr>
                    <th>Medicine Name</th>
                    <th>Medicine Type</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>update</th>
                </tr>
            </thead>
            <tbody>
                <!-- Rows will be dynamically added here -->
            </tbody>
        </table>
        <br>
        <button class="button" onclick="addRow()">Add Medicine</button><br><br>

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
            // Get the current date
            var currentDate = new Date(getCurrentDateTime());

            // Time string
            var timeString = targetDatetime2;

            // Split the time string into hours and minutes
            var timeParts = timeString.split(':');
            var hours = parseInt(timeParts[0], 10);
            var minutes = parseInt(timeParts[1], 10);

            // Set the time on the current date
            currentDate.setHours(hours);
            currentDate.setMinutes(minutes);
            currentDate.setSeconds(0);

            // Convert targetDatetime1 and targetDatetime2 to Date objects
            let targetDate1 = new Date(targetDatetime1);
            let targetDate2 = currentDate;

            if (targetDate1 > targetDate2) {
                alert('Entered date time is less than current time');
                return 0;
            }

            // Calculate the difference in milliseconds between targetDatetime1 and targetDatetime2
            let delayMilliseconds = Math.abs(targetDate2.getTime() - targetDate1.getTime());

            // Return the delay in milliseconds
            return delayMilliseconds;
        }

        function executeRotation(delayMilliseconds) {
            const xhttp = new XMLHttpRequest();
            xhttp.onload = function() {
                alert("Request from ESP: " + this.responseText);
            }
            xhttp.open("GET", "http://192.168.147.145/index.html?delay=" + delayMilliseconds, true);
            xhttp.send();
            alert('Successfully scheduled');
        }

        let medicineData = [];

        function addRow() {
            let medicineName = prompt("Enter Medicine Name:");
            let medicineType = prompt("Enter Medicine Type:");
            let date = prompt("Enter Date:");
            let time = prompt("Enter Time:");

            if (medicineName && medicineType && date && time) {
                let newRow = document.createElement('tr');
                newRow.innerHTML = `
                <td>${medicineName}</td>
                <td>${medicineType}</td>
                <td>${date}</td>
                <td>${time}</td>
                <td><button onclick="cancelRow(this)">Cancel</button></td>
            `;
                document.querySelector('#medicineTable tbody').appendChild(newRow);

                // Store data in JavaScript variable as JSON
                medicineData.push({
                    name: medicineName,
                    type: medicineType,
                    date: date,
                    time: time
                });

                document.getElementById("medicine").value = JSON.stringify(medicineData);
            } else {
                alert("Please enter Medicine Name, Medicine Type, Date, and Time.");
            }
        }
        function cancelRow(button) {
    // Get the row containing the cancel button
    let row = button.parentNode.parentNode;
    // Remove the row from the table
    row.parentNode.removeChild(row);
    // Remove the corresponding entry from the medicineData array
    let index = Array.from(row.parentNode.children).indexOf(row);
    medicineData.splice(index, 1);
    // Update the hidden input field
    document.getElementById("medicine").value = JSON.stringify(medicineData);
}

        //MULTIPLE ROTATION TIME FUNCTION
        document.getElementById("addTime").addEventListener("click", function() {
            var timeInputs = document.getElementById("timeInputs");
            var newInput = document.createElement("input");
            newInput.type = "time";
            newInput.name = "time[]";
            timeInputs.appendChild(newInput);
        });

        document.getElementById("saveTime").addEventListener("click", function() {
            var delayArray = "";
            var timeInputs = document.getElementsByName("time[]");
            for (var i = 0; i < timeInputs.length; i++) {
                if (timeInputs[i].value == '') {
                    alert('Please complete all time input');
                    delayArray = "";
                    return;
                }
                let delayMilliseconds = calculateDelay(getCurrentDateTime(), timeInputs[i].value);
                delayArray = delayArray + "," + delayMilliseconds;
            }
            console.log("Before rebuilt:");
            var sliceddelay = delayArray.slice(1);
            console.log(sliceddelay);
            // Step 1: Split the string into individual values
            var values = sliceddelay.split(",").map(Number);

            // Step 2: Calculate differences between consecutive values
            var differences = [];
            for (var i = 0; i < values.length; i++) {
                var diff = values[i] - (values[i - 1] || 0);
                differences.push(diff);
            }

            // Step 3: Rebuild the comma-separated list with recalculated values
            var rebuiltString = differences.join(",");
            console.log("After rebuilt:");
            console.log(rebuiltString);
            executeRotation(rebuiltString);
        });
    </script>

</body>

</html>
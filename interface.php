<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
// Start session
session_start();
$conn = new mysqli("localhost", "fikriainfyp", "mPIDZ.y73lNRg)Ew", "elderainfik");
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

    // Check if elder is registered
    $checkSql = $conn->prepare("SELECT * FROM patient WHERE email = ?");
    $checkSql->bind_param("s", $_POST['elder_email']);
    $checkSql->execute();
    $result = $checkSql->get_result();

    if ($result->num_rows == 0) {
        echo "<script>alert('Person is not registered yet, Please register.'); window.location='register.php';</script>";
        exit;
    }

    // If registered, proceed with the insertion of medicine information

    //DELETE DATA YANG DAH ADA
    $delete = $conn->prepare("DELETE FROM medicine");
    $delete->execute();
    $sql = $conn->prepare("INSERT INTO medicine(id, eldername, email, medicine, consumptiondate, consumptiontime, remark, caretakeremail) VALUES (NULL,?,?,?,?,?,?,?)");
    $sql->bind_param("ssssssi", $_POST['elder_name'], $_POST['elder_email'], $_POST['medicine'], $_POST['consumption_date'], $_POST['consumption_time'], $_POST['remark'], $caretakeremail);
    $caretakeremail = 0;
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

if (isset($_POST['daterotatenew'])) {
    $addmotorspinsql = $conn->prepare("INSERT INTO `motorspin`(`id`, `datetime`, `spinstate`) VALUES (NULL,?,?)");
    $addmotorspinsql->bind_param("ss", $datetimenew, $spinstate);
    $dateformatnew = new DateTime($_POST['daterotatenew']);
    $datetimenew = $dateformatnew->format("Y-m-d H:i:s");
    $spinstate = "false";
    $addmotorspinsql->execute();
    echo "<script>alert('Insert successfully'); window.location='interface.php';</script>";
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
            font-family: Arial;
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

        .split {
            height: 100%;
            width: 50%;
            position: fixed;
            z-index: 1;
            top: 0;
            overflow-x: hidden;
            padding-top: 20px;
        }

        .left {
            left: 0;
            background-image: url('asset/left.jpg');
        }

        .right {
            right: 0;
            background-image: url('asset/right.jpg');
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

        .table-container {
            width: 100%;
            margin: 20px auto;
            border-collapse: collapse;
        }

        .table-container th,
        .table-container td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .table-container th {
            background-color: #f2f2f2;
        }

        .table-container tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .table-container tr:hover {
            background-color: #ddd;
        }

        /* Style for pagination links */
        .pagination {
            display: flex;
            justify-content: center;
            padding: 10px 0;
        }

        .pagination a {
            color: black;
            padding: 8px 16px;
            text-decoration: none;
            border: 1px solid #ddd;
            margin: 0 2px;
        }

        .pagination a.active {
            background-color: #04AA6D;
            color: white;
            border: 1px solid #04AA6D;
        }

        .pagination a:hover:not(.active) {
            background-color: #ddd;
        }
    </style>
</head>

<body class="body">
    <div class="split left">
        <div class="navbar">
            <a href="register.php">Register</a>
            <a href="list.php">Record</a>
            <a href="interface.php">Admin</a>
            <a href="monitoring.php">Monitor</a>
        </div>
        <h1><span class="highlighted">PERSONAL MEDICINE DISPENSER WITH NOTIFICATION FOR ELDERLY CARE IN
                NURSING HOME USING ESP32 INTERGRATED WITH MYSQL DATABASE</h1></span>

        <label class="font" for="rotation_time">Setup rotation time:</label>

        <form id="timeForm" method="POST">
            <div id="timeInputs">
                <input type="datetime-local" name="daterotatenew" required>
            </div>
            <button type="submit" class="button" id="saveTime">Add rotation time</button>
        </form>
        <table id="medicineTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Rotation Datetime</th>
                    <th>Rotated status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $displaymotorspinsql = $conn->prepare("SELECT `id`, `datetime`, `spinstate` FROM `motorspin`");
                $displaymotorspinsql->execute();
                $displaymotorspinsql->store_result();
                $displaymotorspinsql->bind_result($idmotor, $datetimespinmotor, $statespinmotor);
                while ($displaymotorspinsql->fetch()) {
                ?>
                    <tr>
                        <td><?php echo $idmotor ?></td>
                        <td><?php echo $datetimespinmotor ?></td>
                        <td><?php echo $statespinmotor ?></td>
                        <td><a href="editmotor.php?id=<?php echo $idmotor ?>">Edit</a> <a onclick="return confirm('Are you sure want to delete this motor spin schedule?')" href="deletemotor.php?id=<?php echo $idmotor ?>">Delete</a></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <button type="button" class="button" onclick="MyWindow=window.open('spinworker.php','MyWindow','width=600,height=300'); return false;">Start spin worker</button>

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
            <textarea id="remark" name="remark" rows="4" cols="50" placeholder="Optional"></textarea>
            <br><br>

            <button class="button" type="submit">Submit Medicine Info</button>
        </form>
    </div>
    <div class="split right">
        <?php
        // Establish connection to the database
        $connect = mysqli_connect("localhost", "fikriainfyp", "mPIDZ.y73lNRg)Ew", "elderainfik");

        // Check the connection
        if (mysqli_connect_errno()) {
            die("Failed to connect to MySQL: " . mysqli_connect_error());
        }

        // Check if form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $searchName = mysqli_real_escape_string($connect, $_POST['name']);
        } else {
            $searchName = '';
        }

        // Pagination logic
        $records_per_page = 10; // Number of records to display per page
        $current_page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $offset = ($current_page - 1) * $records_per_page;

        // Query to count total records
        $count_query = "SELECT COUNT(*) FROM patient WHERE name LIKE '%$searchName%'";
        $count_result = mysqli_query($connect, $count_query);
        $total_records = mysqli_fetch_array($count_result)[0];
        $total_pages = ceil($total_records / $records_per_page);

        // Query to fetch filtered resident data with pagination
        $query = "SELECT id, name, email, illness FROM patient WHERE name LIKE '%$searchName%' ORDER BY id LIMIT $offset, $records_per_page";
        $result = mysqli_query($connect, $query);

        if ($result) {
            echo '<div class="table-container">';
            echo '<div align="center">';
            echo '<table border="2">
    <tr>
        <td><b>Resident ID</b></td>
        <td><b>Elder Name</b></td>
        <td><b>Email</b></td>
        <td><b>Illness</b></td>
    </tr>';

            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                echo '<tr>
        <td>' . $row['id'] . '</td>
        <td>' . $row['name'] . '</td>
        <td>' . $row['email'] . '</td>
        <td>' . $row['illness'] . '</td>
        </tr>';
            }

            echo '</table>';
            echo '</div>';
            echo '</div>';

            // Pagination links
            echo '<div class="pagination">';
            for ($page = 1; $page <= $total_pages; $page++) {
                echo '<a href="?page=' . $page . '"';
                if ($page == $current_page) {
                    echo ' class="active"';
                }
                echo '>' . $page . '</a> ';
            }
            echo '</div>';
        } else {
            echo "Error: " . mysqli_error($connect);
        }

        // Close the database connection
        mysqli_close($connect);
        ?>

        <!-- JavaScript for making table cells editable on double-click -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const cells = document.querySelectorAll('.editable-cell');

                cells.forEach(cell => {
                    cell.addEventListener('dblclick', () => {
                        const text = cell.innerText.trim();
                        let inputType = 'text';
                        if (cell.classList.contains('editable-date')) {
                            inputType = 'date';
                        } else if (cell.classList.contains('editable-time')) {
                            inputType = 'time';
                        }
                        cell.innerHTML = `<input type="${inputType}" class="editable-input" value="${text}">`;
                        const input = cell.querySelector('.editable-input');
                        input.focus();

                        input.addEventListener('blur', () => {
                            const newValue = input.value.trim();
                            const field = cell.getAttribute('data-field');
                            const id = cell.getAttribute('data-id');

                            // Update the database with the new value
                            updateCellValue(field, id, newValue);

                            cell.innerHTML = newValue;
                        });

                        input.addEventListener('keydown', (event) => {
                            if (event.key === 'Enter') {
                                input.blur();
                            }
                        });
                    });
                });

                // Function to update cell value in the database via AJAX
                function updateCellValue(field, id, value) {
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', 'update_cell.php', true);
                    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                    xhr.send(`field=${field}&id=${id}&value=${encodeURIComponent(value)}`);
                }
            });
        </script>
    </div>
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
            xhttp.open("GET", "http://192.168.94.145/index.html", true);
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

        function updateHiddenField() {
            var table = document.getElementById("medicineTable");
            var medicineArray = [];

            for (var i = 1, row; row = table.rows[i]; i++) {
                var medicineName = row.cells[0].innerHTML;
                var medicineType = row.cells[1].innerHTML;
                var consumptionDate = row.cells[2].innerHTML;
                var consumptionTime = row.cells[3].innerHTML;

                medicineArray.push({
                    name: medicineName,
                    type: medicineType,
                    date: consumptionDate,
                    time: consumptionTime
                });
            }

            document.getElementById("medicine").value = JSON.stringify(medicineArray);
        }

        // Function to handle table cell double-click for editing
        document.getElementById('medicineTable').addEventListener('dblclick', function(e) {
            if (e.target.tagName === 'TD') {
                let currentValue = e.target.innerHTML;
                let input = document.createElement('input');
                input.type = 'text';
                input.value = currentValue;
                input.onblur = function() {
                    e.target.innerHTML = input.value;
                    updateHiddenField(); // Update hidden field when editing is complete
                };
                e.target.innerHTML = '';
                e.target.appendChild(input);
                input.focus();
            }
        });
    </script>

</body>

</html>
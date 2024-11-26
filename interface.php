<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
// Start session
session_start();
$conn = new mysqli("localhost", "root", "", "elderainfik");

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
    isset($_POST['elder_name']) &&
    isset($_POST['elder_email']) &&
    isset($_POST['new_medicinename']) &&
    isset($_POST['new_medicinetype']) &&
    isset($_POST['new_datetime']) &&
    isset($_POST['remark'])
) {
    // $medicineData = json_decode($_POST['medicine'], true);
    $tableHtml = '<table>';
    $tableHtml .= '<thead><tr><th>Medicine Name</th><th>Medicine Type</th><th>DateTime</th></tr></thead>';
    $tableHtml .= '<tbody>';

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
    $newMedicinenames = $_POST['new_medicinename'];
    $newMedicinetypes = $_POST['new_medicinetype'];
    $newDatetimes = $_POST['new_datetime'];
    foreach ($newMedicinenames as $index => $name) {
        $medicinename = htmlspecialchars($name);
        $medicinetype = htmlspecialchars($newMedicinetypes[$index]);
        $datetime = htmlspecialchars($newDatetimes[$index]);
        $spinstate = "false";
        $remarks = $_POST['remark'];

        $tableHtml .= '<tr>';
        $tableHtml .= '<td>' . $medicinename . '</td>';
        $tableHtml .= '<td>' . $medicinetype . '</td>';
        $tableHtml .= '<td>' . $datetime . '</td>';
        $tableHtml .= '</tr>';

        // Insert new record into the database
        $insertmotorspinsql = $conn->prepare("INSERT INTO `motorspin` (`id`, `eldername`, `elderemail`, `medicinename`, `medicinetype`, `datetime`, `spinstate`, `remarks`) VALUES (NULL,?,?,?,?,?,?,?)");
        $insertmotorspinsql->bind_param("sssssss", $_POST['elder_name'], $_POST['elder_email'], $medicinename, $medicinetype, $datetime, $spinstate, $remarks);
        $insertmotorspinsql->execute();
    }

    $tableHtml .= '</tbody></table>';

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
        $mail->Password   = 'fdvhejgdxfddoapo';                               //SMTP password
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
            'Remark: ' . $_POST['remark'];

        $mail->AltBody = $_POST['elder_name'] . ' Thank you for your submission. Here are the details submitted. ' .
            'Email: ' . $_POST['elder_email'] . '. Medicine details: ' . $_POST['medicine'] . '. Remark: ' . $_POST['remark'];


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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <title>Medicine Dispenser</title>
    <style>
        /* Basic reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        /* General body styling */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }

      
         /* Navbar Styling */
    .navbar {
    display: flex;
    justify-content: space-between;
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
    margin-left: auto; /* Ensures the navigation links are aligned to the right */
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

.nav-links .active {
    font-weight: bold;
    text-decoration: underline;
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

        
        /* Main Heading */
        h1 {
            text-align: center;
            font-size: 1.8em;
            margin: 20px 0;
            color: #333;
            font-weight: 600;
        }

        h2 {
            text-align: center;
            font-size: 1.6em;
            color: #333;
            margin-bottom: 20px;
        }

        /* Form Styles */
        form {
            max-width: 770px; /* Limit the width for better readability */
            background-image: url('asset/nursing.png');
            background-repeat: no-repeat; /* Prevents multiple logos */
            background-position: center center; /* Centers the logo */
            background-size: contain; /* Adjusts the logo size to fit within the container */
            margin: 0 auto; /* Center the form */
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        label {
            font-weight: bold;
            margin-top: 10px;
            display: block; /* Display labels as block for better spacing */
        }

        input[type="text"],
        select,
        textarea {
            width: 100%; /* Full width for inputs */
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc; /* Light border */
            border-radius: 5px; /* Rounded corners */
            font-size: 1em; /* Font size */
            transition: border-color 0.3s; /* Smooth transition for border color */
        }

        input[type="text"]:focus,
        select:focus,
        textarea:focus {
            border-color: #007bff; /* Change border color on focus */
            outline: none; /* Remove default outline */
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 20px 0;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 1em;
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.3s; /* Smooth transition */
        }
        
        .btn1 {
            display: inline-block;
            padding: 10px 20px;
            margin: 20px 0;
            background-color: #000080;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 1em;
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.3s; /* Smooth transition */
        }
        
        .btn1:hover {
            background-color: #AEC6CF;
        }

        .btn:hover {
            background-color: #45a049;
        }

        /* Table styles */
        .table-container {
            width: 100%;
            margin-top: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0 auto; /* Center the table */
        }

        th,
        td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }

        th {
            background-color: #007bff;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        td {
            font-size: 1em;
        }
    </style>
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

    <h1>Medcare Admin Panel</h1>
    <h2>Elder Information</h2>

    <form id="timeForm" method="POST">
        <input type="hidden" name="medicine" id="medicine">
        <label for="elder_name">Elder Name:</label>
        <select id="elder_name" name="elder_name" required>
            <option value="">Select Elder</option>
            <?php
            // Fetch elder names from the database
            $elderSql = "SELECT name FROM patient";
            $elderResult = $conn->query($elderSql);
            
            if ($elderResult->num_rows > 0) {
                while ($row = $elderResult->fetch_assoc()) {
                    echo '<option value="' . htmlspecialchars($row['name']) . '">' . htmlspecialchars($row['name']) . '</option>';
                }
            } else {
                echo '<option value="">No elders available</option>';
            }
            ?>
        </select>

        <label for="elder_email">Elder Email:</label>
        <input type="text" id="elder_email" name="elder_email" required>

        <label for="remark">Remarks:</label>
        <textarea id="remark" name="remark" rows="4" placeholder="Optional"></textarea>

        <button type="button" class="btn1" id="saveTime" onclick="addNewRowMotor()">Add Rotation Time</button>
        
        <div class="table-container">
            <table id="motorTableBody">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Medicine Name</th>
                        <th>Medicine Type</th>
                        <th>Rotation Datetime</th>
                        <th>Rotated Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $displaymotorspinsql = $conn->prepare("SELECT `id`, `medicinename`, `medicinetype`, `datetime`, `spinstate` FROM `motorspin`");
                    $displaymotorspinsql->execute();
                    $displaymotorspinsql->store_result();
                    $displaymotorspinsql->bind_result($idmotor, $medicinename, $medicinetype, $datetimespinmotor, $statespinmotor);
                    while ($displaymotorspinsql->fetch()) {
                    ?>
                        <tr>
                            <td><?php echo $idmotor ?></td>
                            <td><?php echo $medicinename ?></td>
                            <td><?php echo $medicinetype ?></td>
                            <td><?php echo $datetimespinmotor ?></td>
                            <td><?php echo $statespinmotor ?></td>
                            <td><a href="editmotor.php?id=<?php echo $idmotor ?>">Edit</a> <a onclick="return confirm('Are you sure want to delete this motor spin schedule?')" href="deletemotor.php?id=<?php echo $idmotor ?>">Delete</a></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <br>
            <button type="submit" class="btn">Submit</button>
        </form>
        <br>
        <button type="button" class="btn1" onclick="MyWindow=window.open('spinworker.php','MyWindow','width=600,height=300'); return false;">Start spin worker</button>
        <button type="button" class="btn1" onclick="executeRotation()">Test spin</button><br><br>
    <script>
        function addNewRowMotor() {
            const tableBody = document.getElementById('motorTableBody');

            // Create a new row
            const newRow = document.createElement('tr');

            // Add editable cells for the new row
            newRow.innerHTML = `
        <td>New</td>
        <td><input type="text" name="new_medicinename[]" placeholder="Medicine Name" required></td>
        <td><input type="text" name="new_medicinetype[]" placeholder="Medicine Type" required></td>
        <td><input type="datetime-local" name="new_datetime[]" required></td>
        <td>false</td>
        <td><button type="button" onclick="removeRowMotor(this)">Remove</button></td>
    `;

            // Append the new row to the table
            tableBody.appendChild(newRow);
        }

        function removeRowMotor(button) {
            const row = button.closest('tr');
            row.remove();
        }

        function addConsumptionFields() {
            const container = document.getElementById('consumption-container');

            const div = document.createElement('div');
            div.className = 'consumption-group';

            div.innerHTML = `
                <label class="font" for="consumption_date[]">Consumption Date:</label>
                <input type="date" name="consumption_date[]" required>
                <br><br>
                <label class="font" for="consumption_time[]">Consumption Time:</label>
                <input type="time" name="consumption_time[]" required>
                <br><br>
            `;

            container.appendChild(div);
        }

        function removeLastConsumptionFields() {
            const container = document.getElementById('consumption-container');
            const consumptionGroups = container.getElementsByClassName('consumption-group');

            // Only remove if there is more than one set of fields
            if (consumptionGroups.length > 1) {
                container.removeChild(consumptionGroups[consumptionGroups.length - 1]);
            }
        }

        function executeRotation() {
            const xhttp = new XMLHttpRequest();
            xhttp.onload = function() {
                alert("Request from ESP: " + this.responseText);
            }
            xhttp.open("GET", "http://192.168.12.234/index.html", true);
            xhttp.send();
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
    <script>
    $(document).ready(function() {
        $('#elder_name').on('input', function() {
            var elderName = $(this).val();

            if (elderName !== '') {
                $.ajax({
                    url: 'fetch_elder_email.php',
                    method: 'POST',
                    data: { elder_name: elderName },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#elder_email').val(response.email);
                        } else {
                            $('#elder_email').val(''); // Clear email if no match found
                        }
                    }
                });
            } else {
                $('#elder_email').val(''); // Clear email if name is cleared
            }
        });
    });
</script>


</body>

</html>
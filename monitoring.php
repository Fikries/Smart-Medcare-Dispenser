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
        <a href="list.php">Record</a>
        <a href="interface.php">Admin</a>
        <a href="monitoring.php">Monitor</a>
    </div>

    <h1><span class="highlighted">PERSONAL MEDICINE DISPENSER WITH NOTIFICATION FOR ELDERLY CARE IN
            NURSING HOME USING ESP32 INTERGRATED WITH MYSQL DATABASE</h1></span>


    <?php
    $conn = new mysqli("localhost", "root", "", "project2");
    $elder = $conn->prepare("SELECT `id`, `eldername`, `email`, `medicine`, `consumptiondate`, `consumptiontime`, `caretakeremail`, `remark` FROM `medicine`");
    $elder->execute();
    $elder->store_result();
    if ($elder->num_rows < 1) {
        echo "No data";
        die();
    }
    $elder->bind_result($id, $eldername, $email, $medicine, $compdate, $comptime, $caretaker, $remark);
    $elder->fetch();

    $medicinestatus = $conn->prepare("SELECT `id`, `date`, `time` FROM `pushtime` WHERE `date` = ? AND `time` = ?");
    $medicinestatus->bind_param("ss", $eachdate, $eachtime);

    $medicinedata = json_decode($medicine, true);
    ?>
    <h3><?php echo $eldername ?></h3>
    <table>
        <tr>
            <th>Medicine</th>
            <th>Type</th>
            <th>Date</th>
            <th>Time</th>
            <th>Status</th>
        </tr>
        <?php
        for ($i = 0; $i < count($medicinedata); $i++) {
            $eachdate = $medicinedata[$i]["date"];
            $eachtime = $medicinedata[$i]["time"];
            $medicinestatus->execute();
            $medicinestatus->store_result();
            if ($medicinestatus->num_rows < 1) {
                $color = "red";
            } else {
                $color = "green";
            }
            $displayrow = true;
            if (isset($_GET['status'])) {
                if ($_GET['status'] == 'successful') {
                    if ($color == "green") {
                        $displayrow = true;
                    } else {
                        $displayrow = false;
                    }
                } else if ($_GET['status'] == 'unsuccessful') {
                    if ($color == "red") {
                        $displayrow = true;
                    } else {
                        $displayrow = false;
                    }
                }
            }

            if ($displayrow) {
        ?>
                <tr>
                    <td><?php echo $medicinedata[$i]["name"] ?></td>
                    <td><?php echo $medicinedata[$i]["type"] ?></td>
                    <td><?php echo $medicinedata[$i]["date"] ?></td>
                    <td><?php echo $medicinedata[$i]["time"] ?></td>
                    <td style="background-color: <?php echo $color ?>;"></td>
                </tr>
        <?php
            }
        }
        ?>
    </table>
    <script>
        function refresh() {
            // Your function code here
            window.location.reload();
        }
        setTimeout(refresh, 5000);
    </script>
</body>

</html>
<?php
<<<<<<< HEAD
$conn = new mysqli("localhost", "root", "", "elderainfik");
=======
$conn = new mysqli("localhost", "root", "", "project2");
>>>>>>> 18efd40711513c6e1deef57c6c172f4aa5f268e5
// Compare year, month, day, hour, and minute
$time = '2024-07-25 15:39:00';
$now = new DateTime();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spin worker for motorspin</title>
</head>

<body>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Rotation Datetime</th>
                <th>Rotated status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $displaymotorspinsql = $conn->prepare("SELECT `id`, `datetime`, `spinstate` FROM `motorspin` WHERE `spinstate` = 'false'");
            $displaymotorspinsql->execute();
            $displaymotorspinsql->store_result();
            $displaymotorspinsql->bind_result($idmotor, $datetimespinmotor, $statespinmotor);
            while ($displaymotorspinsql->fetch()) {
            ?>
                <tr>
                    <td><?php echo $idmotor ?></td>
                    <td><?php echo $datetimespinmotor ?></td>
                    <td><?php echo $statespinmotor ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <script>
        function executeRotation() {
            const xhttp = new XMLHttpRequest();
            xhttp.onload = function() {
                window.location.reload();
            }
<<<<<<< HEAD
            xhttp.open("GET", "http://192.168.12.234/index.html", true);
=======
            xhttp.open("GET", "http://192.168.70.145/index.html", true);
>>>>>>> 18efd40711513c6e1deef57c6c172f4aa5f268e5
            xhttp.send();
        }

        const interval = setInterval(function() {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var response = JSON.parse(this.responseText);
                    // console.log(response);
                    if(response["success"]){
                        // SPIN MOTOR HERE, AFTER THAT, RELOAD PAGE
                        executeRotation();
                    }
                }
            };
            xmlhttp.open("GET", "spinworkeraction.php", true);
            xmlhttp.send();
        }, 3000);
    </script>
</body>

</html>
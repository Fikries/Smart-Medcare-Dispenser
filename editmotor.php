<?php
$conn = new mysqli("localhost", "fikriainfyp", "mPIDZ.y73lNRg)Ew", "elderainfik");
if (isset($_POST['daterotatenew']) && isset($_POST['id'])) {
    $addmotorspinsql = $conn->prepare("UPDATE `motorspin` SET `datetime` = ? WHERE `id` = ?");
    $addmotorspinsql->bind_param("si", $datetimenew, $_POST['id']);
    $dateformatnew = new DateTime($_POST['daterotatenew']);
    $datetimenew = $dateformatnew->format("Y-m-d H:i:s");
    $spinstate = "false";
    $addmotorspinsql->execute();
    echo "<script>alert('Update successfully'); window.location='interface.php';</script>";
}

$deletemotorsql = $conn->prepare("SELECT `datetime`, `spinstate` FROM `motorspin` WHERE `id` = ?");
$deletemotorsql->bind_param("i", $_GET['id']);
$deletemotorsql->execute();
$deletemotorsql->store_result();
$deletemotorsql->bind_result($datetime, $spinstate);
$deletemotorsql->fetch();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit motor</title>
</head>

<body>
    <form id="timeForm" method="POST">
        <div id="timeInputs">
            Current motor spin time data: <?php echo $datetime ?><br>
            Enter new rotation time to edit
            <input type="hidden" name="id" value="<?php echo $_GET['id'] ?>" required>
            <input type="datetime-local" name="daterotatenew" required>
        </div>
        <button type="submit" class="button" id="saveTime">Save rotation time</button>
    </form>
</body>

</html>
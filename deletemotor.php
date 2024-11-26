<?php
<<<<<<< HEAD
$conn = new mysqli("localhost", "root", "", "elderainfik");
=======
$conn = new mysqli("localhost", "root", "", "project2");
>>>>>>> 18efd40711513c6e1deef57c6c172f4aa5f268e5
$deletemotorsql = $conn->prepare("DELETE FROM `motorspin` WHERE `id` = ?");
$deletemotorsql->bind_param("i", $_GET['id']);
$deletemotorsql->execute();
echo "<script>alert('Delete successfully'); window.location='interface.php';</script>";
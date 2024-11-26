<?php
$conn = new mysqli("localhost", "root", "", "elderainfik");
$deletemotorsql = $conn->prepare("DELETE FROM `motorspin` WHERE `id` = ?");
$deletemotorsql->bind_param("i", $_GET['id']);
$deletemotorsql->execute();
echo "<script>alert('Delete successfully'); window.location='interface.php';</script>";
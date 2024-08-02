<?php
$conn = new mysqli("localhost", "root", "", "project2");
$deletemotorsql = $conn->prepare("DELETE FROM `motorspin` WHERE `id` = ?");
$deletemotorsql->bind_param("i", $_GET['id']);
$deletemotorsql->execute();
echo "<script>alert('Delete successfully'); window.location='interface.php';</script>";
<?php
$conn = new mysqli("localhost", "fikriainfyp", "mPIDZ.y73lNRg)Ew", "elderainfik");
$deletemotorsql = $conn->prepare("DELETE FROM `motorspin` WHERE `id` = ?");
$deletemotorsql->bind_param("i", $_GET['id']);
$deletemotorsql->execute();
echo "<script>alert('Delete successfully'); window.location='interface.php';</script>";
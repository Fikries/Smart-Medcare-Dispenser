<?php
date_default_timezone_set("Asia/Kuala_Lumpur");
$date = date("Y-m-d H:i:s");
$conn = new mysqli("localhost", "root", "", "elderainfik");
$insertsql = $conn->prepare("INSERT INTO `pushtime`(`id`, `date`) VALUES (NULL,?)");
$insertsql->bind_param("s", $date);

$insertsql->execute();
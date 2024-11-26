<?php
date_default_timezone_set("Asia/Kuala_Lumpur");
<<<<<<< HEAD
$date = date("Y-m-d H:i:s");
$conn = new mysqli("localhost", "root", "", "elderainfik");
$insertsql = $conn->prepare("INSERT INTO `pushtime`(`id`, `date`) VALUES (NULL,?)");
$insertsql->bind_param("s", $date);
=======
$date = date('j/n/Y');
$time = date('ga');
$timeuppercase = strtoupper($time);

$conn = new mysqli("localhost", "root", "", "project2");
$insertsql = $conn->prepare("INSERT INTO `pushtime`(`id`, `date`, `time`) VALUES (NULL,?,?)");
$insertsql->bind_param("ss", $date, $timeuppercase);
>>>>>>> 18efd40711513c6e1deef57c6c172f4aa5f268e5

$insertsql->execute();
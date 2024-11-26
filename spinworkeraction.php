<?php
$conn = new mysqli("localhost", "root", "", "elderainfik");

//COMPARE IT, IF ANY IS SAME, SET IT TO ALREADY SPIN
// 1. CHECK IT FIRST
$checktimesqlstmt = $conn->prepare("SELECT `id`, `datetime`, `spinstate` FROM `motorspin` WHERE (DATE_FORMAT(`datetime`, '%Y-%m-%d %H:%i') = DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i')) AND `spinstate` = 'false'");
$checktimesqlstmt->execute();
$checktimesqlstmt->store_result();

// 2. IF FOUND, SET IT TO TRUE AND RETURN TRUE FOR SPINNING MOTORS
if($checktimesqlstmt->num_rows > 0) {
    // 3. FETCH IT FROM FOUNDED DATA
    $checktimesqlstmt->bind_result($id, $datetime, $spinstate);
    $checktimesqlstmt->fetch();

    // 4. SET IT TO SPINNED, AND RETURN TRUE IF SUCCESS
    $spinmotorsqlstmt = $conn->prepare("UPDATE `motorspin` SET `spinstate` = 'true' WHERE `id` = ?");
    $spinmotorsqlstmt->bind_param("i", $id);
    $resultspin = $spinmotorsqlstmt->execute();
    if($resultspin) {
        echo json_encode(array("success" => true));
    }else{
        echo json_encode(array("success" => false, "desc" => "Update spin failed"));
    }
} else {
    echo json_encode(array("success" => false, "desc" => "No same time found"));
}
<?php
$conn = new mysqli("localhost", "root", "", "elderainfik");

if (isset($_POST['elder_name'])) {
    $elder_name = $_POST['elder_name'];

    $query = $conn->prepare("SELECT email FROM patient WHERE name = ?");
    $query->bind_param("s", $elder_name);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode(['success' => true, 'email' => $row['email']]);
    } else {
        echo json_encode(['success' => false, 'email' => '']);
    }
} else {
    echo json_encode(['success' => false, 'email' => '']);
}
?>

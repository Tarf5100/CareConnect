<?php
session_start();
include 'db_connect.php';

if (isset($_POST['appointment_id']) && isset($_SESSION['user_id']) && $_SESSION['user_type'] === 'doctor') {
    $appointment_id = $_POST['appointment_id'];
    $doctorID = $_SESSION['user_id'];

  
    $stmt = $conn->prepare("UPDATE appointment SET status = 'Confirmed' WHERE ID = ? AND DoctorID = ?");
    $stmt->bind_param("ii", $appointment_id, $doctorID);
    if ($stmt->execute()) {
        echo 'true'; 
    } else {
        echo 'false'; 
    }
} else {
    echo 'false'; 
}
?>

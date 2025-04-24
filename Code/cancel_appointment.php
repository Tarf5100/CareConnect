<?php
session_start();
include 'db_connect.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_SESSION['user_id'])) {
    $appointmentId = $_POST['id'];
    $patientId = $_SESSION['user_id'];

    $stmt = $conn->prepare("DELETE FROM appointment WHERE ID = ? AND PatientID = ?");
    $stmt->bind_param("ii", $appointmentId, $patientId);

    if ($stmt->execute()) {
        echo json_encode(true);
    } else {
        echo json_encode(false);
    }
    exit;
}

echo json_encode(false);

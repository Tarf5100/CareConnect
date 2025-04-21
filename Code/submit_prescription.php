<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$appointmentId = $_POST['appointment_id'] ?? null;
$medicationIds = $_POST['medications'] ?? [];

if (!$appointmentId) {
    die("Missing appointment ID.");
}

include 'db_connect.php';

if (in_array("none", $medicationIds)) {
    $stmt = $conn->prepare("UPDATE appointment SET status = 'Done' WHERE ID = ?");
    $stmt->bind_param("i", $appointmentId);
    $stmt->execute();
} else {
    $update = $conn->prepare("UPDATE appointment SET status = 'Done' WHERE ID = ?");
    $update->bind_param("i", $appointmentId);
    $update->execute();

    $insert = $conn->prepare("INSERT INTO prescription (AppointmentID, MedicationID) VALUES (?, ?)");
    foreach ($medicationIds as $medId) {
        $insert->bind_param("ii", $appointmentId, $medId);
        $insert->execute();
    }
}
header("Location: doctor_homepage.php");
exit;
?>

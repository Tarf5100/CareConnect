<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'db_connect.php';

if (!isset($_POST['patient_id'], $_POST['doctor_id'], $_POST['date'], $_POST['time'], $_POST['reason'])) {
    die("Missing appointment details.");
}

$patientId = $_POST['patient_id'];
$doctorId  = $_POST['doctor_id'];
$date      = $_POST['date'];
$time      = $_POST['time'];
$reason    = $_POST['reason'];

$stmt = $conn->prepare("INSERT INTO appointment (PatientID, DoctorID, date, time, reason, status) VALUES (?, ?, ?, ?, ?, 'Pending')");
$stmt->bind_param("iisss", $patientId, $doctorId, $date, $time, $reason);

if ($stmt->execute()) {
    header("Location: patient_homepage.php?message=Appointment+booked+successfully");
    exit;
} else {
    echo "Error booking appointment: " . $stmt->error;
}

$conn->close();
?>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_connect.php';

if (isset($_GET['speciality_id'])) {
    $specialityId = $_GET['speciality_id'];

    $stmt = $conn->prepare("SELECT ID, FirstName, LastName FROM Doctor WHERE SpecialityID = ?");
    $stmt->bind_param("i", $specialityId);
    $stmt->execute();
    $result = $stmt->get_result();

    $doctors = [];
    while ($row = $result->fetch_assoc()) {
        $doctors[] = $row;
    }

    echo json_encode($doctors);
}
?>
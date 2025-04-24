<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $stmt = $conn->prepare("SELECT * FROM Patient WHERE emailAddress = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        header("Location: signup.php?error=Email already exists");
        exit();
    }

    $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO Patient (FirstName, LastName, Gender, DoB, emailAddress, Password) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $_POST['FirstName'], $_POST['LastName'], $_POST['Gender'], $_POST['DoB'], $email, $hashedPassword);

    if ($stmt->execute()) {
        $_SESSION['user_id'] = $stmt->insert_id;
        $_SESSION['user_type'] = 'patient';
        header("Location: patient_homepage.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

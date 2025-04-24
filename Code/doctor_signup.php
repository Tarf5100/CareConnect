<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $doctorID = $_POST['ID'];

    // Check if email already exists
    $stmt = $conn->prepare("SELECT * FROM Doctor WHERE emailAddress = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        header("Location: signup.php?error=Email already exists");
        exit();
    }

    // Check if ID already exists
    $stmt = $conn->prepare("SELECT * FROM Doctor WHERE ID = ?");
    $stmt->bind_param("i", $doctorID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        header("Location: signup.php?error=Doctor ID already exists");
        exit();
    }

    $uniqueFileName = uniqid() . '_' . basename($_FILES['image']['name']);
    $uploadPath = "images/" . $uniqueFileName;
    move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath);

    // Hash password
    $hashedPassword = password_hash($_POST['Password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO Doctor (ID, FirstName, LastName, UniqueFileName, SpecialityID, emailAddress, Password) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssiss", $doctorID, $_POST['FirstName'], $_POST['LastName'], $uniqueFileName, $_POST['speciality'], $email, $hashedPassword);

    if ($stmt->execute()) {
        $_SESSION['user_id'] = $doctorID; 
        $_SESSION['user_type'] = 'doctor';
        header("Location: doctor_homepage.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

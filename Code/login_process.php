<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $userType = $_POST['role']; 
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($userType) || empty($email) || empty($password)) {
        header("Location: login.html?error=Please fill in all fields");
        exit();
    }

    if ($userType === "doctor") {
        $query = "SELECT * FROM Doctor WHERE emailAddress = ?";
    } elseif ($userType === "patient") {
        $query = "SELECT * FROM Patient WHERE emailAddress = ?";
    } else {
        header("Location: login.html?error=Invalid user type");
        exit();
    }

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['Password'])) {
            $_SESSION['user_id'] = $user['ID'];
            $_SESSION['user_type'] = $userType;

            if ($userType === "doctor") {
                header("Location: doctor_homepage.php");
            } else {
                header("Location: patient_homepage.php");
            }
            exit();
        } else {
            header("Location: login.html?error=Incorrect password");
            exit();
        }
    } else {
        header("Location: login.html?error=User not found");
        exit();
    }
} else {
    header("Location: login.html");
    exit();
}
?>

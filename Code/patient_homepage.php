<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'patient') {
    header("Location: login.html");
    exit;
}

$patientID = $_SESSION['user_id'];

if (isset($_GET['cancel_id'])) {
    $cancelId = $_GET['cancel_id'];
    $stmt = $conn->prepare("DELETE FROM Appointment WHERE ID = ? AND PatientID = ?");
    $stmt->bind_param("ii", $cancelId, $patientID);
    $stmt->execute();
    header("Location: patient_homepage.php");
    exit;
}

$stmt = $conn->prepare("SELECT firstName, lastName, emailAddress FROM Patient WHERE ID = ?");
$stmt->bind_param("i", $patientID);
$stmt->execute();
$result = $stmt->get_result();
$patient = $result->fetch_assoc();

$apptStmt = $conn->prepare("
    SELECT a.ID, a.date, a.time, a.status, d.firstName AS docFirst, d.lastName AS docLast, d.UniqueFileName 
    FROM Appointment a 
    JOIN Doctor d ON a.DoctorID = d.ID 
    WHERE a.PatientID = ? 
    ORDER BY a.date, a.time
");
$apptStmt->bind_param("i", $patientID);
$apptStmt->execute();
$appointments = $apptStmt->get_result();
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Patient Dashboard</title>
    <style>
         body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            background-color: #f4f1ef;
            color: #333;
            box-sizing: border-box;
            overflow-x: hidden;
        }
         .site-header {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 40px;
            background-color: #ffffff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin: 0;
            box-sizing: border-box;
        }

        .logo-section {
            display: flex;
            align-items: center;
        }

        .logo {
            width: 100px;
            height: 100px;
            margin-right: 15px;
        }

        .site-name {
            font-size: 2em;
            color: #4f5a56;
            letter-spacing: 1px;
        }

        .logout-section {
            font-size: 1.2em;
        }

        .logout-section a {
            text-decoration: none;
            color: #006cff;
        }

        .logout-section a:hover {
            color: #004bbd;
        }

        .header {
            padding: 20px;
            text-align: center;
        }
	
        .container{
            margin:15px;
        }

        h1 {
            font-family: Roboto ;
            color: #878264;
        }

        h2 {
            color: #333;
            margin-top: 20px;
        }
		
        .patient-info {
            background-color: #919d9d;
            border-radius: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
            padding: 20px;
            margin: 20px 0;
            color: #fff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #ffffff;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
            color: #333;
        }

        tr:hover {
            background-color: #f9f9f9;
        }
        
        #email{
            color:#333 ;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        a {
            color: #007bff;
            text-decoration: none;
            transition: color 0.3s;
        }

        a:hover {
            color: #0056b3;
            text-decoration: underline;
        }

        img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
        }

        footer {
            text-align: center;
            padding: 2em;
            background-color: #cdc5b8;
            font-family: Roboto;
            font-size: 0.9em;
            color: #000;
            margin: 40px -20px -20px -20px;
            border-radius: 40px 40px 0 0;
        }

        .contact-info {
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 1em;
            gap: 2em;
        }

        .contact-msg img, .contact-phone img, .contact-email img {
            width: 25px;
            height: 25px;
            border: 2px solid #4f5a56;
            border-radius: 50%;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }

        .contact-info div {
            display: flex;
            align-items: center;
            gap: 0.5em;
        }

        .contact-info strong {
            font-size: 1.1em;
            color: #000;
            margin-bottom: 0.5em;
            display: inline-block;
        }

        hr {
            height: 1px;
            width: 600px;
            background-color: #919d9d;
            border: none;
        }

        #email {
            color: #333;
            text-decoration: none;
            transition: color 0.3s;
        }

        @media (max-width: 768px) {
            .site-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .logout-section {
                margin-top: 10px;
            }

            table {
                font-size: 0.9em;
            }

            .contact-info {
                flex-direction: column;
                gap: 1em;
            }
        }
    </style>
</head>
<body>
    <header class="site-header">
            <div class="logo-section">
                <img src="images/logo.png" alt="CareConnect Logo" class="logo">
                <h1 class="site-name">CareConnect</h1>
            </div>
            <div class="logout-section">
                <a href="logout.php">Log Out</a>
            </div>
    </header>
    <div class="container">
        <br><br>
        <h1>Welcome, <?= htmlspecialchars($patient['firstName']) ?>!</h1>

        <div class="patient-info">
            <p><strong>Name:</strong> <?= $patient['firstName'] . ' ' . $patient['lastName'] ?></p>
            <p><strong>Email:</strong> <?= $patient['emailAddress'] ?></p>
        </div>

        <h2>Your Appointments</h2>
        <p><a href="appoinment_booking.php">➕ Book an Appointment</a></p>

        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Doctor’s Name</th>
                    <th>Doctor’s Photo</th>
                    <th>Status</th>
                    <th>Cancel</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($appt = $appointments->fetch_assoc()): ?>
                    <tr>
                        <td><?= $appt['date'] ?></td>
                        <td><?= $appt['time'] ?></td>
                        <td>Dr. <?= $appt['docFirst'] . ' ' . $appt['docLast'] ?></td>
                        <td>
                            <img src="images/<?= htmlspecialchars($appt['UniqueFileName']) ?>" alt="Doctor Photo">
                        </td>
                        <td><?= $appt['status'] ?></td>
                        <td>
                            <a href="patient_homepage.php?cancel_id=<?= $appt['ID'] ?>" onclick="return confirm('Cancel this appointment?')">Cancel</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <footer>
            <div style="margin-top: 1em; font-size: 2em;">
                <strong>Contact Us</strong>
                <hr>
            </div>
            <div class="contact-info">
                <div class="contact-msg">
                    <img src="images/msg.png" alt="Message Icon"> +966-507443395
                </div>
                <div class="contact-phone">
                    <img src="images/tel.png" alt="Phone Icon"> 0114738434
                </div>
                <div class="contact-email">
                    <img src="images/email.jpeg" alt="Email Icon">
                    <a href="mailto:CareConnect@gmail.com" id="email">CareConnect@gmail.com</a>
                </div>
            </div>
            <div><small>&copy; 2025 CareConnect. All rights reserved.</small></div>
        </footer>
</body>
</html>

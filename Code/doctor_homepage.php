<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');

include 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'doctor') {
    header('Location: login.html');
    exit;
}

$doctorID = $_SESSION['user_id'];

if (isset($_GET['confirm_appointment_id'])) {
    $appointment_id = $_GET['confirm_appointment_id'];
    $stmt = $conn->prepare("UPDATE Appointment SET status = 'Confirmed' WHERE ID = ?");
    $stmt->bind_param("i", $appointment_id);
    $stmt->execute();
    header('Location: doctor_homepage.php');
    exit;
}

$stmt = $conn->prepare("
    SELECT d.firstName, d.lastName, d.emailAddress, d.UniqueFileName, s.speciality 
    FROM Doctor d 
    JOIN Speciality s ON d.SpecialityID = s.id 
    WHERE d.ID = ?
");
$stmt->bind_param("i", $doctorID);
$stmt->execute();
$result = $stmt->get_result();
$doctor = $result->fetch_assoc();

$doctor_first_name = $doctor['firstName'];
$doctor_last_name = $doctor['lastName'];
$doctor_email = $doctor['emailAddress'];
$doctor_speciality = $doctor['speciality'];
$doctor_photo = $doctor['UniqueFileName'];  

function calculateAge($dob) {
    $dobDate = new DateTime($dob);
    $now = new DateTime();
    return $dobDate->diff($now)->y;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Doctor Homepage</title>
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px 0;
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
		
        button {
            padding: 5px 10px;
            cursor: pointer;
            background-color: blue;
            color: white;
            border: none;
            border-radius: 5px;
        }
        button:hover {
            background-color: darkblue;
        }
        a:hover {
            color: darkblue;
        }
        img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-top: 10px;
        }
        .doctor-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #919d9d;
            border-radius: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
            color: #fff;
            padding: 20px 40px;
            margin: 30px 0;
        }

        .doctor-details p {
            margin: 8px 0;
            font-size: 18px;
        }

        .doctor-photo-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #doctor-photo {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }

        @keyframes borderGlow {
            0% {
                box-shadow: 0 0 5px #112b2c;
            }
            50% {
                box-shadow: 0 0 20px #112b2c;
            }
            100% {
                box-shadow: 0 0 5px #112b2c;
            }
        }
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.0);
            }
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
    <h1>Welcome Dr. <?php echo $doctor_first_name . ' ' . $doctor_last_name; ?></h1>
    <div class="doctor-info">
        <div class="doctor-details">
            <p><strong>Email:</strong> <?= htmlspecialchars($doctor_email) ?></p>
            <p><strong>Speciality:</strong> <?= htmlspecialchars($doctor_speciality) ?></p>
        </div>
        <div class="doctor-photo-wrapper">
            <img src="images/<?= htmlspecialchars($doctor_photo) ?>" alt="Doctor Photo" id="doctor-photo">
        </div>
    </div>


    <h2>Your Appointments (Pending/Confirmed)</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Date</th>
                <th>Time</th>
                <th>Patient's Name</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $apptQuery = "
                SELECT * FROM Appointment 
                WHERE DoctorID = ? AND status IN ('Pending', 'Confirmed') 
                ORDER BY date, time
            ";
            $stmt = $conn->prepare($apptQuery);
            $stmt->bind_param("i", $doctorID);
            $stmt->execute();
            $appointments = $stmt->get_result();

            while ($appointment = $appointments->fetch_assoc()) {
                $patientStmt = $conn->prepare("SELECT firstName, lastName, Gender, DoB FROM Patient WHERE id = ?");
                $patientStmt->bind_param("i", $appointment['PatientID']);
                $patientStmt->execute();
                $patientResult = $patientStmt->get_result();
                $patient = $patientResult->fetch_assoc();

                $age = calculateAge($patient['DoB']);
                $status = $appointment['status'];

                echo "<tr>
                        <td>{$appointment['date']}</td>
                        <td>{$appointment['time']}</td>
                        <td>{$patient['firstName']} {$patient['lastName']}</td>
                        <td>$age</td>
                        <td>{$patient['Gender']}</td>
                        <td>{$appointment['reason']}</td>
                        <td>$status</td>
                        <td>";

                if ($status === 'Pending') {
                    echo "<a href='doctor_homepage.php?confirm_appointment_id={$appointment['ID']}'>Confirm</a>";
                } elseif ($status === 'Confirmed') {
                    echo "<a href='prescribe_medication.php?appointment_id={$appointment['ID']}'>Prescribe</a>";
                }

                echo "</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <h2>Your Past Patients (Appointments Done)</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Name</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Medications</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "
                SELECT DISTINCT p.id, p.firstName, p.lastName, p.Gender, p.DoB
                FROM Patient p
                JOIN Appointment a ON p.id = a.PatientID
                WHERE a.DoctorID = ? AND a.status = 'Done'
                ORDER BY p.firstName ASC
            ";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $doctorID);
            $stmt->execute();
            $patients = $stmt->get_result();

            while ($patient = $patients->fetch_assoc()) {
                $age = calculateAge($patient['DoB']);

                 $medsQuery = "
                    SELECT m.MedicationName
                    FROM Prescription pr
                    JOIN Medication m ON pr.MedicationID = m.id
                    JOIN Appointment a ON pr.AppointmentID = a.id
                    WHERE a.DoctorID = ? AND a.PatientID = ?
                ";
                $medStmt = $conn->prepare($medsQuery);
                $medStmt->bind_param("ii", $doctorID, $patient['id']);
                $medStmt->execute();
                $medsResult = $medStmt->get_result();

                $medications = [];
                while ($med = $medsResult->fetch_assoc()) {
                    $medications[] = $med['MedicationName'];
                }

                echo "<tr>
                        <td>{$patient['firstName']} {$patient['lastName']}</td>
                        <td>$age</td>
                        <td>{$patient['Gender']}</td>
                        <td>";

                if (count($medications) > 0) {
                    echo implode(', ', $medications);
                } else {
                    echo "<em>No prescriptions needed</em>";
                }

                echo "</td></tr>";

            }
            ?>
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

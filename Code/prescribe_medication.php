<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'doctor') {
    header('Location: login.html');
    exit;
}

$appointmentId = $_GET['appointment_id'] ?? null;
if (!$appointmentId) {
    die("No appointment selected.");
}

include 'db_connect.php';

$stmt = $conn->prepare("SELECT PatientID FROM appointment WHERE ID = ?");
$stmt->bind_param("i", $appointmentId);
$stmt->execute();
$result = $stmt->get_result();
$appointment = $result->fetch_assoc();

if (!$appointment) {
    die("Appointment not found.");
}

$patientId = $appointment['PatientID'];

$stmt = $conn->prepare("SELECT FirstName, LastName, Gender, DoB FROM patient WHERE ID = ?");
$stmt->bind_param("i", $patientId);
$stmt->execute();
$result = $stmt->get_result();
$patient = $result->fetch_assoc();
if (!$patient) {
    die("Patient not found.");
}

$dob = new DateTime($patient['DoB']);
$now = new DateTime();
$age = $now->diff($dob)->y;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Prescribe Medication</title>
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Segoe UI', Arial, sans-serif;
    }

    body {
        background-color: #f0e9df;
        color: #5a4a28;
        line-height: 1.6;
        padding: 120px;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
    }
    .site-header {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 999;
        background-color: #ffffff;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 40px;
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
        font-family: Roboto
    }
        
    .container {
        max-width: 500px;
        margin: 50px auto;
        background-color: #ece3da;
        border: 2px solid #715f20;
        border-radius: 10px;
        padding: 30px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    h1 {
        color: #756425;
        font-size: 28px;
        font-weight: 600;
        text-align: center;
        margin-bottom: 25px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #5a4a28;
    }

    input[type="text"],
    input[type="number"] {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 16px;
    }

    .gender-options {
        display: flex;
        gap: 15px;
    }

    .gender-label {
        display: flex;
        align-items: center;
        cursor: pointer;
    }

    input[type="radio"] {
        margin-right: 5px;
        cursor: pointer;
    }

    .medications-title {
        color: #756425;
        font-size: 22px;
        margin-top: 25px;
        margin-bottom: 15px;
    }

    .med-item {
        display: block;
        margin-bottom: 12px;
        position: relative;
        padding-left: 5px;
        cursor: pointer;
        font-size: 16px;
    }

    input[type="checkbox"] {
        margin-right: 8px;
        cursor: pointer;
    }

    .submit-btn {
        background-color: #8a7844;
        color: white;
        border: none;
        border-radius: 4px;
        padding: 12px;
        font-size: 16px;
        cursor: pointer;
        width: 100%;
        margin-top: 20px;
        transition: background-color 0.2s ease;
    }

    .submit-btn:hover {
        background-color: #6a5d34;
    }
    </style>
</head>
<body>
    <header class="site-header">
        <div class="logo-section">
            <img src="images/logo.png" alt="CareConnect Logo" class="logo">
            <h1 class="site-name">CareConnect</h1>
        </div>
    </header>
    <div class="container">
        <h1>Prescribe Medication</h1>

        <form method="POST" action="submit_prescription.php">
            <input type="hidden" name="appointment_id" value="<?= $_GET['appointment_id'] ?>">

            <p><strong>Patient Name:</strong> <?= $patient['FirstName'] . ' ' . $patient['LastName'] ?></p>
            <p><strong>Age:</strong> <?= $age ?></p>
            <p><strong>Gender:</strong> <?= $patient['Gender'] ?></p>

            <h3>Select Medications:</h3>
            <label class="med-item">
                <input type="checkbox" name="medications[]" value="none" id="noneOption" onclick="handleNoneOption(this)">
                No prescriptions needed
            </label>

            <?php
            $meds = $conn->query("SELECT ID, MedicationName FROM medication");
            while ($row = $meds->fetch_assoc()):
            ?>
                <label class="med-item">
                    <input type="checkbox" name="medications[]" class="medOption" value="<?= $row['ID'] ?>" onclick="disableNoneOption()">
                    <?= htmlspecialchars($row['MedicationName']) ?>
                </label>
            <?php endwhile; ?>



            <br><button type="submit">Submit Prescription</button>
        </form>
    </div>
    <script>
    function handleNoneOption(checkbox) {
        const otherMeds = document.querySelectorAll('.medOption');
        if (checkbox.checked) {
            otherMeds.forEach(cb => {
                cb.checked = false;
                cb.disabled = true;
            });
        } else {
            otherMeds.forEach(cb => cb.disabled = false);
        }
    }

    function disableNoneOption() {
        const none = document.getElementById('noneOption');
        none.checked = false;
        none.disabled = true;

        // Re-enable after a short delay in case user unchecks all meds
        setTimeout(() => {
            const anyChecked = [...document.querySelectorAll('.medOption')].some(cb => cb.checked);
            if (!anyChecked) {
                none.disabled = false;
            }
        }, 100);
    }
    </script>

</body>
</html>

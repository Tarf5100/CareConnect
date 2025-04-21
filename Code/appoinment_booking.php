<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_connect.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'patient') {
    header('Location: login.html');
    exit;
}


$specialities = [];
$doctors = [];
$selectedSpeciality = '';

$specialityResult = $conn->query("SELECT ID, speciality FROM speciality");
while ($row = $specialityResult->fetch_assoc()) {
    $specialities[] = $row;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['speciality_id'])) {
    $selectedSpeciality = $_POST['speciality_id'];
    $stmt = $conn->prepare("SELECT ID, FirstName, LastName FROM doctor WHERE SpecialityID = ?");
    $stmt->bind_param("i", $selectedSpeciality);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $doctors[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Appointment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ece3da;
            margin: 0;
        }

        .site-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
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
            font-family:Roboto;
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
            text-align: center;
            color: #715f20;
        }

        label, select, input, textarea, button {
            display: block;
            width: 100%;
            margin-bottom: 15px;
            font-size: 16px;
        }

        button {
            background-color: #715f20;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background-color: #5a4a1a;
        }

        .no-doctors {
            color: red;
            text-align: center;
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
    <h1>Book an Appointment</h1>

    <?php if (empty($doctors)): ?>
        <!-- Form 1: Choose speciality -->
        <form method="POST">
            <label for="speciality_id">Select Speciality:</label>
            <select name="speciality_id" required>
                <option value="">--Select--</option>
                <?php foreach ($specialities as $s): ?>
                    <option value="<?= $s['ID'] ?>" <?= ($selectedSpeciality == $s['ID']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($s['speciality']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Next</button>
        </form>

        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($doctors)): ?>
            <p class="no-doctors">No doctors found for this speciality.</p>
        <?php endif; ?>

    <?php else: ?>
        <!-- Form 2: Select doctor and book -->
        <form action="appointment_processing.php" method="POST">
            <input type="hidden" name="patient_id" value="<?= $_SESSION['user_id'] ?>">
            <input type="hidden" name="speciality_id" value="<?= $selectedSpeciality ?>">

            <label for="doctor_id">Select Doctor:</label>
            <select name="doctor_id" required>
                <option value="">--Select--</option>
                <?php foreach ($doctors as $doc): ?>
                    <option value="<?= $doc['ID'] ?>">
                        <?= htmlspecialchars($doc['FirstName'] . ' ' . $doc['LastName']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="date">Date:</label>
            <input type="date" name="date" min="<?= date('Y-m-d') ?>" required>

            <label for="time">Time:</label>
            <input type="time" name="time" required>

            <label for="reason">Reason:</label>
            <textarea name="reason" required></textarea>

            <button type="submit">Book Appointment</button>
        </form>
    <?php endif; ?>
</div>

</body>
</html>

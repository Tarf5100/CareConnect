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
$specialityResult = $conn->query("SELECT ID, speciality FROM speciality");
while ($row = $specialityResult->fetch_assoc()) {
    $specialities[] = $row;
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
            font-family: Roboto;
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

    <form action="appointment_processing.php" method="POST" id="appointmentForm">
        <input type="hidden" name="patient_id" value="<?= $_SESSION['user_id'] ?>">

        <label for="speciality_id">Select Speciality:</label>
        <select name="speciality_id" id="specialitySelect" required>
            <option value="">--Select--</option>
            <?php foreach ($specialities as $s): ?>
                <option value="<?= $s['ID'] ?>"><?= htmlspecialchars($s['speciality']) ?></option>
            <?php endforeach; ?>
        </select>

        <label for="doctor_id">Select Doctor:</label>
        <select name="doctor_id" id="doctorSelect" required disabled>
            <option value="">--Select a speciality first--</option>
        </select>

        <label for="date">Date:</label>
        <input type="date" name="date" min="<?= date('Y-m-d') ?>" required>

        <label for="time">Time:</label>
        <input type="time" name="time" required>

        <label for="reason">Reason:</label>
        <textarea name="reason" required></textarea>

        <button type="submit">Book Appointment</button>
    </form>
</div>

<script>
document.getElementById('specialitySelect').addEventListener('change', function () {
    const specialityId = this.value;
    const doctorSelect = document.getElementById('doctorSelect');

    doctorSelect.innerHTML = '<option>Loading...</option>';
    doctorSelect.disabled = true;

    if (specialityId) {
        fetch('get_doctors.php?speciality_id=' + specialityId)
            .then(response => response.json())
            .then(data => {
                doctorSelect.innerHTML = '';
                if (data.length > 0) {
                    doctorSelect.disabled = false;
                    doctorSelect.innerHTML = '<option value="">--Select--</option>';
                    data.forEach(doctor => {
                        const option = document.createElement('option');
                        option.value = doctor.ID;
                        option.textContent = doctor.FirstName + ' ' + doctor.LastName;
                        doctorSelect.appendChild(option);
                    });
                } else {
                    doctorSelect.innerHTML = '<option>No doctors found</option>';
                }
            })
            .catch(error => {
                console.error('Error fetching doctors:', error);
                doctorSelect.innerHTML = '<option>Error loading doctors</option>';
            });
    }
});
</script>

</body>
</html>
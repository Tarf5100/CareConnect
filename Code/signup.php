<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 20px;
            background-color: #f4f1ef;
            color: #333;
        }
        .site-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            margin-bottom: 20px;
            margin-top:-20px;
            margin-left:-20px;
            margin-right:-20px;            
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
        }
        
        h1 {
            font-family: Roboto ;
            color: #878264;
            text-align: center;		
        }
        h2{
            font-family: Roboto ;
            font-weight:lighter;
            font-size:150%;
            text-align: center;
        }
        #signup{
            padding:20px;
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
        .hidden {
            display: none;
        }

        .button {
            margin: 10px 0;
            display: flex;
            justify-content: center;
        }

        .button input,
        .button button {
            padding: 10px 20px;
            background-color: #878264;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        form input,
        form select {
            display: block;
            width: 100%;
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box; 
        }

        form button {
            background-color: #878264;
            color: white;
            width:80px;
            cursor: pointer;
            margin-bottom: 10px;
            padding: 10px;
            border: none;
            border-radius: 5px;			
        }
		
        footer {
            text-align: center;
            padding: 2em;
            background-color: #cdc5b8;
            font-family:Roboto; 
            font-size: 0.9em;
            color: #000;
            margin-bottom: -40px;
            margin-top:10px;
            margin-left:-20px;
            margin-right:-20px; 
            border-radius: 40px 40px 0px 0px;			
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
        hr{
            height: 1px;  
            width: 600px;
            background-color: #919d9d;  
            border: none;		
        }
		
    </style>
</head>

<body>
    <div class="container">
	<header class="site-header">
        <div class="logo-section">
            <img src="images/logo.png" alt="CareConnect Logo" class="logo">
            <h1 class="site-name">CareConnect</h1>
        </div>
    </header>
        <h1 id="signup">Sign-Up</h1>
        <h2>Select your role</h2>
        <div class="button">
            <button onclick="showForm('patientForm')">Patient</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <button onclick="showForm('doctorForm')">Doctor</button>
        </div>

        <!-- Patient Form -->
      <form id="patientForm" class="hidden" method="POST" action="patient_signup.php">


            <h3>Patient Form</h3>
         <input type="text" id="patientFirstName" name="firstName" placeholder="First Name" required>
        <input type="text" id="patientLastName" name="lastName" placeholder="Last Name" required>
            <select id="patientGender" name="gender" required>
                <option value="" disabled selected>Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
            <input type="date" id="patientDOB" name="dob" required>
            <input type="email" id="patientEmail" name="email" placeholder="Email" required>
            <input type="password" id="patientPassword" name="password" placeholder="Password" required>
			<br>
            <button type="submit">Sign Up</button>
        </form>

        <!-- Doctor Form -->
       <form id="doctorForm" class="hidden" method="POST" action="doctor_signup.php" enctype="multipart/form-data">

            <h3>Doctor Form</h3>
              <input type="text" id="doctorFirstName" name="firstName" placeholder="First Name" required>
                <input type="text" id="doctorLastName" name="lastName" placeholder="Last Name" required>
                <input type="text" id="doctorID" name="id" placeholder="ID" required>
            <h5>Photo</h5>
             <input type="file" id="doctorPhoto" name="image" >
            <select id="doctorSpeciality" name="speciality" required>
                <option value="" disabled selected>Select Speciality</option>
                <option value="1">Cardiology</option>
                <option value="2">Neurology</option>
                <option value="3">Pediatrics</option>
            </select>
            <input type="email" id="doctorEmail" name="email" placeholder="Email" required>
            <input type="password" id="doctorPassword" name="password" placeholder="Password" required>
			<br>
            <button type="submit">Sign Up</button>
        </form>
		<br>
		<br>
		<footer>
			<div style="margin-top: 1em; font-size: 2em;">
				<strong>Contact Us</strong><hr>
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
		</footer>

    <div><small>&copy; 2025 CareConnect. All rights reserved.</small></div>
    </div>

    <script>
        function showForm(formId) {
            document.getElementById('patientForm').classList.add('hidden');
            document.getElementById('doctorForm').classList.add('hidden');
            document.getElementById(formId).classList.remove('hidden');
        }
    </script>
</body>

</html>

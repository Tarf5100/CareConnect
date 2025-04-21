<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CareConnect</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: Roboto ;
            display: flex;
            justify-content: center;
            align-items: center;
            background-image: url('images/background.png'); 
            background-size: cover;
        }

        .container {
            width: 400px; 
            height: 400px; 
            background: rgba(59, 66, 75, 0.9); 
            border-radius: 50%; 
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            position: relative;
        }

        .logo {
            width: 200px; 
            margin-bottom: 20px;
        }

        h1 {
            font-size: 30px; 
            margin: 0;
            color: #ece3da;
            text-align: center;
        }

        .login-button {
            display: inline-block;
            padding: 15px 30px;
            font-size: 18px;
            color: #ece3da;
            background-color: #878264;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 20px;
        }
        p{
            display: block;
            margin-top: 15px;
            font-size: 14px;
            color:#ece3da;
        }
        .signup-link {
            display: inline;
            margin-top: 15px;
            font-size: 14px;
            color:#878264;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="images/logo.png" alt="CareConnect Logo" class="logo"> 
        <h1>CareConnect</h1>
        <a href="login.html" class="login-button">Log In</a>
        <p> New User?<a href="signup.php" class="signup-link"> Sign Up</a></p>
    </div>
</body>
</html>
<?php
include 'conn_assignment.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(120deg,#d8f3dc,#b7e4c7,#95d5b2); 
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            width: 850px;
            background-color: white;
            padding: 40px 60px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.4);
        }

        h1 {
            text-align: center;
            font-style: italic;
            font-weight: 800;
            margin-bottom: 40px;
            font-size: 28px;
            color: #1b4332;
        }

        .form-content {
            display: flex;
            justify-content: space-between;
            gap: 30px;
        }

        .left-side, .right-side {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        input {
            width: 100%;
            padding: 15px;
            margin-bottom: 25px;
            border: none;
            background-color: #e9f5ec;
            color: #1b4332;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
            font-style: italic;
            font-weight: bold;
        }

        .photo-box {
            width: 100%;
            height: 135px;
            background-color: #f6fff8;
            border: 2px dashed #6fbf73;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            border-radius: 8px;
            margin-bottom: 25px;
            box-sizing: border-box;
        }

        .photo-box img {
            width: 50px;
            margin-bottom: 10px;
            opacity: 0.6;
        }

        .photo-label {
            font-weight: bold;
            color: #555;
            font-size: 13px;
        }

        .button-container {
            text-align: center;
            margin-top: 20px;
        }

        button {
            width: 400px;
            padding: 16px;
            background-color: #636060;
            color: white;
            border: none;
            border-radius: 50px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background-color: #444;
        }

        .login-text{
    text-align:center;
    margin-top:10px;
    color:#6f6f6f;
    font-style:italic;
    font-size:14px;
}

.login-link{
    font-weight:800;
    color:black;
    text-decoration:none;
}
        @media (max-width: 768px){

    body{
        align-items: flex-start;
        padding: 30px 0;
    }

    .container{
        width: 95%;
        padding: 25px 20px;
    }

    .form-content{
        flex-direction: column;
        gap: 10px;
    }

    .left-side,
    .right-side{
        width: 100%;
    }

    input{
        margin-bottom: 18px;
        font-size: 14px;
        padding: 12px;
    }

    .photo-box{
        width: 100%;
        height: 120px;
    }

    h1{
        font-size: 22px;
        margin-bottom: 25px;
    }

    button{
        width: 100%;
        font-size: 16px;
        padding: 14px;
    }
}
    </style>
    
</head>
<body>

<div class="container">
    <h1>Registration Form</h1>

    <form action="register_process.php" method="POST" enctype="multipart/form-data">
        <div class="form-content">
            <div class="left-side">
                <input type="text" name="full_name" placeholder="Full Name" required>
                <input type="email" name="email" placeholder="E-mail" required>
                <input type="date" name="dob" required title="Date of Birth">
                <input type="password" name="user_password" placeholder="Password" required>
            </div>

            <div class="right-side">
                <div class="photo-box" onclick="document.getElementById('photoInput').click();">
                    <img src="https://cdn-icons-png.flaticon.com/512/3342/3342137.png" alt="Upload Icon">
                    <span class="photo-label">Click to Upload Photo</span>
                    <input type="file" id="photoInput" name="photo" style="display:none;" accept="image/*">
                </div>
                
                <input type="text" name="gender" placeholder="Gender" required>
                <input type="text" name="address" placeholder="Address" required>
            </div>
        </div>

        <input type="hidden" name="role" value="student">

        <div class="button-container">
            <button type="submit">Create Account</button>
            <p class="login-text">
             Already have account?
    <a href="loginstudent.php" class="login-link">Login here</a>
</p>
        </div>
    </form>
</div>

</body>
</html>
<?php
include 'conn_assignment.php';
session_start();

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email']);
    $password = trim($_POST['user_password']);

    $adminSQL = "SELECT * FROM admin 
                WHERE email='$email' 
                AND password='$password'";
    $adminResult = mysqli_query($conn, $adminSQL);

    if (mysqli_num_rows($adminResult) >= 1) {

        $admin = mysqli_fetch_assoc($adminResult);

        $_SESSION['admin_id'] = $admin['admin_id'];
        $_SESSION['admin_name'] = $admin['name'];

        header("Location: admin_home.php"); //改php
        exit;
    }

    $vendorSQL = "SELECT * FROM vendor 
                  WHERE email='$email' 
                  AND password='$password'";
    $vendorResult = mysqli_query($conn, $vendorSQL);

    if (mysqli_num_rows($vendorResult) >= 1) {

        $vendor = mysqli_fetch_assoc($vendorResult);

        $_SESSION['vendor_id'] = $vendor['vendor_id'];
        $_SESSION['vendor_name'] = $vendor['name'];

        header("Location: AIndex.php"); //改php
        exit;
    }

    $sql = "SELECT * FROM student 
            WHERE email='$email' 
            AND password='$password'";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) >= 1) {

        $row = mysqli_fetch_assoc($result);

        $_SESSION['student_id'] = $row['student_id'];
        $_SESSION['full_name'] = $row['name'];

        header("Location: Home.php");
        exit;

    } else {
        $error = "Invalid email or password. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Login Page</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">


<style>
:root{
  --page-grad: linear-gradient(120deg,#d1f2eb,#eaf2f8,#f2d7d5);
  --green: #2e7d32;
  --light-green: #eef7f0;
  --card-shadow: 0 18px 40px rgba(0,0,0,0.15);
}

*{
  box-sizing:border-box;
}

body{
  margin:0;
  min-height:100vh;
  display:flex;
  align-items:center;
  justify-content:center;
  font-family: Arial, Helvetica, sans-serif;
  background: var(--page-grad);
}

.box{
  width:440px;
  background:#ffffff;
  padding:45px 50px;
  border-radius:16px;
  box-shadow: var(--card-shadow);
}

.box h2{
  text-align:center;
  margin-bottom:35px;
  font-style:italic;
  font-weight:900;
  color:#1b5e20;
}

label{
  font-weight:700;
  display:block;
  margin-bottom:6px;
  font-size:15px;
  color:#333;
}

input{
  width:100%;
  padding:14px 16px;
  border-radius:8px;
  border:none;
  background: var(--light-green);
  font-size:15px;
  outline:none;
  margin-bottom:22px;
}

input::placeholder{
  color:#888;
}

button{
  width:100%;
  padding:14px;
  border:none;
  border-radius:999px;
  background:#6b6b6b;
  color:white;
  font-size:16px;
  font-weight:800;
  cursor:pointer;
  transition: background 0.25s ease, transform 0.2s ease;
}

button:hover{
  background:#4f4f4f;
  transform: translateY(-2px);
}

.text{
  text-align:center;
  margin-top:28px;
  font-size:14px;
  color:#666;
  font-style:italic;
}

.text a{
  font-weight:800;
  color:#1b5e20;
  text-decoration:none;
}

.text a:hover{
  text-decoration:underline;
}

.error{
  background:#fdecea;
  color:#b71c1c;
  padding:10px 14px;
  border-radius:8px;
  margin-bottom:18px;
  font-size:14px;
  text-align:center;
}

@media (max-width: 768px){
  .box{
    width:92%;
    padding:30px 22px;
  }
}


@media (max-width: 768px){

    body{
        min-height: 100vh;
        height: auto;
        padding: 0;
        display:flex;
        justify-content:center;
        align-items:center;
    }

    .box{
        width: 92%;
        padding: 30px 22px;
        border-radius: 10px;
    }

    h2{
        font-size: 20px;
        margin-bottom: 25px;
    }

    label{
        font-size: 16px;
    }

    input{
        font-size: 14px;
        padding: 10px 5px;
        margin-bottom: 18px;
    }

    button{
        width: 100%;
        font-size: 16px;
        padding: 12px;
    }

    .text{
        margin-top: 18px;
        font-size: 13px;
    }
}
</style>
</head>

<body>

<div class="box">
    <h2>Login</h2>

    <?php if($error != "") echo "<p class='error'>$error</p>"; ?>

    <form method="POST">
        <label>Email</label>
        <input type="email" name="email" placeholder="Enter Email" required>

        <label>Password</label>
        <input type="password" name="user_password" placeholder="Enter Password" required>

        <button type="submit">Login</button>
    </form>

    <p class="text">Dont have account? 
        <a href="registerform.php">Register here</a>
    </p>
</div>

</body>
</html>

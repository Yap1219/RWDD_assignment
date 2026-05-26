<?php
include "admin_db.php";

$role = $_GET['role'] ?? 'student';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $role = $_POST['role'];

    if ($role == "student") {

        $student_id = $_POST['student_id'];
        $name = $_POST['name'];
        $dob = $_POST['date_of_birth'];
        $gender = $_POST['gender'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $address = $_POST['address'];

        $photoName = "";

        if (!empty($_FILES["photo"]["name"])) {
            $photoName = time() . "_" . basename($_FILES["photo"]["name"]);
            $target = "uploads/" . $photoName;
            move_uploaded_file($_FILES["photo"]["tmp_name"], $target);
        }

        $query = "INSERT INTO student(student_id,name,date_of_birth,gender,email,password,photo,address,total_points) 
        VALUES('$student_id','$name','$dob','$gender','$email','$password','$photoName','$address',0)";
        $conn->query($query);
    }

    elseif ($role == "admin") {

        $id = $_POST['admin_id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        $query = "INSERT INTO admin(admin_id,name,email,password)
                  VALUES('$id','$name','$email','$password')";
        $conn->query($query);
    }

    elseif ($role == "vendor") {

        $id = $_POST['vendor_id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        $query = "INSERT INTO vendor(vendor_id,name,email,password)
                  VALUES('$id','$name','$email','$password')";
        $conn->query($query);
    }

    header("Location: admin_manage_user.php?role=$role");
    exit;
}
?>

<html>
    <head>
        <title>Add User</title>

        <style>
            body{
                background:#e8fff5;
                font-family:Arial;
            }

            .container{
                width:60%;
                margin:auto;
                margin-top:40px;
                padding:25px;
                border-radius:12px;
                background:#222;
                color:white;
            }

            input,select,textarea{
                width:100%;
                padding:10px;
                margin-top:6px;
                margin-bottom:15px;
                border-radius:6px;
                border:none;
            }

            .btn{
                padding:10px 22px;
                border-radius:6px;
                text-decoration:none;
                color:white;
                border:none;
                cursor:pointer;
            }

            .save{ background:#27ae60; }
            .cancel{ background:#c0392b; }
        </style>
    </head>

    <body>

        <div class="container">

        <h2>Add <?= ucfirst($role) ?></h2>

            <form method="POST" enctype="multipart/form-data">

                <input type="hidden" name="role" value="<?= $role ?>">

                <?php if($role=="student"){ ?>

                    ID
                    <input type="text" name="student_id" required>

                    Name
                    <input type="text" name="name" required>

                    Email
                    <input type="email" name="email" required>

                    Password
                    <input type="password" name="password" required>

                    Date of Birth
                    <input type="date" name="date_of_birth" required>

                    Gender
                    <select name="gender" required>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    </select>

                    Address
                    <textarea name="address"></textarea>

                    Photo
                    <input type="file" name="photo">

                <?php } ?>



                <?php if($role=="admin"){ ?>

                    Admin ID
                    <input type="text" name="admin_id" required>

                    Name
                    <input type="text" name="name" required>

                    Email
                    <input type="email" name="email" required>

                    Password
                    <input type="password" name="password" required>

                <?php } ?>



                <?php if($role=="vendor"){ ?>

                    Vendor ID
                    <input type="text" name="vendor_id" required>

                    Name
                    <input type="text" name="name" required>

                    Email
                    <input type="email" name="email" required>

                    Password
                    <input type="password" name="password" required>

                <?php } ?>


                <button class="btn save" type="submit">Save</button>
                <a class="btn cancel" href="admin_manage_user.php?role=<?= $role ?>">Cancel</a>

            </form>

        </div>

    </body>
</html>

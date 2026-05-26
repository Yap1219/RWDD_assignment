<?php
    include "admin_db.php";

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $role = isset($_POST['role']) ? $_POST['role'] : '';
        $id   = isset($_POST['id']) ? $_POST['id'] : '';

        if ($role == "" || $id == "") {
            die("Missing role or id");
        }

        $name     = $_POST['name'] ?? '';
        $email    = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if ($role == "student") {
            $dob     = $_POST['date_of_birth'] ?? '';
            $gender  = $_POST['gender'] ?? '';
            $address = $_POST['address'] ?? '';
            $points  = $_POST['total_points'] ?? 0;

            $photo = $_POST['old_photo'];
            if (!empty($_FILES['photo']['name'])) {
                $photo = "uploads/" . basename($_FILES['photo']['name']);
                move_uploaded_file($_FILES['photo']['tmp_name'], $photo);
            }

            $sql = "UPDATE student SET 
                        name='$name',
                        email='$email',
                        password='$password',
                        date_of_birth='$dob',
                        gender='$gender',
                        address='$address',
                        total_points='$points',
                        photo='$photo'
                    WHERE student_id='$id'";
        }

        elseif ($role == "admin") {
            $sql = "UPDATE admin SET 
                        name='$name',
                        email='$email',
                        password='$password'
                    WHERE admin_id='$id'";
        }

        elseif ($role == "vendor") {
            $sql = "UPDATE vendor SET 
                        name='$name',
                        email='$email',
                        password='$password'
                    WHERE vendor_id='$id'";
        }

        else {
            die("Invalid Role");
        }

        if ($conn->query($sql)) {
            header("Location: admin_manage_user.php?role=$role");
            exit();
        } else {
            echo "Update Failed: " . $conn->error;
        }

    } else {
        die("Invalid Request");
    }
?>

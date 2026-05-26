<?php
include 'conn_assignment.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $getID = mysqli_query($conn, "SELECT MAX(student_id) AS last_id FROM student");
$row = mysqli_fetch_assoc($getID);

if ($row['last_id']) {
    $num = (int) substr($row['last_id'], 1); 
    $num++;
    $student_id = "S" . sprintf("%03d", $num);
} else {
    $student_id = "S001";
}

    $name = $_POST['full_name'];
    $email = $_POST['email'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $password = $_POST['user_password'];

 

    $photoName = $_FILES['photo']['name'];
$photoTmpName = $_FILES['photo']['tmp_name'];

if (!is_dir('uploads')) {
    mkdir('uploads', 0777, true);
}

if(!empty($photoName)) {
    $newPhotoName = uniqid() . "_" . $photoName;
    $uploadFolder = "uploads/" . $newPhotoName;
    move_uploaded_file($photoTmpName, $uploadFolder);
} else {
    $uploadFolder = "uploads/";
}

    move_uploaded_file($photoTmpName, $uploadFolder);

    $sql = "INSERT INTO student 
        (student_id, name, date_of_birth, gender, email, password, address, photo)
        VALUES 
        ('$student_id', '$name', '$dob', '$gender', '$email', '$password', '$address', '$uploadFolder')";

    if (mysqli_query($conn, $sql)) {
        header("Location: loginstudent.php");
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

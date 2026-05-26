<?php
include "admin_db.php";
session_start();

if (!isset($_GET['id'])) {
    die("Invalid request");
}

$id = $_GET['id'];

$conn->begin_transaction();

try {

    $conn->query("DELETE FROM point_history WHERE student_id = '$id'");
    $conn->query("DELETE FROM submission WHERE student_id = '$id'");
    $conn->query("DELETE FROM redemption WHERE student_id = '$id'");

    $conn->query("DELETE FROM student WHERE student_id = '$id'");
    $conn->query("DELETE FROM admin WHERE admin_id = '$id'");
    $conn->query("DELETE FROM vendor WHERE vendor_id = '$id'");

    $conn->commit();

} catch (Exception $e) {
    $conn->rollback();
    die("Delete failed");
}

header("Location: admin_manage_user.php");
exit;

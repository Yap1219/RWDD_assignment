<?php
include "admin_db.php";
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

if (!isset($_GET['id'])) {
    die("Invalid request");
}

$id = $_GET['id'];

$conn->query("
UPDATE submission
SET status = 'approved'
WHERE submission_id = '$id'
AND status = 'submitted'
");

header("Location: admin_submission_list.php");
exit;

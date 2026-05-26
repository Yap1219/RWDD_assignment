<?php
include "admin_db.php";

$id = $_GET['id'];

$conn->query("
UPDATE submission
SET status = 'Rejected'
WHERE submission_id = '$id'
AND status = 'Submitted'
");

header("Location: admin_reward_submission_list.php");
exit;
?>



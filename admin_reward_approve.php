<?php
include "admin_db.php";

if(!isset($_GET['id'])){
    die("Invalid request");
}

$id = $_GET['id'];

$data = $conn->query("
SELECT sub.*, e.reward_point
FROM submission sub
JOIN event e ON sub.event_id = e.event_id
WHERE sub.submission_id = '$id'
AND sub.status = 'submitted'
")->fetch_assoc();

if(!$data){
    die("Submission not found or already processed");
}

$conn->query("
UPDATE submission
SET status = 'approved'
WHERE submission_id = '$id'
");

$conn->query("
UPDATE student
SET total_points = total_points + {$data['reward_point']}
WHERE student_id = '{$data['student_id']}'
");

$conn->query("
INSERT INTO point_history
(history_id, student_id, change_amount, date, reason)
VALUES
(UUID(), '{$data['student_id']}', {$data['reward_point']}, CURDATE(), 'Event Submission Approved')
");

header("Location: admin_submission_list.php");
exit;
?>

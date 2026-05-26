<?php
include("conn.php");

session_start();


if (!isset($_SESSION['student_id'])) {
    header("Location: loginstudent.php");
    exit;
}

$studentId = $_SESSION['student_id'];


if (!isset($_GET["event_id"])) {
    die("No event selected.");
}

$eventId = mysqli_real_escape_string($conn, $_GET["event_id"]);
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $proofText = mysqli_real_escape_string($conn, $_POST["proof"]);
    $filePath = null;

    if (isset($_FILES["proof_file"]) && $_FILES["proof_file"]["error"] === UPLOAD_ERR_OK) {
        $uploadDir = "uploads/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $originalName = basename($_FILES["proof_file"]["name"]);
        $ext = pathinfo($originalName, PATHINFO_EXTENSION);
        $newName = "proof_" . date("YmdHis") . "_" . rand(100,999) . "." . $ext;
        $targetPath = $uploadDir . $newName;

        if (move_uploaded_file($_FILES["proof_file"]["tmp_name"], $targetPath)) {
            $filePath = $targetPath;
        } else {
            $message = "File upload failed. ";
        }
    }

    $checkSql = "
        SELECT submission_id 
        FROM Submission 
        WHERE student_id = '$studentId' AND event_id = '$eventId'
    ";
    $checkRes = mysqli_query($conn, $checkSql);

    if ($checkRes && mysqli_num_rows($checkRes) > 0) {
        if ($filePath !== null) {
            $updateSql = "
                UPDATE Submission 
SET proof = '$proofText',
    status = 'submitted'

                WHERE student_id = '$studentId' AND event_id = '$eventId'
            ";
        } else {
            $updateSql = "
                UPDATE Submission 
                SET proof = '$proofText',
                    status = 'submitted'
                WHERE student_id = '$studentId' AND event_id = '$eventId'
            ";
        }

        if (mysqli_query($conn, $updateSql)) {
            $message .= "Proof submitted successfully. Waiting for admin approval.";
        } else {
            $message .= "Error: " . mysqli_error($conn);
        }
    } else {
        $submissionId = "SUB" . date("YmdHis") . rand(100, 999);

        $cols = "submission_id, student_id, event_id, proof, status";
        $vals = "'$submissionId', '$studentId', '$eventId', '$proofText', 'submitted'";

        if ($filePath !== null) {
            $cols .= ", proof_file";
            $vals .= ", '$filePath'";
        }

        $insertSql = "INSERT INTO Submission ($cols) VALUES ($vals)";

        if (mysqli_query($conn, $insertSql)) {
            $message .= "Proof submitted successfully. Waiting for admin approval.";
        } else {
            $message .= "Error: " . mysqli_error($conn);
        }
    }
}

$eventSql = "SELECT name FROM Event WHERE event_id = '$eventId'";
$eventRes = mysqli_query($conn, $eventSql);
$eventRow = mysqli_fetch_assoc($eventRes);
$eventName = $eventRow ? $eventRow["name"] : $eventId;
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Submit Proof</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
body{
    font-family:Arial,sans-serif;
    background:#f4f4f4;
    margin:0;
    padding:20px;
}
.box{
    max-width:600px;
    margin:0 auto;
    background:#ffffff;
    border-radius:12px;
    padding:16px 18px;
    box-shadow:0 4px 12px rgba(0,0,0,0.1);
}
h2{
    margin-top:0;
    margin-bottom:8px;
}
.subtitle{
    font-size:13px;
    color:#666;
    margin-bottom:12px;
}
textarea{
    width:100%;
    min-height:120px;
    padding:8px;
    font-family:Arial,sans-serif;
    font-size:13px;
    box-sizing:border-box;
}
input[type="file"]{
    font-size:13px;
    margin-top:6px;
    margin-bottom:10px;
}
button{
    margin-top:10px;
    padding:7px 14px;
    border-radius:999px;
    border:none;
    background:#2e7d32;
    color:#ffffff;
    font-size:13px;
    cursor:pointer;
}
.back-link{
    display:inline-block;
    margin-top:10px;
    font-size:12px;
    color:#2e7d32;
    text-decoration:none;
}
.message{
    font-size:13px;
    margin-bottom:10px;
}
.success{
    color:#1b5e20;
}
.error{
    color:#c62828;
}
</style>
</head>
<body>

<div class="box">
    <h2>Submit Proof</h2>
    <div class="subtitle">
        Challenge: <?php echo htmlspecialchars($eventName); ?>
        (ID: <?php echo htmlspecialchars($eventId); ?>)
    </div>

    <?php if ($message != "") { ?>
        <div class="message <?php echo (strpos($message, 'Error') === 0) ? 'error' : 'success'; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php } ?>

    <form method="post" enctype="multipart/form-data">
        <label for="proof" style="font-size:13px;">Proof description</label><br>
        <textarea id="proof" name="proof" required
                  placeholder="Example: I recycled 5 plastic bottles at Block A recycling bin."></textarea><br><br>

        <label style="font-size:13px;">Upload file (optional)</label><br>
        <input type="file" name="proof_file" accept="image/*,application/pdf"><br>

        <button type="submit">Submit Proof</button>
    </form>

    <a href="view_progress.php" class="back-link">← Back to My Progress</a>
</div>

</body>
</html>
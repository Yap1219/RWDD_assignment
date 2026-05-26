<?php
include("conn.php");

$studentId = "STU001"; 


$sql = "
    SELECT 
        e.event_id,
        e.name,
        e.start_date,
        e.end_date,
        e.reward_point,
        s.status
    FROM Submission s
    JOIN Event e ON e.event_id = s.event_id
    WHERE s.student_id = '$studentId'
    ORDER BY e.start_date
";

$result = mysqli_query($conn, $sql);

$totalPoints = 0;
$rows = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
        if ($row['status'] === 'approved') {
            $totalPoints += $row['reward_point'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Progress</title>
    <meta name="viewport" content="width=device-width, initial-scale=1…
[16:47, 06/01/2026] Jason Wong: view_progress
[16:47, 06/01/2026] Jason Wong: <?php
include("conn.php");

$studentId = "STU001";

$eventId = $_GET["id"] ?? "";
if ($eventId === "") {
    die("No event selected.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"]) && $_POST["action"] === "accept") {

    $checkSql = "
        SELECT submission_id 
        FROM Submission
        WHERE student_id = '$studentId' AND event_id = '$eventId'
    ";
    $checkRes = mysqli_query($conn, $checkSql);

    if ($checkRes && mysqli_num_rows($checkRes) > 0) {

        $updateSql = "
            UPDATE Submission
            SET status = 'accepted'
            WHERE student_id = '$studentId' AND event_id = '$eventId'
        ";
        mysqli_query($conn, $updateSql);

    } else {

        $submissionId = 'SUB' . date('YmdHis') . rand(100,999);

        $insertSql = "
            INSERT INTO Submission (submission_id, student_id, event_id, status)
            VALUES ('$submissionId', '$studentId', '$eventId', 'accepted')
        ";
        mysqli_query($conn, $insertSql);
    }
}

$sql = "
    SELECT 
        e.event_id,
        e.name,
        e.description,
        e.start_date,
        e.end_date,
        e.reward_point,
        s.status
    FROM Event e
    LEFT JOIN Submission s
      ON e.event_id = s.event_id
     AND s.student_id = '$studentId'
    WHERE e.event_id = '$eventId'
";

$res = mysqli_query($conn, $sql);
if (!$res) {
    die(mysqli_error($conn));
}

$challenge = mysqli_fetch_assoc($res);
if (!$challenge) {
    die("Challenge not found.");
}

$status = $challenge["status"] ?? "not_started";

if ($status === null || $status === "") {
    $status = "not_started";
}

$percent = 0;
if ($status === "accepted") {
    $percent = 40;
} elseif ($status === "submitted") {
    $percent = 70;
} elseif ($status === "approved") {
    $percent = 100;
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Challenge Detail</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
:root{
    --green-main:#2e7d32;
    --green-dark:#1b5e20;
    --bg:#f4f4f4;
    --text-main:#222222;
    --text-muted:#666666;
}
*{
    box-sizing:border-box;
    margin:0;
    padding:0;
}
body{
    font-family:Arial,sans-serif;
    background:var(--bg);
    color:var(--text-main);
}
header{
    background:linear-gradient(135deg,var(--green-dark),var(--green-main));
    color:#ffffff;
    padding:16px 15px;
}
.header-inner{
    max-width:900px;
    margin:0 auto;
    display:flex;
    justify-content:space-between;
    align-items:center;
}
.logo-title{
    display:flex;
    gap:10px;
    align-items:center;
}
.logo-circle{
    width:28px;
    height:28px;
    border-radius:50%;
    background:#ffffff;
    color:var(--green-dark);
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight:bold;
}
.site-title{
    font-size:18px;
    font-weight:bold;
}
.page-wrap{
    max-width:900px;
    margin:18px auto 30px;
    padding:0 15px;
}
.card{
    background:#ffffff;
    border-radius:12px;
    padding:16px;
    box-shadow:0 4px 12px rgba(0,0,0,0.08);
}
.title-main{
    font-size:18px;
    font-weight:bold;
    margin-bottom:4px;
}
.meta{
    font-size:13px;
    color:var(--text-muted);
    margin-bottom:8px;
}
.points{
    display:inline-block;
    background:var(--green-main);
    color:#ffffff;
    border-radius:999px;
    padding:4px 10px;
    font-size:12px;
    font-weight:bold;
    margin-bottom:8px;
}
.desc{
    font-size:13px;
    margin-bottom:10px;
}
.status-text{
    font-size:13px;
    margin-bottom:6px;
}
.bar-wrap{
    width:100%;
    background:#eeeeee;
    border-radius:999px;
    overflow:hidden;
    height:18px;
    margin-bottom:6px;
}
.bar-fill{
    height:18px;
    background:var(--green-main);
}
.btn-row{
    margin-top:12px;
    display:flex;
    gap:8px;
}
.btn{
    font-size:13px;
    padding:7px 11px;
    border-radius:999px;
    border:none;
    cursor:pointer;
    text-decoration:none;
}
.btn-primary{
    background:var(--green-main);
    color:#ffffff;
}
.btn-ghost{
    background:#ffffff;
    color:var(--text-muted);
    border:1px solid #cccccc;
}
</style>
</head>
<body>

<header>
<div class="header-inner">
    <div class="logo-title">
        <div class="logo-circle">G</div>
        <div class="site-title">Green Point & Reward</div>
    </div>
    <nav>
        <a href="view_challenge.php" style="color:#ffffff;font-size:13px;text-decoration:none;margin-right:10px;">Challenges</a>
        <a href="view_progress.php" style="color:#ffffff;font-size:13px;text-decoration:none;">My Progress</a>
    </nav>
</div>
</header>

<main class="page-wrap">
<div class="card">

<div class="title-main"><?php echo htmlspecialchars($challenge["name"]); ?></div>

<div class="meta">
Start: <?php echo $challenge["start_date"]; ?> · End: <?php echo $challenge["end_date"]; ?>
</div>

<div class="points">
+<?php echo $challenge["reward_point"]; ?> pts
</div>

<p class="desc">
<?php echo htmlspecialchars($challenge["description"]); ?>
</p>

<p class="status-text">
Status:
<?php
if ($status === "not_started") {
    echo "Not started";
} elseif ($status === "accepted") {
    echo "In progress";
} elseif ($status === "submitted") {
    echo "Pending approval";
} elseif ($status === "approved") {
    echo "Completed";
} else {
    echo htmlspecialchars($status);
}
?>
</p>

<div class="bar-wrap">
<div class="bar-fill" style="width:<?php echo $percent; ?>%;"></div>
</div>

<p class="status-text">Progress: <?php echo $percent; ?>%</p>

<div class="btn-row">

<form method="post">
    <input type="hidden" name="action" value="accept">
    <button type="submit" class="btn btn-primary">Accept Challenge</button>
</form>

<a href="submit_proof.php?event_id=<?php echo $challenge["event_id"]; ?>" class="btn btn-primary">Submit Proof</a>

<a href="view_challenge.php" class="btn btn-ghost">Back</a>

</div>

</div>
</main>

</body>
</html>
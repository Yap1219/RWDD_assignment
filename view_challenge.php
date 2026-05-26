<?php
include("conn.php");
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: loginstudent.php");
    exit;
}

$studentId = $_SESSION['student_id'];


$userSql = "SELECT name, total_points FROM student WHERE student_id = '$studentId'";
$userRes = mysqli_query($conn, $userSql);

if ($userRow = mysqli_fetch_assoc($userRes)) {
    $studentName = $userRow['name'];
    $studentPoints = $userRow['total_points'];
} else {
    $studentName = "Student";
    $studentPoints = 0;
}


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["accept_event_id"])) {
    $eventId = mysqli_real_escape_string($conn, $_POST["accept_event_id"]);

    $checkSql = "
        SELECT submission_id 
        FROM Submission 
        WHERE student_id='$studentId' 
        AND event_id='$eventId'
        LIMIT 1
    ";
    $checkRes = mysqli_query($conn, $checkSql);

    if (mysqli_num_rows($checkRes) === 0) {
        $submissionId = "SUB" . date("YmdHis") . rand(100, 999);
        $insertSql = "
            INSERT INTO Submission (submission_id, student_id, event_id, status)
            VALUES ('$submissionId', '$studentId', '$eventId', 'accepted')
        ";
        mysqli_query($conn, $insertSql);
    } else {
        $updateSql = "
            UPDATE Submission
            SET status='accepted'
            WHERE student_id='$studentId'
            AND event_id='$eventId'
        ";
        mysqli_query($conn, $updateSql);
    }

    header("Location: " . $_SERVER["PHP_SELF"]);
    exit;
}


$sql = "
SELECT e.event_id,
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
ORDER BY e.start_date
";

$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Challenges</title>

<style>
html,body{height:100%;}
.page-wrapper{min-height:100%;display:flex;flex-direction:column;}
*{box-sizing:border-box;}

body{
  margin:0;
  font-family:Arial, Helvetica, sans-serif;
  background: linear-gradient(120deg,#d1f2eb,#eaf2f8,#f2d7d5);
  color:#111;
}

.nav{
  background:white;
  display:flex;
  align-items:center;
  justify-content:space-between;
  padding:14px 20px;
}

.nav-left{font-weight:900;}

.nav-center a{
  margin:0 18px;
  text-decoration:none;
  color:#111;
  font-weight:800;
  font-size:14px;
}

.nav-right{
  display:flex;
  align-items:center;
  gap:12px;
  font-size:13px;
}

.nav__user{
  font-weight:800;
}

.pill{
  border:1px solid #2e7d32;
  padding:6px 14px;
  border-radius:20px;
  font-weight:800;
  color:#2e7d32;
}

.avatar{
  width:34px;
  height:34px;
  border-radius:50%;
  overflow:hidden;
  display:flex;
  align-items:center;
  justify-content:center;
  background:#eee;
}

.avatar img{
  width:100%;
  height:100%;
  object-fit:cover;
}

.main-content{flex:1;}

.container{
  max-width:1100px;
  margin:40px auto;
}

.page-title{
  font-size:22px;
  font-weight:900;
}

.subtext{
  color:#666;
  font-size:14px;
  margin-bottom:20px;
}

.challenge-grid{
  display:grid;
  grid-template-columns:repeat(2,1fr);
  gap:18px;
}

.challenge-card{
  background:white;
  padding:15px;
  border-radius:15px;
  box-shadow:0 4px 15px rgba(0,0,0,0.08);
}

.points{
  background:#2e7d32;
  color:white;
  padding:6px 12px;
  border-radius:30px;
  font-size:13px;
  font-weight:bold;
}

.btn{
  padding:6px 12px;
  border-radius:30px;
  border:none;
  cursor:pointer;
  font-size:12px;
}

.btn-green{background:#2e7d32;color:white;}
.btn-outline{background:white;border:1px solid #2e7d32;color:#2e7d32;}
.btn-secondary{
  background:#fff;
  color:#2e7d32;
  border:1px solid #2e7d32;
  border-radius:999px;
  padding:6px 12px;
  text-decoration:none;
}

.footer{
  background:white;
  padding:25px 60px;
  display:grid;
  grid-template-columns:1fr 1fr 1fr;
}

.footer h4{margin-bottom:10px;font-weight:900;}
.footer a{
  display:block;
  text-decoration:none;
  color:#111;
  margin:6px 0;
  font-weight:800;
}

.social{display:flex;gap:14px;}
.social div{
  width:40px;
  height:40px;
  background:black;
  border-radius:8px;
}
</style>
</head>

<body>
<div class="page-wrapper">

<div class="nav">
  <div class="nav-left">
    <a href="Home.php" style="text-decoration:none;color:#111;font-weight:900;">EcoEarn</a>
  </div>

  <div class="nav-center">
    <a href="Home.php">EVENT</a>
    <a href="redeem_reward.php">REWARD REDEMPTION</a>
    <a href="view_challenge.php" style="color:green;">CHALLENGE</a>
  </div>

  <div class="nav-right">
    <span class="nav__user"><?php echo htmlspecialchars($studentName); ?></span>
    <div class="pill"><?php echo $studentPoints; ?> pts</div>
  </div>
</div>

<div class="main-content">
<div class="container">

<div class="page-title">Available Challenges</div>
<div class="subtext">Data from Event table (database).</div>

<div class="challenge-grid">

<?php
if ($result && mysqli_num_rows($result) > 0) {
while ($row = mysqli_fetch_assoc($result)) {
$status = $row["status"];
$accepted = ($status === "accepted" || $status === "submitted" || $status === "approved");
?>

<div class="challenge-card">
<b><?php echo $row["name"]; ?></b><br>
<span style="color:#666;font-size:13px;">
Start: <?php echo $row["start_date"]; ?> · End: <?php echo $row["end_date"]; ?>
</span>

<div style="margin:8px 0;">
<?php echo $row["description"]; ?>
</div>

<div style="display:flex;justify-content:space-between;align-items:center;">
<div class="points">+<?php echo $row["reward_point"]; ?> pts</div>

<div>
<?php if($accepted){ ?>
<button class="btn btn-green" disabled>Accepted</button>
<a href="submit_proof.php?event_id=<?php echo $row['event_id']; ?>" class="btn btn-outline">Submit Proof</a>
<a href="view_progress.php" class="btn btn-secondary">View Progress</a>
<?php } else { ?>
<form method="post" style="display:inline;">
<input type="hidden" name="accept_event_id" value="<?php echo $row["event_id"]; ?>">
<button class="btn btn-green">Accept</button>
</form>
<button class="btn btn-outline" disabled>Submit Proof</button>
<a href="view_progress.php" class="btn btn-secondary">View Progress</a>
<?php } ?>
</div>

</div>
</div>

<?php
}
} else {
echo "<p>No challenges available.</p>";
}
?>

</div>
</div>
</div>

<!-- FOOTER -->
<div class="footer">
  <div>
    <h4>Help</h4>
    <a href="#">FAQs</a>
    <a href="#">About Us</a>
    <a href="#">Contact</a>
  </div>
  <div>
    <h4>Info</h4>
    <a href="#">News</a>
    <a href="#">Policy</a>
    <a href="#">Support</a>
  </div>
  <div>
    <h4>Connect With Us</h4>
    <div class="social">
      <div></div><div></div><div></div><div></div>
    </div>
  </div>
</div>

</div>
</body>
</html>

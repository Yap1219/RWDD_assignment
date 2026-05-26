<?php
session_start();
include("conn.php");


if (!isset($_SESSION['student_id'])) {
    header("Location: loginstudent.php");
    exit;
}

$studentId = $_SESSION['student_id'];


$sql_student = "SELECT * FROM Student WHERE student_id='$studentId'";
$res_student = mysqli_query($conn,$sql_student);
$stu = mysqli_fetch_assoc($res_student);
$my_name = $stu ? $stu['name'] : "Student";
$my_balance = $stu ? $stu['total_points'] : 0;


$sql = "
SELECT e.event_id, e.name, e.start_date, e.end_date, e.reward_point, s.status
FROM Submission s
JOIN Event e ON e.event_id = s.event_id
WHERE s.student_id = '$studentId'
ORDER BY e.start_date
";
$result = mysqli_query($conn,$sql);


$totalPoints = 0;
$rows = [];
if ($result){
    while($r=mysqli_fetch_assoc($result)){
        $rows[]=$r;
        if($r['status']=='approved') $totalPoints += $r['reward_point'];
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>My Progress</title>

<style>
html,body{height:100%;}
.page-wrapper{min-height:100%;display:flex;flex-direction:column;}
.main-content{flex:1;}

*{box-sizing:border-box;}
body{
  margin:0;
  font-family: Arial, Helvetica, sans-serif;
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
.nav-left{
  font-weight:900;
  cursor:pointer;
}
.nav-center a{
  margin:0 18px;
  text-decoration:none;
  color:#111;
  font-weight:800;
  font-size:14px;
}
.nav-center a.active{
  border-bottom:3px solid #111;
}
.nav-right{
  display:flex;
  align-items:center;
  gap:10px;
}
.pill{
  border:1px solid #16a34a;
  padding:6px 14px;
  border-radius:20px;
}

.container{
  max-width:1100px;
  margin:40px auto;
}

.card{
  background:white;
  border-radius:12px;
  padding:16px;
  box-shadow:0 4px 15px rgba(0,0,0,0.1);
  margin-bottom:18px;
}

.summary-value{
  font-size:26px;
  font-weight:900;
  color:#2e7d32;
}


table{
  width:100%;
  border-collapse:collapse;
  background:white;
  border-radius:12px;
  overflow:hidden;
}
th,td{
  padding:10px;
  border-bottom:1px solid #eee;
}
th{
  background:#f5f5f5;
}

.status-badge{
  padding:4px 10px;
  border-radius:999px;
  font-size:12px;
}
.approved{background:#e8f5e9;color:#1b5e20;}
.submitted{background:#e3f2fd;color:#1565c0;}
.accepted{background:#fff8e1;color:#ff8f00;}


.footer{
  background:white;
  padding:25px 60px;
  display:grid;
  grid-template-columns:1fr 1fr 1fr;
  margin-top:70px;
}
.footer h4{
  font-weight:900;
}
.footer a{
  display:block;
  color:black;
  text-decoration:none;
  margin:6px 0;
  font-weight:800;
}
.social{
  display:flex;
  gap:14px;
}
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
  <div class="nav-left" onclick="window.location.href='Home.php'">
    EcoEarn
</div>

  <div class="nav-center">
    <a href="view_challenge.php">EVENT</a>
    <a href="redeem_reward.php">REWARD REDEMPTION</a>
    <a href="view_challenge.php" style="color:green;">CHALLENGE</a>

  </div>

  <div class="nav-right">
    <span><?php echo $my_name; ?></span>
    <div class="pill"><?php echo $my_balance; ?> pts</div>
  </div>
</div>


<div class="main-content">
<div class="container">

<h2>My Progress</h2>

<div class="card">
  <b>Total Points Earned:</b>
  <span class="summary-value"><?php echo $totalPoints; ?> pts</span>
</div>

<?php if(count($rows)==0): ?>
<p>You have not joined any challenges yet.</p>

<?php else: ?>
<table>
<tr>
  <th>Challenge</th>
  <th>Period</th>
  <th>Points</th>
  <th>Status</th>
</tr>

<?php foreach($rows as $r): ?>
<tr>
<td><?php echo $r["name"]; ?></td>
<td><?php echo $r["start_date"]; ?> - <?php echo $r["end_date"]; ?></td>
<td><b><?php echo $r["reward_point"]; ?></b></td>
<td>
<?php if($r["status"]=="approved"): ?>
<span class="status-badge approved">Completed</span>
<?php elseif($r["status"]=="submitted"): ?>
<span class="status-badge submitted">Pending Approval</span>
<?php else: ?>
<span class="status-badge accepted">In Progress</span>
<?php endif; ?>
</td>
</tr>
<?php endforeach; ?>

</table>
<?php endif; ?>
</div>
</div>


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
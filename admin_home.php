<?php
session_start();
include "conn.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: loginstudent.php");
    exit;
}

$adminId = $_SESSION['admin_id'];

$sql = "SELECT name FROM admin WHERE admin_id = '$adminId'";
$result = mysqli_query($conn, $sql);

if ($row = mysqli_fetch_assoc($result)) {
    $adminName = $row['name'];
} else {
    $adminName = "Admin";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Home</title>
  <link rel="stylesheet" href="admin_home.css" />
  <link rel="stylesheet" href="Home.css" />
</head>
<body>

<div class="page">

<header class="nav">
  <div class="nav__left">
    <div class="brand">Eco Earn</div>
  </div>

  <nav class="nav__center">
    <a class="nav__link" href="event_review_center.php">EVENT</a>
    <a class="nav__link" href="admin_submission_list.php">VIEW REWARD</a>
    <a class="nav__link" href="admin_reward_redemption_list.php">MANAGE REWARD</a>
    <a class="nav__link" href="admin_manage_user.php">MANAGE USER</a>
  </nav>

  <div class="nav__right">
    <span class="nav__user"><?php echo htmlspecialchars($adminName); ?></span>
    <a href="loginstudent.php" class="avatar">
      <img src="logout.jpg" alt="Logout">
    </a>

    <button class="burger" id="burgerBtn" aria-label="Open menu" aria-expanded="false">
      <span></span><span></span><span></span>
    </button>
  </div>

  <div class="mobileMenu" id="mobileMenu">
    <a class="nav__link is-active" href="event_review_center.php">EVENT</a>
    <a class="nav__link" href="admin_submission_list.php">VIEW REWARD</a>
    <a class="nav__link" href="admin_reward_redemption_list.php">MANAGE REWARD</a>
    <a class="nav__link" href="admin_manage_user.php">MANAGE USER</a>
  </div>
</header>

<main class="main">
  <section class="grid">

    <div class="card card--big" style="
      background-image: url('APU_image.png') !important;
      background-size: cover;
      background-position: center center;
      background-repeat: no-repeat;
      overflow: hidden;
    ">

      <div style="width:90%; text-align:left;">
        <h2 style="color:black;">Featured Challenge ⭐</h2>
        <h3 style="color:black;">Campus Cleanup Day</h3>
        <p>Join the movement to make our campus greener. Earn 100 pts!</p>

        <a href="event_review_center.php">
          <button style="
            padding:10px 18px;
            border-radius:30px;
            border:none;
            background:#2e7d32;
            color:white;
            cursor:pointer;
          ">
            View Event
          </button>
        </a>
      </div>
    </div>

    <div class="stack">

      <div class="card card--points">
        <div class="box-content">
          <h3 style="color:black;">Welcome back</h3>
          <h1 style="margin:8px 0; font-size:36px; color:green;">
            <?php echo htmlspecialchars($adminName); ?>
          </h1>
        </div>
      </div>

      <div class="card">
        <div class="box-content">
          <h3 style="margin:0 0 8px 0;color:black;">Announcements</h3>
          <ul style="margin:0; padding-left:18px;color:green;">
            <li>System maintenance on Friday</li>
            <li>New reward items added</li>
            <li>New event submissions pending review</li>
          </ul>
        </div>
      </div>

      <div class="card">
        <div class="box-content">
          <h3 style="color:black;">Upcoming Challenges</h3>
          <ul style="color:green;">
            <li>Plastic Free Week</li>
            <li>Campus Cleanup Day</li>
            <li>Recycle Awareness Drive</li>
          </ul>
        </div>
      </div>

      <div class="card">
        <div class="box-content">
          <h3 style="color:black;">Recent Activity</h3>
          <ul style="color:green;">
            <li>Reviewed event submissions</li>
            <li>Approved reward redemptions</li>
            <li>Updated reward stock</li>
          </ul>
        </div>
      </div>

    </div>
  </section>
</main>

<footer class="footer">
  <div class="footer__col">
    <a href="#">FAQs</a>
    <a href="#">About us</a>
    <a href="#">Contact Us</a>
  </div>

  <div class="footer__col">
    <a href="#">News</a>
    <a href="#">Policy</a>
    <a href="#">Support</a>
  </div>

  <div class="footer__col footer__social">
    <div class="footer__title">Connect With Us</div>
    <div class="social">
      <a class="social__icon" href="#"></a>
      <a class="social__icon" href="#"></a>
      <a class="social__icon" href="#"></a>
      <a class="social__icon" href="#"></a>
    </div>
  </div>
</footer>

</div>

<script src="admin_home.js"></script>
</body>
</html>

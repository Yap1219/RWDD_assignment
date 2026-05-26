<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Home</title>
  <link rel="stylesheet" href="Home.css" />
</head>
<body>

  <div class="page">

<header class="nav">
  <div class="nav__left">
    <div class="brand">EcoEarn</div>
  </div>

  <nav class="nav__center" id="mainNav">
    <a class="nav__link" href="home.php">EVENT</a>
    <a class="nav__link" href="redeem_reward.php">REWARD REDEMPTION</a>
    <a class="nav__link" href="view_challenge.php">CHALLENGE</a>
  </nav>

  <div class="nav__right">
    <span class="nav__user"></span>
    <a href="loginstudent.php" class="avatar">
      <img src="logout.jpg" alt="Logout">
    </a>



    <button class="burger" id="burgerBtn" aria-label="Open menu" aria-expanded="false">
      <span></span><span></span><span></span>
    </button>
  </div>

  <div class="mobileMenu" id="mobileMenu">
    <a class="nav__link" href="Home.php">EVENT</a>
    <a class="nav__link" href="redeem_reward">REWARD REDEMPTION</a>
    <a class="nav__link" href="view_challeng.php">CHALLENGE</a>
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
            
            

            <a href="view_challenge.php"><button style="
              padding:10px 18px;
              border-radius:30px;
              border:none;
              background:#2e7d32;
              color:white;
              cursor:pointer;
            ">
              View Challenge
            </button></a>
          </div>
        </div>

        <div class="stack">
          <div class="card card--points">
            <div class="box-content">
      <h3 style="color:black;">My Points</h3>
      <h1 style="margin:8px 0; font-size:36px; color:Green;">350 pts</h1>
    </div> </div>
          <div class="card">
            <div class="box-content"><div class="box-content">

      <h3 style="margin:0 0 8px 0;color:black">Announcements</h3>

      <ul style="margin:0; padding-left:18px;color:green">
        <li>System maintenance on Friday</li>
        <li>New reward items added</li>
        <li>New event submissions pending review</li>
      </ul>
    </div>
      
</div> </div>
          <div class="card">
            <div class="box-content">
              <div style="width:90%; text-align:left;">
      <h3 style="color:black;">Upcoming Challenges</h3>
      <ul style="color:green;">
        <li>Plastic Free Week</li>
        <li>Campus Cleanup Day</li>
        <li>Recycle Awareness Drive</li>
      </ul>
    </div>
          </div>
  </div>
          <div class="card">
            <div class="box-content">
      <h3 style ="color:black">Recent Activity</h3>
      <ul style="color:green;">
        <li>Submitted proof for “Green Bottle Challenge”</li>
        <li>Redeemed Reusable Cup – 40pts</li>
        <li>Joined Plastic Free Week</li>
      </ul>
    </div> </div>
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
          <a class="social__icon" href="#" aria-label="Instagram"></a>
          <a class="social__icon" href="#" aria-label="TikTok"></a>
          <a class="social__icon" href="#" aria-label="X"></a>
          <a class="social__icon" href="#" aria-label="Other"></a>
        </div>
      </div>
    </footer>

  </div>
<script src="Home.js"></script>
</body>
</html>
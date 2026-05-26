<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Vendor Dashboard</title>

<style>
:root{
    --main-grad: linear-gradient(120deg,#AEE6E6,#F6F8D4);
}
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial, sans-serif;
}

html,body{
    height: 100%;
}

body{
    min-height:100vh;
    display: flex;
    flex-direction: column;
    background:var(--main-grad);
    background-size:300% 300%;
    animation:gradientMove 14s ease infinite;
}

.top-nav{
    display:flex;
    justify-content:space-between;
    align-items:center;

    background:rgba(255,255,255,0.65);
    backdrop-filter:blur(10px);
    -webkit-backdrop-filter:blur(10px);

    padding:15px 30px;
    box-shadow:0 4px 20px rgba(0,0,0,0.12);
}

.logo{
    font-size:20px;
    font-weight:bold;
}

.nav__user{
    margin-left:auto;
    margin-right:10px;
    font-size:13px;
    font-weight:bold;
    padding:6px 14px;
    border-radius:20px;
}

.avatar{
    width:34px;
    height:34px;
    border-radius:50%;
    background:#d9d9d9;
    flex:0 0 34px;
}

.container{
    display:flex;
    flex: 1;
}

.sidebar{
    width:250px;
    min-height: 100%;
    padding:20px;

    background:rgba(255,255,255,0.30);
    border-right:1px solid rgba(255,255,255,0.35);

    backdrop-filter:blur(18px);
    -webkit-backdrop-filter:blur(18px);

    box-shadow:2px 0 25px rgba(0,0,0,0.12);
}

.sidebar ul{
    list-style:none;
}

.sidebar a{
    text-decoration:none;
    color:#333;
    display:block;
}

.sidebar li{
    padding:20px 12px;
    margin-bottom:8px;
    border-radius:8px;
    cursor:pointer;
    transition:transform .25s ease, background .25s ease, box-shadow .25s ease;
}

.sidebar li:hover{
    background:rgba(255,255,255,0.45);
    transform:scale(1.04);
    box-shadow:0 6px 16px rgba(0,0,0,0.15);
}

.sidebar .active{
    background:rgba(255,255,255,0.55);
    font-weight:bold;
}

.sidebar li.logout{
    margin-top:30px;
}

.sidebar li.logout a{
    font-weight: 600;
    color:#c0392b;
}

.sidebar li.logout:hover{
    background:rgba(192,57,43,0.18);
}

.sidebar li.logout:hover a {
    color: #a93226;
}

.main-content{
    flex:1;
    padding:30px;
}

.main-content h1{
    margin-bottom:20px;
}

.dashboard-box{
    width:100%;
    height:500px;
    background:#fff;
    border:2px solid #ccc;
    border-radius:10px;
    overflow:hidden;
}

.dashboard-box img{
    width:87%;
    height:100%;
    object-fit:cover;
    object-position:center;
}


@media (max-width: 768px) {

  body {
    overflow-x: hidden;
  }

  .container {
    flex-direction: column;
  }

  .top-nav {
    flex-direction: row;
    justify-content: space-between;
    padding: 12px 18px;
  }

  .logo {
    font-size: 18px;
  }

  .nav__user {
    padding: 4px 10px;
    font-size: 12px;
  }

  .sidebar {
    width: 100%;
    min-height: auto;
    padding: 12px;
    display: block;
  }

  .sidebar ul {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 10px;
  }

  .sidebar li {
    margin-bottom: 0;
    padding: 14px 10px;
    text-align: center;
    font-size: 14px;
  }

  .sidebar .logout {
    grid-column: span 2;
    text-align: center;
  }

  .main-content {
    padding: 18px;
  }

  .main-content h1 {
    font-size: 20px;
    text-align: center;
  }

  .dashboard-box {
    height: auto;
  }

  .dashboard-box img {
    width: 100%;
    height: auto;
    object-fit: contain;
  }

  .sidebar li:hover {
    transform: none;
    box-shadow: none;
  }
}

@keyframes gradientMove{
 0%{background-position:0% 50%;}
 50%{background-position:100% 50%;}
 100%{background-position:0% 50%;}
}
</style>

</head>

<body>

<header class="top-nav">
    <div class="logo">ECOEarn</div>
    <span class="nav__user">Vendor</span>
    <div class="avatar"></div>
</header>

<div class="container">

    <aside class="sidebar">
        <ul>
            <li><a href="AManageGift.php">🎁 Manage Gift</a></li>
            <li><a href="AManageDiscount.php">🤝 Manage Collaboration Gift</a></li>
            <li><a href="AManagePoint.php">📊 Manage Point</a></li>
            <li class="logout"><a href="loginstudent.php">⏻ Logout</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <h1>Vendor Dashboard</h1>

        <div class="dashboard-box">
            <img src="https://slidebazaar.com/wp-content/uploads/2023/04/3R-Reduce-Reuse-Recycle-PowerPoint-Template.jpg">
        </div>
    </main>

</div>


</body>
</html>

<?php 
    include "admin_db.php";

    $role = isset($_GET['role']) ? $_GET['role'] : "student";

    if($role == "student"){
        $title = "Student List";
        $result = $conn->query("SELECT student_id AS id, name, email, gender FROM student");
    }
    elseif($role == "vendor"){
        $title = "Vendor List";
        $result = $conn->query("SELECT vendor_id AS id, name, email FROM vendor");
    }
    else{
        $title = "Admin List";
        $result = $conn->query("SELECT admin_id AS id, name, email FROM admin");
    }
?>

<html>
<head>
    <title>Manage Users</title>
    <link rel="stylesheet" href="Home.css" />
    <style>

        body{
            margin:0;
            font-family:Arial;
            background: var(--main-grad);
        }

        .container{
            width:85%;
            margin:40px auto;
            padding:25px;
            border-radius:16px;
            box-shadow: var(--shadow);
            color:#111;
            background: rgba(255,255,255,0.25);
            backdrop-filter: blur(6px);
        }

        .role-tabs{
            margin-bottom:20px;
        }

        .role-tabs a{
            padding:10px 18px;
            background:#555;
            border-radius:6px;
            margin-right:8px;
            color:white;
            text-decoration:none;
        }

        .role-tabs .active{
            background:#27ae60;
        }

        .table-box{
            margin-top:15px;
        }

        table{
            width:100%;
            border-collapse:collapse;
        }

        th{
            padding:14px;
            text-align:center;
            font-size:16px;
            color:white;
            background: rgba(0,0,0,0.3);
        }

        thead tr th:first-child{
            border-top-left-radius:14px;
        }

        thead tr th:last-child{
            border-top-right-radius:14px;
        }

        td{
            padding:14px;
            text-align:center;
            color:#111;
            border-bottom:1px solid rgba(255,255,255,0.2);
        }

        tr:nth-child(even){
            background: rgba(255,255,255,0.55);
        }

        tr:nth-child(odd){
            background: rgba(255,255,255,0.40);
        }

        tr:hover{
            background: rgba(255,255,255,0.85);
            transition:0.25s;
        }

        a.btn{
            padding:6px 12px;
            border-radius:8px;
            text-decoration:none;
            color:white;
            margin: 0 5px;
            display:inline-block;
        }

        .add{ background:#27ae60 }
        .view{ background:#2980b9 }
        .del{ background:#c0392b }

        .brand a{
            color: #000;   
            text-decoration: none; 
            font-family: "Times New Roman", Times, serif;
            font-weight: 700;  
        }

        .brand a:hover{
            text-decoration: none;       
            color: #000; 
        }


        @media(max-width: 768px){

        .container{
            width:95%;
            padding:18px;
        }

        table{
            font-size:14px;
        }

        th,td{
            padding:10px;
        }

        .role-tabs a{
            display:inline-block;
            margin-bottom:6px;
        }

        .btn{
            padding:5px 8px;
            font-size:12px;
        }
        }

    </style>
</head>

<body>

    <header class="nav">
        <div class="nav__left">
             <a href="admin_home.php" style="
                color:#000;
                text-decoration:none;
                font-family:'Times New Roman', serif;
                font-size:22px;
                font-weight:bold;">
                Eco Earn
            </a>
        </div>

        <nav class="nav__center">
            <a class="nav__link" href="event_review_center.php">EVENT</a>
            <a class="nav__link" href="admin_submission_list.php">VIEW REWARD</a>
            <a class="nav__link" href="admin_reward_redemption_list.php">MANAGE REWARD</a>
            <a class="nav__link is-active" href="admin_manage_user.php">MANAGE USER</a>
        </nav>

        <div class="nav__right">
            <span class="nav__user"></span>
            <div class="avatar"></div>
        </div>
    </header>


    <div class="container">

    <h2><?= $title ?></h2>

    <div class="role-tabs">
        <a href="admin_manage_user.php?role=student" class="<?= ($role=='student')?'active':'' ?>">Student</a>
        <a href="admin_manage_user.php?role=admin" class="<?= ($role=='admin')?'active':'' ?>">Admin</a>
        <a href="admin_manage_user.php?role=vendor" class="<?= ($role=='vendor')?'active':'' ?>">Vendor</a>
    </div>


    <a class="btn add" href="admin_user_add.php?role=<?= $role ?>">+ Add <?= ucfirst($role) ?></a>
    <a class="btn" style="background:#888" href="admin_home.php">Back</a>


    <div class="table-box">
    <table>

    <thead>
    <tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>

    <?php if($role=="student"){ ?>
        <th>Gender</th>
        <?php } ?>

        <th>Action</th>
        </tr>
        </thead>

        <tbody>
        <?php while($r=$result->fetch_assoc()){ ?>
        <tr>
        <td><?= $r['id'] ?></td>
        <td><?= $r['name'] ?></td>
        <td><?= $r['email'] ?></td>

        <?php if($role=="student"){ ?>
        <td><?= $r['gender'] ?></td>
        <?php } ?>

        <td>
        <a class="btn view" href="admin_user_detail.php?id=<?= $r['id']?>&role=<?= $role ?>">View</a>
        <a class="btn del" onclick="return confirm('Delete this user?')" href="admin_user_delete.php?id=<?= $r['id']?>&role=<?= $role ?>">Delete</a>
        </td>
        </tr>
    <?php } ?>
    </tbody>

    </table>
    </div>

    </div>
    
    </body>
</html>

<?php
include "admin_db.php";

if (!isset($_GET['id'])) {
    die("Invalid request.");
}

$id = $_GET['id'];

$sql = "
SELECT 
    r.redemption_id,
    r.status,
    r.redemption_date,
    s.student_id,
    s.name AS student_name,
    s.total_points,
    w.name AS reward_name,
    w.point_cost,
    w.stock
FROM redemption r
JOIN student s ON r.student_id = s.student_id
JOIN reward w ON r.reward_id = w.reward_id
WHERE r.redemption_id = '$id'
";

$result = $conn->query($sql);

if (!$result || $result->num_rows === 0) {
    die("Redemption record not found.");
}

$data = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reward Redemption Detail</title>
    <link rel="stylesheet" href="Home.css">
    <style>
        body{
            margin:0;
            font-family:Arial;
            background: var(--main-grad);
        }

        .detail-row{
            margin:14px 0;
            font-size:16px;
        }

        .detail-label{
            font-weight:700;
            display:inline-block;
            width:180px;
        }

        .action-bar{
            margin-top:30px;
        }
    </style>
</head>

<body>

<header class="nav">
    <div class="nav__left">
        <a href="admin_home.php"
           style="color:#000;text-decoration:none;font-family:'Times New Roman',serif;font-size:22px;font-weight:bold;">
            Eco Earn
        </a>
    </div>

    <nav class="nav__center">
        <a class="nav__link" href="event_review_center.php">EVENT</a>
        <a class="nav__link is-active" href="admin_reward_redemption_list.php">MANAGE REWARD</a>
        <a class="nav__link" href="admin_manage_user.php">MANAGE USER</a>
    </nav>

    <div class="nav__right">
        <div class="avatar"></div>
    </div>
</header>

<div class="container">

    <h2>Reward Redemption Detail</h2>

    <div class="detail-row">
        <span class="detail-label">Redemption ID:</span>
        <?= $data['redemption_id'] ?>
    </div>

    <div class="detail-row">
        <span class="detail-label">Student:</span>
        <?= $data['student_name'] ?> (<?= $data['student_id'] ?>)
    </div>

    <div class="detail-row">
        <span class="detail-label">Student Points:</span>
        <?= $data['total_points'] ?>
    </div>

    <div class="detail-row">
        <span class="detail-label">Reward:</span>
        <?= $data['reward_name'] ?>
    </div>

    <div class="detail-row">
        <span class="detail-label">Point Cost:</span>
        <?= $data['point_cost'] ?>
    </div>

    <div class="detail-row">
        <span class="detail-label">Stock:</span>
        <?= $data['stock'] ?>
    </div>

    <div class="detail-row">
        <span class="detail-label">Status:</span>
        <span class="status <?= strtolower($data['status']) ?>">
            <?= $data['status'] ?>
        </span>
    </div>

    <div class="action-bar">
        <?php if ($data['status'] === 'Submitted') { ?>
            <a class="btn add"
               href="admin_reward_approve.php?ids?id=<?= $data['redemption_id'] ?>">
               Approve
            </a>

            <a class="btn del"
               onclick="return confirm('Reject this redemption?')"
               href="admin_reward_reject.php?id=<?= $data['redemption_id'] ?>">
               Reject
            </a>
        <?php } ?>

        <a class="btn" style="background:#888"
           href="admin_reward_redemption_list.php">
           Back
        </a>
    </div>

</div>

</body>
</html>

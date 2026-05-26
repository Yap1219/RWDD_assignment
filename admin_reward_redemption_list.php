<?php
include "admin_db.php";

$result = $conn->query("
SELECT 
    sub.submission_id,
    sub.status,
    sub.proof,
    s.student_id,
    s.name AS student_name
FROM submission sub
JOIN student s ON sub.student_id = s.student_id
WHERE sub.status = 'submitted'
ORDER BY sub.submission_id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Submitted Requests</title>

    <link rel="stylesheet" href="Home.css">

    <style>
        body{
            margin:0;
            font-family:Arial;
            background: var(--main-grad);
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
        <a class="nav__link" href="admin_submission_list.php">VIEW REWARD</a>
        <a class="nav__link is-active" href="admin_reward_redemption_list.php">MANAGE REWARD</a>
        <a class="nav__link" href="admin_manage_user.php">MANAGE USER</a>
    </nav>

    <div class="nav__right">
        <div class="avatar"></div>
    </div>
</header>

<div class="container">

    <h2>Submitted Requests</h2>

    <div class="table-box">
        <table>
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Proof</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
            <?php if($result->num_rows > 0){ ?>
                <?php while($r = $result->fetch_assoc()){ ?>
                <tr>
                    <td><?= $r['student_name'] ?> (<?= $r['student_id'] ?>)</td>

                    <td>
                        <img src="<?= $r['proof'] ?>" width="60">
                    </td>

                    <td>
                        <span class="status pending">
                            <?= $r['status'] ?>
                        </span>
                    </td>

                    <td>
                        <a class="btn add"
                           href="admin_submission_approve.php?id=<?= $r['submission_id'] ?>">
                           Approve
                        </a>

                        <a class="btn del"
                           onclick="return confirm('Reject this submission?')"
                           href="admin_submission_reject.php?id=<?= $r['submission_id'] ?>">
                           Reject
                        </a>
                    </td>
                </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="4">No submitted requests.</td>
                </tr>
            <?php } ?>
            </tbody>

        </table>
    </div>

</div>

</body>
</html>

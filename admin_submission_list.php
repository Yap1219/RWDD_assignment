<?php
include "admin_db.php";
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

$sql = "
SELECT 
    sub.submission_id,
    sub.proof,
    sub.status,
    s.student_id,
    s.name AS student_name
FROM submission sub
JOIN student s ON sub.student_id = s.student_id
ORDER BY sub.submission_id DESC
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Submissions</title>

    <link rel="stylesheet" href="Home.css">

    <style>
        body{
            margin:0;
            font-family:Arial, Helvetica, sans-serif;
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
        <a class="nav__link is-active" href="admin_submission_list.php">VIEW REWARD</a>
        <a class="nav__link" href="admin_reward_redemption_list.php">MANAGE REWARD</a>
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
            <?php if ($result && $result->num_rows > 0) { ?>
                <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= htmlspecialchars($row['student_name']) ?> (<?= $row['student_id'] ?>)</td>

                    <td>
                        <img src="<?= htmlspecialchars($row['proof']) ?>" width="60">
                    </td>

                    <td>
                        <?php
                            $statusClass = strtolower($row['status']);
                        ?>
                        <span class="status <?= $statusClass ?>">
                            <?= ucfirst($row['status']) ?>
                        </span>
                    </td>

                    <td>
                        <?php if ($row['status'] === 'submitted') { ?>
                            <a class="btn add"
                               href="admin_submission_approve.php?id=<?= $row['submission_id'] ?>">
                               Approve
                            </a>

                            <a class="btn del"
                               href="admin_submission_reject.php?id=<?= $row['submission_id'] ?>"
                               onclick="return confirm('Reject this submission?')">
                               Reject
                            </a>
                        <?php } else { ?>
                            <a class="btn view"
                               href="admin_submission_detail.php?id=<?= $row['submission_id'] ?>">
                               View
                            </a>
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="4">No submissions found.</td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>

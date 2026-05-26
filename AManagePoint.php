<?php
include("vendor_db.php");

$search = $_GET['search'] ?? "";

$sql = "
SELECT Point_History.*, Student.name 
FROM Point_History
JOIN Student 
ON Point_History.student_id = Student.student_id
";

if($search != ""){
    $sql .= " WHERE Student.name LIKE '%$search%'";
}

$sql .= " ORDER BY date DESC";

$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Point</title>
<link rel="stylesheet" href="AmanagesPoint.css">
</head>

<body>

<header class="page-header">
    <a href="AIndex.php" class="back-btn">←</a>
    <h1>Manage Point</h1>
</header>

<main class="wrapper">

    <div class="search-bar">
        <form method="GET">
            <label>Search by Student Name</label>
            <input 
                type="text" 
                name="search" 
                placeholder="Enter student name"
                value="<?php echo $search; ?>">
        </form>
    </div>

    <section class="point-list">

    <?php 
    if(mysqli_num_rows($result) == 0){
        echo "<p style='text-align:center; opacity:.7;'>No point history found.</p>";
    }

    while($row = mysqli_fetch_assoc($result)) { 
    ?>

        <div class="point-row">

            <div class="avatar"></div>

            <div class="info">
                <h3>
                    <?php echo $row['name']; ?> 
                    <span>(<?php echo $row['student_id']; ?>)</span>
                </h3>

                <div class="point <?php echo ($row['change_amount'] < 0 ? 'minus' : 'plus'); ?>">
                    <?php echo $row['change_amount']; ?> Point
                </div>

                <div class="reason">
                    <strong>Reason:</strong> 
                    <?php echo $row['reason']; ?>
                </div>

                <div class="reason">
                    <strong>Date:</strong> 
                    <?php echo $row['DATE']; ?>
                </div>
            </div>

            <div class="status success">Redeemed</div>

        </div>

    <?php } ?>

    </section>

</main>

</body>
</html>

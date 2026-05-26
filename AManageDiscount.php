<?php
include("vendor_db.php");


$search = $_GET['search'] ?? "";


$sql = "
SELECT Reward.*, Discount.discount_percentage, Discount.start_time, Discount.end_time
FROM Reward
JOIN Discount
ON Reward.discount_id = Discount.discount_id
WHERE Reward.discount_id IS NOT NULL
";

if($search != ""){
    $sql .= " AND Reward.name LIKE '%$search%'";
}

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Collaboration Discount</title>
<link rel="stylesheet" href="AmanagesDiscount.css">
</head>

<body>

<header class="page-header">
    <a href="AIndex.php" class="back-btn">←</a>
    <h1>Manage Collaboration Discount</h1>
</header>

<main class="wrapper">

    
    <div class="action-bar">
        <a href="vendor_add_discount.php">
            <button class="primary-btn">＋ Add New Discount</button>
        </a>

        <form method="GET" class="search-box">
            <input type="text" 
                   name="search"
                   placeholder="Search discount"
                   value="<?php echo $search; ?>">
            <button type="submit">🔍</button>
        </form>
    </div>

    <section class="discount-list">

        <?php 
        if(mysqli_num_rows($result) == 0){
        ?>
        
        <p style="text-align:center; opacity:.7; padding:20px;">
            No collaboration discount added yet.
        </p>

        <?php
        }
        ?>


        <?php while($row = mysqli_fetch_assoc($result)) { 
        
        $oldPoint = $row['point_cost'];
        $percent  = $row['discount_percentage'];

        $newPoint = round($oldPoint - ($oldPoint * ($percent/100)));
        ?>

        <div class="discount-row">

            <img src="<?php echo $row['image']; ?>" class="img-box">

            <div class="info">
                <h3><?php echo $row['NAME']; ?></h3>

                <div class="price">
                    <span class="discount">
                        -<?php echo $percent; ?>%
                    </span>

                    <span class="new">
                        <?php echo $newPoint; ?> Point
                    </span>

                    <span class="old">
                        <s><?php echo $oldPoint; ?> Point</s>
                    </span>
                </div>

                <span class="valid">
                    Valid: <?php echo $row['start_time']; ?> → <?php echo $row['end_time']; ?>
                </span>
            </div>

            <a href="vendor_edit_discount.php?id=<?php echo $row['discount_id']; ?>">
                <button class="edit-btn">Edit</button>
            </a>

        </div>

        <?php } ?>

    </section>

</main>

</body>
</html>

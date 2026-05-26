<?php
include("vendor_db.php");

if(isset($_POST['delete'])){

    $discountID = $_POST['discount_id'];

    mysqli_query($conn,"
        UPDATE Reward 
        SET discount_id = NULL 
        WHERE discount_id = '$discountID'
    ");

    mysqli_query($conn,"
        DELETE FROM Discount WHERE discount_id = '$discountID'
    ");

    header("Location: AManageDiscount.php");
    exit;
}

if(isset($_POST['update'])){

    $discountID = $_POST['discount_id'];
    $percent = $_POST['discount_percentage'];
    $start = $_POST['start'];
    $end = $_POST['end'];

    mysqli_query($conn,"
        UPDATE Discount
        SET discount_percentage = '$percent',
            start_time = '$start',
            end_time = '$end'
        WHERE discount_id = '$discountID'
    ");

    header("Location: AManageDiscount.php");
    exit;
}


$id = $_GET['id'];

$query = mysqli_query($conn,"
SELECT Reward.name, Reward.point_cost, Reward.image,
       Discount.discount_id, Discount.discount_percentage,
       Discount.start_time, Discount.end_time
FROM Reward 
JOIN Discount
ON Reward.discount_id = Discount.discount_id
WHERE Discount.discount_id = '$id'
");

$data = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Discount</title>

<style>
body{
    font-family: Arial;
    background:#f2f2f2;
}

.box{
    width:480px;
    margin:80px auto;
    background:white;
    padding:25px;
    border-radius:15px;
    box-shadow:0 15px 35px rgba(0,0,0,.2);
}

.gift{
    display:flex;
    align-items:center;
    gap:15px;
    margin-bottom:10px;
}

.gift img{
    width:70px;
    height:70px;
    object-fit:cover;
    border-radius:10px;
}

.input{
    width:100%;
    padding:10px;
    margin-bottom:10px;
    border-radius:8px;
    border:1px solid #ccc;
}

.btn{
    padding:10px 18px;
    border:none;
    border-radius:8px;
    cursor:pointer;
}

.save{ background:#2f4cff; color:white; }
.cancel{ background:#ccc; text-decoration:none; padding:8px 12px; border-radius:8px; }
.delete{ background:#ff3b3b; color:white; }
</style>

</head>

<body>

<div class="box">

<h2>Edit Discount</h2>

<div class="gift">
    <img src="<?php echo $data['image']; ?>">
    <div>
        <b><?php echo $data['name']; ?></b><br>
        Original: <?php echo $data['point_cost']; ?> Point
    </div>
</div>

<hr>

<form method="POST">

<input type="hidden" name="discount_id" value="<?php echo $data['discount_id']; ?>">

<label>Discount Percentage (%)</label>
<input class="input" type="number" name="discount_percentage" 
       value="<?php echo $data['discount_percentage']; ?>" min="1" max="90" required>

<label>Start Date</label>
<input class="input" type="date" name="start" value="<?php echo $data['start_time']; ?>" required>

<label>End Date</label>
<input class="input" type="date" name="end" value="<?php echo $data['end_time']; ?>" required>

<button class="btn save" name="update">Save</button>
<a href="AManageDiscount.php" class="cancel">Cancel</a>

</form>

<hr>

<form method="POST" 
      onsubmit="return confirm('Are you sure you want to DELETE this discount?');">
      
    <input type="hidden" name="discount_id" value="<?php echo $data['discount_id']; ?>">
    <button class="btn delete" name="delete">Delete Discount</button>
</form>

</div>

</body>
</html>

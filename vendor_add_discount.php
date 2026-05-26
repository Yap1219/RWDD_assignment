<?php
include("vendor_db.php");

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $giftId  = $_POST['reward_id'];
    $percent = $_POST['discount_percentage'];
    $start   = $_POST['start'];
    $end     = $_POST['end'];

    $getLast = mysqli_query($conn,
        "SELECT discount_id FROM Discount ORDER BY discount_id DESC LIMIT 1"
    );

    if(mysqli_num_rows($getLast) > 0){
        $row = mysqli_fetch_assoc($getLast);
        $num = intval(substr($row["discount_id"],1)) + 1;
        $discountID = "D".str_pad($num,4,"0",STR_PAD_LEFT);
    } else {
        $discountID = "D0001";
    }

    $insert = mysqli_query($conn,"
        INSERT INTO Discount(discount_id, start_time, end_time, discount_percentage)
        VALUES('$discountID','$start','$end','$percent')
    ");

    if(!$insert){
        die("INSERT Discount FAILED: ". mysqli_error($conn));
    }

    $update = mysqli_query($conn,"
        UPDATE Reward 
        SET discount_id = '$discountID'
        WHERE reward_id = '$giftId'
    ");

    if(!$update){
        die("UPDATE Reward FAILED: ". mysqli_error($conn));
    }

    header("Location: AManageDiscount.php");
    exit;
}

$rewardList = mysqli_query($conn,"
    SELECT * FROM Reward
    WHERE discount_id IS NULL
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Discount</title>

<style>
body{
    font-family: Arial;
    background:#f2f2f2;
}

.box{
    width:450px;
    margin:80px auto;
    background:white;
    padding:25px;
    border-radius:15px;
    box-shadow:0 15px 35px rgba(0,0,0,.2);
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

.save{
    background:#2f4cff;
    color:white;
}

.cancel{
    background:#ccc;
    text-decoration:none;
    padding:8px 15px;
    border-radius:8px;
}
</style>

</head>

<body>

<div class="box">

<h2>Add Collaboration Discount</h2>

<form method="POST">

<label>Select Gift</label>
<select name="reward_id" class="input" required>
    <option value="">-- Choose Gift --</option>

    <?php while($g = mysqli_fetch_assoc($rewardList)) { ?>
        <option value="<?php echo $g['reward_id']; ?>">
            <?php echo $g['NAME']; ?> (<?php echo $g['point_cost']; ?> Point)
        </option>
    <?php } ?>

</select>

<label>Discount Percentage (%)</label>
<input type="number" name="discount_percentage" class="input" min="1" max="90" required>

<label>Start Date</label>
<input type="date" name="start" class="input" required>

<label>End Date</label>
<input type="date" name="end" class="input" required>

<button class="btn save" type="submit">Save</button>
<a href="AManageDiscount.php" class="cancel">Cancel</a>

</form>

</div>

</body>
</html>

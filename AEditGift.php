<?php
include('vendor_db.php');



if (isset($_POST['delete'])) {

    $deleteID = $_POST['reward_id'];

    mysqli_query($conn, "DELETE FROM Reward WHERE reward_id = '$deleteID'");

    header("Location: AManageGift.php");
    exit;
}




if (isset($_POST['update'])) {

    $id = $_POST['reward_id'];
    $stock = $_POST['stock'];

    
    mysqli_query($conn,
        "UPDATE Reward 
         SET stock = '$stock'
         WHERE reward_id = '$id'"
    );

    header("Location: AManageGift.php");
    exit;
}



$id = $_GET['id'];

$result = mysqli_query($conn, "SELECT * FROM Reward WHERE reward_id = '$id'");
$row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Gift</title>

<style>
body{
    font-family: Arial;
    background:#f2f2f2;
}

.edit-box{
    width:420px;
    margin:100px auto;
    background:white;
    padding:25px;
    border-radius:15px;
    box-shadow:0 15px 35px rgba(0,0,0,.2);
}

h2{
    margin:0 0 10px 0;
}

.input{
    width:100%;
    padding:10px;
    margin-bottom:10px;
    border-radius:8px;
    border:1px solid #ccc;
}

.save-btn{
    padding:10px 18px;
    background:#2f4cff;
    color:white;
    border:none;
    border-radius:8px;
    cursor:pointer;
}

.cancel{
    padding:10px 18px;
    background:#ccc;
    border:none;
    border-radius:8px;
    text-decoration:none;
}

.delete-btn{
    padding:10px 18px;
    background:#ff3b3b;
    color:white;
    border:none;
    border-radius:8px;
    cursor:pointer;
    margin-top:10px;
}
</style>

</head>

<body>

<div class="edit-box">

<h2>Edit Gift</h2>

<form method="POST">

    <p><b><?php echo $row['NAME']; ?></b></p>

    <input type="hidden" name="reward_id" value="<?php echo $row['reward_id']; ?>">

    <label>Stock Quantity</label>
    <input type="number" name="stock" class="input" value="<?php echo $row['stock']; ?>" min="0" required>

    <p>
        Current Status:
        <b style="color:<?php echo $row['stock']>0 ? 'green' : 'red'; ?>">
            <?php echo $row['stock']>0 ? 'Active' : 'Expired'; ?>
        </b>
    </p>

    <button class="save-btn" type="submit" name="update">Save</button>
    <a href="AManageGift.php" class="cancel">Cancel</a>

</form>

<hr>

<form method="POST" onsubmit="return confirm('Are you sure you want to DELETE this gift?');">
    <input type="hidden" name="reward_id" value="<?php echo $row['reward_id']; ?>">
    <button type="submit" name="delete" class="delete-btn">Delete Gift</button>
</form>

</div>

</body>
</html>

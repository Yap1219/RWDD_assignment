<?php
include('vendor_db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name   = $_POST['name'];
    $points = $_POST['points'];
    $status = $_POST['status'];

    
    $result = mysqli_query($conn, "SELECT reward_id FROM Reward ORDER BY reward_id DESC LIMIT 1");

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        $num = intval(substr($row['reward_id'], 1)) + 1;
        $newId = "R" . str_pad($num, 4, "0", STR_PAD_LEFT);
    } 
    else {
        $newId = "R0001";
    }

    
    $stock = $_POST['stock'];

    
    $imageName = $_FILES['image']['name'];
    $tmp = $_FILES['image']['tmp_name'];

    $folder = "uploads/" . $imageName;

    if (!is_dir("uploads")) {
        mkdir("uploads");
    }

    move_uploaded_file($tmp, $folder);

    
    $vendorID   = "V0001";

    
    $sql = "INSERT INTO Reward(reward_id, name, point_cost, stock, image, vendor_id, discount_id)
            VALUES('$newId', '$name', '$points', '$stock', '$folder', '$vendorID', NULL)";

    mysqli_query($conn, $sql);

    header("Location: AManageGift.php");
    exit;
}
?>

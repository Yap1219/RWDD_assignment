<?php
session_start(); 
include 'conn.php'; 

if (!isset($_SESSION['student_id'])) {
    header("Location: loginstudent.php");
    exit;
}

$student_id = $_SESSION['student_id'];


$sql_student = "SELECT * FROM student WHERE student_id = '$student_id'";
$result_student = mysqli_query($conn, $sql_student);

if (!$result_student) {
    die("Database Error (Student): " . mysqli_error($conn));
}

$my_data = mysqli_fetch_assoc($result_student);

if ($my_data) {
    $my_name = $my_data['name'];
    $my_balance = $my_data['total_points'];
} else {
    $my_name = "Unknown";
    $my_balance = 0;
}

$message = ""; 

if (isset($_POST['redeem_btn'])) {
    $id_to_buy = $_POST['reward_id']; 
    $cost = intval($_POST['point_cost']);

    $check_sql = "SELECT total_points FROM student WHERE student_id = '$student_id'";
    $check_res = mysqli_query($conn, $check_sql);
    $check_row = mysqli_fetch_assoc($check_res);
    $current_balance = $check_row['total_points'];

    if ($current_balance >= $cost) {
        
        $redemption_id = "RED-" . uniqid(); 
        $today = date("Y-m-d");

        $sql_deduct = "UPDATE student SET total_points = total_points - $cost WHERE student_id = '$student_id'";
        mysqli_query($conn, $sql_deduct);
        
        $sql_get_latest = "SELECT history_id FROM point_history ORDER BY history_id DESC LIMIT 1";
        $res_latest = mysqli_query($conn, $sql_get_latest);
        
        $last_id_num = 0;
        if ($res_latest && mysqli_num_rows($res_latest) > 0) {
            $row_latest = mysqli_fetch_assoc($res_latest);
            $last_id_num = intval(substr($row_latest['history_id'], 1)); 
        }
        $new_id_num = $last_id_num + 1;
        $new_history_id = "H" . str_pad($new_id_num, 4, "0", STR_PAD_LEFT);
        
        $change_amount = -1 * $cost; 
        $reason = "gift redeemed";
        
        $sql_history = "INSERT INTO point_history (history_id, student_id, change_amount, date, reason) 
                        VALUES ('$new_history_id', '$student_id', '$change_amount', '$today', '$reason')";
        mysqli_query($conn, $sql_history);

        $sql_stock = "UPDATE reward SET stock = stock - 1 WHERE reward_id = '$id_to_buy'";
        mysqli_query($conn, $sql_stock);
        
        $sql_log = "INSERT INTO redemption (redemption_id, student_id, reward_id, redemption_date, status) 
                    VALUES ('$redemption_id', '$student_id', '$id_to_buy', '$today', 'Approved')";
        
        if(mysqli_query($conn, $sql_log)){
            $message = "🎉 Success! You redeemed this item.";
            $my_balance = $current_balance - $cost;
        } else {
            $message = "Error: " . mysqli_error($conn);
        }

    } else {
        $message = "Error: Not enough points.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reward Redemption</title>
    <style>

        
        body { 
            font-family: Arial, Helvetica, sans-serif;
            margin: 0; 
            background: linear-gradient(120deg,#d1f2eb,#eaf2f8,#f2d7d5); 
            color: #111; 
        }

        .page-wrapper{
            min-height:100%;
            display:flex;
            flex-direction:column;
        }

        .main-content{ flex:1; }
        
        * { box-sizing: border-box; }

        .nav{
            background:white;
            display:flex;
            align-items:center;
            justify-content:space-between;
            padding:14px 20px;
        }
        .nav-left{
            font-weight:900;
            cursor:pointer;
        }
        .nav-center a{
            margin:0 18px;
            text-decoration:none;
            color:#111;
            font-weight:800;
            font-size:14px;
        }

        .nav-center{
            display: flex;
            align-items: center;
            flex-wrap: wrap;
        }
        .nav-right{
            display:flex;
            gap:10px;
            align-items:center;
        }
        .pill{
            border:1px solid #16a34a;
            padding:6px 14px;
            border-radius:20px;
        }

        .container { 
            max-width: 1100px; 
            margin: 40px auto; 
            padding: 0 20px;
            text-align: center;
        }

        .msg { 
            text-align: center;
            color: green;
            font-weight: bold; 
            margin-bottom: 20px; 
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -15px;
        }

        [class*="col-"] {
            padding: 15px;
            width: 100%;
        }

        @media only screen and (min-width: 600px) { .col-s-6 { width: 50%; } }
        @media only screen and (min-width: 768px) { .col-4 { width: 33.33%; } }

        .card { 
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            text-align: center;
            padding: 20px;
            overflow: hidden;
            
            height: 100%;               
            display: flex;              
            flex-direction: column;    
            justify-content: space-between;
        }

        .photo-box { 
            height: 150px;
            background-color: #eaeef3;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px; 
            border-radius:10px;
        }

        .photo-box img { 
            width: 100%;
            height: 100%; 
            object-fit: contain;
            padding: 5px;
        }
        
        button { 
            width: 100%; 
            padding: 10px; 
            background-color: #16a34a; 
            color: white; border: none; 
            border-radius: 6px; 
            cursor: pointer; 
        }

        button:disabled { 
            background-color: #ccc; 
            cursor: not-allowed; 
        }

        .footer{
    background: white;
    margin-top: 60px;
    padding: 20px 40px;

    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 30px;
}

.footer > div{
    flex: 1;
}

.footer h4{
    margin-bottom: 8px;
    font-weight: 900;
    font-size: 14px;
}

.footer a{
    display: block;
    text-decoration: none;
    color: #111;
    margin: 4px 0;
    font-weight: 700;
    font-size: 13px;
}

.social{
    display: flex;
    gap: 10px;
    margin-top: 6px;
}

.social div{
    width: 32px;
    height: 32px;
    background: black;
    border-radius: 6px;
}

@media (max-width: 600px){
    .footer{
        padding: 20px;
        gap: 20px;
    }

    .footer h4{
        font-size: 13px;
    }

    .footer a{
        font-size: 12px;
    }

    .social div{
        width: 28px;
        height: 28px;
    }
}



    </style>
</head>
<body>

<div class="page-wrapper">

    <div class="nav"> 
        <div class="nav-left">
            <a href="Home.php" style="text-decoration:none; color:#111; font-weight:900;">EcoEarn</a>
        </div>

        <div class="nav-center">
            <a href="Home.php">EVENT</a>
            <a href="redeem_reward.php" style="color:green;">REWARD REDEMPTION</a>
            <a href="view_challenge.php">CHALLENGE</a>
        </div>

        <div class="nav-right">
            <span><?php echo $my_name; ?></span>
            <div class="pill"><?php echo $my_balance; ?> pts</div>
        </div>
    </div>

    <div class="main-content">
        <div class="container">
            <h2>Reward Catalog</h2>
            <div class="msg"><?php echo $message; ?></div>

            <div class="row">
                <?php
                $sql_rewards = "SELECT reward.*, discount.discount_percentage 
                    FROM reward 
                    LEFT JOIN discount ON reward.discount_id = discount.discount_id";
                $result_rewards = mysqli_query($conn, $sql_rewards); 

                if (!$result_rewards) {
                    echo "<p>Database Error: " . mysqli_error($conn) . "</p>";
                } elseif (mysqli_num_rows($result_rewards) > 0) {
                    while ($row = mysqli_fetch_assoc($result_rewards)) { 
                        if (!empty($row['image'])) {
                            $img_url = $row['image']; 
                        } else {
                            $img_url = "https://placehold.co/300x200/16a34a/FFF?text=No+Image";
                        }
                ?>
                    <div class="col-12 col-s-6 col-4">
                        <div class="card">
                            <div class="photo-box">
                                <img src="<?php echo $img_url; ?>" alt="Reward">
                            </div>
                            <h3><?php echo $row['NAME']; ?></h3>

                            <?php 
                            $actual_cost = $row['point_cost'];
                            $final_cost = $actual_cost; 

                            if (!empty($row['discount_percentage'])) {
                                $discount_amount = $actual_cost * ($row['discount_percentage'] / 100);
                                $final_cost = ceil($actual_cost - $discount_amount);
                                
                                echo '<div>';
                                echo '<span style="color:#999; text-decoration: line-through; margin-right:5px; font-size: 0.9em;">' . $actual_cost . ' Pts</span>';
                                echo '<span style="color:#e11d48; font-weight:bold; font-size: 1.1em;">🔥 ' . $final_cost . ' Pts</span>';
                                echo '</div>';
                            } else {
                                echo '<p style="color:#16a34a; font-weight:bold">' . $actual_cost . ' Pts</p>';
                            }
                            ?>
                            <p style="font-size:0.8em; color:#666">Stock: <?php echo $row['stock']; ?></p>
                            
                            <?php if ($row['stock'] > 0) { ?>
                                <form method="POST">
                                    <input type="hidden" name="reward_id" value="<?php echo $row['reward_id']; ?>">
                                    <input type="hidden" name="point_cost" value="<?php echo $final_cost; ?>">
                                    <button type="submit" name="redeem_btn">Redeem</button>
                                </form>
                            <?php } else { ?>
                                <button disabled>Out of Stock</button>
                            <?php } ?>
                        </div>
                    </div>
                <?php 
                    } 
                } else {
                    echo "<p>No rewards found.</p>";
                }
                ?>
            </div> 
        </div> 
        </div> 
            <div class="footer">
        <div>
            <h4>Help</h4>
            <a href="#">FAQs</a>
            <a href="#">About Us</a>
            <a href="#">Contact</a>
        </div>

        <div>
            <h4>Info</h4>
            <a href="#">News</a>
            <a href="#">Policy</a>
            <a href="#">Support</a>
        </div>

        <div>
            <h4>Connect With Us</h4>
            <div class="social">
            <div></div> <div></div> <div></div> <div></div>
            </div>
        </div>
    </div>

</div> </body>
</html>
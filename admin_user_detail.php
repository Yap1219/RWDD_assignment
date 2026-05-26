<?php
    include "admin_db.php";

    $role = $_GET['role'] ?? '';
    $id   = $_GET['id'] ?? '';

    if(!$role || !$id){
        die("Invalid access");
    }

    $table = $role;
    $key   = $role . "_id";

    $sql = "SELECT * FROM $table WHERE $key='$id'";
    $result = $conn->query($sql);
    $data = $result->fetch_assoc();

    if(!$data){
        die("User not found");
    }
?>

<html>
    <head>
    <title>Manage <?= ucfirst($role) ?></title>

    <style>
        body{
            background: linear-gradient(120deg,#d1f2eb,#eaf2f8,#f2d7d5);
            font-family: Arial;
        }
        .box{
            width:60%;
            margin:40px auto;
            padding:25px;
            background:#222;
            color:white;
            border-radius:10px;
        }
        input,select,textarea{
            width:100%;
            padding:10px;
            margin-top:6px;
            border-radius:6px;
            border:0;
        }
        .btn-save{
            background:#27ae60;
            padding:10px 18px;
            color:white;
            border-radius:6px;
            border:0;
        }
        .btn-cancel{
            background:#c0392b;
            padding:10px 18px;
            color:white;
            border-radius:6px;
            border:0;
        }
    </style>

    </head>

    <body>

        <div class="box">

        <h2>Manage <?= ucfirst($role) ?></h2>

            <form action="admin_user_update.php" method="POST" enctype="multipart/form-data">

                <input type="hidden" name="role" value="<?= $role ?>">
                <input type="hidden" name="id" value="<?= $id ?>">

                <label>ID</label>
                <input type="text" value="<?= $data[$key] ?>" disabled>

                <label>Name</label>
                <input type="text" name="name" value="<?= $data['name'] ?>">

                <label>Email</label>
                <input type="text" name="email" value="<?= $data['email'] ?>">

                <label>Password</label>
                <input type="text" name="password" value="<?= $data['password'] ?>">


                <?php if($role == "student") { ?>

                <label>Date of Birth</label>
                <input type="date" name="date_of_birth" value="<?= $data['date_of_birth'] ?>">

                <label>Gender</label>
                <select name="gender">
                <option <?= ($data['gender']=="Male")?"selected":"" ?> value="Male">Male</option>
                <option <?= ($data['gender']=="Female")?"selected":"" ?> value="Female">Female</option>
                </select>

                <label>Address</label>
                <textarea name="address"><?= $data['address'] ?></textarea>

                <label>Total Points</label>
                <input type="number" name="total_points" value="<?= $data['total_points'] ?>">

                <label>Photo</label>
                <input type="file" name="photo">

                <?php } ?>

                <br><br>

                <button class="btn-save" type="submit">Save</button>
                <a href="admin_manage_user.php?role=<?= $role ?>" class="btn-cancel">Cancel</a>

            </form>

        </div>

    </body>
</html>

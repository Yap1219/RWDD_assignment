<?php
include ('vendor_db.php'); 

$search = $_GET['search'] ?? '';

$sql = "SELECT * FROM Reward WHERE 1";

if (!empty($search)) {
    $sql .= " AND name LIKE '%$search%'";
}

$result = mysqli_query($conn, $sql);

$countActive = mysqli_fetch_assoc(mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM Reward WHERE stock > 0"
))['total'];

$countExpired = mysqli_fetch_assoc(mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM Reward WHERE stock = 0"
))['total'];

$countDisabled = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Gift</title>
<link rel="stylesheet" href="AmanageGift.css">
</head>

<body>

<header class="page-header">
    <a href="AIndex.php" class="back-btn">←</a>
    <h1>Manage Gift</h1>
</header>

<main class="content">

    <section class="summary">
        <div class="summary-card">
            <p class="number"><?php echo $countActive; ?></p>
            <p>Active</p>
        </div>

        <div class="summary-card">
            <p class="number"><?php echo $countExpired; ?></p>
            <p>Expired</p>
        </div>

        <div class="summary-card">
            <p class="number"><?php echo $countDisabled; ?></p>
            <p>Disabled</p>
        </div>
    </section>

    <section class="action-bar">
        <button class="add-btn" onclick="openModal()">+ Add Gift</button>

        <form method="GET" class="search-box">
            <input 
                type="text" 
                name="search"
                placeholder="Search gift..."
                value="<?php echo htmlspecialchars($search); ?>"
            >
            <button type="submit">🔍</button>
        </form>
    </section>

    <section class="table-card">
        <table>
            <thead>
                <tr>
                    <th>Gift</th>
                    <th>Point</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td class="partner">
                    <img src="<?php echo $row['image']; ?>" class="gift-img">
                    <?php echo $row['NAME']; ?>
                </td>

                <td><?php echo $row['point_cost']; ?> point</td>

                <td>
                    <?php 
                    if ($row['stock'] > 0) {
                        echo $row['stock'] . " left";
                    } else {
                        echo "<span style='color:red;'>Out of stock</span>";
                    }
                    ?>
                </td>

                <td>
                    <span class="status <?php echo ($row['stock'] > 0 ? 'active' : 'expired'); ?>">
                        <?php echo ($row['stock'] > 0 ? 'Active' : 'Expired'); ?>
                    </span>
                </td>

                <td>
                    <a href="AEditGift.php?id=<?php echo $row['reward_id']; ?>" class="edit">Edit</a>
                </td>
            </tr>
            <?php } ?>
            </tbody>
        </table>
    </section>

</main>

<div id="giftModal" class="modal">
    <div class="modal-content">

        <h2>Add Collaboration Gift</h2>

        <form action="Aadd_gift.php" method="POST" enctype="multipart/form-data">

            <label>Gift Image</label>
            <input type="file" name="image" class="input" required>

            <label>Gift Name</label>
            <input type="text" name="reward_name" class="input" required>

            <label>Points</label>
            <input type="number" name="points" class="input" required>

            <label>Stock</label>
            <input type="number" name="stock" class="input" min="0" required>

            <label>Status</label>
            <select name="status" class="input">
                <option value="Active">Active</option>
                <option value="Expired">Expired</option>
                <option value="Disabled">Disabled</option>
            </select>

            <div class="modal-buttons">
                <button type="submit" class="save-btn">Save</button>
                <button type="button" class="cancel-btn" onclick="closeModal()">Cancel</button>
            </div>

        </form>

    </div>
</div>

<script>
function openModal() {
    const modal = document.getElementById("giftModal");
    modal.style.display = "flex";

    setTimeout(() => {
        document.querySelector(".modal-content").classList.add("show");
    }, 10);
}

function closeModal() {
    document.querySelector(".modal-content").classList.remove("show");

    setTimeout(() => {
        document.getElementById("giftModal").style.display = "none";
    }, 200);
}
</script>

</body>
</html>

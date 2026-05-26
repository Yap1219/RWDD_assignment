<?php
require_once __DIR__ . "/db.php";

function calcProgress($start, $end) {
  $today = new DateTime("today");
  $s = new DateTime($start);
  $e = new DateTime($end);

  if ($today < $s) return 0;
  if ($today > $e) return 100;

  $total = $s->diff($e)->days;
  if ($total <= 0) return 100;

  $passed = $s->diff($today)->days;
  return (int) round(($passed / $total) * 100);
}

$id = isset($_GET["event_id"]) ? trim($_GET["event_id"]) : "";
if ($id === "") die("Invalid event.");

$sql = "SELECT * FROM `event` WHERE event_id = :id LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(":id", $id, PDO::PARAM_STR);
$stmt->execute();

$e = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$e) die("Event not found.");

$progress = calcProgress($e["start_date"], $e["end_date"]);

$today = new DateTime("today");
$start = new DateTime($e["start_date"]);
$end   = new DateTime($e["end_date"]);

if ($today < $start) {
  $status = "Upcoming";
  $statusClass = "upcoming";
} elseif ($today > $end) {
  $status = "Ended";
  $statusClass = "ended";
} else {
  $status = "Ongoing";
  $statusClass = "ongoing";
}

$photo = $e["photo_path"] ?? "";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Event Detail</title>

<style>
*{ box-sizing:border-box; }
body{
  margin:0;
  font-family: Arial, Helvetica, sans-serif;
  min-height:100vh;
  background: linear-gradient(135deg,#89CFF0,#C1E1C1,#FDE2E4,#BEE7E8);
  background-size:300% 300%;
  animation: bgMove 20s ease infinite;
}
@keyframes bgMove{
  0%{ background-position: 0% 50%; }
  50%{ background-position: 100% 50%; }
  100%{ background-position: 0% 50%; }
}

.nav{
  background:#fff;
  padding:14px 22px;
  display:flex;
  align-items:center;
  justify-content:space-between;
  box-shadow:0 8px 20px rgba(0,0,0,.15);
}
.back{
  text-decoration:none;
  font-weight:900;
  color:#111;
}

.main{
  display:flex;
  justify-content:center;
  padding:60px 20px;
}
.detail-card{
  width:100%;
  max-width:720px;
  padding:28px;
  border-radius:22px;

  background: rgba(255,255,255,.28);
  backdrop-filter: blur(16px);
  -webkit-backdrop-filter: blur(16px);

  border:1px solid rgba(255,255,255,.4);
  box-shadow:
    0 30px 60px rgba(0,0,0,.25),
    inset 0 1px 0 rgba(255,255,255,.4);
}

.enter{
  opacity: 0;
  transform: translateY(14px) scale(0.98);
  animation: cardEnter .55s ease-out forwards;
}
@keyframes cardEnter{
  to{ opacity: 1; transform: translateY(0) scale(1); }
}

.detail-header{
  display:flex;
  align-items:center;
  justify-content:space-between;
  gap:12px;
  margin-bottom:12px;
}
.detail-title{
  font-size:22px;
  font-weight:900;
  color:#111;
  margin:0;
  flex: 1;
}

.detail-actions{
  display:flex;
  align-items:center;
  gap:10px;
  flex-wrap:wrap;
}
.status{
  padding:6px 14px;
  border-radius:999px;
  font-size:12px;
  font-weight:900;
  letter-spacing:.5px;
  white-space:nowrap;
}
.status.upcoming{ background:#f1c40f; color:#111; }
.status.ongoing{
  background: linear-gradient(135deg,#2ecc71,#27ae60);
  color:#fff;
  box-shadow:0 6px 14px rgba(46,204,113,.45);
  animation:pulse 1.8s infinite;
}
.status.ended{ background:#e74c3c; color:#fff; }
@keyframes pulse{
  0%{ box-shadow:0 0 0 0 rgba(46,204,113,.55); }
  70%{ box-shadow:0 0 0 10px rgba(46,204,113,0); }
  100%{ box-shadow:0 0 0 0 rgba(46,204,113,0); }
}

.actionBtn{
  border:0;
  cursor:pointer;
  text-decoration:none;

  padding:6px 14px;
  border-radius:999px;

  font-size:12px;
  font-weight:900;

  background: rgba(255,255,255,.35);
  color:#111;

  border:1px solid rgba(255,255,255,.5);
  backdrop-filter: blur(8px);

  transition: transform .18s ease, background .18s ease, box-shadow .18s ease;
}
.actionBtn:hover{
  transform: translateY(-2px);
  background: rgba(255,255,255,.55);
  box-shadow: 0 6px 14px rgba(0,0,0,.18);
}
.actionBtn.edit{ color:#2c7be5; }
.actionBtn.delete{ color:#e74c3c; }

.photoWrap{
  margin: 14px 0 18px;
  border-radius: 18px;
  overflow: hidden;
  border: 1px solid rgba(255,255,255,.45);
  box-shadow: 0 18px 40px rgba(0,0,0,.18);
}
.eventPhoto{
  width:100%;
  height:260px;
  display:block;
  object-fit:cover;
}

.meta{
  display:flex;
  flex-wrap:wrap;
  gap:14px;
  font-size:13px;
  font-weight:800;
  margin-bottom:18px;
}
.badge{
  padding:6px 14px;
  border-radius:999px;
  background: rgba(0,0,0,.65);
  color:#fff;
}

.progress-wrap{
  height:14px;
  background: rgba(255,255,255,.7);
  border-radius:999px;
  overflow:hidden;
  border:2px solid rgba(0,0,0,.6);
  margin-bottom:20px;
}
.progress-bar{
  height:100%;
  width: <?= (int)$progress ?>%;
  background:#2ecc71;
}

.desc{
  font-size:14px;
  font-weight:700;
  line-height:1.6;
  color:rgba(58, 47, 47, 0.75);
  white-space:pre-wrap;
}

@media(max-width:600px){
  .detail-card{ padding:22px; }
  .detail-title{ font-size:18px; }
  .eventPhoto{ height:200px; }
  .detail-header{
    flex-direction:column;
    align-items:flex-start;
    gap:10px;
  }
}
</style>
</head>

<body>

<header class="nav">
  <a class="back" href="/Assignment/event_review_center.php">← Back</a>
  <div><strong>EVENT DETAIL</strong></div>
  <div></div>
</header>

<main class="main">
  <div class="detail-card enter">

    <div class="detail-header">
      <h1 class="detail-title"><?= htmlspecialchars($e["name"]) ?></h1>

      <div class="detail-actions">
        <span class="status <?= $statusClass ?>"><?= $status ?></span>

        <a class="actionBtn edit"
           href="edit_event.php?event_id=<?= urlencode($e["event_id"]) ?>">
           Edit
        </a>

        <button class="actionBtn delete"
                id="deleteBtn"
                data-id="<?= htmlspecialchars($e["event_id"]) ?>">
          Delete
        </button>
      </div>
    </div>

    <?php if (!empty($photo)): ?>
      <div class="photoWrap">
        <img class="eventPhoto" src="<?= htmlspecialchars($photo) ?>" alt="Event Photo">
      </div>
    <?php endif; ?>

    <div class="meta">
      <div class="badge">Start: <?= htmlspecialchars($e["start_date"]) ?></div>
      <div class="badge">End: <?= htmlspecialchars($e["end_date"]) ?></div>
      <div class="badge">Reward: <?= (int)$e["reward_point"] ?> pts</div>
      <div class="badge">ID: #<?= htmlspecialchars($e["event_id"]) ?></div>
    </div>

    <div class="progress-wrap">
      <div class="progress-bar"></div>
    </div>

    <div class="desc">
      <?= nl2br(htmlspecialchars($e["description"])) ?>
    </div>

  </div>
</main>

<script>
  const delBtn = document.getElementById("deleteBtn");
  if (delBtn) {
    delBtn.addEventListener("click", () => {
      const id = delBtn.dataset.id;
      if (!id) return;

      const ok = confirm("Are you sure you want to delete this event?\nThis action cannot be undone.");
      if (ok) {
        window.location.href = "delete_event.php?event_id=" + encodeURIComponent(id);
      }
    });
  }
</script>

</body>
</html>

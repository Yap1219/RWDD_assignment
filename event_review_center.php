<?php
require_once __DIR__ . "/db.php";

function calcProgress($start, $end) {
  try {
    $today = new DateTime("today");
    $s = new DateTime($start);
    $e = new DateTime($end);

    if ($today < $s) return 0;
    if ($today > $e) return 100;

    $total = $s->diff($e)->days;
    if ($total <= 0) return 100;

    $passed = $s->diff($today)->days;
    $pct = (int) round(($passed / $total) * 100);
    return max(0, min(100, $pct));
  } catch (Exception $ex) {
    return 0;
  }
}

$limit = 6;
$sql = "SELECT event_id, NAME, description, start_date, end_date, reward_point
        FROM event
        ORDER BY start_date ASC
        LIMIT :limit";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(":limit", $limit, PDO::PARAM_INT);
$stmt->execute();
$events = $stmt->fetchAll();
if (!$events) $events = [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Event Review Center</title>
  <link rel="stylesheet" href="event_review_center.css" />
</head>
<body>

<div class="page">

  <header class="nav">
    <div class="nav__left">
      <a class="homeLink" href="admin_home.php">Eco Earn</a>
    </div>

    <div class="nav__title">EVENT REVIEW CENTER</div>

    <div class="nav__right">
      <div class="avatar" title="Admin"></div>

      <button class="burger" id="burgerBtn" aria-label="Open menu" aria-expanded="false">
        <span></span><span></span><span></span>
      </button>
    </div>

    <div class="mobileMenu" id="mobileMenu">
      <a href="admin_home.php">HOME</a>
      <a href="create_event.php">CREATE</a>
    </div>
  </header>

  <div class="content">
    <aside class="sidebar">
      <a href="admin_home.php">HOME</a>
      <a href="create_event.php">CREATE</a>
    </aside>

    <main class="main">
      <div class="card-grid" id="eventGrid">
        <?php if (count($events) === 0): ?>
          <div class="empty">No events found in database.</div>
        <?php endif; ?>

        <?php foreach ($events as $e):
          $id = $e["event_id"];
          $name = htmlspecialchars($e["name"] ?? "");
          $desc = htmlspecialchars($e["description"] ?? "");
          $start = htmlspecialchars($e["start_date"] ?? "");
          $end = htmlspecialchars($e["end_date"] ?? "");
          $reward = (int)($e["reward_point"] ?? 0);

          $shortDesc = mb_strlen($desc) > 80 ? mb_substr($desc, 0, 80) . "..." : $desc;
          $progress = calcProgress($e["start_date"], $e["end_date"]);
        ?>
          <a class="event-card" href="event_detail.php?event_id=<?= urlencode($id) ?>">
            <div class="event-title"><?= $name ?></div>
            <div class="event-date"><?= $start ?> ~ <?= $end ?></div>
            <div class="event-desc"><?= $shortDesc ?></div>

            <div class="progress-wrap" aria-label="Progress <?= $progress ?>%">
              <div class="progress-bar" style="width: <?= $progress ?>%"></div>
            </div>

            <div class="event-meta">
              <span class="reward">Reward: <?= $reward ?> pts</span>
              <span class="pid">#<?= $id ?></span>
            </div>
        </a>
        <?php endforeach; ?>
        </div>
    </main>

  </div>


  <footer class="footer">
    <div class="footer__col">
      <a href="#">FAQs</a>
      <a href="#">About us</a>
      <a href="#">Contact Us</a>
    </div>
    <div class="footer__col">
      <a href="#">News</a>
      <a href="#">Policy</a>
      <a href="#">Support</a>
    </div>
    <div class="footer_col footer_social">
      <div class="footer__title">Connect With Us</div>
      <div class="social">
        <a class="social__icon" href="#" aria-label="Instagram"></a>
        <a class="social__icon" href="#" aria-label="TikTok"></a>
        <a class="social__icon" href="#" aria-label="X"></a>
        <a class="social__icon" href="#" aria-label="Other"></a>
      </div>
    </div>
  </footer>
</div>

<div class="mobile-bottom">
  <a href="create_event.php">CREATE</a>
</div>


<script>
  window._EVENT_OFFSET_ = <?= count($events) ?>;
</script>
<script src="event_review_center.js"></script>
</body>
</html>
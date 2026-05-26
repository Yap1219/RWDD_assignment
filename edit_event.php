<?php
require_once __DIR__ . "/db.php";

$id = trim($_GET["event_id"] ?? "");
if ($id === "") die("Invalid event id.");

$sql = "SELECT event_id, name, description, start_date, end_date, reward_point, photo_path
        FROM event
        WHERE event_id = :id
        LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(":id", $id, PDO::PARAM_STR);
$stmt->execute();
$e = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$e) die("Event not found.");

function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, "UTF-8"); }

$photoPath = $e["photo_path"] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Edit Event</title>
  <link rel="stylesheet" href="create_event.css">
</head>
<body>

<div class="page">

  <header class="nav">
    <a class="homeLink" href="event_review_center.php">← BACK</a>
    <div class="nav__title">EDIT EVENT</div>
    <div class="nav__right">
      <div class="avatar" title="Admin"></div>
    </div>
  </header>

  <main class="main">
    <div class="wrap">

      <form
        id="editEventForm"
        class="glass form"
        method="post"
        action="edit_event_submit.php"
        enctype="multipart/form-data"
      >
        <h2 class="sectionTitle">Event Info</h2>

        <input type="hidden" name="event_id" value="<?= h($e["event_id"]) ?>">
        <input type="hidden" name="old_photo_path" value="<?= h($photoPath ?? "") ?>">

        <div class="field">
          <label>Event Name</label>
          <input type="text" name="name" value="<?= h($e["name"]) ?>" required>
        </div>

        <div class="row">
          <div class="field">
            <label>Start Date</label>
            <input type="date" name="start_date" value="<?= h($e["start_date"]) ?>" required>
          </div>
          <div class="field">
            <label>End Date</label>
            <input type="date" name="end_date" value="<?= h($e["end_date"]) ?>" required>
          </div>
        </div>

        <div class="field">
          <label>Reward Point</label>
          <input type="number" name="reward_point" min="0" value="<?= (int)$e["reward_point"] ?>" required>
        </div>

        <div class="field">
          <label>Description</label>
          <textarea name="description" rows="6" required><?= h($e["description"]) ?></textarea>
        </div>

        <div class="field">
          <label>Replace Photo (Optional)</label>
          <input id="photoInput" type="file" name="photo" accept="image/*">
        </div>

        <div class="actions">
          <button class="btn btnPrimary" type="submit">SAVE</button>
          <a class="btn btnGhost" href="event_detail.php?event_id=<?= urlencode($e["event_id"]) ?>">CANCEL</a>
        </div>
      </form>

      <div class="glass preview">
        <div class="previewHead">
          <h2 class="sectionTitle">Photo Preview</h2>
        </div>

        <div class="photoBox" id="photoBox">
          <div class="photoHint" id="photoHint" style="<?= !empty($photoPath) ? 'display:none;' : '' ?>">
            No image selected
          </div>

          <img
            id="photoPreview"
            alt="Preview"
            style="<?= !empty($photoPath) ? '' : 'display:none;' ?>"
            src="<?= !empty($photoPath) ? h($photoPath) : '' ?>"
          />
        </div>

        <div class="tips">
          Tip: If you don’t choose a new photo, the old photo will be kept.
        </div>
      </div>

    </div>
  </main>

</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
  const input = document.getElementById("photoInput");
  const img = document.getElementById("photoPreview");
  const hint = document.getElementById("photoHint");
  if (!input || !img) return;

  input.addEventListener("change", () => {
    const file = input.files && input.files[0];
    if (!file) return;

    if (!file.type.startsWith("image/")) {
      alert("Please choose an image file.");
      input.value = "";
      return;
    }

    img.src = URL.createObjectURL(file);
    img.style.display = "block";
    if (hint) hint.style.display = "none";
  });
});
</script>

</body>
</html>
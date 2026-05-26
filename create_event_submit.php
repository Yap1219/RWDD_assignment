<?php
require_once __DIR__ . "/db.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  header("Location: create_event.php");
  exit;
}

$name         = trim($_POST["name"] ?? "");
$description  = trim($_POST["description"] ?? "");
$start_date   = $_POST["start_date"] ?? "";
$end_date     = $_POST["end_date"] ?? "";
$reward_point = isset($_POST["reward_point"]) ? (int)$_POST["reward_point"] : 0;

$adminID = $_SESSION["admin_id"] ?? null;

if ($name === "" || $description === "" || $start_date === "" || $end_date === "") {
  die("Missing required fields.");
}

$datePart = date("Ymd");
$prefix = "EVT{$datePart}_";

$stmt = $pdo->prepare("
  SELECT MAX(CAST(SUBSTRING_INDEX(event_id, '_', -1) AS UNSIGNED))
  FROM event
  WHERE event_id LIKE :p
");
$stmt->execute([":p" => $prefix . "%"]);

$maxSeq = (int)$stmt->fetchColumn();
$nextSeq = $maxSeq + 1;

$eventID = $prefix . str_pad((string)$nextSeq, 4, "0", STR_PAD_LEFT);

$photoPath = null;

$uploadDir = __DIR__ . "/uploads/";
$uploadWebPrefix = "uploads/";

if (!is_dir($uploadDir)) {
  mkdir($uploadDir, 0777, true);
}

if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] !== UPLOAD_ERR_NO_FILE) {

  if ($_FILES["photo"]["error"] !== UPLOAD_ERR_OK) {
    die("Upload error: " . $_FILES["photo"]["error"]);
  }

  $maxSize = 3 * 1024 * 1024;
  if ($_FILES["photo"]["size"] > $maxSize) {
    die("Image too large. Max 3MB.");
  }

  $originalName = $_FILES["photo"]["name"];
  $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

  $allowed = ["jpg", "jpeg", "png", "webp"];
  if (!in_array($ext, $allowed)) {
    die("Invalid image format. Allowed: JPG, PNG, WEBP.");
  }

  $newFileName = "event_" . $eventID . "" . date("His") . "" . bin2hex(random_bytes(4)) . "." . $ext;
  $targetFullPath = $uploadDir . $newFileName;

  if (!move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFullPath)) {
    die("Failed to move uploaded file.");
  }

  $photoPath = $uploadWebPrefix . $newFileName;
}


$sql = "
INSERT INTO event
(event_id, name, description, start_date, end_date, reward_point, photo_path, admin_id)
VALUES
(:event_id, :name, :description, :start_date, :end_date, :reward_point, :photo_path, :admin_id)
";

$stmt = $pdo->prepare($sql);

$stmt->execute([
  ":event_id"     => $eventID,
  ":name"         => $name,
  ":description"  => $description,
  ":start_date"   => $start_date,
  ":end_date"     => $end_date,
  ":reward_point" => $reward_point,
  ":photo_path"   => $photoPath,
  ":admin_id"     => $adminID
]);

header("Location: event_review_center.php");
exit;
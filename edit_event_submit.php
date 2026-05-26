<?php
require_once __DIR__ . "/db.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  header("Location: event_review_center.php");
  exit;
}

$event_id    = trim($_POST["event_id"] ?? ""); 
$name        = trim($_POST["name"] ?? "");
$description = trim($_POST["description"] ?? "");
$start_date  = $_POST["start_date"] ?? "";
$end_date    = $_POST["end_date"] ?? "";
$reward      = isset($_POST["reward_point"]) ? (int)$_POST["reward_point"] : 0;

$oldPhoto = trim($_POST["old_photo_path"] ?? ""); 

if ($event_id === "") die("Invalid event id.");
if ($name === "" || $description === "" || $start_date === "" || $end_date === "") {
  die("Missing required fields.");
}


$newPhotoPath = ($oldPhoto !== "") ? $oldPhoto : null;

if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] !== UPLOAD_ERR_NO_FILE) {

  if ($_FILES["photo"]["error"] !== UPLOAD_ERR_OK) {
    die("Upload failed. Error code: " . $_FILES["photo"]["error"]);
  }

  $finfo = new finfo(FILEINFO_MIME_TYPE);
  $mime  = $finfo->file($_FILES["photo"]["tmp_name"]);

  $allowed = [
    "image/jpeg" => "jpg",
    "image/png"  => "png",
    "image/webp" => "webp"
  ];

  if (!isset($allowed[$mime])) {
    die("Only JPG/PNG/WEBP allowed.");
  }

  $uploadDir = __DIR__ . "/uploads/";
  if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

  $ext = $allowed[$mime];

  do {
    $fileName = "event_" . $event_id . "" . date("Ymd_His") . "" . bin2hex(random_bytes(4)) . "." . $ext;
    $destAbs  = $uploadDir . $fileName;
  } while (file_exists($destAbs));

  if (!move_uploaded_file($_FILES["photo"]["tmp_name"], $destAbs)) {
    die("Failed to save uploaded file.");
  }

  $newPhotoPath = "uploads/" . $fileName;

  if ($oldPhoto !== "" && strpos($oldPhoto, "uploads/") === 0) {
    $oldAbs = __DIR__ . "/" . $oldPhoto;
    if (is_file($oldAbs)) @unlink($oldAbs);
  }
}


$sql = "
UPDATE event
SET
  name = :name,
  description = :description,
  start_date = :start_date,
  end_date = :end_date,
  reward_point = :reward_point,
  photo_path = :photo_path
WHERE event_id = :event_id
LIMIT 1
";

$stmt = $pdo->prepare($sql);
$stmt->execute([
  ":name"         => $name,
  ":description"  => $description,
  ":start_date"   => $start_date,
  ":end_date"     => $end_date,
  ":reward_point" => $reward,
  ":photo_path"   => $newPhotoPath,
  ":event_id"     => $event_id
]);

header("Location: event_detail.php?event_id=" . urlencode($event_id));
exit;
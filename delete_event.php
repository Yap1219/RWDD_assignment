<?php
require_once __DIR__ . "/db.php";

$id = trim($_GET["event_id"] ?? "");
if ($id === "") {
  die("Invalid event id.");
}

$sql = "SELECT photo_path FROM event WHERE event_id = :id LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(":id", $id, PDO::PARAM_STR);
$stmt->execute();
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
  die("Event not found.");
}

$sql = "DELETE FROM event WHERE event_id = :id LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(":id", $id, PDO::PARAM_STR);
$stmt->execute();

if (!empty($event["photo_path"]) && str_starts_with($event["photo_path"], "uploads/")) {
  $filePath = __DIR__ . "/" . $event["photo_path"];
  if (is_file($filePath)) {
    @unlink($filePath);
  }
}


header("Location: event_review_center.php");
exit;
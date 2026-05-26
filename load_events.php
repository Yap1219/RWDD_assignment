<?php
require_once __DIR__ . "/db.php";

$limit  = 6;
$offset = isset($_GET["offset"]) ? (int)$_GET["offset"] : 0;

$sql = "SELECT event_id, name, description, start_date, end_date, reward_point
        FROM Event
        ORDER BY start_date ASC
        LIMIT :limit OFFSET :offset";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(":limit", $limit, PDO::PARAM_INT);
$stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
$stmt->execute();

$events = $stmt->fetchAll();
if (!$events) $events = [];

header("Content-Type: application/json; charset=utf-8");
echo json_encode($events);
?>
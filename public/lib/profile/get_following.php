<?php
require '../../../src/DBconnect.php';

$userId = $_GET['user_id'] ?: null;
if (!$userId) {
    echo json_encode([]);
    exit();
}

$stmt = $connection->prepare("SELECT u.username FROM active_followers f JOIN users u ON f.follower_id = u.user_id WHERE f.following_id = ?");
$stmt->execute([$userId]);
$following = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($following);

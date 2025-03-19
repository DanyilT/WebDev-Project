<?php
require '../../src/DBconnect.php';

$userId = $_GET['user_id'] ?: null;
if (!$userId) {
    echo json_encode([]);
    exit();
}

$stmt = $connection->prepare("SELECT u.username FROM followers f JOIN users u ON f.following_id = u.user_id WHERE f.follower_id = ? AND f.is_following = TRUE");
$stmt->execute([$userId]);
$following = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($following);

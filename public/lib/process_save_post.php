<?php
session_start();
require '../../src/DBconnect.php';

if (!isset($_SESSION['username'])) {
    header('Location: ../account.php#login');
    exit();
}

$username = $_SESSION['username'];
$title = $_POST['title'];
$content = $_POST['content'];
$media = null;

if (savePost($username, $title, $content, null, $connection)) {
    echo "Post saved successfully.";
    header('Location: ../profile.php?username=' . $username);
} else {
    echo "Failed to save post.";
}
exit();

function savePost($username, $title, $content, $media, $connection) {
    $stmt = $connection->prepare("SELECT user_id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $userId = $stmt->fetchColumn();

    if (!$userId) {
        echo "User not found.";
        return false;
    }

    // Insert post into the database
    $stmt = $connection->prepare("INSERT INTO posts (user_id, title, content, media) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$userId, $title, $content, $media]);

}

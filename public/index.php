<?php

use Models\Users\UserProfile;

session_start();
require '../src/DBconnect.php';
require '../src/Models/UserProfile.php';

$userProfile = new UserProfile($connection);
$posts = [];

if (isset($_SESSION['username'])) {
    $user = $userProfile->getUserProfile($_SESSION['username']);
    $following = $userProfile->getFollowings($user['user_id']);

    foreach ($following as $followed) {
        $userPosts = $userProfile->getUserPosts($followed['user_id'], 5);
        $posts = array_merge($posts, $userPosts);
    }
} else {
    // Fetch most recent posts from all users if not logged in
    $stmt = $connection->prepare("SELECT p.post_id, u.username, p.title, p.content, p.likes, p.created_at FROM active_posts p JOIN active_users u ON p.user_id = u.user_id ORDER BY p.created_at DESC LIMIT 5");
    $stmt->execute();
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<?php
$styles = '<link rel="stylesheet" href="css/pages/index.css">';
include 'layout/header.php';
?>

<main>
    <section class="posts">
        <h2>Recent Posts</h2>
        <?php foreach ($posts as $post): ?>
            <article class="post">
                <p class="username">@<?php echo htmlspecialchars($post['username']); ?></p>
                <h3 class="title"><?php echo htmlspecialchars($post['title']); ?></h3>
                <hr>
                <p class="body"><?php echo htmlspecialchars($post['content']); ?></p>
<!--                <img class="media" src="--><?php //echo htmlspecialchars($post['media']); ?><!--" alt="Post Media">-->
                <span class="likes">Likes: <?php echo htmlspecialchars($post['likes'] ?: 0); ?></span>
                <p class="date">Date: <?php echo htmlspecialchars($post['created_at']); ?></p>
            </article>
        <?php endforeach; ?>
    </section>
</main>

<?php include 'layout/footer.php'; ?>

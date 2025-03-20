<?php
session_start();
require 'lib/profile/profile_data.php';

$posts = [];

if (isset($_SESSION['username'])) {
    // TODO: fix - display the same post for all other users' followed posts
    $user = getUserProfile($connection, $_SESSION['username']);
    $userId = $user['user_id'];

    $stmt = $connection->prepare("SELECT u.user_id, u.username FROM active_followers f JOIN users u ON f.following_id = u.user_id WHERE f.follower_id = ?");
    $stmt->execute([$userId]);
    $following = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($following as $followedUser) {
        $followedUserId = $followedUser['user_id'];
        $followedUsername = $followedUser['username'];
        $userPosts = getUserPosts($connection, $followedUserId);
        foreach ($userPosts as &$post) {
            $post['username'] = $followedUsername;
        }
        $posts = array_merge($posts, $userPosts);
    }
} else {
    // Fetch most recent posts from all users if not logged in
    $stmt = $connection->prepare("SELECT p.post_id, u.username, p.title, p.content, p.likes, p.created_at FROM posts p JOIN users u ON p.user_id = u.user_id ORDER BY p.created_at DESC LIMIT 5");
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

<?php

use Models\User\UserRead;

// Assuming DBconnect.php is required in parent file to establish a database connection
// require '../src/Database/DBconnect.php';
// Require the necessary classes if not already loaded
class_exists("Models\User\UserRead") or require_once '../src/Models/UserRead.php';

// Get the username from the URL (GET request)
$username = preg_replace('/[^a-z0-9_]/', '', strtolower(trim($_GET['username'])));
if (!$username) {
    header('Location: index.php');
    exit();
}

// Fetch user's profile information (assuming $connection is a valid PDO connection -- passed from DBconnect.php)
$userRead = new UserRead($connection);
$userProfile = $userRead->getUserProfile($username);

// Check if the user exists
if (!$userProfile) {
    echo "User not found.";
    echo "<a href='/'>Go back</a>";
    exit();
}

// Fetch additional user information
$userId = $userProfile['user_id']; // or $userId = $userRead->getUserId($username);
$posts = $userRead->getUserPosts($userId);
?>

<section class="posts">
    <h2>Posts</h2>
    <?php foreach ($posts as $post): ?>
        <article class="post">
            <p class="username"><?php echo htmlspecialchars($post['username']); ?></p>
            <h3 class="title"><?php echo htmlspecialchars($post['title']); ?></h3>
            <hr>
            <p class="body"><?php echo htmlspecialchars($post['content']); ?></p>
            <?php if ($post['media']): ?>
                <p class="media">Media:</p>
                <img class="media" src="<?php echo htmlspecialchars($post['media']); ?>" alt="Post Media">
            <?php endif; ?>
            <span class="likes">Likes: <?php echo htmlspecialchars($post['likes'] ?: 0); ?></span>
            <p class="date">Date: <?php echo htmlspecialchars($post['created_at']); ?></p>
        </article>
    <?php endforeach; ?>
</section>

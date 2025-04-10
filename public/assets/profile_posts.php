<?php

use Models\User\UserRead;

// Assuming DBconnect.php is required in parent file to establish a database connection
// require '../src/Database/DBconnect.php';
// Require the necessary classes if not already loaded
class_exists("Models\User\UserRead") or require_once '../src/Models/UserRead.php';

// Check if the user is logged in, and get the session username if available
$sessionUsernameIsSet = $_SESSION['username'] ?? null;

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
            <form action="lib/process/process_post_reaction.php" method="post">
                <input type="hidden" name="post_id" value="<?php echo htmlspecialchars($post['post_id']); ?>">
                <input type="hidden" name="user_id" value="<?php echo $sessionUsernameIsSet ? htmlspecialchars($userRead->getUserId($sessionUsernameIsSet)) : null; ?>">
                <button <?php echo $sessionUsernameIsSet ? 'type="submit"' : 'type="button" onclick="if(confirm(\'Please login to follow this user.\')) { window.location.href = \'account.php#login\'; }"'; ?> name="like">Like / Dislike</button>
            </form>
                <button type="button" <?php echo $sessionUsernameIsSet ? 'onclick="toggleCommentForm(' . htmlspecialchars($post['post_id']) . ')"' : 'onclick="if(confirm(\'Please login to follow this user.\')) { window.location.href = \'account.php#login\'; }"'; ?>>Comment</button>
            <div id="comment-form-<?php echo htmlspecialchars($post['post_id']); ?>" class="comment-form" style="display: none;">
                <form action="lib/process/process_post_reaction.php" method="post">
                    <input type="hidden" name="post_id" value="<?php echo htmlspecialchars($post['post_id']); ?>">
                    <input type="hidden" name="user_id" value="<?php echo $sessionUsernameIsSet ? htmlspecialchars($userRead->getUserId($sessionUsernameIsSet)) : null; ?>">
                    <label for="comment">Comment:
                        <textarea name="comment" placeholder="Write a comment..."></textarea>
                    </label>
                    <button type="submit">Submit</button>
                </form>
            </div>
            <!-- TODO: Show all comments -->
        </article>
    <?php endforeach; ?>
</section>

<script>
    function toggleCommentForm(postId) {
        const form = document.getElementById('comment-form-' + postId);
        if (form.style.display === 'none' || form.style.display === '') {
            form.style.display = 'block';
        } else {
            form.style.display = 'none';
        }
    }
</script>

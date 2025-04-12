<?php
/** @var Post[] $posts */ // Should pass this in from Post Controller

use Models\Post\Post;
use Models\Post\PostReaction;
use Models\Post\PostComment;
use Models\User\UserRead;

require_once __DIR__ . '/../../src/Models/PostReaction.php';
require_once __DIR__ . '/../../src/Models/PostComment.php';

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['username']);

if ($isLoggedIn) {
    require_once __DIR__ . '/../../src/Database/DBconnect.php';
    require_once __DIR__ . '/../../src/Models/UserRead.php';
    $userRead = new UserRead($connection);
    $sessionUsername = $_SESSION['username'];
    $sessionUserId = $userRead->getUserId($sessionUsername);
}

// Handle offset and limit for comments
$offset = 0;
$limit = 5;
?>

<?php foreach ($posts as $post): ?>
    <?php if ($post->getIsDeleted()): ?>
        <div class="deleted-post">
            <p>This post has been deleted.</p>
        </div>
        <?php continue; ?>
    <?php endif; ?>
    <div class="post" style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">
        <h3 class=creator-username><a href="/profile.php?username=<?= htmlspecialchars($post->getCreatorUsername()) ?>"><?= htmlspecialchars($post->getCreatorUsername()) ?></a></h3>
        <h3 class="title"><?= htmlspecialchars($post->getTitle()) ?></h3>
        <p class="content"><?= nl2br(htmlspecialchars($post->getContent())) ?></p>
        <?php if (!empty($post->getMedia())): ?>
            <div class="media">
                <?php foreach ($post->getMedia() as $media): ?>
                    <img src="<?= htmlspecialchars($media) ?>" alt="media" style="max-width: 200px;">
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <p class="date"><small>Posted at: <?= date('Y-m-d', strtotime($post->getCreatedAt())) ?></small></p>
        <p class="likes"><strong>Likes: <?= htmlspecialchars(count((new PostReaction())->getLikes($connection, $post->getPostId()) ?: [])) ?></strong></p>

        <?php if ($isLoggedIn): ?>
            <form action="lib/process/process_post_reaction.php" method="post">
                <input type="hidden" name="post_id" value="<?= htmlspecialchars($post->getPostId()) ?>">
                <input type="hidden" name="user_id" value="<?= htmlspecialchars($sessionUserId) ?>">
                <button type="submit" name="like"><?= in_array($sessionUserId, (new PostReaction())->getLikes($connection, $post->getPostId()) ?: []) ? 'Unlike 🩶' : 'Like ❤️' ?></button>
            </form>
            <button type="button" onclick="toggleCreateComment(<?= htmlspecialchars($post->getPostId()) ?>)">Comment</button>
            <form action="lib/process/process_post_reaction.php" method="post" id="comment-form-<?= htmlspecialchars($post->getPostId()) ?>" style="display: none;">
                <input type="hidden" name="post_id" value="<?= htmlspecialchars($post->getPostId()) ?>">
                <input type="hidden" name="user_id" value="<?= htmlspecialchars($sessionUserId) ?>">
                <label for="comment">Comment:
                    <textarea name="comment" placeholder="Write a comment..." required></textarea>
                </label>
                <button type="submit">Submit Comment</button>
            </form>
        <?php else: ?>
            <em onclick="if(confirm('Please login to follow this user.')) { window.location.href = '/account.php#login'; }">Login to like/unlike.</em>
        <?php endif; ?>

        <button type="button" onclick="toggleComments(<?= htmlspecialchars($post->getPostId()) ?>)">Show Comments</button>
        <div id="comments-<?= htmlspecialchars($post->getPostId()) ?>" class="comments" style="display: none; margin-top: 10px;">
            <?php
            $comments = array_slice((new PostComment($post->getPostId(), $post->getUserId(), $post->getTitle(), $post->getContent(), $post->getMedia()))->getComments($connection), $offset, $limit);
            if (!$comments) {
                echo "<p>Error fetching comments.</p>";
            } elseif (empty($comments)) {
                if ($offset > 0) {
                    echo "<p>No more comments to show.</p>";
                } else {
                    echo "<p>No comments yet.</p>";
                }
            }
            include_once __DIR__ . '/comments.php';
            if (count($comments) >= $limit + $offset) {
                echo '<button onclick="loadMoreComments(' . htmlspecialchars($post->getPostId()) . ', ' . ($offset + $limit) . ')">Load More Comments</button>';
            }
            ?>
        </div>
        <hr>
    </div>
<?php endforeach; ?>

<script>
    function toggleCreateComment(postId) {
        const form = document.getElementById('comment-form-' + postId);
        form.style.display = form.style.display === 'none' || form.style.display === '' ? 'block' : 'none';
    }

    function toggleComments(postId) {
        const commentsDiv = document.getElementById('comments-' + postId);
        commentsDiv.style.display = commentsDiv.style.display === 'none' || commentsDiv.style.display === '' ? 'block' : 'none';
    }

    function loadMoreComments(postId, offset) {
        // Implement AJAX to load more comments
        // For now, just a placeholder
        alert('Load more comments for post ID: ' + postId + ' with offset: ' + offset);
    }
</script>

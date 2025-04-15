<?php
/**
 *
 * @var PDO $connection Database connection object (assumed to be passed from DBconnect.php - should be required in the parent file)
 * @var Post[] $posts Posts to display (Should be passed from the Post Controller)
 * @var array $sessionAuth Session authentication data (user_id and username)
 *
 */

use Models\Post\Post;
use Models\Post\PostComment;
use Models\Post\PostReaction;

?>

<?php foreach ($posts as $post): ?>
    <?php if ($post->getIsDeleted()): ?>
        <article class="post deleted-post">
            <p>This post has been deleted.</p>
        </article>
        <?php continue; ?>
    <?php endif; ?>
    <article class="post">
        <h3 class="author"><a class="author" href="/profile.php?username=<?= htmlspecialchars($post->getCreatorUsername()) ?>"><?= htmlspecialchars($post->getCreatorUsername()) ?></a></h3>
        <div class="post-content">
            <h3 class="title"><?= htmlspecialchars($post->getTitle()) ?></h3>
            <p class="content"><?= nl2br(htmlspecialchars($post->getContent())) ?></p>
            <?php if (!empty($post->getMedia())): ?>
                <div class="media">
                    <img src="<?= htmlspecialchars($post->getMedia()) ?>" alt="media" style="max-width: 200px;">
                </div>
            <?php endif; ?>
        </div>
        <p class="likes">Likes: <strong><?= htmlspecialchars((new PostReaction())->getLikesCount($connection, $post->getPostId())) ?></strong></p>

        <div class="post-actions">
            <?php if ($sessionAuth): ?>
                <form action="lib/process/process_post_reaction.php" method="post">
                    <input type="hidden" name="post_id" value="<?= htmlspecialchars($post->getPostId()) ?>">
                    <input type="hidden" name="user_id" value="<?= htmlspecialchars($sessionAuth['user_id']) ?>">
                    <button class="like-btn <?= in_array($sessionAuth['user_id'], (new PostReaction())->getLikes($connection, $post->getPostId()) ?: []) ? 'unlike-style' : 'like-style' ?>" type="submit" name="like"><?= in_array($sessionAuth['user_id'], (new PostReaction())->getLikes($connection, $post->getPostId()) ?: []) ? 'Unlike 🩶' : 'Like ❤️' ?></button>
                </form>
                <button class="comment-btn" type="button" onclick="toggleCreateComment(<?= htmlspecialchars($post->getPostId()) ?>)">Comment</button>
            <?php else: ?>
                <em onclick="if(confirm('Please login to follow this user.')) { window.location.href = '/auth.php#login'; }" style="cursor: pointer">Login to like/unlike.</em>
            <?php endif; ?>

            <button class="show-comments-btn" type="button" onclick="toggleComments(this, <?= htmlspecialchars($post->getPostId()) ?>)">Show Comments</button>

            <?php if ($sessionAuth && $sessionAuth['user_id'] === $post->getUserId()): ?>
                <a class="edit-post-btn" href="post_edit.php?post_id=<?= htmlspecialchars($post->getPostId()) ?>">Edit Post</a>
                <a class="delete-post-btn" href="post_delete.php?post_id=<?= htmlspecialchars($post->getPostId()) ?>">Delete Post</a>
            <?php endif; ?>
        </div>

        <?php if ($sessionAuth): ?>
            <form class="create-comment" action="lib/process/process_post_reaction.php" method="post" id="comment-form-<?= htmlspecialchars($post->getPostId()) ?>" style="display: none;">
                <input type="hidden" name="post_id" value="<?= htmlspecialchars($post->getPostId()) ?>">
                <input type="hidden" name="user_id" value="<?= htmlspecialchars($sessionAuth['user_id']) ?>">
                <label for="comment">Comment:<textarea name="comment" placeholder="Write a comment..." required></textarea></label>
                <button class="submit-comment-btn" type="submit">Submit Comment</button>
            </form>
        <?php endif; ?>

        <div id="comments-<?= htmlspecialchars($post->getPostId()) ?>" class="comments" style="display: none; margin-top: 10px;">
            <?php
            $comments = array_slice((new PostComment($post->getPostId(), $post->getUserId(), $post->getTitle(), $post->getContent(), $post->getMedia()))->getComments($connection), $offsetComments, $limitComments);
            if (!$comments) {
                echo "<p>Error fetching comments.</p>";
            } elseif (empty($comments)) {
                if ($offsetComments > 0) {
                    echo "<p>No more comments to show.</p>";
                } else {
                    echo "<p>No comments yet.</p>";
                }
            }
            include_once __DIR__ . '/comments.php';
            if (count($comments) >= $limitComments + $offsetComments): ?>
                <button id="load-more-comments-<?= htmlspecialchars($post->getPostId()) ?>" data-offset="<?= $offsetComments + $limitComments ?>" onclick="loadMoreComments(<?= htmlspecialchars($post->getPostId()) ?>, this.dataset.offset)">Load More Comments</button>
            <?php endif; ?>
        </div>
        <p class="date"><small>Posted at: <?= date('Y-m-d', strtotime($post->getCreatedAt())) ?></small></p>
    </article>
<?php endforeach; ?>

<script>
    function toggleCreateComment(postId) {
        const form = document.getElementById('comment-form-' + postId);
        form.style.display = form.style.display === 'none' || form.style.display === '' ? 'flex' : 'none';
    }

    function toggleComments(btn, postId) {
        const commentsDiv = document.getElementById('comments-' + postId);
        commentsDiv.style.display = commentsDiv.style.display === 'none' || commentsDiv.style.display === '' ? 'block' : 'none';
        btn.textContent = commentsDiv.style.display === 'block' ? 'Hide Comments' : 'Show Comments';
    }

    function loadMoreComments(postId, offset, limit = 5) {
        const loadMoreBtn = document.getElementById('load-more-comments-' + postId);
        const url = 'lib/process/load_more_comments.php?postId=' + postId + '&offset=' + offset;
        fetch(url)
            .then(response => response.text())
            .then(html => {
                const commentsDiv = document.getElementById('comments-' + postId);
                commentsDiv.insertAdjacentHTML('beforeend', html);
                loadMoreBtn.dataset.offset = parseInt(offset) + limit;
            })
            .catch(error => console.error(error));
    }
</script>

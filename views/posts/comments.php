<?php /** @var $comments = Post $post->getComments(PDO $connection); */ ?>

<?php foreach ($comments as $comment): ?>
    <div class="comment">
        <h4 class="username"> <?php echo htmlspecialchars($comment['username']); ?></h4>
        <p class="content"><?php echo htmlspecialchars($comment['content']); ?></p>
        <p class="date"><?php echo htmlspecialchars($comment['created_at']); ?></p>
    </div>
<?php endforeach; ?>

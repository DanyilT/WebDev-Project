<?php /** @var $comments = Post $post->getComments(PDO $connection); */ ?>

<?php foreach ($comments as $comment): ?>
    <hr>
    <div class="comment">
        <h4 class="author"><a class="author" href="/profile.php?username=<?= htmlspecialchars($comment['username']) ?>"><?= htmlspecialchars($comment['username']) ?></a></h4>
        <p class="content"><?php echo htmlspecialchars($comment['content']); ?></p>
        <p class="date"><?php echo htmlspecialchars($comment['created_at']); ?></p>
    </div>
<?php endforeach; ?>

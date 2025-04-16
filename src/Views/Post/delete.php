<?php
/**
 * View: Delete Post
 * This file is responsible for displaying the delete post confirmation form.
 *
 * @package Views\Post
 *
 * @var $post
 */

$title = 'Delete Post';
$styles = '<link rel="stylesheet" href="css/pages/post_delete.css">';
include 'layout/header.php';
?>

<main>
    <section>
        <h2>Delete Post</h2>
        <form style="border: 1px solid #ccc; padding: 10px;">
            <h3>Post to delete:</h3>
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($post->getTitle()) ?>" readonly>
            <label for="content">Content:</label>
            <textarea id="content" name="content" readonly><?= htmlspecialchars($post->getContent()) ?></textarea>
        </form>
        <form action="lib/process/process_delete_post.php" method="post">
            <input type="hidden" name="post_id" value="<?= htmlspecialchars($post->getPostId()) ?>">
            <p>Are you sure you want to delete this post?</p>
            <label for="confirm">Confirm:<input type="checkbox" id="confirm" name="confirm" required></label>
            <button type="submit">Delete Post</button>
        </form>
    </section>
</main>

<?php include 'layout/footer.php'; ?>

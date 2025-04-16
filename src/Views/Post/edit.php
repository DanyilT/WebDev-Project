<?php
/**
 * View: Edit Post
 * This file is responsible for displaying the edit post form.
 *
 * @package Views\Post
 *
 * @var $post
 */

$title = 'Edit Post';
$styles = '<link rel="stylesheet" href="css/pages/post_edit.css">';
include 'layout/header.php';
?>

<main>
    <section>
        <h2>Edit Post</h2>
        <form action="lib/process/process_save_post.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="post_id" value="<?= htmlspecialchars($post->getPostId()) ?>">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($post->getTitle()) ?>" required>
            <label for="content">Content:</label>
            <textarea id="content" name="content" required><?= htmlspecialchars($post->getContent()) ?></textarea>
            <!-- TODO: Add media upload functionality -->
            <!-- Can save media file to the Database, but not to the media folder (server) -->
<!--            <label for="media">Media (optional):</label>-->
<!--            <input type="file" id="media" name="media" accept="image/*">-->
            <button type="submit">Update Post</button>
        </form>
    </section>
</main>

<?php include 'layout/footer.php'; ?>

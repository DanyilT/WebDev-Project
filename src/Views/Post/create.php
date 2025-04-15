<?php
$title = 'Create Post';
$styles = '<link rel="stylesheet" href="css/pages/post_new.css">';
include 'layout/header.php';
?>

<main>
    <section>
        <h2>Create a New Post</h2>
        <form action="lib/process/process_save_post.php" method="post" enctype="multipart/form-data">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($_SESSION['auth']['username']); ?>" readonly required> <!-- Not using this POST field in process_save_post.php, use session instead -->
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required>
            <label for="content">Content:</label>
            <textarea id="content" name="content" required></textarea>
            <!-- TODO: Add media upload functionality -->
            <!-- Can save media file to the Database, but not to the media folder (server) -->
<!--            <label for="media">Media (optional):</label>-->
<!--            <input type="file" id="media" name="media" accept="image/*">-->
            <button type="submit">Create Post</button>
        </form>
    </section>
</main>

<?php include 'layout/footer.php'; ?>

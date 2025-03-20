<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: account.php#login');
    exit();
}
?>

<?php
$title = 'Create Post';
$styles = '<link rel="stylesheet" href="css/pages/post_new.css">';
include 'layout/header.php';
?>

<main>
    <section>
        <h2>Create a New Post</h2>
        <form action="lib/process/process_save_post.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" readonly required> <!-- TODO: Not secure -->
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required>
            <label for="content">Content:</label>
            <textarea id="content" name="content" required></textarea>
            <label for="date">Date:</label>
            <input type="date" id="date" name="date" value="<?php echo date('Y-m-d'); ?>" readonly required> <!-- TODO: Not secure -->
            <button type="submit">Create Post</button>
        </form>
    </section>
</main>

<?php include 'layout/footer.php'; ?>

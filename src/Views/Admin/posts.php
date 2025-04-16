<?php


$title = 'Admin Posts Management';
include '../layout/admin/header.php';
?>

<main>
    <section>
        <h2>Find User's Posts</h2>
        <form method="GET" action="/admin/posts.php">
            <input type="text" name="user_id" placeholder="User ID or Username">
            <button type="submit">Search</button>
        </form>

        <h2>All Posts</h2>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>User id|username</th>
                <th>Title</th>
                <th>Content</th>
                <th>Media</th>
                <th>Likes</th>
                <th>Created At</th>
                <th>Is Deleted</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($posts as $post): ?>
                <tr>
                    <td><?= htmlspecialchars($post['post_id']) ?></td>
                    <td><?= htmlspecialchars($post['user_id']) ?> | <?= htmlspecialchars($post['username']) ?></td>
                    <td><?= htmlspecialchars($post['title']) ?></td>
                    <td><?= htmlspecialchars($post['content']) ?></td>
                    <td><?= htmlspecialchars($post['media'] ?: 'null') ?></td>
                    <td><?= htmlspecialchars($post['likes'] ?: 0) ?></td>
                    <td><?= htmlspecialchars($post['created_at']) ?></td>
                    <td><?= htmlspecialchars($post['is_deleted']) ?></td>
                    <td>
                        <form method="POST" action="/admin/lib/manage_posts.php" style="display:inline;">
                            <input type="hidden" name="post_id" value="<?= $post['post_id'] ?>">
                            <input type="hidden" name="user_id" value="<?= $post['user_id'] ?>">
                            <input type="text" name="title" value="<?= htmlspecialchars($post['title']) ?>" placeholder="title" required>
                            <input type="text" name="content" value="<?= htmlspecialchars($post['content']) ?>" placeholder="content" required>
                            <button type="submit" name="update">Update</button>
                        </form>
                        <form method="POST" action="/admin/lib/manage_posts.php" style="display:inline;">
                            <input type="hidden" name="post_id" value="<?= $post['post_id'] ?>">
                            <input type="hidden" name="user_id" value="<?= $post['user_id'] ?>">
                            <button type="submit" name="delete">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </section>
</main>

<?php include '../layout/admin/footer.php'; ?>

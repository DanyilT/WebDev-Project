<?php
$postId = 1; // Example post ID
$posts = [
    1 => ["username" => "user1", "date" => "2025-01-01", "content" => "Content of the first post."],
    2 => ["username" => "user2", "date" => "2025-01-02", "content" => "Content of the second post."],
    3 => ["username" => "user3", "date" => "2025-01-03", "content" => "Content of the third post."]
];

$post = $posts[$postId];
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Social Network (QWERTY) - Post</title>
</head>
<body>
    <header>
        <h1>Post Description</h1>
        <nav>
            <a href="index.php">Home</a> |
            <a href="search.php">Search</a> |
            <a href="products.php">Products</a>
        </nav>
    </header>

    <main>
        <section>
            <h2>Post Details</h2>
            <article>
                <h3>Post ID: <?php echo htmlspecialchars($postId); ?></h3>
                <p><strong>Username:</strong> <?php echo htmlspecialchars($post['username']); ?></p>
                <p><strong>Date:</strong> <?php echo htmlspecialchars($post['date']); ?></p>
                <p><strong>Content:</strong> <?php echo htmlspecialchars($post['content']); ?></p>
            </article>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 QWERTY</p>
    </footer>
</body>
</html>

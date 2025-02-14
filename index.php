<?php
$posts = [
    1 => ["username" => "user1", "date" => "2025-01-01", "content" => "Content of the first post."],
    2 => ["username" => "user2", "date" => "2025-01-02", "content" => "Content of the second post."],
    3 => ["username" => "user3", "date" => "2025-01-03", "content" => "Content of the third post."]
];
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Social Network (QWERTY) - Home</title>
</head>
<body>
    <header>
        <h1>Welcome to Our Social Network</h1>
        <nav>
            <a href="index.php">Home</a> |
            <a href="search.php">Search</a> |
            <a href="products.php">Products</a>
        </nav>
    </header>

    <main>
        <section>
            <h2>Recent Posts</h2>
            <?php
            foreach ($posts as $postId => $post) {
            echo "<article>";
                echo "<h3>Post ID: " . htmlspecialchars($postId) . "</h3>";
                echo "<p>Username: " . htmlspecialchars($post['username']) . "</p>";
                echo "<p>Date: " . htmlspecialchars($post['date']) . "</p>";
                echo "<p>Content: " . htmlspecialchars($post['content']) . "</p>";
                echo "</article>";
            }
            ?>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 QWERTY</p>
    </footer>
</body>
</html>

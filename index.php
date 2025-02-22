<?php
require 'lib/functions.php';

const ACCOUNTS_FILE_PATH = 'data/accounts.json';

$accounts = getAccounts(ACCOUNTS_FILE_PATH);

$posts = [];
foreach ($accounts as $account) {
    foreach ($account['posts'] as $postId => $post) {
        $posts[$postId] = [
            'username' => $account['username'],
            'title' => $post['title'],
            'content' => $post['content'],
            'date' => $post['date']
        ];
    }
}

//    var_dump($accounts);
//    die();
?>

<?php include_once 'layout/header.php'; ?>

<main>
    <section>
        <h2>Recent Posts</h2>
        <?php
        foreach ($posts as $postId => $post) {
            echo "<article>";
            echo "<h3>Post ID: " . htmlspecialchars($postId) . "</h3>";
            echo "<p>Username: " . htmlspecialchars($post['username']) . "</p>";
            echo "<p>Title: " . htmlspecialchars($post['title']) . "</p>";
            echo "<p>Content: " . htmlspecialchars($post['content']) . "</p>";
            echo "<p>Date: " . htmlspecialchars($post['date']) . "</p>";
            echo "</article>";
        }
        ?>
    </section>
</main>

<?php include_once 'layout/footer.php'; ?>

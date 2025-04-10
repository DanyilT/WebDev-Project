<?php
// TODO: Update search.php and make_search.php to use the new User class
$title = 'Search';
$styles = '<link rel="stylesheet" href="css/pages/search.css">';
include 'layout/header.php';
?>

<main>
    <section>
        <h2>Search Users</h2>
        <form action="search.php" method="get">
            <label for="search">Search for users:</label>
            <input type="text" id="search" name="search" placeholder="Enter username...">
            <button type="submit">Search</button>
        </form>
    </section>

    <?php require 'lib/make_search.php'; ?>
</main>

<?php include 'layout/footer.php'; ?>

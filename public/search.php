<?php

use Controllers\User\UserController;

// Require the necessary classes
require '../src/Database/DBconnect.php';
require '../src/Controllers/User/UserController.php';
require_once 'lib/functions.php'; // Include the functions file

if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $limit = $_GET['limit'] ?? null;

    $userController = new UserController($connection);
    $users = $userController->searchUsers($search);

    $users = array_slice($users, 0, $limit);}
?>

<?php
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
            <label for="limit">Limit:<input type="number" id="limit" name="limit" value="10" min="1"></label>
            <button type="submit">Search</button>
        </form>
    </section>

    <?php if (isset($_GET['search'])) displaySearchResults($users); ?>
</main>

<?php include 'layout/footer.php'; ?>

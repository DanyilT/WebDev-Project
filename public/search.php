<?php

use Controllers\User\UserController;

// Require the necessary classes
require '../src/Database/DBconnect.php';
require '../src/Controllers/User/UserController.php';
require_once 'lib/functions.php'; // Include the functions file

if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $limit = $_GET['limit'] ?? null;
    $sort = $_GET['sort'] ?? 'username_asc';

    $userController = new UserController($connection);
    $users = $userController->searchUsers($search);

    switch ($sort) {
        case 'username_asc':
            usort($users, function ($a, $b) {
                return strcmp($a['username'], $b['username']);
            });
            break;
        case 'username_desc':
            usort($users, function ($a, $b) {
                return strcmp($b['username'], $a['username']);
            });
            break;
        case 'created_at_asc':
            usort($users, function ($a, $b) {
                return strtotime($a['created_at']) - strtotime($b['created_at']);
            });
            break;
        case 'created_at_desc':
            usort($users, function ($a, $b) {
                return strtotime($b['created_at']) - strtotime($a['created_at']);
            });
            break;
    }
    $users = array_slice($users, 0, $limit);
}
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
            <input type="text" id="search" name="search" placeholder="Enter username..." value="<?php echo htmlentities($_GET['search'] ?? '', ENT_QUOTES); ?>">
            <div class="search-options">
                <label for="limit">Limit:<input type="number" id="limit" name="limit" value="<?php echo htmlentities($_GET['limit'] ?? '10', ENT_QUOTES); ?>" min="1"></label>
                <label for="sort">Sort by:
                    <select id="sort" name="sort">
                        <option value="username_asc" <?php echo (isset($_GET['sort']) && $_GET['sort'] === 'username_asc') ? 'selected' : ''; ?>>Username (A-Z)</option>
                        <option value="username_desc" <?php echo (isset($_GET['sort']) && $_GET['sort'] === 'username_desc') ? 'selected' : ''; ?>>Username (Z-A)</option>
                        <option value="created_at_desc" <?php echo (isset($_GET['sort']) && $_GET['sort'] === 'created_at_desc') ? 'selected' : ''; ?>>Created At (Newest)</option>
                        <option value="created_at_asc" <?php echo (isset($_GET['sort']) && $_GET['sort'] === 'created_at_asc') ? 'selected' : ''; ?>>Created At (Oldest)</option>
                    </select>
                </label>
            </div>
            <button type="submit">Search</button>
        </form>
    </section>

    <?php if (isset($_GET['search'])) displaySearchResults($users); ?>
</main>

<?php include 'layout/footer.php'; ?>

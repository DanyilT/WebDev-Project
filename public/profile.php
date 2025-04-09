<?php
// Require the necessary classes
require '../src/Database/DBconnect.php';

$title = chr('64') . preg_replace('/[^a-z0-9_]/', '', strtolower(trim($_GET['username']))) . "'s Profile";
$styles = '<link rel="stylesheet" href="css/pages/profile.css">';
include 'layout/header.php';
?>

<main>
    <?php include 'assets/profile_view.php'; ?>

    <?php include 'assets/profile_posts.php'; ?>
</main>

<?php
if ($_SESSION['username'] == $username)
    include 'assets/profile_edit_modal.php';
?>

<?php include 'layout/footer.php'; ?>

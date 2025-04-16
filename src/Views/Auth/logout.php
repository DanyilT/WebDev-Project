<?php
/**
 * View: Logout
 * This file is responsible for displaying the logout page.
 *
 * @package Views\Auth
 */

$title = 'Register';
$styles = '<link rel="stylesheet" href="css/pages/auth.css">';
include 'layout/header.php';
?>

<main>
    <section class="page register">
        <h2>Logout</h2>
        <?php if (isset($_GET['logout']) && $_GET['logout'] == 'success'): ?>
            <p>You have been logged out successfully.</p>
            <p><a href="/auth.php?login">Login again</a></p>
        <?php elseif (isset($_GET['error'])): ?>
            <p class="error">Error: <?php echo htmlspecialchars($_GET['error']); ?></p>
        <?php elseif (isset($_SESSION['auth'])): ?>
            <p>Logged in as: <strong onclick="location.href='/profile.php?username=<?php echo htmlspecialchars($_SESSION['auth']['username']); ?>'"><?php echo htmlspecialchars($_SESSION['auth']['username']); ?></strong></p>
            <form id="logout-form" action="lib/auth/logout.php" method="post">
                <button type="submit">Logout</button>
            </form>
        <?php else: ?>
            <p>You are not logged in.</p>
            <p><a href="/auth.php?login">Login</a></p>
        <?php endif; ?>
    </section>
</main>

<?php include 'layout/footer.php'; ?>

<?php
/**
 * View: Login
 * This file is responsible for displaying the login page.
 *
 * @package Views\Auth
 */

$title = 'Login';
$styles = '<link rel="stylesheet" href="css/pages/auth.css">';
include 'layout/header.php';
?>

<main>
    <section class="page login">
        <h2>Login</h2>
        <form action="lib/auth/login.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="/auth.php?register">Register</a></p>
        <?php if (isset($_SESSION['auth'])): ?>
            <p>Logged in as: <strong onclick="location.href='/profile.php?username=<?php echo htmlspecialchars($_SESSION['auth']['username']); ?>'"><?php echo htmlspecialchars($_SESSION['auth']['username']); ?></strong> <a href="/auth.php?logout">Logout</a></p>
        <?php endif; ?>
    </section>
</main>

<?php include 'layout/footer.php'; ?>

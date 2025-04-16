<?php
/**
 * Page: Login/Register
 * This file is responsible for handling user authentication.
 *
 * @package public
 */

// Include necessary files
if (isset($_GET['login'])) {
    require_once '../src/Views/Auth/login.php';
    exit();
} elseif (isset($_GET['register'])) {
    require_once '../src/Views/Auth/register.php';
    exit();
} elseif (isset($_GET['logout'])) {
    require_once '../src/Views/Auth/logout.php';
    exit();
}

// If the user is already logged in, redirect to the profile page
session_start();
if (isset($_SESSION['auth']['username'])) {
    header('Location: profile.php?username=' . $_SESSION['auth']['username']);
    exit();
}

// Include the header
$title = 'Login/Register';
$styles = '<link rel="stylesheet" href="css/pages/auth.css">';
include 'layout/header.php';
?>

<!-- HTML -->
<main>
    <article>
        <?php if (isset($_SESSION['auth']['username'])): ?>
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION['auth']['username']); ?></h1>
            <form action="lib/auth/logout.php" method="post">
                <button type="submit">Logout</button>
            </form>
        <?php else: ?>
            <h1>Welcome to the Account Page</h1>
            <button id="login-btn">Login</button>
            <button id="register-btn">Register</button>
        <?php endif; ?>
        <a href="/admin"> [Admin]</a>
    </article>
    <?php if (isset($_SESSION['auth']['username'])): ?>
        <p>This is your account page.</p>
    <?php else: ?>
        <p>Please log in or sign up to access your account.</p>
    <?php endif; ?>
</main>

<!-- Login Modal & Register Modal -->
<?php include 'assets/modals/account_login_modal.php'; ?>
<?php include 'assets/modals/account_register_modal.php'; ?>

<?php include 'layout/footer.php'; ?>

<!-- Scripts -->
<script src="js/account.js"></script>

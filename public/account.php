<?php
session_start();
if (isset($_SESSION['username'])) {
    header('Location: profile.php?username=' . $_SESSION['username']);
    exit();
}
?>

<?php
$title = 'Login/Register';
$styles = '<link rel="stylesheet" href="css/pages/account.css">';
include 'layout/header.php';
?>

<main>
    <article>
        <?php if (isset($_SESSION['username'])): ?>
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
            <form action="lib/process/process_logout.php" method="post">
                <button type="submit">Logout</button>
            </form>
        <?php else: ?>
            <h1>Welcome to the Account Page</h1>
            <button id="login-btn">Login</button>
            <button id="register-btn">Register</button>
        <?php endif; ?>
        <a href="admin_crud.php"> [Admin]</a>
    </article>
    <?php if (isset($_SESSION['username'])): ?>
        <p>This is your account page.</p>
    <?php else: ?>
        <p>Please log in or sign up to access your account.</p>
    <?php endif; ?>
</main>

<!-- Login Modal & Register Modal -->
<?php include 'assets/modals/account_login_modal.php'; ?>
<?php include 'assets/modals/account_register_modal.php'; ?>

<?php include 'layout/footer.php'; ?>

<script src="js/account.js"></script>

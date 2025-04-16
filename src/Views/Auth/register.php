<?php
/**
 * View: Register
 * This file is responsible for displaying the registration page.
 *
 * @package Views\Auth
 */

$title = 'Register';
$styles = '<link rel="stylesheet" href="css/pages/auth.css">';
include 'layout/header.php';
?>

<main>
    <section class="page register">
        <h2>Register</h2>
        <form action="lib/auth/register.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="/auth.php?login">Login</a></p>
        <?php if (isset($_SESSION['auth'])): ?>
            <p>Logged in as: <strong onclick="location.href='/profile.php?username=<?php echo htmlspecialchars($_SESSION['auth']['username']); ?>'"><?php echo htmlspecialchars($_SESSION['auth']['username']); ?></strong> <a href="/auth.php?logout">Logout</a></p>
        <?php endif; ?>
    </section>
</main>

<?php include 'layout/footer.php'; ?>

<?php
/**
 * Modal: Register
 * This file contains the modal for displaying the register modal form.
 *
 * @package public/assets/modals
 */
?>

<dialog id="register-modal" class="modal register">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Register</h2>
            <span id="close-register" class="close">&times;</span>
        </div>
        <form action="lib/auth/register.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <label for="accept-terms">
                <input type="checkbox" id="accept-terms" name="accept-terms" required>
                I accept the <a href="/terms/terms_and_conditions.php">Terms and Conditions</a>
            </label>
            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="#login">Login</a></p>
        <?php if (isset($_GET['error'])): ?>
            <p style="color:red;"><?php echo htmlspecialchars($_GET['error']); ?></p>
        <?php endif; ?>
    </div>
</dialog>

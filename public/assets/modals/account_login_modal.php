<?php
/**
 * Modal: Login
 * This file contains the modal for displaying the login modal form.
 * Improved with enhanced validation and user feedback.
 *
 * @package public/assets/modals
 */
?>

<dialog id="login-modal" class="modal login">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Login</h2>
            <span id="close-login" class="close">&times;</span>
        </div>

        <!-- Error message container -->
        <div id="login-error" class="form-error" style="display: none;"></div>

        <!-- Success message container -->
        <div id="login-success" class="success-notification" style="display: none;">
            <p>Login successful! Redirecting...</p>
        </div>

        <form id="login-form" action="lib/auth/login.php" method="post">
            <div class="form-group">
                <label for="login-username" class="required">Username:</label>
                <input type="text" id="login-username" name="username" class="form-control"
                       placeholder="Enter your username" required>
                <div id="login-username-validation" class="validation-message"></div>
            </div>

            <div class="form-group">
                <label for="login-password" class="required">Password:</label>
                <input type="password" id="login-password" name="password" class="form-control"
                       placeholder="Enter your password" required>
                <div id="login-password-validation" class="validation-message"></div>
            </div>

            <div id="login-loading-indicator" class="loading-spinner" style="display: none;">
                <div class="spinner"></div>
                <p>Logging in...</p>
            </div>

            <button type="submit" class="btn btn-primary">Login</button>
        </form>

        <p>Don't have an account? <a href="#register">Register</a></p>

        <?php if (!empty($_GET['error'])): ?>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const errorContainer = document.getElementById('login-error');
                    errorContainer.textContent = '<?php echo htmlspecialchars($_GET['error']); ?>';
                    errorContainer.style.display = 'block';
                });
            </script>
        <?php endif; ?>

        <?php if (isset($_GET['registered']) && $_GET['registered'] == 1): ?>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const successContainer = document.getElementById('login-success');
                    successContainer.innerHTML = '<p>Registration successful! You can now log in.</p>';
                    successContainer.style.display = 'block';
                });
            </script>
        <?php endif; ?>
    </div>
</dialog>

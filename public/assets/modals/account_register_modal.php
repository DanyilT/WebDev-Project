<?php
/**
 * Modal: Register
 * This file contains the modal for displaying the register modal form.
 * Improved with enhanced validation and user feedback.
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

        <!-- Error message container -->
        <div id="register-error" class="form-error" style="display: none;"></div>

        <!-- Success message container -->
        <div id="register-success" class="success-notification" style="display: none;">
            <p>Registration successful! You are now logged in.</p>
        </div>

        <form id="register-form" action="lib/auth/register.php" method="post">
            <div class="form-group">
                <label for="username" class="required">Username:</label>
                <input type="text" id="username" name="username" class="form-control"
                       placeholder="Choose a username (e.g., john_doe123)" required>
                <div id="username-validation" class="validation-message"></div>
                <ul class="field-requirements">
                    <li id="username-length">At least 3 characters long</li>
                    <li id="username-chars">Only letters, numbers, and underscores</li>
                    <li id="username-unique">Must be unique</li>
                </ul>
            </div>

            <div class="form-group">
                <label for="email" class="required">Email:</label>
                <input type="email" id="email" name="email" class="form-control"
                       placeholder="Your email address" required>
                <div id="email-validation" class="validation-message"></div>
            </div>

            <div class="form-group">
                <label for="name" class="required">Name:</label>
                <input type="text" id="name" name="name" class="form-control"
                       placeholder="Your full name" required>
                <div id="name-validation" class="validation-message"></div>
            </div>

            <div class="form-group">
                <label for="password" class="required">Password:</label>
                <input type="password" id="password" name="password" class="form-control"
                       placeholder="Create a secure password" required>
                <div id="password-validation" class="validation-message"></div>
                <ul class="field-requirements">
                    <li id="password-length">At least 8 characters long</li>
                    <li id="password-uppercase">Contains at least one uppercase letter</li>
                    <li id="password-lowercase">Contains at least one lowercase letter</li>
                    <li id="password-number">Contains at least one number</li>
                </ul>
            </div>

            <div class="form-group">
                <label for="accept-terms" style="font-weight: 400" class="required">
                    <input type="checkbox" id="accept-terms" name="accept-terms" required>
                    I accept the <a href="/terms/terms_and_conditions.php">Terms and Conditions</a>
                </label>
                <div id="terms-validation" class="validation-message"></div>
            </div>

            <div id="loading-indicator" class="loading-spinner" style="display: none;">
                <div class="spinner"></div>
                <p>Processing registration...</p>
            </div>

            <button type="submit" class="btn btn-primary">Register</button>
        </form>

        <p>Already have an account? <a href="#login">Login</a></p>

        <?php if (isset($_GET['error']) && !empty($_GET['error'])): ?>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const errorContainer = document.getElementById('register-error');
                    errorContainer.textContent = '<?php echo htmlspecialchars($_GET['error']); ?>';
                    errorContainer.style.display = 'block';
                });
            </script>
        <?php endif; ?>
    </div>
</dialog>
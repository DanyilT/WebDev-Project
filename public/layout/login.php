<dialog id="login-modal" class="modal login">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Login</h2>
            <span id="close-login" class="close">&times;</span>
        </div>
        <form action="lib/process/process_login.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="#register">Register</a></p>
    </div>
</dialog>

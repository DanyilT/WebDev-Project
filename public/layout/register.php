<dialog id="register-modal" class="modal register">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Sign Up</h2>
            <span id="close-register" class="close">&times;</span>
        </div>
        <form action="lib/process_register.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Sign Up</button>
        </form>
        <p>Already have an account? <a href="#login">Login</a></p>
    </div>
</dialog>

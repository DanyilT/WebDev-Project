<?php
/**
 * File: auth.php
 * This file contains the authentication logic for the admin panel.
 * It checks if the user is authenticated and if not, it prompts for the admin password.
 *
 * @package public/admin/lib
 *
 * @var string `ADMIN_PASSWORD` Static password for admin authentication
 * @var string $error Error message if authentication fails
 */


// TODO: Don't store the admin password like this:
const ADMIN_PASSWORD = 'admin-password'; // Hey everyone, look here! This is the admin password!
$hashed_password = password_hash(ADMIN_PASSWORD, PASSWORD_DEFAULT);

session_start();
if (!isset($_SESSION['admin_authenticated'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admin_password'])) {
        if (password_verify($_POST['admin_password'], $hashed_password)) {
            $_SESSION['admin_authenticated'] = true;
            header('Location: ' . $_SERVER['PHP_SELF']);
        } else {
            $error = 'Invalid password';
        }
    } else {
        $error = 'Please enter the admin password';
    }

    echo '<form method="POST">
            <label for="admin_password">Admin Password:</label>
            <input type="password" id="admin_password" name="admin_password" required>
            <button type="submit">Submit</button>
          </form>';
    if ($error) {
        echo '<p style="color:red;">' . htmlspecialchars($error) . '</p>';
    }
    exit();
}

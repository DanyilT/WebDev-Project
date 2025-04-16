<?php
/**
 * View: Admin Settings
 * This file is responsible for displaying the admin settings page.
 *
 * @package Views\Admin
 */

$title = 'Admin Settings';
include '../layout/admin/header.php';
?>

<main>
    <section>
        <h2>Settings</h2>
        <form method="POST" action="/admin/lib/manage_settings.php">
            <label for="admin_password">Admin Password:</label>
            <input type="password" id="admin_password" name="admin_password" placeholder="Update admin password" required>
            <button type="submit">Save Settings</button>
        </form>
    </section>
</main>

<?php include '../layout/admin/footer.php'; ?>

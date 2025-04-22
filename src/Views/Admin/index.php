<?php
/**
 * View: Admin Dashboard
 * This file is responsible for displaying the admin dashboard.
 *
 * @package Views\Admin
 */

$title = 'Admin Dashboard';
include '../layout/admin/header.php';
?>

<main>
    <section>
        <h2>Welcome, Admin</h2>
        <p>Use the navigation above to manage users, posts, and settings.</p>
    </section>
    <hr>
    <section>
        <h2>Validation Tests</h2>
        <a href="lib/validation_tests.php">Run Validation Tests</a>
    </section>
</main>

<?php include '../layout/admin/footer.php'; ?>

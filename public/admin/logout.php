<?php

session_start();

// Check if the user is authenticated as an admin
if (!isset($_SESSION['admin_authenticated'])) {
    header('Location: /admin');
    exit();
}

// Unset the session variable to log out the admin
unset($_SESSION['admin_authenticated']);

$title = 'Logout';
include '../layout/admin/header.php';
?>

<main>
    <section>
        <h2>Logout</h2>
        <p><strong>Success!</strong> You have been logged out.</p>
        <p><a href="/admin">Go back to Admin Panel</a></p>
        <p><a href="/">Go Home</a></p>
    </section>
</main>

<?php include '../layout/admin/footer.php'; ?>

<?php
/**
 * Layout: Header (admin panel)
 * This file contains the header layout for the application's admin panel.
 *
 * @package public/layout/admin
 *
 * @var string $title Page title (optional)
 */

// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Admin Dashboard (QWERTY)'; ?></title>
    <link rel="stylesheet" href="/css/main.css">
    <link rel="stylesheet" href="/css/pages/admin.css">
    <link rel="icon" href="favicon_io/favicon.ico" type="image/x-icon">
</head>
<body>
<header>
    <h1 class="title">Admin Dashboard</h1>
    <div class="hamburger">
        <span></span>
        <span></span>
        <span></span>
    </div>
    <nav>
        <ul>
            <li class="nav-item nav-text <?php echo $_SERVER['PHP_SELF'] == '/admin/index.php' ? 'active' : ''; ?>"><a href="/admin/index.php">Dashboard</a></li>
            <li class="nav-item nav-text <?php echo $_SERVER['PHP_SELF'] == '/admin/users.php' ? 'active' : ''; ?>"><a href="/admin/users.php">Manage Users</a></li>
            <li class="nav-item nav-text <?php echo $_SERVER['PHP_SELF'] == '/admin/posts.php' ? 'active' : ''; ?>"><a href="/admin/posts.php">Manage Posts</a></li>
            <li class="nav-item nav-text <?php echo $_SERVER['PHP_SELF'] == '/admin/settings.php' ? 'active' : ''; ?>"><a href="/admin/settings.php">Settings</a></li>
            <li class="nav-item nav-text <?php echo $_SERVER['PHP_SELF'] == '/admin/logout.php' ? 'active' : ''; ?>"><a href="/admin/logout.php">Logout</a></li>
        </ul>
    </nav>
</header>

<script>
    const nav = document.querySelector('header nav');
    const hamburger = document.querySelector('.hamburger');

    hamburger.addEventListener('click', function () {
        if (nav.classList.contains('open')) {
            nav.classList.remove('open');
            nav.classList.add('closing');
            setTimeout(() => {nav.classList.remove('closing'); nav.style.display = 'none';}, 300); // Match animation duration
        } else {
            nav.style.display = 'flex';
            nav.classList.add('open');
        }
        this.classList.toggle('active');
        document.body.classList.toggle('no-scroll');
    });
</script>

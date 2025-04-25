<?php
/**
 * Layout: Header
 * This file contains the header layout for the application.
 *
 * @package public/layout
 * 
 * @var string $title Page title (optional)
 * @var string $styles Additional styles (optional)
 */

// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Social Network (QWERTY)'; ?></title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="icon" href="img/favicon_io/favicon.ico" type="image/x-icon">
    <?php if (isset($styles)) echo $styles; ?>
</head>
<body>
<header>
    <h1 class="title" onclick="location.href='/'">QWERTY</h1>
    <div class="hamburger">
        <span></span>
        <span></span>
        <span></span>
    </div>
    <nav>
        <a href="index.php" class="nav-item">
            <img src="img/icons/nav/home-iconly.svg" alt="Home Icon" class="nav-icon">
            <span class="nav-text">Home</span>
        </a>
        <hr>
        <a href="search.php" class="nav-item">
            <img src="img/icons/nav/user-search-iconly.svg" alt="Search for User Icon" class="nav-icon">
            <span class="nav-text">Search</span>
        </a>
        <hr>
        <a href="post_new.php" class="nav-item">
            <img src="img/icons/nav/create-new-iconly.svg" alt="Create New post Icon" class="nav-icon">
            <span class="nav-text">Create</span>
        </a>
        <hr>
        <?php if (isset($_SESSION['auth']['username'])): ?>
            <a href="profile.php?username=<?php echo $_SESSION['auth']['username']; ?>" class="nav-item">
                <img src="img/icons/nav/profile-iconly.svg" alt="Profile Icon" class="nav-icon">
                <span class="nav-text">Profile</span>
            </a>
        <?php else: ?>
            <a href="auth.php#login" class="nav-item">
                <img src="img/icons/nav/login-iconly.svg" alt="Login Icon" class="nav-icon">
                <span class="nav-text">Login/Register</span>
            </a>
        <?php endif; ?>
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

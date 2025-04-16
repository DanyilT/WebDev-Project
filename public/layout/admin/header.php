<?php if (session_status() == PHP_SESSION_NONE) session_start(); ?>

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

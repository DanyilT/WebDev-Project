<?php
session_start();
if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: admin_crud.php');
    exit();
}

if (!isset($_SESSION['admin_authenticated'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admin_password'])) {
        // TODO: Don't store the admin password like this:
        $admin_password = 'admin-password';
        $hashed_password = password_hash($admin_password, PASSWORD_DEFAULT);
        if (password_verify($_POST['admin_password'], $hashed_password)) {
            $_SESSION['admin_authenticated'] = true;
            header('Location: admin_crud.php');
        } else {
            $error = 'Invalid password';
        }
    } else {
        $error = 'Please enter the admin password';
    }

    if (!isset($_SESSION['admin_authenticated'])) {
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
}

require '../src/DBconnect.php';
require 'lib/UserManager.php';

$userManager = new UserManager($connection, null, null, null);

// Handle Create
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create'])) {
    $userManager = new UserManager($connection, $_POST['username'], $_POST['email'], $_POST['name'], $_POST['bio'] ?: null, $_POST['profile_pic'] ?: null);
    $userManager->createUser($_POST['password']);
    header('Location: admin_crud.php');
}

// Handle Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $userManager->updateUser($_POST['user_id'], $_POST['username'], $_POST['email'], $_POST['name'], $_POST['bio'] ?: null, $_POST['profile_pic'] ?: null, $_POST['created_at'], $_POST['is_deleted']);
    header('Location: admin_crud.php');
}

// Handle Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $userManager->deleteUser($_POST['user_id']);
    header('Location: admin_crud.php');
}

// Fetch all users
$users = $userManager->getAllUsers();
?>

<?php
$title = 'Admin CRUD (QWERTY)';
$styles = '<link rel="stylesheet" href="css/pages/admin_crud.css">';
include 'layout/header.php'
?>

<main>
    <h1>Admin CRUD Page</h1>
    <form method="POST" style="display:inline;">
        <button type="submit" name="logout">Logout</button>
    </form>

    <h2>Create User</h2>
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="name" placeholder="Name" required>
        <input type="text" name="bio" placeholder="Bio">
        <input type="text" name="profile_pic" placeholder="Profile Picture Link">
        <button type="submit" name="create">Create</button>
    </form>

    <h2>Users</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Name</th>
            <th>Bio</th>
            <th>Profile Picture</th>
            <th>Created At</th>
            <th>Is Deleted</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user['user_id']) ?></td>
                <td><?= htmlspecialchars($user['username']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td><?= htmlspecialchars($user['name']) ?></td>
                <td><?= htmlspecialchars($user['bio'] ?: 'N/A') ?></td>
                <td><?= htmlspecialchars($user['profile_pic'] ?: 'N/A') ?></td>
                <td><?= htmlspecialchars($user['created_at']) ?></td>
                <td><?= htmlspecialchars($user['is_deleted']) ?></td>
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                        <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
                        <!-- password stored as hashed -->
                        <!-- <input type="text" name="password" value="<?= htmlspecialchars($user['password']) ?>" required> -->
                        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                        <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
                        <input type="text" name="bio" value="<?= htmlspecialchars($user['bio'] ?: '') ?>">
                        <input type="text" name="profile_pic" value="<?= htmlspecialchars($user['profile_pic'] ?: '') ?>">
                        <input type="text" name="created_at" value="<?= htmlspecialchars($user['created_at']) ?>">
                        <input type="text" name="is_deleted" value="<?= htmlspecialchars($user['is_deleted']) ?>">
                        <button type="submit" name="update">Update</button>
                    </form>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                        <button type="submit" name="delete">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</main>

<?php include 'layout/footer.php'; ?>

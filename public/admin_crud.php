<!-- TODO: It is not ok admin login system, and other login & register must take data from database, not from json file -->

<?php
session_start();
if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: admin_crud.php');
    exit();
}

if (!isset($_SESSION['admin_authenticated'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admin_password'])) {
        $hashed_password = password_hash('admin-password', PASSWORD_DEFAULT);
        if (password_verify($_POST['admin_password'], $hashed_password)) {
            $_SESSION['admin_authenticated'] = true;
            header('Location: admin_crud.php');
        } else {
            $error = 'Invalid password';
        }
    } else {
        $error = '';
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

$config = require '../config.php';

$pdo = new PDO("mysql:host={$config['host']};dbname={$config['dbname']}", $config['user'], $config['password'], $config['options']);

// Handle Create
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create'])) {
    $stmt = $pdo->prepare("INSERT INTO users (username, password, email, name) VALUES (?, ?, ?, ?)");
    $stmt->execute([$_POST['username'], password_hash($_POST['password'], PASSWORD_DEFAULT), $_POST['email'], $_POST['name']]);
}

// Handle Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, name = ? WHERE user_id = ?");
    $stmt->execute([$_POST['username'], $_POST['email'], $_POST['name'], $_POST['user_id']]);
}

// Handle Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->execute([$_POST['user_id']]);
}

// Fetch all users
$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin CRUD</title>
</head>
<body>
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
    <button type="submit" name="create">Create</button>
</form>

<h2>Users</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Email</th>
        <th>Name</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($users as $user): ?>
        <tr>
            <td><?= htmlspecialchars($user['user_id']) ?></td>
            <td><?= htmlspecialchars($user['username']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td><?= htmlspecialchars($user['name']) ?></td>
            <td>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                    <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
                    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                    <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
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
</body>
</html>

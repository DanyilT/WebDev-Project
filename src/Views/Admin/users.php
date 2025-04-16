<?php
/**
 * View: Admin
 * This file is responsible for displaying the admin users management page.
 *
 * @package Views\Admin
 *
 * @var array $users Users to display (Should be passed from parent)
 */

$title = 'Admin Users Management';
include '../layout/admin/header.php';
?>

<main>
    <section>
        <h2>Create User</h2>
        <form method="POST" action="/admin/lib/manage_users.php" style="display:inline;">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="name" placeholder="Name" required>
            <input type="text" name="bio" placeholder="Bio">
            <button type="submit" name="create">Create</button>
        </form>

        <h2>Search User</h2>
        <form method="GET">
            <input type="text" name="search" placeholder="Search by username">
            <button type="submit">Search</button>
        </form>

        <h2>All Users</h2>
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
                    <td><?= htmlspecialchars($user['bio'] ?: 'null') ?></td>
                    <td><?= htmlspecialchars($user['profile_pic'] ?: 'null') ?></td>
                    <td><?= htmlspecialchars($user['created_at']) ?></td>
                    <td><?= htmlspecialchars($user['is_deleted']) ?></td>
                    <td>
                        <form method="POST" action="/admin/lib/manage_users.php" style="display:inline;">
                            <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                            <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" placeholder="username" required>
                            <input type="text" name="password" value="change password" placeholder="password" required> <!-- password stored as hashed -->
                            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" placeholder="email" required>
                            <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" placeholder="name" required>
                            <input type="text" name="bio" value="<?= htmlspecialchars($user['bio'] ?: '') ?>" placeholder="bio">
                            <input type="text" name="created_at" value="<?= htmlspecialchars($user['created_at']) ?>" placeholder="created_at">
                            <input type="text" name="is_deleted" value="<?= htmlspecialchars($user['is_deleted']) ?>" placeholder="is_deleted">
                            <button type="submit" name="update">Update</button>
                        </form>
                        <form method="POST" action="/admin/lib/manage_users.php" style="display:inline;">
                            <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                            <button type="submit" name="delete">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </section>
</main>

<?php include '../layout/admin/footer.php'; ?>

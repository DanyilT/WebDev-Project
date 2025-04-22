<?php
/**
 * File: manage_users.php
 * This file handles the management of users in the admin panel.
 * It includes functionality to update and delete users, as well as create new users.
 *
 * @package public/admin/lib
 *
 * @var PDO $connection Database connection object (passed from DBconnect.php)
 */

require 'auth.php';

use Controllers\Admin\AdminController;

require '../../../src/Database/DBconnect.php';
require '../../../src/Controllers/Admin/AdminController.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /admin/users.php?error=Invalid request method');
    exit;
}

if (isset($_POST['create'])) {
    // Handle user creation
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $name = $_POST['name'];
    $bio = $_POST['bio'] ?: null;
    $profile_pic = $_POST['profile_pic'] ?: null;

    // Validation logic
    validateUserInput($username, $password, $email);

    // Try to create the user, catching any exceptions to the GET request
    try {
        (new AdminController($connection))->createUser($username, $password, $email, $name, $bio, $profile_pic);
        header('Location: /admin/users.php');
    } catch (Exception $e) {
        header('Location: /admin/users.php?error=' . urlencode($e->getMessage()));
    }
    exit;
} elseif (isset($_POST['update'])) {
    // Handle user update
    $userId = $_POST['user_id'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $name = $_POST['name'];
    $bio = $_POST['bio'] ?: null;
    $profile_pic = $_POST['profile_pic'] ?: null;
    $created_at = $_POST['created_at'];
    $is_deleted = $_POST['is_deleted'];

    // Validation logic
    validateUserInput($username, $password, $email);

    (new AdminController($connection))->updateUser($userId, ['username' => $username, 'password' => $password, 'email' => $email, 'name' => $name, 'bio' => $bio, 'profile_pic' => $profile_pic, 'created_at' => $created_at, 'is_deleted' => $is_deleted]);
    header('Location: /admin/users.php');
    exit;
} elseif (isset($_POST['delete'])) {
    // Handle user deletion
    (new AdminController($connection))->deleteUser($_POST['user_id']);
    header('Location: /admin/users.php');
    exit;
}

header('Location: /admin');
exit;

// Validation logic
function validateUserInput($username, $password, $email) {
    // Check if username is valid
    if (empty($username) || !preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username) || !preg_match('/[a-zA-Z]/', $username)) {
        header('Location: /admin/users.php?error=Invalid username');
        exit;
    }

    // Check if email is valid
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('Location: /admin/users.php?error=Invalid email');
        exit;
    }

    // Check if password is valid
    if (empty($password) || strlen($password) < 6) {
        header('Location: /admin/users.php?error=Invalid password');
        exit;
    }

    return true;
}

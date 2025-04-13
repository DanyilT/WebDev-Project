<?php

use Models\User\UserRead;

session_start();
require '../../../src/Database/DBconnect.php';
require '../../../src/Models/UserRead.php';

$username = preg_replace('/[^a-z0-9_]/', '', strtolower(trim($_POST['username'])));
$password = $_POST['password'];

if (validateLogin($connection, $username, $password)) {
    $_SESSION['username'] = $username;
    header('Location: /account.php');
} else {
    header('Location: /account.php?error=invalid_credentials');
}
exit();

function validateLogin($connection, $username, $password): bool {
    $userRead = new UserRead($connection);
    if (empty($username) || empty($password)) {
        return false;
    }
    if (!$userRead->isUsernameExist($username, $connection)) {
        return false;
    }
    $userProfile = $userRead->getUserProfile($username);
    if (!$userProfile) {
        return false;
    }
    return password_verify($password, $userRead->getUserPassword($username));
}

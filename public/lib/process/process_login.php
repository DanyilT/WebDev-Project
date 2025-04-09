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

function validateLogin($connection, $username, $password) {
    $userRead = new UserRead($connection);
    return $userRead->isUsernameExist($username, $connection) && $userRead->getUserProfile($username) && password_verify($password, $userRead->getUserPassword($username));
}

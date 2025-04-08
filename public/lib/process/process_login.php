<?php

use Models\User\UserRead;

session_start();
require '../../../src/DBconnect.php';
require '../../../src/Models/UserRead.php';

$username = $_POST['username'];
$password = $_POST['password'];

if (validateLogin($connection, $username, $password)) {
    $_SESSION['username'] = strtolower(trim(chr(64) . $username));
    $_SESSION['username'] = $username;
    header('Location: ../../account.php');
} else {
    header('Location: ../../account.php?error=invalid_credentials');
}
exit();

function validateLogin($connection, $username, $password) {
    $userRead = new UserRead($connection);
    return $userRead->isUsernameExist($username, $connection) && $userRead->getUserProfile($username) && password_verify($password, $userRead->getUserPassword($username));
}

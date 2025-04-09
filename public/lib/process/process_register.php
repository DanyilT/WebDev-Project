<?php

use Models\User\UserCreate;

session_start();
require '../../../src/Database/DBconnect.php';
require '../../../src/Models/UserCreate.php';

$username = preg_replace('/[^a-z0-9_]/', '', strtolower(trim($_POST['username'])));
$email = $_POST['email'];
$name = $_POST['name'];
$password = $_POST['password'];

if (registerUser($connection, $username, $password, $email, $name)) {
    $_SESSION['username'] = $username;
    header('Location: /account.php');
} else {
    header('Location: /account.php?error=registration_failed');
}
exit();

function registerUser($connection, $username, $password, $email, $name) {
    $userCreate = new UserCreate($connection, $username, $password, $email, $name);
    try {
        return $userCreate->createUser();
    } catch (Exception $e) {
        return false;
    }
}

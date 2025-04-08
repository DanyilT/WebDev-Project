<?php

use Models\User\UserCreate;

session_start();
require '../../../src/DBconnect.php';
require '../../../src/Models/UserCreate.php';

$username = $_POST['username'];
$email = $_POST['email'];
$name = $_POST['name'];
$password = $_POST['password'];

if (registerUser($connection, $username, $password, $email, $name)) {
    $_SESSION['username'] = strtolower(trim(chr(64) . $username));
    header('Location: ../../account.php');
} else {
    header('Location: ../../account.php?error=registration_failed');
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

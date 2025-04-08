<?php

use Models\Users\UserCreator;

session_start();
require '../../../src/DBconnect.php';
require '../../../src/Models/UserCreator.php';

$username = $_POST['username'];
$email = $_POST['email'];
$name = $_POST['name'];
$password = $_POST['password'];

if (registerUser($username, $password, $email, $name, $connection)) {
    $_SESSION['username'] = $username;
    header('Location: ../../account.php');
} else {
    header('Location: ../../account.php?error=registration_failed');
}
exit();

function registerUser($username, $password, $email, $name, $connection) {
    $userCreator = new UserCreator($connection, $username, $email, $name);
    try {
        return $userCreator->createUser($password);
    } catch (Exception $e) {
        return false;
    }
}

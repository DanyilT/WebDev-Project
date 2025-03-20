<?php
session_start();
require '../../../src/DBconnect.php';
require '../UserCreator.php';

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
    $userCreator = new UserCreator($connection);
    try {
        return $userCreator->createUser($username, $password, $email, $name);
    } catch (Exception $e) {
        return false;
    }
}

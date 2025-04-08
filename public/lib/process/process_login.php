<?php

use Models\Users\UserProfile;

session_start();
require '../../../src/DBconnect.php';
require '../../../src/Models/UserProfile.php';

$username = $_POST['username'];
$password = $_POST['password'];

if (validateLogin($username, $password, $connection)) {
    $_SESSION['username'] = $username;
    header('Location: ../../account.php');
} else {
    header('Location: ../../account.php?error=invalid_credentials');
}
exit();

function validateLogin($username, $password, $connection) {
    $userProfile = new UserProfile($connection);
    return $userProfile->getUserProfile($username) && password_verify($password, $userProfile->getUserPassword($username));
}

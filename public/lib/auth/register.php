<?php

use Controllers\Auth\AuthController;

require '../../../src/Database/DBconnect.php';
require '../../../src/Controllers/Auth/AuthController.php';

$username = $_POST['username'];
$email = $_POST['email'];
$name = $_POST['name'];
$password = $_POST['password'];

if ((new AuthController($connection))->register($username, $password, $email, $name, true)['status'] === 'success') {
    header('Location: /auth.php');
} else {
    header('Location: /auth.php?error=registration_failed');
}
exit();

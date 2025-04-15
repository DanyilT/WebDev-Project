<?php

use Controllers\Auth\AuthController;

require '../../../src/Database/DBconnect.php';
require '../../../src/Controllers/Auth/AuthController.php';

$username = $_POST['username'];
$password = $_POST['password'];

if ((new AuthController($connection))->login($username, $password)['status'] === 'success') {
    header('Location: /auth.php');
} else {
    header('Location: /auth.php?error=invalid_credentials');
}
exit();

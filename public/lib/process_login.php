<?php
session_start();

require '../../src/DBconnect.php';

$username = $_POST['username'];
$password = $_POST['password'];

if (validateLogin($username, $password, $connection)) {
    $_SESSION['username'] = $username;
    header('Location: ../account.php');
} else {
    header('Location: ../account.php?error=invalid_credentials');
}
exit();

function validateLogin($username, $password, $connection) {
    $stmt = $connection->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        return true;
    }
    return false;
}

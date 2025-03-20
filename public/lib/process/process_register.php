<?php
session_start();
require '../../../src/DBconnect.php';

$username = $_POST['username'];
$email = $_POST['email'];
$name = $_POST['name'];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT);

if (registerUser($username, $password, $email, $name, $connection)) {
    $_SESSION['username'] = $username;
    header('Location: ../../account.php');
} else {
    header('Location: ../../account.php?error=registration_failed');
}
exit();

function registerUser($username, $password, $email, $name, $connection) {
    // Check if the username already exists
    $stmt = $connection->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetch(PDO::FETCH_ASSOC)) {
        return false;
    }

    // Insert the new user into the database
    $stmt = $connection->prepare("INSERT INTO users (username, password, email, name) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$username, $password, $email, $name]);
}

<?php
session_start();

require __DIR__ . '/functions.php';
const ACCOUNTS_FILE_PATH = __DIR__ . '/../data/accounts.json';

$username = $_POST['username'];
$password = $_POST['password'];
$name = $_POST['name'];

if (registerUser($username, $password, $name)) {
    $_SESSION['username'] = $username;
    header('Location: /account.php');
    exit();
} else {
    header('Location: /account.php?error=registration_failed');
    exit();
}

function registerUser($username, $password, $name) {
    $accounts = getAccounts(ACCOUNTS_FILE_PATH);
    foreach ($accounts as $account) {
        if ($account['username'] === $username) {
            return false;
        }
    }
    $accounts[] = [
        'id' => uniqid(),
        'username' => $username,
        'password' => $password,
        'name' => $name,
        'followers' => [],
        'following' => [],
        'posts' => []
    ];
    $json = json_encode($accounts, JSON_PRETTY_PRINT);
    file_put_contents(ACCOUNTS_FILE_PATH, $json);
    return true;
}

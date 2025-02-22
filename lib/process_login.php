<?php
session_start();

require __DIR__ . '/functions.php';
const ACCOUNTS_FILE_PATH = __DIR__ . '/../data/accounts.json';

$username = $_POST['username'];
$password = $_POST['password'];

if (validateLogin($username, $password)) {
    $_SESSION['username'] = $username;
    header('Location: /account.php');
    exit();
} else {
    header('Location: /account.php?error=invalid_credentials');
    exit();
}

function validateLogin($username, $password) {
    $accounts = getAccounts(ACCOUNTS_FILE_PATH);
    foreach ($accounts as $account) {
        if ($account['username'] === $username && $account['password'] === $password) {
            return true;
        }
    }
    return false;
}

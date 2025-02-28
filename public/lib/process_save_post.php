<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: ../account.php#login');
    exit();
}

require __DIR__ . '/functions.php';
const ACCOUNTS_FILE_PATH = __DIR__ . '/../data/accounts.json';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_SESSION['username'];
    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $content = isset($_POST['content']) ? $_POST['content'] : '';
    $date = date('d-m-Y-H-i-s');

    if (empty($title) || empty($content)) {
        header('Location: ../posts_new.php?error=empty_fields');
        exit();
    }

    createPost($username, $title, $content, $date);
    header('Location: ../index.php');
    exit();
} else {
    header('Location: ../posts_new.php');
    exit();
}

function createPost($username, $title, $content, $date) {
    $accounts = get_accounts(ACCOUNTS_FILE_PATH);

    foreach ($accounts as &$account) {
        if ($account['username'] === $username) {
            $postId = uniqid();
            $account['posts'][$postId] = [
                'title' => $title,
                'content' => $content,
                'date' => $date
            ];
            break;
        }
    }

    save_accounts(ACCOUNTS_FILE_PATH, $accounts);
}

<?php
session_start();
if (!isset($_SESSION['auth'])) {
    header('Location: /auth.php#login');
    exit();
}

require_once '../src/Views/Post/create.php';

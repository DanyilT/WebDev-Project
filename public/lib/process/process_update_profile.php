<?php

// Require necessary files
use Models\User\UserRead;
use Models\User\UserUpdate;
use Models\User\UserDelete;

// Start session
session_start();

require '../../../src/Database/DBconnect.php';
require_once '../../../src/Models/UserRead.php';
require_once '../../../src/Models/UserUpdate.php';
require_once '../../../src/Models/UserDelete.php';

// Create a new UserRead and UserUpdate instance
$userRead = new UserRead($connection);
$userUpdate = new UserUpdate($connection);
$userDelete = new UserDelete($connection);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['userId'];

    if ($_POST['editField']) {
        $editField = $_POST['editField'];

        try {
            if ($editField === 'password') {
                $currentPassword = $_POST['current_password'];
                $newPassword = $_POST['new_password'];

                if (empty($currentPassword) || empty($newPassword)) {
                    echo 'Current password and new password are required.';
                    exit();
                }

                if (strlen($newPassword) < 6) {
                    echo 'New password must be at least 6 characters long.';
                    exit();
                }

                if (password_verify($currentPassword, $userRead->getUserPassword($userRead->getUsername($userId)))) {
                    $userUpdate->updateUserPassword($userId, $newPassword);
                } else {
                    echo 'Current password is incorrect.';
                    exit();
                }
            } elseif ($editField === 'username') {
                $userUpdate->updateUser($userId, [$editField => $_POST[$editField]]);
                $_SESSION['username'] = preg_replace('/[^a-z0-9_]/', '', strtolower(trim($_POST[$editField])));
            } else {
                $userUpdate->updateUser($userId, [$editField => $_POST[$editField]]);
            }

            header('Location: /profile.php?username=' . $_SESSION['username']);
            exit();
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    } elseif ($_POST['delete']) {
        $userDelete->deleteUser($userId);
        session_destroy();
        header('Location: /');
        exit();
    } else {
        echo 'Invalid request.';
        exit();
    }
}

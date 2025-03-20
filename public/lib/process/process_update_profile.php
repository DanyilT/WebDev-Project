<?php
session_start();
require '../../../src/DBconnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id'];
    $editField = $_POST['edit_field'];

    try {
        // Fetch current user data
        $stmt = $connection->prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt->execute([$userId]);
        $currentUser = $stmt->fetch();

        if ($editField === 'password') {
            $currentPassword = $_POST['current_password'];
            $newPassword = $_POST['new_password'];

            if (password_verify($currentPassword, $currentUser['password'])) {
                $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $stmt = $connection->prepare("UPDATE users SET password = ? WHERE user_id = ?");
                $stmt->execute([$hashedNewPassword, $userId]);
            } else {
                echo 'Current password is incorrect.';
                exit();
            }
        } else {
            $newValue = $_POST[$editField];
            $changes = [];

            if ($currentUser[$editField] !== $newValue) {
                $changes[$editField] = ['old' => $currentUser[$editField], 'new' => $newValue, 'timestamp' => date('Y-m-d H:i:s')];
            }

            if (!empty($changes)) {
                $changesJson = json_encode($changes);
                if (empty($currentUser['data_changes_history'])) {
                    $origin = json_encode(['origin' => $currentUser]);
                    $stmt = $connection->prepare("UPDATE users SET $editField = ?, data_changes_history = JSON_ARRAY(CAST(? AS JSON), CAST(? AS JSON)) WHERE user_id = ?");
                    $stmt->execute([$newValue, $origin, $changesJson, $userId]);
                } else {
                    $stmt = $connection->prepare("UPDATE users SET $editField = ?, data_changes_history = JSON_ARRAY_APPEND(data_changes_history, '$', CAST(? AS JSON)) WHERE user_id = ?");
                    $stmt->execute([$newValue, $changesJson, $userId]);
                }
            } else {
                $stmt = $connection->prepare("UPDATE users SET $editField = ? WHERE user_id = ?");
                $stmt->execute([$newValue, $userId]);
            }
        }

        header('Location: ../../profile.php?username=' . $_SESSION['username']);
        exit();
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}

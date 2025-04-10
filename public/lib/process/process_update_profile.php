<?php

// Require necessary files
use Models\User\UserRead;
use Models\User\UserUpdate;
use Models\User\UserDelete;
use Services\Media\MediaManager;

// Start session
session_start();

require '../../../src/Database/DBconnect.php';
require_once '../../../src/Models/UserRead.php';
require_once '../../../src/Models/UserUpdate.php';
require_once '../../../src/Models/UserDelete.php';
require_once '../../../src/Services/MediaManager.php';

// Create a new UserRead and UserUpdate instance
$userRead = new UserRead($connection);
$userUpdate = new UserUpdate($connection);
$userDelete = new UserDelete($connection);
$mediaManager = new MediaManager();

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
            } elseif ($editField === 'profile_pic') {
                if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === 0) {
                    // Find the highest hex-based file name
                    $maxHex = 0;
                    $existingFiles = $mediaManager->getUserFolderDirectory($userId, 'profile_pics');
                    foreach ($existingFiles as $file) {
                        if (preg_match('/^' . $userId . '_profilepic' . '_([0-9A-Fa-f]{8})\.jpg$/', $file, $matches)) {
                            $val = hexdec($matches[1]);
                            if ($val > $maxHex) {
                                $maxHex = $val;
                            }
                        }
                    }
                    $nextHex = str_pad(dechex($maxHex + 1), 8, '0', STR_PAD_LEFT);

                    // Convert to JPG if needed and save
                    $tmpName = $_FILES['profile_pic']['tmp_name'];
                    $newFileName = $userId . '_profilepic_' . $nextHex . '.jpg';
                    $destination = $userId . '/profile_pics/' . $newFileName;

                    $imageType = exif_imagetype($tmpName);
                    if ($imageType !== IMAGETYPE_JPEG) {
                        // TODO: Handle other image types (PNG, GIF, etc.) or convert to JPG
                        // Conversion using GD
//                        $image = imagecreatefromstring(file_get_contents($tmpName));
//                        imagejpeg($image, $destination, 90);
//                        imagedestroy($image);
                    }

                    // Upload the file using MediaManager
                    $uploadedFile = $mediaManager->uploadFile($_FILES['profile_pic'], $destination);

                    // Update user record with new image path
                    $userUpdate->updateUser($userId, ['profile_pic' => $newFileName]);
                }
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

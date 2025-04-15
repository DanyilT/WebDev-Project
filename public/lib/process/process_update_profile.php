<?php

use Controllers\User\UserController;
use Services\Media\MediaManager;

// Require necessary files
require '../../../src/Database/DBconnect.php';
require_once '../../../src/Controllers/User/UserController.php';
require_once '../../../src/Services/MediaManager.php';

// Start session
session_start();

// Create a new UserController instance and MediaManager instance
$userController = new UserController($connection);
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

                if (password_verify($currentPassword, $userController->getUserPassword($userController->getUsername($userId)))) {
                    $userController->updateUserPassword($userId, $newPassword);
                } else {
                    echo 'Current password is incorrect.';
                    exit();
                }
            } elseif ($editField === 'username') {
                $userController->updateUser($userId, [$editField => $_POST[$editField]]);
                $_SESSION['auth']['username'] = preg_replace('/[^a-z0-9_]/', '', strtolower(trim($_POST[$editField])));
            } elseif ($editField === 'profile_pic') {
                if (!isset($_FILES['profile_pic']) || $_FILES['profile_pic']['error'] === UPLOAD_ERR_NO_FILE) {
                    // If no file posted, clear the profile picture.
                    $userController->updateUser($userId, ['profile_pic' => '']);
                } elseif ($_FILES['profile_pic']['error'] === 0 && $_FILES['profile_pic']['tmp_name'] && is_uploaded_file($_FILES['profile_pic']['tmp_name'])) {
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

                    // Generate a new file name
                    $newFileName = $userId . '_profilepic_' . $nextHex . '.jpg';
                    $destination = $userId . '/profile_pics/' . $newFileName;

                    // Create image resource from uploaded file
                    $tmpName = $_FILES['profile_pic']['tmp_name'];
                    if (function_exists('gd_info')) {
                        echo "GD extension is enabled.";
                        // Change the image type to JPEG
                        switch (exif_imagetype($tmpName)) {
                            case IMAGETYPE_JPEG:
                                $image = imagecreatefromjpeg($tmpName);
                                break;
                            case IMAGETYPE_PNG:
                                $image = imagecreatefrompng($tmpName);
                                break;
                            case IMAGETYPE_GIF:
                                $image = imagecreatefromgif($tmpName);
                                break;
                            default:
                                echo 'Unsupported image format.';
                                exit();
                        }
                    } else {
                        echo "GD extension is not enabled.";
                        echo '<p>Please enable the GD extension in your PHP configuration.<br>pnp.ini -> extension=gd (if exists, uncomment it)</p>';
                    }

                    // Crop the image to 1:1 aspect ratio
                    $width = imagesx($image);
                    $height = imagesy($image);
                    $size = min($width, $height);
                    $x = (int)(($width - $size) / 2);
                    $y = (int)(($height - $size) / 2);

                    $croppedImage = imagecreatetruecolor($size, $size);
                    imagecopyresampled($croppedImage, $image, 0, 0, $x, $y, $size, $size, $size, $size);
                    imagedestroy($image);

                    // Compress the image to 80% quality
                    $tempFile = tempnam(sys_get_temp_dir(), 'profile_pic_');
                    $maxSizeAllowed = 100 * 1024; // 100 KB
                    $quality = 80;
                    do {
                        imagejpeg($croppedImage, $tempFile, $quality);
                        clearstatcache(true, $tempFile);
                        $compressedSize = filesize($tempFile);
                        if ($compressedSize <= $maxSizeAllowed) {
                            break;
                        }
                        $quality -= 5;
                    } while ($quality > 10);
                    if ($compressedSize > $maxSizeAllowed) {
                        echo '<strong>Image too large, please upload a smaller image.</strong>';
                        unlink($tempFile);
                        imagedestroy($croppedImage);
                        exit();
                    }
                    imagedestroy($croppedImage);

                    // Replace the uploaded file with the new compressed one
                    $_FILES['profile_pic']['name'] = $newFileName;
                    $_FILES['profile_pic']['type'] = 'image/jpeg';
                    $_FILES['profile_pic']['tmp_name'] = $tempFile;
                    $_FILES['profile_pic']['size'] = filesize($tempFile);

                    // Upload the file using MediaManager
                    $uploadedFile = $mediaManager->uploadFile($_FILES['profile_pic'], $destination);
                    unlink($tempFile);

                    // Update user record with new image path (name)
                    if ($uploadedFile) {
                        $userController->updateUser($userId, ['profile_pic' => $newFileName]);
                    } else {
                        echo 'Failed to upload file.';
                        exit();
                    }
                }
            } else {
                $userController->updateUser($userId, [$editField => $_POST[$editField]]);
            }

            header('Location: /profile.php?username=' . $_SESSION['auth']['username']);
            exit();
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    } elseif ($_POST['delete']) {
        $userController->deleteUser($userId);
        session_destroy();
        header('Location: /');
        exit();
    } else {
        echo 'Invalid request.';
        exit();
    }
}

<?php
/**
 * File: logout.php
 * This file handles user logout.
 *
 * @package public/lib/auth
 */

use Controllers\Auth\AuthController;

// Require necessary files
require '../../../src/Controllers/Auth/AuthController.php';

// Process logout
if ((new AuthController(null))->logout()['status'] === 'success') {
    header('Location: /auth.php?logout=success');
} else {
    header('Location: /auth.php?error=logout_failed');
}
exit();

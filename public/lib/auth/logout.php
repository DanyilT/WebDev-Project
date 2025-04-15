<?php

use Controllers\Auth\AuthController;

require '../../../src/Controllers/Auth/AuthController.php';

if ((new AuthController(null))->logout()['status'] === 'success') {
    header('Location: /auth.php?logout=success');
} else {
    header('Location: /auth.php?error=logout_failed');
}
exit();

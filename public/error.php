<?php

use controllers\Error\ErrorController;

require_once __DIR__ . '/../src/Controllers/Error/ErrorController.php';

$error = $_GET['error'] ?? null;

$errorController = new ErrorController();

switch ($error) {
    case '404':
        $errorController->notFound();
        break;
    case '500':
        $errorController->internalServerError();
        break;
    case '418':
        $errorController->teapot();
        break;
    default:
        echo 'Invalid error code.<br>';
        echo 'Available error codes (for now): 404, 500, 418<br>';
        echo '<a href="/">Go to Homepage</a>';
        break;
}

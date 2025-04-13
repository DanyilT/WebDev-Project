<?php

namespace Controllers\Error;

class ErrorController {
    public function notFound(): void {
        http_response_code(404);
        include __DIR__ . '/../../Views/Error/404.php';
    }

    public function internalServerError(): void {
        http_response_code(500);
        include __DIR__ . '/../../Views/Error/500.php';
    }

    public function teapot(): void {
        http_response_code(418);
        include __DIR__ . '/../../Views/Error/418.php';
    }
}

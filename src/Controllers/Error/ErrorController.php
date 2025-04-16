<?php

namespace Controllers\Error;

/**
 * Class ErrorController
 *
 * This class handles error responses for the application.
 * It provides methods to display custom error pages for different HTTP status codes.
 *
 * @package Controllers\Error
 */
class ErrorController {
    /**
     * Handle 404 Not Found error
     *
     * @return void Displays the 404 error page
     */
    public function notFound(): void {
        http_response_code(404);
        include __DIR__ . '/../../Views/Error/404.php';
    }

    /**
     * Handle 500 Internal Server Error
     *
     * @return void Displays the 500 error page
     */
    public function internalServerError(): void {
        http_response_code(500);
        include __DIR__ . '/../../Views/Error/500.php';
    }

    /**
     * Handle 403 Forbidden error
     *
     * @return void Displays the 403 error page
     */
    public function teapot(): void {
        http_response_code(418);
        include __DIR__ . '/../../Views/Error/418.php';
    }
}

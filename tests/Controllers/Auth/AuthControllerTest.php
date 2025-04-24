<?php

namespace Controllers\Auth;

use PHPUnit\Framework\TestCase;
use PDO;

require '../../../src/Controllers/Auth/AuthController.php';

/**
 * Class: AuthControllerTest.php
 * Test: Controllers\Auth\AuthController.php
 *
 * Description:
 * This test class is responsible for testing the AuthController class.
 * It includes tests for user registration with valid and invalid data.
 *
 * @package Controllers\Auth
 */
class AuthControllerTest extends TestCase {
    private $mockPDO;
    private $mockStmt;

    protected function setUp(): void {
        // Create a mock PDO connection
        $this->mockPDO = $this->createMock(PDO::class);
        $this->mockStmt = $this->createMock(\PDOStatement::class);
    }

    public function testRegisterWithValidData() {
        // Create a mock UserController
        $mockUserController = $this->createMock(\Controllers\User\UserController::class);

        // Configure UserController mock to return a valid user ID
        $mockUserController->expects($this->any())
            ->method('getUserId')
            ->willReturn(1); // Return an integer user ID

        $mockUserController->expects($this->any())
            ->method('isValidUsername')
            ->willReturn(['status' => 'success']);

        $mockUserController->expects($this->any())
            ->method('isValidPassword')
            ->willReturn(['status' => 'success']);

        $mockUserController->expects($this->once())
            ->method('createUser')
            ->willReturn(true);

        // Configure PDO mock
        $this->mockPDO->expects($this->any())
            ->method('prepare')
            ->willReturn($this->mockStmt);

        $this->mockStmt->expects($this->any())
            ->method('execute')
            ->willReturn(true);

        $this->mockStmt->expects($this->any())
            ->method('fetchColumn')
            ->willReturn(0);

        // Create AuthController with reflection to inject our mocked UserController
        $authController = new AuthController($this->mockPDO);

        // Use reflection to replace the UserController with our mock
        $reflection = new \ReflectionClass($authController);
        $property = $reflection->getProperty('userController');
        $property->setAccessible(true);
        $property->setValue($authController, $mockUserController);

        // Test the register method
        $result = $authController->register(
            'testuser',
            'password123',
            'test@example.com',
            'Test User'
        );

        // Assert that registration was successful
        $this->assertEquals('success', $result['status']);
    }

    public function testRegisterWithInvalidUsername() {
        // Configure mocks
        $this->mockPDO->expects($this->any())
            ->method('prepare')
            ->willReturn($this->mockStmt);

        $this->mockStmt->expects($this->any())
            ->method('execute')
            ->willReturn(true);

        $this->mockStmt->expects($this->any())
            ->method('fetchColumn')
            ->willReturn(0); // Username doesn't exist

        // Create an AuthController instance
        $authController = new AuthController($this->mockPDO);

        // Test the register method with an invalid username
        $result = $authController->register(
            'qwerty',
            'password123',
            'test@example.com',
            'Test User'
        );

        // Assert that the registration failed due to invalid username
        $this->assertEquals('error', $result['status']);
        $this->assertEquals('username', $result['field']);
    }

    public function testRegisterWithInvalidEmail() {
        // Configure mocks
        $this->mockPDO->expects($this->any())
            ->method('prepare')
            ->willReturn($this->mockStmt);

        $this->mockStmt->expects($this->any())
            ->method('execute')
            ->willReturn(true);

        $this->mockStmt->expects($this->any())
            ->method('fetchColumn')
            ->willReturn(0); // Username doesn't exist

        // Create an AuthController instance
        $authController = new AuthController($this->mockPDO);

        // Test the register method with an invalid email
        $result = $authController->register(
            'testuser',
            'password123',
            'invalid-email',
            'Test User'
        );

        // Assert that the registration failed due to invalid email
        $this->assertEquals('error', $result['status']);
        $this->assertEquals('email', $result['field']);
    }

    public function testRegisterWithInvalidPassword() {
        // Configure mocks
        $this->mockPDO->expects($this->any())
            ->method('prepare')
            ->willReturn($this->mockStmt);

        $this->mockStmt->expects($this->any())
            ->method('execute')
            ->willReturn(true);

        $this->mockStmt->expects($this->any())
            ->method('fetchColumn')
            ->willReturn(0); // Username doesn't exist

        // Create an AuthController instance
        $authController = new AuthController($this->mockPDO);

        // Test the register method with an invalid password
        $result = $authController->register(
            'testuser',
            '123',
            'test@example.com',
            'Test User'
        );

        // Assert that the registration failed due to invalid password
        $this->assertEquals('error', $result['status']);
        $this->assertEquals('password', $result['field']);
    }
}

<?php

namespace Models\User;

use PHPUnit\Framework\TestCase;
use PDO;

require '../../../src/Models/User/UserCreate.php';

class UserCreateTest extends TestCase
{
    private $mockPDO;
    private $mockStmt;

    protected function setUp(): void
    {
        // Create a mock PDO connection
        $this->mockPDO = $this->createMock(PDO::class);
        $this->mockStmt = $this->createMock(\PDOStatement::class);
    }

    public function testCreateUserWithValidData()
    {
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

        // Create a UserCreate instance with valid data
        $userCreate = new UserCreate(
            $this->mockPDO,
            'testuser',
            'password123',
            'test@example.com',
            'Test User',
            'Test bio',
            null
        );

        // Test the createUser method
        $result = $userCreate->createUser();

        // Assert that the user was created successfully
        $this->assertTrue($result);
    }

    public function testIsUsernameExistReturnsTrue()
    {
        // Configure mocks
        $this->mockPDO->expects($this->any())
            ->method('prepare')
            ->willReturn($this->mockStmt);

        $this->mockStmt->expects($this->any())
            ->method('execute')
            ->willReturn(true);

        $this->mockStmt->expects($this->any())
            ->method('fetchColumn')
            ->willReturn(1); // Username exists

        // Create a UserCreate instance
        $userCreate = new UserCreate(
            $this->mockPDO,
            'existinguser',
            'password123',
            'test@example.com',
            'Test User'
        );

        // Test the isUsernameExist method
        $result = $userCreate->isUsernameExist('existinguser', $this->mockPDO);

        // Assert that the username exists
        $this->assertTrue($result);
    }

    public function testIsUsernameExistReturnsFalse()
    {
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

        // Create a UserCreate instance
        $userCreate = new UserCreate(
            $this->mockPDO,
            'newuser',
            'password123',
            'test@example.com',
            'Test User'
        );

        // Test the isUsernameExist method
        $result = $userCreate->isUsernameExist('newuser', $this->mockPDO);

        // Assert that the username doesn't exist
        $this->assertFalse($result);
    }

    public function testCreateUserWithInvalidUsername()
    {
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

        // Create a UserCreate instance with invalid username
        $userCreate = new UserCreate(
            $this->mockPDO,
            'qwerty',
            'password123',
            'test@example.com',
            'Test User',
            'Test bio',
            null
        );

        // Test the createUser method
        $this->expectException(\Exception::class);
        $userCreate->createUser();
    }

    public function testCreateUserWithInvalidEmail()
    {
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

        // Create a UserCreate instance with invalid email
        $userCreate = new UserCreate(
            $this->mockPDO,
            'testuser',
            'password123',
            'invalid-email',
            'Test User',
            'Test bio',
            null
        );

        // Test the createUser method
        $this->expectException(\Exception::class);
        $userCreate->createUser();
    }

    public function testCreateUserWithInvalidPassword()
    {
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

        // Create a UserCreate instance with invalid password
        $userCreate = new UserCreate(
            $this->mockPDO,
            'testuser',
            '123',
            'test@example.com',
            'Test User',
            'Test bio',
            null
        );

        // Test the createUser method
        $this->expectException(\Exception::class);
        $userCreate->createUser();
    }
}

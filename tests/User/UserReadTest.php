<?php

namespace User;

use Models\User\UserRead;
use PHPUnit\Framework\TestCase;
use PDO;

require '../../src/Models/UserRead.php';

class UserReadTest extends TestCase
{
    private $mockPDO;
    private $mockStmt;

    protected function setUp(): void
    {
        // Create a mock PDO connection
        $this->mockPDO = $this->createMock(PDO::class);
        $this->mockStmt = $this->createMock(\PDOStatement::class);
    }

    public function testGetUserProfile()
    {
        // Sample user data
        $userData = [
            'user_id' => 1,
            'username' => '@testuser',
            'email' => 'test@example.com',
            'name' => 'Test User',
            'bio' => 'Test bio'
        ];

        // Configure mocks
        $this->mockPDO->expects($this->once())
            ->method('prepare')
            ->with("SELECT * FROM active_users WHERE username = ?")
            ->willReturn($this->mockStmt);

        $this->mockStmt->expects($this->once())
            ->method('execute')
            ->with(['@testuser']);

        $this->mockStmt->expects($this->once())
            ->method('fetch')
            ->with(PDO::FETCH_ASSOC)
            ->willReturn($userData);

        // Create a UserRead instance
        $userRead = new UserRead($this->mockPDO);

        // Test the getUserProfile method
        $result = $userRead->getUserProfile('testuser');

        // Assert that the user profile matches the expected data
        $this->assertEquals($userData, $result);
    }

    public function testGetUserId()
    {
        // Configure mocks
        $this->mockPDO->expects($this->once())
            ->method('prepare')
            ->with("SELECT user_id FROM active_users WHERE username = ?")
            ->willReturn($this->mockStmt);

        $this->mockStmt->expects($this->once())
            ->method('execute')
            ->with(['@testuser']);

        $this->mockStmt->expects($this->once())
            ->method('fetchColumn')
            ->willReturn(1);

        // Create a UserRead instance
        $userRead = new UserRead($this->mockPDO);

        // Test the getUserId method
        $result = $userRead->getUserId('testuser');

        // Assert that the user ID is correct
        $this->assertEquals(1, $result);
    }

    public function testIsUsernameExist()
    {
        // Configure mocks
        $this->mockPDO->expects($this->once())
            ->method('prepare')
            ->with("SELECT COUNT(*) FROM users WHERE username = ?")
            ->willReturn($this->mockStmt);

        $this->mockStmt->expects($this->once())
            ->method('execute')
            ->with(['@testuser']);

        $this->mockStmt->expects($this->once())
            ->method('fetchColumn')
            ->willReturn(1);

        // Create a UserRead instance
        $userRead = new UserRead($this->mockPDO);

        // Test the isUsernameExist method
        $result = $userRead->isUsernameExist('testuser', $this->mockPDO);

        // Assert that the username exists
        $this->assertTrue($result);
    }
}

<?php

namespace User;

use Models\User\UserDelete;
use PHPUnit\Framework\TestCase;
use PDO;

require '../../src/Models/UserDelete.php';

class UserDeleteTest extends TestCase
{
    private $mockPDO;
    private $mockStmt;

    protected function setUp(): void
    {
        // Create a mock PDO connection
        $this->mockPDO = $this->createMock(PDO::class);
        $this->mockStmt = $this->createMock(\PDOStatement::class);
    }

    public function testDeleteUser()
    {
        // Configure mocks
        $this->mockPDO->expects($this->exactly(3))
            ->method('prepare')
            ->willReturn($this->mockStmt);

        $this->mockStmt->expects($this->exactly(3))
            ->method('execute')
            ->willReturn(true);

        $this->mockStmt->expects($this->exactly(2))
            ->method('fetchColumn')
            ->willReturnOnConsecutiveCalls('@testuser', 0);

        // Create a UserDelete instance
        $userDelete = new UserDelete($this->mockPDO);

        // Test the deleteUser method
        $result = $userDelete->deleteUser(1);

        // Assert that the user was deleted successfully
        $this->assertTrue($result);
    }

    public function testActuallyDeleteUser()
    {
        // Set up the admin authentication session variable
        $_SESSION['admin_authenticated'] = true;

        // Configure mocks
        $this->mockPDO->expects($this->once())
            ->method('prepare')
            ->with("DELETE FROM users WHERE user_id = ?")
            ->willReturn($this->mockStmt);

        $this->mockStmt->expects($this->once())
            ->method('execute')
            ->with([1])
            ->willReturn(true);

        // Create a UserDelete instance
        $userDelete = new UserDelete($this->mockPDO);

        // Test the actuallyDeleteUser method
        $result = $userDelete->actuallyDeleteUser(1);

        // Assert that the user was permanently deleted successfully
        $this->assertTrue($result);

        // Clean up the session variable
        unset($_SESSION['admin_authenticated']);
    }

    public function testActuallyDeleteUserNotAuthenticated()
    {
        // Ensure the admin authentication session variable is not set
        if (isset($_SESSION['admin_authenticated'])) {
            unset($_SESSION['admin_authenticated']);
        }

        // Create a UserDelete instance
        $userDelete = new UserDelete($this->mockPDO);

        // Test the actuallyDeleteUser method
        $result = $userDelete->actuallyDeleteUser(1);

        // Assert that the user was not deleted (not authenticated)
        $this->assertFalse($result);
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

        // Create a UserDelete instance
        $userDelete = new UserDelete($this->mockPDO);

        // Test the isUsernameExist method
        $result = $userDelete->isUsernameExist('testuser', $this->mockPDO);

        // Assert that the username exists
        $this->assertTrue($result);
    }
}
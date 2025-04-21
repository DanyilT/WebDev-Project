<?php

namespace Models\User;

use PHPUnit\Framework\TestCase;
use PDO;

require '../../../src/Models/User/UserDelete.php';

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
        $this->mockPDO->expects($this->exactly(4))
            ->method('prepare')
            ->willReturn($this->mockStmt);

        $this->mockStmt->expects($this->exactly(4))
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

    public function testDeleteUserWithNonExistentUser()
    {
        // Configure mocks - first query returns null indicating user doesn't exist
        $this->mockPDO->expects($this->exactly(1))
            ->method('prepare')
            ->willReturn($this->mockStmt);

        $this->mockStmt->expects($this->exactly(1))
            ->method('execute')
            ->willReturn(true);

        $this->mockStmt->expects($this->exactly(1))
            ->method('fetchColumn')
            ->willReturn(false); // User does not exist

        // Create a UserDelete instance
        $userDelete = new UserDelete($this->mockPDO);

        // Test the deleteUser method
        $result = $userDelete->deleteUser(999); // Non-existent user ID

        // Assert that the user was not deleted
        $this->assertFalse($result);
    }

    public function testActuallyDeleteUserWithNonExistentUser()
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
            ->with([999]) // Non-existent user ID
            ->willReturn(false);

        // Create a UserDelete instance
        $userDelete = new UserDelete($this->mockPDO);

        // Test the actuallyDeleteUser method
        $result = $userDelete->actuallyDeleteUser(999);

        // Assert that the user was not deleted
        $this->assertFalse($result);

        // Clean up the session variable
        unset($_SESSION['admin_authenticated']);
    }

    public function testDeleteUserWithDatabaseError()
    {
        // Configure mocks - only expecting 3 prepare calls when there's a database error
        $this->mockPDO->expects($this->exactly(3))
            ->method('prepare')
            ->willReturn($this->mockStmt);

        // Different approach for the execute calls
        $executeCount = 0;
        $this->mockStmt->method('execute')
            ->willReturnCallback(function($params) use (&$executeCount) {
                $executeCount++;
                // Third execute call (updating the user) will fail
                return $executeCount < 3;
            });

        $this->mockStmt->expects($this->exactly(2))
            ->method('fetchColumn')
            ->willReturnOnConsecutiveCalls('@testuser', 0);

        // Create a UserDelete instance
        $userDelete = new UserDelete($this->mockPDO);

        // Test the deleteUser method
        $result = $userDelete->deleteUser(1);

        // Assert that the user was not deleted due to database error
        $this->assertFalse($result);
    }

    public function testActuallyDeleteUserWithDatabaseError()
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
            ->willReturn(false); // Simulate database error

        // Create a UserDelete instance
        $userDelete = new UserDelete($this->mockPDO);

        // Test the actuallyDeleteUser method
        $result = $userDelete->actuallyDeleteUser(1);

        // Assert that the user was not deleted due to database error
        $this->assertFalse($result);

        // Clean up the session variable
        unset($_SESSION['admin_authenticated']);
    }
}

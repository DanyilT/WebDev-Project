<?php

namespace Models\User;

use PHPUnit\Framework\TestCase;
use PDO;

require '../../../src/Models/User/UserUpdate.php';

class UserUpdateTest extends TestCase
{
    private $mockPDO;
    private $mockStmt;

    protected function setUp(): void
    {
        // Create a mock PDO connection
        $this->mockPDO = $this->createMock(PDO::class);
        $this->mockStmt = $this->createMock(\PDOStatement::class);
    }

    public function testUpdateUser()
    {
        // Fields to update
        $updateFields = [
            'name' => 'Updated Name',
            'bio' => 'Updated Bio'
        ];

        // Configure mocks
        $this->mockPDO->expects($this->exactly(2))
            ->method('prepare')
            ->willReturn($this->mockStmt);

        $this->mockStmt->expects($this->exactly(2))
            ->method('execute')
            ->willReturn(true);

        // Create a UserUpdate instance
        $userUpdate = new UserUpdate($this->mockPDO);

        // Test the updateUser method
        $result = $userUpdate->updateUser(1, $updateFields);

        // Assert that the user was updated successfully
        $this->assertTrue($result);
    }

    public function testUpdateUserPassword()
    {
        // Configure mocks
        $this->mockPDO->expects($this->once())
            ->method('prepare')
            ->with("UPDATE users SET password = ? WHERE user_id = ?")
            ->willReturn($this->mockStmt);

        $this->mockStmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        // Create a UserUpdate instance
        $userUpdate = new UserUpdate($this->mockPDO);

        // Test the updateUserPassword method
        $result = $userUpdate->updateUserPassword(1, 'newpassword123');

        // Assert that the password was updated successfully
        $this->assertTrue($result);
    }

    public function testUpdateFollowersNewFollow()
    {
        // Configure mocks for checking if record exists
        $this->mockPDO->expects($this->exactly(2))
            ->method('prepare')
            ->willReturn($this->mockStmt);

        // First call to check if record exists should return false
        $this->mockStmt->expects($this->exactly(2))
            ->method('execute')
            ->willReturn(true);

        $this->mockStmt->expects($this->once())
            ->method('fetch')
            ->willReturn(false);

        // Create a UserUpdate instance
        $userUpdate = new UserUpdate($this->mockPDO);

        // Test the updateFollowers method for a new follow
        $result = $userUpdate->updateFollowers(1, 2);

        // Assert that the follow status was updated as expected
        $this->assertEquals('new follow', $result);
    }

    public function testUpdateFollowersFollowed()
    {
        // Configure mocks for checking if record exists
        $this->mockPDO->expects($this->exactly(2))
            ->method('prepare')
            ->willReturn($this->mockStmt);

        // First call to check if record exists should return true
        $this->mockStmt->expects($this->exactly(2))
            ->method('execute')
            ->willReturn(true);

        $this->mockStmt->expects($this->once())
            ->method('fetch')
            ->willReturn(['is_following' => 0, 'following_history' => json_encode([])]);

        // Create a UserUpdate instance
        $userUpdate = new UserUpdate($this->mockPDO);

        // Test the updateFollowers method for an existing follow
        $result = $userUpdate->updateFollowers(1, 2);

        // Assert that the follow status was updated as expected
        $this->assertEquals('followed', $result);
    }

    public function testUpdateFollowersUnfollowed()
    {
        // Configure mocks for checking if record exists
        $this->mockPDO->expects($this->exactly(2))
            ->method('prepare')
            ->willReturn($this->mockStmt);

        // First call to check if record exists should return true
        $this->mockStmt->expects($this->exactly(2))
            ->method('execute')
            ->willReturn(true);

        $this->mockStmt->expects($this->once())
            ->method('fetch')
            ->willReturn(['is_following' => 1, 'following_history' => json_encode([])]);

        // Create a UserUpdate instance
        $userUpdate = new UserUpdate($this->mockPDO);

        // Test the updateFollowers method for an existing follow
        $result = $userUpdate->updateFollowers(1, 2);

        // Assert that the follow status was updated as expected
        $this->assertEquals('unfollowed', $result);
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

        // Create a UserUpdate instance
        $userUpdate = new UserUpdate($this->mockPDO);

        // Test the isUsernameExist method
        $result = $userUpdate->isUsernameExist('testuser', $this->mockPDO);

        // Assert that the username exists
        $this->assertTrue($result);
    }

    public function testUpdateUserWithInvalidFields()
    {
        // Fields to update
        $updateFields = [
            'invalid_field' => 'Invalid Value'
        ];

        // Configure mocks
        $this->mockPDO->expects($this->once())
            ->method('prepare')
            ->willReturn($this->mockStmt);

        $this->mockStmt->expects($this->once())
            ->method('execute')
            ->willReturn(false);

        // Create a UserUpdate instance
        $userUpdate = new UserUpdate($this->mockPDO);

        // Test the updateUser method
        $result = $userUpdate->updateUser(1, $updateFields);

        // Assert that the user was not updated
        $this->assertFalse($result);
    }

    public function testUpdateUserPasswordWithInvalidPassword()
    {
        // Configure mocks
        $this->mockPDO->expects($this->once())
            ->method('prepare')
            ->with("UPDATE users SET password = ? WHERE user_id = ?")
            ->willReturn($this->mockStmt);

        $this->mockStmt->expects($this->once())
            ->method('execute')
            ->willReturn(false);

        // Create a UserUpdate instance
        $userUpdate = new UserUpdate($this->mockPDO);

        // Test the updateUserPassword method
        $result = $userUpdate->updateUserPassword(1, 'short');

        // Assert that the password was not updated
        $this->assertFalse($result);
    }

    public function testUpdateFollowersWithDatabaseError()
    {
        // Configure mocks for checking if record exists
        $this->mockPDO->expects($this->exactly(2))
            ->method('prepare')
            ->willReturn($this->mockStmt);

        // Configure the mock to return different results for different execute calls
        $this->mockStmt->expects($this->exactly(2))
            ->method('execute')
            ->willReturnOnConsecutiveCalls(true, false);

        $this->mockStmt->expects($this->once())
            ->method('fetch')
            ->willReturn(['is_following' => 0, 'following_history' => json_encode([])]);

        // Create a UserUpdate instance
        $userUpdate = new UserUpdate($this->mockPDO);

        // Test the updateFollowers method for an existing follow
        $result = $userUpdate->updateFollowers(1, 2);

        // Assert that the follow status was not updated due to database error
        $this->assertEquals('error', $result);
    }
}

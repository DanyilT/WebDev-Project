<?php

namespace Models\User;

use PHPUnit\Framework\TestCase;
use PDO;

require '../../../src/Models/User/UserRead.php';

/**
 * Class: UserReadTest.php
 * Test: Models\User\UserRead.php
 *
 * Description:
 * This test class is responsible for testing the UserRead class.
 * It includes tests for retrieving user information.
 *
 * @package Models\User
 */
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

    public function testGetAllUsers()
    {
        // Sample user data
        $userData = [
            [
                'user_id' => 1,
                'username' => '@testuser1',
                'email' => 'test1@example.com',
                'name' => 'Test User 1',
                'bio' => 'Test bio 1'
            ],
            [
                'user_id' => 2,
                'username' => '@testuser2',
                'email' => 'test2@example.com',
                'name' => 'Test User 2',
                'bio' => 'Test bio 2'
            ]
        ];

        // Configure mocks
        $this->mockPDO->expects($this->once())
            ->method('prepare')
            ->with("SELECT * FROM active_users;")
            ->willReturn($this->mockStmt);

        $this->mockStmt->expects($this->once())
            ->method('execute');

        $this->mockStmt->expects($this->once())
            ->method('fetchAll')
            ->with(PDO::FETCH_ASSOC)
            ->willReturn($userData);

        // Create a UserRead instance
        $userRead = new UserRead($this->mockPDO);

        // Test the getAllUsers method
        $result = $userRead->getAllUsers();

        // Assert that the user data matches the expected data
        $this->assertEquals($userData, $result);
    }

    public function testGetFollowers()
    {
        // Sample followers data
        $followersData = [
            [
                'user_id' => 1,
                'username' => '@follower1'
            ],
            [
                'user_id' => 2,
                'username' => '@follower2'
            ]
        ];

        // Configure mocks
        $this->mockPDO->expects($this->once())
            ->method('prepare')
            ->with("SELECT u.user_id, u.username FROM active_followers f JOIN users u ON f.follower_id = u.user_id WHERE f.following_id = ?")
            ->willReturn($this->mockStmt);

        $this->mockStmt->expects($this->once())
            ->method('execute')
            ->with([1]);

        $this->mockStmt->expects($this->once())
            ->method('fetchAll')
            ->with(PDO::FETCH_ASSOC)
            ->willReturn($followersData);

        // Create a UserRead instance
        $userRead = new UserRead($this->mockPDO);

        // Test the getFollowers method
        $result = $userRead->getFollowers(1);

        // Assert that the followers data matches the expected data
        $this->assertEquals($followersData, $result);
    }

    public function testGetFollowings()
    {
        // Sample followings data
        $followingsData = [
            [
                'user_id' => 1,
                'username' => '@following1'
            ],
            [
                'user_id' => 2,
                'username' => '@following2'
            ]
        ];

        // Configure mocks
        $this->mockPDO->expects($this->once())
            ->method('prepare')
            ->with("SELECT u.user_id, u.username FROM active_followers f JOIN users u ON f.following_id = u.user_id WHERE f.follower_id = ?")
            ->willReturn($this->mockStmt);

        $this->mockStmt->expects($this->once())
            ->method('execute')
            ->with([1]);

        $this->mockStmt->expects($this->once())
            ->method('fetchAll')
            ->with(PDO::FETCH_ASSOC)
            ->willReturn($followingsData);

        // Create a UserRead instance
        $userRead = new UserRead($this->mockPDO);

        // Test the getFollowings method
        $result = $userRead->getFollowings(1);

        // Assert that the followings data matches the expected data
        $this->assertEquals($followingsData, $result);
    }

    public function testGetFollowersCount()
    {
        // Sample followers count
        $followersCount = 5;

        // Configure mocks
        $this->mockPDO->expects($this->once())
            ->method('prepare')
            ->with("SELECT COUNT(*) as followers FROM active_followers WHERE following_id = ?")
            ->willReturn($this->mockStmt);

        $this->mockStmt->expects($this->once())
            ->method('execute')
            ->with([1]);

        $this->mockStmt->expects($this->once())
            ->method('fetchColumn')
            ->willReturn($followersCount);

        // Create a UserRead instance
        $userRead = new UserRead($this->mockPDO);

        // Test the getFollowersCount method
        $result = $userRead->getFollowersCount(1);

        // Assert that the followers count matches the expected count
        $this->assertEquals($followersCount, $result);
    }

    public function testGetFollowingsCount()
    {
        // Sample followings count
        $followingsCount = 3;

        // Configure mocks
        $this->mockPDO->expects($this->once())
            ->method('prepare')
            ->with("SELECT COUNT(*) as followings FROM active_followers WHERE follower_id = ?")
            ->willReturn($this->mockStmt);

        $this->mockStmt->expects($this->once())
            ->method('execute')
            ->with([1]);

        $this->mockStmt->expects($this->once())
            ->method('fetchColumn')
            ->willReturn($followingsCount);

        // Create a UserRead instance
        $userRead = new UserRead($this->mockPDO);

        // Test the getFollowingsCount method
        $result = $userRead->getFollowingsCount(1);

        // Assert that the followings count matches the expected count
        $this->assertEquals($followingsCount, $result);
    }

    public function testIsFollowing()
    {
        // Sample following status
        $isFollowing = true;

        // Configure mocks
        $this->mockPDO->expects($this->once())
            ->method('prepare')
            ->with("SELECT * FROM active_followers WHERE follower_id = ? AND following_id = ?")
            ->willReturn($this->mockStmt);

        $this->mockStmt->expects($this->once())
            ->method('execute')
            ->with([1, 2]);

        $this->mockStmt->expects($this->once())
            ->method('fetchColumn')
            ->willReturn($isFollowing);

        // Create a UserRead instance
        $userRead = new UserRead($this->mockPDO);

        // Test the isFollowing method
        $result = $userRead->isFollowing(1, 2);

        // Assert that the following status matches the expected status
        $this->assertTrue($result);
    }
}

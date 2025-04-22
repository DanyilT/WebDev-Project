<?php

namespace Models\User;

use PHPUnit\Framework\TestCase;
use PDO;

require '../../../src/Models/User/User.php';
require '../../../src/Models/User/UserRead.php';

/**
 * Class: UserTest.php
 * Test: Models\User\User.php
 *
 * Description:
 * This test class is responsible for testing the User class.
 * It includes tests for retrieving user information.
 *
 * @package Models\User
 */
class UserTest extends TestCase {
    private $user;
    private $mockPDO;
    private $mockStmt;

    protected function setUp(): void {
        $this->mockPDO = $this->createMock(PDO::class);
        $this->mockStmt = $this->createMock(\PDOStatement::class);
    }

    public function testGetUserId() {
        // Set up specific mock
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

        $userRead = new UserRead($this->mockPDO);
        $this->assertEquals(1, $userRead->getUserId('testuser'));
    }

    public function testGetUsername() {
        // Set up specific mock
        $this->mockPDO->expects($this->once())
            ->method('prepare')
            ->with("SELECT username FROM active_users WHERE user_id = ?")
            ->willReturn($this->mockStmt);

        $this->mockStmt->expects($this->once())
            ->method('execute')
            ->with([1]);

        $this->mockStmt->expects($this->once())
            ->method('fetchColumn')
            ->willReturn('testuser');

        $userRead = new UserRead($this->mockPDO);
        $this->assertEquals('testuser', $userRead->getUsername(1));
    }

    public function testGetUserPassword() {
        $hashedPassword = password_hash('password123', PASSWORD_DEFAULT);

        $this->mockPDO->expects($this->once())
            ->method('prepare')
            ->with("SELECT password FROM active_users WHERE username = ?")
            ->willReturn($this->mockStmt);

        $this->mockStmt->expects($this->once())
            ->method('execute')
            ->with(['@testuser']);

        $this->mockStmt->expects($this->once())
            ->method('fetchColumn')
            ->willReturn($hashedPassword);

        $userRead = new UserRead($this->mockPDO);
        $this->assertTrue(password_verify('password123', $userRead->getUserPassword('testuser')));
    }
}

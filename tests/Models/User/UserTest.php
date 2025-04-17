<?php

namespace Models\User;

use PHPUnit\Framework\TestCase;
use PDO;

require '../../../src/Models/User/User.php';
require '../../../src/Models/User/UserRead.php';

class UserTest extends TestCase {
    private $user;
    private $mockPDO;
    private $mockStmt;

    protected function setUp(): void {
        $this->mockPDO = $this->createMock(PDO::class);
        $this->mockStmt = $this->createMock(\PDOStatement::class);

        $this->mockPDO->expects($this->any())
            ->method('prepare')
            ->willReturn($this->mockStmt);

        $this->mockStmt->expects($this->any())
            ->method('execute')
            ->willReturn(true);

        $this->mockStmt->expects($this->any())
            ->method('fetch')
            ->willReturn([
                'user_id' => 1,
                'username' => 'testuser',
                'email' => 'test@example.com',
                'name' => 'Test User',
                'bio' => 'This is a bio',
                'profile_pic' => 'profile.jpg'
            ]);

        $this->user = $this->getMockForAbstractClass(User::class, [$this->mockPDO]);
    }

    public function testGetUserId() {
        $userRead = new UserRead($this->mockPDO);
        $this->assertEquals(1, $userRead->getUserId('testuser'));
    }

    public function testGetUsername() {
        $userRead = new UserRead($this->mockPDO);
        $this->assertEquals('testuser', $userRead->getUsername(1));
    }

    public function testGetUserPassword() {
        $this->mockStmt->expects($this->any())
            ->method('fetchColumn')
            ->willReturn(password_hash('password123', PASSWORD_DEFAULT));

        $userRead = new UserRead($this->mockPDO);
        $this->assertTrue(password_verify('password123', $userRead->getUserPassword('testuser')));
    }

    public function testGetEmail() {
        $this->assertEquals('test@example.com', $this->user->getEmail());
    }

    public function testGetName() {
        $this->assertEquals('Test User', $this->user->getName());
    }

    public function testGetBio() {
        $this->assertEquals('This is a bio', $this->user->getBio());
    }

    public function testGetProfilePic() {
        $this->assertEquals('profile.jpg', $this->user->getProfilePic());
    }

    public function testDisplayUserInfo() {
        $expected = "Username: testuser, Email: test@example.com, Name: Test User, Bio: This is a bio, Profile Pic: profile.jpg";
        $this->assertEquals($expected, $this->user->displayUserInfo());
    }
}

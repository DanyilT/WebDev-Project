<?php

use PHPUnit\Framework\TestCase;

class UserTest extends TestCase {
    private $user;

    protected function setUp(): void {
        $this->user = $this->getMockForAbstractClass(User::class, ['testuser', 'test@example.com', 'Test User', 'This is a bio', 'profile.jpg']);
    }

    public function testGetUsername() {
        $this->assertEquals('testuser', $this->user->getUsername());
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

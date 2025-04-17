<?php

namespace Models\Post;

use PHPUnit\Framework\TestCase;

require '../../../src/Models/Post/Post.php';

class PostTest extends TestCase {
    private $post;

    protected function setUp(): void {
        $this->post = new Post(1, 1, 'Test Title', 'Test Content', 'test.jpg', [1, 2], '2023-01-01 00:00:00', false, 'testuser');
    }

    public function test__construct() {
        $this->assertInstanceOf(Post::class, $this->post);
        $this->assertEquals(1, $this->post->getPostId());
        $this->assertEquals(1, $this->post->getUserId());
        $this->assertEquals('Test Title', $this->post->getTitle());
        $this->assertEquals('Test Content', $this->post->getContent());
        $this->assertEquals('test.jpg', $this->post->getMedia());
        $this->assertEquals([1, 2], $this->post->getLikes());
        $this->assertEquals('2023-01-01 00:00:00', $this->post->getCreatedAt());
        $this->assertFalse($this->post->getIsDeleted());
        $this->assertEquals('testuser', $this->post->getCreatorUsername());
    }


    public function testGetPostId() {
        $this->assertEquals(1, $this->post->getPostId());
    }

    public function testGetUserId() {
        $this->assertEquals(1, $this->post->getUserId());
    }

    public function testGetTitle() {
        $this->assertEquals('Test Title', $this->post->getTitle());
    }

    public function testGetContent() {
        $this->assertEquals('Test Content', $this->post->getContent());
    }

    public function testGetMedia() {
        $this->assertEquals('test.jpg', $this->post->getMedia());
    }

    public function testGetLikes() {
        $this->assertEquals([1, 2], $this->post->getLikes());
    }

    public function testGetCreatedAt() {
        $this->assertEquals('2023-01-01 00:00:00', $this->post->getCreatedAt());
    }

    public function testGetIsDeleted() {
        $this->assertFalse($this->post->getIsDeleted());
    }

    public function testGetCreatorUsername() {
        $this->assertEquals('testuser', $this->post->getCreatorUsername());
    }
}

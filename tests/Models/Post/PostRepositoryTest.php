<?php

namespace Models\Post;

use PHPUnit\Framework\TestCase;
use PDO;

require '../../../src/Models/Post/PostRepository.php';

class PostRepositoryTest extends TestCase {
    private $postRepository;
    private $mockPDO;
    private $mockStmt;

    protected function setUp(): void {
        $this->postRepository = new PostRepository();
        $this->mockPDO = $this->createMock(PDO::class);
        $this->mockStmt = $this->createMock(\PDOStatement::class);
    }

    public function testGetAllPosts() {
        $this->mockPDO->expects($this->once())
            ->method('query')
            ->willReturn($this->mockStmt);

        $this->mockStmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([['post_id' => 1, 'user_id' => 1, 'title' => 'Test Title', 'content' => 'Test Content', 'media' => 'test.jpg', 'likes' => json_encode([1, 2]), 'created_at' => '2023-01-01 00:00:00', 'is_deleted' => false, 'username' => 'testuser']]);

        $result = $this->postRepository->getAllPosts($this->mockPDO);
        $this->assertEquals([['post_id' => 1, 'user_id' => 1, 'title' => 'Test Title', 'content' => 'Test Content', 'media' => 'test.jpg', 'likes' => json_encode([1, 2]), 'created_at' => '2023-01-01 00:00:00', 'is_deleted' => false, 'username' => 'testuser']], $result);
    }

    public function testGetAllPostsEvenIfDeleted() {
        $this->mockPDO->expects($this->once())
            ->method('query')
            ->willReturn($this->mockStmt);

        $this->mockStmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([['post_id' => 1, 'user_id' => 1, 'title' => 'Test Title', 'content' => 'Test Content', 'media' => 'test.jpg', 'likes' => json_encode([1, 2]), 'created_at' => '2023-01-01 00:00:00', 'is_deleted' => true, 'username' => 'testuser']]);

        $result = $this->postRepository->getAllPostsEvenIfDeleted($this->mockPDO);
        $this->assertEquals([['post_id' => 1, 'user_id' => 1, 'title' => 'Test Title', 'content' => 'Test Content', 'media' => 'test.jpg', 'likes' => json_encode([1, 2]), 'created_at' => '2023-01-01 00:00:00', 'is_deleted' => true, 'username' => 'testuser']], $result);
    }

    public function testGetUserPosts() {
        $this->mockPDO->expects($this->once())
            ->method('prepare')
            ->willReturn($this->mockStmt);

        $this->mockStmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->mockStmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([['post_id' => 1, 'user_id' => 1, 'title' => 'Test Title', 'content' => 'Test Content', 'media' => 'test.jpg', 'likes' => json_encode([1, 2]), 'created_at' => '2023-01-01 00:00:00', 'is_deleted' => false, 'username' => 'testuser']]);

        $result = $this->postRepository->getUserPosts($this->mockPDO, 1);
        $this->assertEquals([['post_id' => 1, 'user_id' => 1, 'title' => 'Test Title', 'content' => 'Test Content', 'media' => 'test.jpg', 'likes' => json_encode([1, 2]), 'created_at' => '2023-01-01 00:00:00', 'is_deleted' => false, 'username' => 'testuser']], $result);
    }

    public function testGetUserPostsEvenIfDeleted() {
        $this->mockPDO->expects($this->once())
            ->method('prepare')
            ->willReturn($this->mockStmt);

        $this->mockStmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->mockStmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([['post_id' => 1, 'user_id' => 1, 'title' => 'Test Title', 'content' => 'Test Content', 'media' => 'test.jpg', 'likes' => json_encode([1, 2]), 'created_at' => '2023-01-01 00:00:00', 'is_deleted' => true, 'username' => 'testuser']]);

        $result = $this->postRepository->getUserPostsEvenIfDeleted($this->mockPDO, 1);
        $this->assertEquals([['post_id' => 1, 'user_id' => 1, 'title' => 'Test Title', 'content' => 'Test Content', 'media' => 'test.jpg', 'likes' => json_encode([1, 2]), 'created_at' => '2023-01-01 00:00:00', 'is_deleted' => true, 'username' => 'testuser']], $result);
    }

    public function testGetPostById() {
        $this->mockPDO->expects($this->once())
            ->method('prepare')
            ->willReturn($this->mockStmt);

        $this->mockStmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->mockStmt->expects($this->once())
            ->method('fetch')
            ->willReturn(['post_id' => 1, 'user_id' => 1, 'title' => 'Test Title', 'content' => 'Test Content', 'media' => 'test.jpg', 'likes' => json_encode([1, 2]), 'created_at' => '2023-01-01 00:00:00', 'is_deleted' => false, 'username' => 'testuser']);

        $result = $this->postRepository->getPostById($this->mockPDO, 1);
        $this->assertInstanceOf(Post::class, $result);
        $this->assertEquals(1, $result->getPostId());
    }

    public function testCreatePost() {
        $post = new Post(1, 1, 'Test Title', 'Test Content', 'test.jpg', [1, 2], '2023-01-01 00:00:00', false, 'testuser');

        // We need to expect two different prepare calls
        $this->mockPDO->expects($this->exactly(2))
            ->method('prepare')
            ->willReturnCallback(function($sql) {
                return $this->mockStmt;
            });

        // Execute will be called twice - once for insert, once for select
        $this->mockStmt->expects($this->exactly(2))
            ->method('execute')
            ->willReturn(true);

        $this->mockPDO->expects($this->once())
            ->method('lastInsertId')
            ->willReturn('1');

        $this->mockStmt->expects($this->once())
            ->method('fetch')
            ->willReturn(['post_id' => 1, 'user_id' => 1, 'title' => 'Test Title', 'content' => 'Test Content', 'media' => 'test.jpg', 'likes' => json_encode([1, 2]), 'created_at' => '2023-01-01 00:00:00', 'is_deleted' => false, 'username' => 'testuser']);

        $result = $this->postRepository->createPost($this->mockPDO, $post);
        $this->assertInstanceOf(Post::class, $result);
        $this->assertEquals(1, $result->getPostId());
    }

    public function testUpdatePost() {
        $updates = ['title' => 'Updated Title', 'content' => 'Updated Content', 'media' => 'updated.jpg'];

        $this->mockPDO->expects($this->exactly(3))
            ->method('prepare')
            ->willReturn($this->mockStmt);

        $this->mockStmt->expects($this->exactly(3))
            ->method('execute')
            ->willReturn(true);

        $this->mockStmt->expects($this->once())
            ->method('fetchColumn')
            ->willReturn(1);

        $this->mockStmt->expects($this->once())
            ->method('fetch')
            ->willReturn(['post_id' => 1, 'user_id' => 1, 'title' => 'Updated Title', 'content' => 'Updated Content', 'media' => 'updated.jpg', 'likes' => json_encode([1, 2]), 'created_at' => '2023-01-01 00:00:00', 'is_deleted' => false, 'username' => 'testuser']);

        $result = $this->postRepository->updatePost($this->mockPDO, 1, 1, $updates);
        $this->assertInstanceOf(Post::class, $result);
        $this->assertEquals('Updated Title', $result->getTitle());
    }

    public function testSoftDeletePost() {
        $this->mockPDO->expects($this->exactly(2))
            ->method('prepare')
            ->willReturn($this->mockStmt);

        $this->mockStmt->expects($this->exactly(2))
            ->method('execute')
            ->willReturn(true);

        $this->mockStmt->expects($this->once())
            ->method('fetchColumn')
            ->willReturn(1);

        $result = $this->postRepository->softDeletePost($this->mockPDO, 1, 1);
        $this->assertTrue($result);
    }

    public function testActuallyDeletePost() {
        $this->mockPDO->expects($this->exactly(2))
            ->method('prepare')
            ->willReturn($this->mockStmt);

        $this->mockStmt->expects($this->exactly(2))
            ->method('execute')
            ->willReturn(true);

        $this->mockStmt->expects($this->once())
            ->method('fetchColumn')
            ->willReturn(1);

        $result = $this->postRepository->actuallyDeletePost($this->mockPDO, 1, 1);
        $this->assertTrue($result);
    }
}

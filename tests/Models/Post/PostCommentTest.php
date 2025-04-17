<?php

namespace Models\Post;

use PHPUnit\Framework\TestCase;
use PDO;

require '../../../src/Models/Post/PostComment.php';

class PostCommentTest extends TestCase {
    private $postComment;
    private $mockPDO;
    private $mockStmt;

    protected function setUp(): void {
        $this->mockPDO = $this->createMock(PDO::class);
        $this->mockStmt = $this->createMock(\PDOStatement::class);
        $this->postComment = new PostComment(1, 1, 'Test Title', 'Test Content', 'test.jpg', [1, 2], '2023-01-01 00:00:00', false, 'testuser');
    }

    public function testAddComment() {
        $this->mockPDO->expects($this->once())
            ->method('prepare')
            ->willReturn($this->mockStmt);

        $this->mockStmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->mockStmt->expects($this->once())
            ->method('fetch')
            ->willReturn(['comment_id' => 1, 'content' => 'Test Comment', 'created_at' => '2023-01-01 00:00:00', 'username' => 'testuser']);

        $result = $this->postComment->addComment($this->mockPDO, 1, 'Test Comment');
        $this->assertEquals(['comment_id' => 1, 'content' => 'Test Comment', 'created_at' => '2023-01-01 00:00:00', 'username' => 'testuser'], $result);
    }

    public function testGetComments() {
        $this->mockPDO->expects($this->once())
            ->method('prepare')
            ->willReturn($this->mockStmt);

        $this->mockStmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->mockStmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([['comment_id' => 1, 'content' => 'Test Comment', 'created_at' => '2023-01-01 00:00:00', 'username' => 'testuser']]);

        $result = $this->postComment->getComments($this->mockPDO);
        $this->assertEquals([['comment_id' => 1, 'content' => 'Test Comment', 'created_at' => '2023-01-01 00:00:00', 'username' => 'testuser']], $result);
    }

    public function testGetCommentCount() {
        $this->mockPDO->expects($this->once())
            ->method('prepare')
            ->willReturn($this->mockStmt);

        $this->mockStmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->mockStmt->expects($this->once())
            ->method('fetchColumn')
            ->willReturn(1);

        $result = $this->postComment->getCommentCount($this->mockPDO);
        $this->assertEquals(1, $result);
    }

    public function testGetCommentById() {
        $this->mockPDO->expects($this->once())
            ->method('prepare')
            ->willReturn($this->mockStmt);

        $this->mockStmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->mockStmt->expects($this->once())
            ->method('fetch')
            ->willReturn(['comment_id' => 1, 'content' => 'Test Comment', 'created_at' => '2023-01-01 00:00:00', 'username' => 'testuser']);

        $result = $this->postComment->getCommentById($this->mockPDO, 1);
        $this->assertEquals(['comment_id' => 1, 'content' => 'Test Comment', 'created_at' => '2023-01-01 00:00:00', 'username' => 'testuser'], $result);
    }

    public function testSoftDeleteComment() {
        $this->mockPDO->expects($this->once())
            ->method('prepare')
            ->willReturn($this->mockStmt);

        $this->mockStmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $result = $this->postComment->softDeleteComment($this->mockPDO, 1, 1);
        $this->assertTrue($result);
    }
}

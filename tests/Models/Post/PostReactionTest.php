<?php

namespace Models\Post;

use PHPUnit\Framework\TestCase;
use PDO;

require '../../../src/Models/Post/PostReaction.php';

class PostReactionTest extends TestCase {
    private $postReaction;
    private $mockPDO;
    private $mockStmt;

    protected function setUp(): void {
        $this->postReaction = new PostReaction();
        $this->mockPDO = $this->createMock(PDO::class);
        $this->mockStmt = $this->createMock(\PDOStatement::class);
    }

    public function testLikePost() {
        // Need to expect 2 prepare calls - one for SELECT, one for UPDATE
        $this->mockPDO->expects($this->exactly(2))
            ->method('prepare')
            ->willReturn($this->mockStmt);

        // First execute for the SELECT query
        $this->mockStmt->expects($this->exactly(2))
            ->method('execute')
            ->willReturn(true);

        $this->mockStmt->expects($this->once())
            ->method('fetch')
            ->willReturn(['likes' => json_encode([1, 2])]);

        $result = $this->postReaction->likePost($this->mockPDO, 1, 3);
        $this->assertTrue($result);
    }

    public function testDislikePost() {
        // Need to expect 2 prepare calls - one for SELECT, one for UPDATE
        $this->mockPDO->expects($this->exactly(2))
            ->method('prepare')
            ->willReturn($this->mockStmt);

        // Execute will be called twice
        $this->mockStmt->expects($this->exactly(2))
            ->method('execute')
            ->willReturn(true);

        $this->mockStmt->expects($this->once())
            ->method('fetch')
            ->willReturn(['likes' => json_encode([1, 2, 3])]);

        $result = $this->postReaction->dislikePost($this->mockPDO, 1, 3);
        $this->assertTrue($result);
    }

    public function testGetLikes() {
        $this->mockPDO->expects($this->once())
            ->method('prepare')
            ->willReturn($this->mockStmt);

        $this->mockStmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->mockStmt->expects($this->once())
            ->method('fetch')
            ->willReturn(['likes' => json_encode([1, 2, 3])]);

        $result = $this->postReaction->getLikes($this->mockPDO, 1);
        $this->assertEquals([1, 2, 3], $result);
    }

    public function testGetLikesCount() {
        // getLikesCount calls getLikes internally, so prepare is called twice
        $this->mockPDO->expects($this->exactly(2))
            ->method('prepare')
            ->willReturn($this->mockStmt);

        // Execute will be called twice
        $this->mockStmt->expects($this->exactly(2))
            ->method('execute')
            ->willReturn(true);

        // Fetch will be called twice
        $this->mockStmt->expects($this->exactly(2))
            ->method('fetch')
            ->willReturn(['likes' => json_encode([1, 2, 3])]);

        $result = $this->postReaction->getLikesCount($this->mockPDO, 1);
        $this->assertEquals(3, $result);
    }
}

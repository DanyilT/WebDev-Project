<?php

namespace Models\Post;

use PDO;

require_once 'Post.php';

/**
 * Class PostComment
 * @package Models\Post
 *
 * This class is responsible for handling comments on posts in the database.
 * It provides methods to add, retrieve, and delete comments.
 */
class PostComment extends Post {
    /**
     * Adds a new comment to the 'comments' table.
     *
     * @param PDO $connection
     * @param int $userId
     * @param string $commentContent
     *
     * @return array|null
     */
    public function addComment(PDO $connection, int $userId, string $commentContent): ?array {
        $stmt = $connection->prepare("INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?)");
        $stmt->execute([$this->getPostId(), $userId, $commentContent]);
        return $this->getCommentById($connection, $connection->lastInsertId());
    }

    /**
     * Retrieves not-deleted comments for this post.
     *
     * @param PDO $connection
     *
     * @return array
     */
    public function getComments(PDO $connection): array {
        $stmt = $connection->prepare("SELECT c.comment_id, c.content, c.created_at, u.username FROM active_comments c JOIN users u ON c.user_id = u.user_id WHERE c.post_id = ? ORDER BY c.created_at ASC");
        $stmt->execute([$this->getPostId()]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieves the count of not-deleted comments for this post.
     *
     * @param PDO $connection
     *
     * @return int
     */
    public function getCommentCount(PDO $connection): int {
        $stmt = $connection->prepare("SELECT COUNT(*) FROM active_comments WHERE post_id = ?");
        $stmt->execute([$this->getPostId()]);
        return (int)$stmt->fetchColumn();
    }

    /**
     * Retrieves a specific comment by its ID.
     *
     * @param PDO $connection
     * @param int $commentId
     *
     * @return array|null
     */
    public function getCommentById(PDO $connection, int $commentId): ?array {
        $stmt = $connection->prepare("SELECT c.comment_id, c.content, c.created_at, u.username FROM active_comments c JOIN users u ON c.user_id = u.user_id WHERE c.comment_id = ?");
        $stmt->execute([$commentId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Soft-deletes a specific comment if the user is its owner.
     *
     * @param PDO $connection
     * @param int $commentId
     * @param int $ownerId
     *
     * @return bool
     */
    public function softDeleteComment(PDO $connection, int $commentId, int $ownerId): bool {
        $stmt = $connection->prepare("UPDATE comments SET is_deleted = 1 WHERE comment_id = ? AND user_id = ?");
        return $stmt->execute([$commentId, $ownerId]);
    }
}

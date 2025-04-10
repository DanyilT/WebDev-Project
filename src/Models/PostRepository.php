<?php

namespace Models\Post;

use PDO;

require_once 'Post.php';

class PostRepository {
    /**
     * Retrieves all posts from the database
     *
     * @param PDO $connection
     *
     * @return array
     */
    public function getAllPosts(PDO $connection): array {
        $stmt = $connection->query("SELECT p.*, u.username FROM active_posts p JOIN users u ON p.user_id = u.user_id ORDER BY p.created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieves the posts of a user by their user ID
     * And includes the username of the user who created the post
     * With an optional limit on the number of posts
     *
     * @param PDO $connection
     * @param int $userId
     * @param int|null $limit
     *
     * @return array
     */
    public function getUserPosts(PDO $connection, int $userId, int $limit = null): array {
        $stmt = $connection->prepare("SELECT p.*, u.username FROM active_posts p JOIN users u ON p.user_id = u.user_id WHERE p.user_id = ? ORDER BY p.created_at DESC" . ($limit ? " LIMIT $limit;" : ';'));
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieves a post by its ID
     *
     * @param PDO $connection
     * @param int $postId
     *
     * @return Post|null
     */
    public function getPostById(PDO $connection, int $postId): ?Post {
        $stmt = $connection->prepare("SELECT p.*, u.username FROM active_posts p JOIN users u ON p.user_id = u.user_id WHERE p.post_id = ?");
        $stmt->execute([$postId]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return new Post(
            $data['post_id'],
            $data['user_id'],
            $data['title'],
            $data['content'],
            $data['media'],
            $data['likes'],
            $data['created_at'],
            $data['is_deleted'],
            $data['username']
        );
    }

    /**
     * Creates a new post in the database
     * And returns the created post
     *
     * @param PDO $connection
     * @param Post $data
     *
     * @return Post|null
     */
    public function createPost(PDO $connection, Post $data): ?Post {
        $stmt = $connection->prepare("INSERT INTO posts (user_id, title, content, media) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$data->getUserId(), $data->getTitle(), $data->getContent(), $data->getMedia()])) {
            $postId = $connection->lastInsertId();
            return $this->getPostById($connection, $postId);
        }
        return null;
    }

    /**
     * Updates a post in the database
     * And returns the updated post
     *
     * @param PDO $connection
     * @param int $postId
     * @param int $ownerId
     * @param array $updates
     *
     * @return Post|null
     */
    public function updatePost(PDO $connection, int $postId, int $ownerId, array $updates): ?Post {
        // Check ownership
        $checkStmt = $connection->prepare("SELECT user_id FROM active_posts WHERE post_id = ?");
        $checkStmt->execute([$postId]);
        $postUserId = $checkStmt->fetchColumn();
        if ((int)$postUserId !== $ownerId) {
            return null;
        }

        // Update
        $stmt = $connection->prepare("UPDATE posts SET title = ?, content = ?, media = ? WHERE post_id = ?");
        $stmt->execute([$updates['title'], $updates['content'], $updates['media'], $postId]);
        return $this->getPostById($connection, $postId);
    }

    /**
     * Soft deletes a post in the database
     *
     * @param PDO $connection
     * @param int $postId
     * @param int $ownerId
     *
     * @return bool
     */
    public function softDeletePost(PDO $connection, int $postId, int $ownerId): bool {
        // Check ownership
        $checkStmt = $connection->prepare("SELECT user_id FROM active_posts WHERE post_id = ?");
        $checkStmt->execute([$postId]);
        $postUserId = $checkStmt->fetchColumn();
        if ((int)$postUserId !== $ownerId) {
            return false;
        }

        // Soft delete
        $stmt = $connection->prepare("UPDATE posts SET is_deleted = 1 WHERE post_id = ?");
        return $stmt->execute([$postId]);
    }
}

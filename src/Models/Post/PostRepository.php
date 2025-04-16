<?php

namespace Models\Post;

use PDO;

require_once 'Post.php';

/**
 * Class PostRepository
 * This class is responsible for interacting with the database to manage posts.
 * It provides methods to create, read, update, and delete posts.
 *
 * @package Models\Post
 */
class PostRepository {
    /**
     * Retrieves all posts from the database
     *
     * @param PDO $connection
     * @param int $offset
     * @param int|null $limit
     *
     * @return array
     */
    public function getAllPosts(PDO $connection, int $offset = 0, int $limit = null): array {
        $stmt = $connection->query("SELECT p.*, u.username FROM active_posts p JOIN users u ON p.user_id = u.user_id ORDER BY p.created_at DESC" . ($limit ? " LIMIT $offset, $limit;" : ';'));
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieves all posts from the database even if they are deleted
     *
     * @param PDO $connection
     * @param int $offset
     * @param int|null $limit
     *
     * @return array
     */
    public function getAllPostsEvenIfDeleted(PDO $connection, int $offset = 0, int $limit = null): array {
        $stmt = $connection->query("SELECT p.*, u.username FROM posts p JOIN users u ON p.user_id = u.user_id ORDER BY p.created_at DESC" . ($limit ? " LIMIT $offset, $limit;" : ';'));
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieves the posts of a user by their user ID
     * And includes the username of the user who created the post
     * With an optional limit on the number of posts
     *
     * @param PDO $connection
     * @param int $userId
     * @param int $offset
     * @param int|null $limit
     *
     * @return array
     */
    public function getUserPosts(PDO $connection, int $userId, int $offset = 0, int $limit = null): array {
        $stmt = $connection->prepare("SELECT p.*, u.username FROM active_posts p JOIN users u ON p.user_id = u.user_id WHERE p.user_id = ? ORDER BY p.created_at DESC" . ($limit ? " LIMIT $offset, $limit;" : ';'));
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserPostsEvenIfDeleted(PDO $connection, int $userId, int $offset = 0, int $limit = null): array {
        $stmt = $connection->prepare("SELECT p.*, u.username FROM posts p JOIN users u ON p.user_id = u.user_id WHERE p.user_id = ? ORDER BY p.created_at DESC" . ($limit ? " LIMIT $offset, $limit;" : ';'));
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieves a post by its ID
     *
     * @param PDO $connection
     * @param int $postId
     *
     * @return Post|null Returns the post object or null if not found
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
            (array)$data['likes'],
            $data['created_at'],
            $data['is_deleted'],
            $data['username']
        );
    }

    /**
     * Creates a new post in the database
     *
     * @param PDO $connection
     * @param Post $data
     *
     * @return Post|null Returns the created post or null if the creation failed
     */
    public function createPost(PDO $connection, Post $data): ?Post {
        // Input validation
        if (empty($data->getTitle()) || empty($data->getContent())) {
            throw new \InvalidArgumentException("Title and content are required.");
        }

        // Error handling
        try {
            $stmt = $connection->prepare("INSERT INTO posts (user_id, title, content, media) VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$data->getUserId(), $data->getTitle(), $data->getContent(), $data->getMedia()])) {
                $postId = $connection->lastInsertId();
                return $this->getPostById($connection, $postId);
            }
        } catch (\PDOException $e) {
            error_log('Error creating post: ' . $e->getMessage());
            return null;
        }

        return null;
    }

    /**
     * Updates a post in the database
     *
     * @param PDO $connection
     * @param int $postId
     * @param int $ownerId
     * @param array $updates
     *
     * @return Post|null Returns the updated post or null if the update failed
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

    /**
     * Permanently deletes a post from the database
     *
     * @param PDO $connection
     * @param int $postId
     * @param int $ownerId
     *
     * @return bool
     */
    public function actuallyDeletePost(PDO $connection, int $postId, int $ownerId): bool {
        // Check ownership
        $checkStmt = $connection->prepare("SELECT user_id FROM posts WHERE post_id = ?");
        $checkStmt->execute([$postId]);
        $postUserId = $checkStmt->fetchColumn();
        if ((int)$postUserId !== $ownerId) {
            return false;
        }

        // Permanently delete
        $stmt = $connection->prepare("DELETE FROM posts WHERE post_id = ?");
        return $stmt->execute([$postId]);
    }
}

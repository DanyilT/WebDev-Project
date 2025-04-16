<?php

namespace Models\Post;

use PDO;

require_once 'Post.php';

/**
 * Class PostReaction
 * Handles post reactions (likes/dislikes) in the database.
 * Provides methods to like, dislike, and retrieve likes for a post.
 *
 * @package Models\Post
 */
class PostReaction {
    /**
     * Adds a user ID to the JSON likes array in the posts table
     *
     * @param PDO $connection
     * @param int $postId
     * @param int $reactorUserId
     *
     * @return bool
     */
    public function likePost(PDO $connection, int $postId, int $reactorUserId): bool {
        $stmt = $connection->prepare("SELECT likes FROM active_posts WHERE post_id = ?");
        $stmt->execute([$postId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return false;
        }

        $likesArray = json_decode($row['likes']);
        if (!is_array($likesArray)) {
            $likesArray = [];
        }
        if (!in_array($reactorUserId, $likesArray)) {
            $likesArray[] = $reactorUserId;
        }

        $newLikes = json_encode($likesArray);
        $updateStmt = $connection->prepare("UPDATE posts SET likes = ? WHERE post_id = ?");
        return $updateStmt->execute([$newLikes, $postId]);
    }

    /**
     * Removes a user ID from the JSON likes array in the posts table
     *
     * @param PDO $connection
     * @param int $postId
     * @param int $reactorUserId
     *
     * @return bool
     */
    public function dislikePost(PDO $connection, int $postId, int $reactorUserId): bool {
        $stmt = $connection->prepare("SELECT likes FROM active_posts WHERE post_id = ?");
        $stmt->execute([$postId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return false;
        }

        $likesArray = json_decode($row['likes']);
        if (!is_array($likesArray)) {
            return false;
        }
        if (($key = array_search($reactorUserId, $likesArray)) !== false) {
            unset($likesArray[$key]);
        }

        $newLikes = json_encode(array_values($likesArray));
        $updateStmt = $connection->prepare("UPDATE posts SET likes = ? WHERE post_id = ?");
        return $updateStmt->execute([$newLikes, $postId]);
    }

    /**
     * Retrieves the likes for a specific post
     *
     * @param PDO $connection
     * @param int $postId
     *
     * @return array|null
     */
    public function getLikes(PDO $connection, int $postId): ?array {
        $stmt = $connection->prepare("SELECT likes FROM active_posts WHERE post_id = ?");
        $stmt->execute([$postId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return json_decode($row['likes']);
    }

    /**
     * Retrieves the count of likes for a specific post
     *
     * @param PDO $connection
     * @param int $postId
     *
     * @return int
     */
    public function getLikesCount(PDO $connection, int $postId): int {
        return $this->getLikes($connection, $postId) ? count($this->getLikes($connection, $postId)) : 0;
    }
}

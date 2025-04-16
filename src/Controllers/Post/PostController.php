<?php

namespace Controllers\Post;

use Models\Post\Post;
use Models\Post\PostRepository;
use PDO;

require_once __DIR__.'/../../Models/Post/Post.php';
require_once __DIR__.'/../../Models/Post/PostRepository.php';

/**
 * Class PostController
 * Handles post-related operations.
 * This class is responsible for managing posts, including creating, updating, deleting, and retrieving posts.
 * It interacts with the PostRepository to perform CRUD operations on posts.
 *
 * @package Controllers\Post
 */
class PostController {
    private PDO $connection;
    private PostRepository $postRepository;

    /**
     * PostController constructor.
     * Initializes the database connection and the PostRepository.
     *
     * @param PDO $connection
     */
    public function __construct(PDO $connection) {
        $this->connection = $connection;
        $this->postRepository = new PostRepository();
    }

    /**
     * Displays all posts or the posts of a specific user.
     *
     * @param int|array|null $userId The ID of the user whose posts to display. If null, all posts are displayed.
     * @param int $offset The offset for pagination. Default is 0.
     * @param int|null $limit The maximum number of posts to display. If null, all posts are displayed.
     *
     * @return string The rendered HTML for the posts. (require the view file)
     */
    public function index(int|array $userId = null, int $offset = 0, int $limit = null): string {
        if ($userId) {
            if (is_array($userId)) {
                $postsArray = $this->getUsersPosts($userId, $offset, $limit);
            } else {
                $postsArray = $this->getUserPosts($userId, $offset, $limit);
            }
        } else {
            $postsArray = $this->getAllPosts($offset, $limit);
        }
        $posts = [];
        foreach ($postsArray as $row) {
            $posts[] = new Post(
                $row['post_id'],
                $row['user_id'],
                $row['title'],
                $row['content'],
                $row['media'] ?? null,
                (array) $row['likes'] ?? null,
                $row['created_at'] ?? null,
                $row['is_deleted'] ?? false,
                $row['username'] ?? null
            );
        }
        $connection = $this->connection;
        return require_once __DIR__ . '/../../Views/Post/index.php';
    }

    /**
     * Retrieves all posts from the database.
     *
     * @param int $offset
     * @param int|null $limit
     *
     * @return array
     */
    public function getAllPosts(int $offset = 0, int $limit = null): array {
        return $this->postRepository->getAllPosts($this->connection, $offset, $limit);
    }

    /**
     * Retrieves the posts of multiple users by their user IDs.
     * And includes the username of the user who created the post
     * With an optional limit on the number of posts
     *
     * @param array $userIds
     * @param int $offset
     * @param int|null $limit
     *
     * @return array
     */
    public function getUsersPosts(array $userIds, int $offset = 0, int $limit = null): array {
        $allPosts = [];
        foreach ($userIds as $userId) {
            $userPosts = $this->getUserPosts($userId);
            $allPosts = array_merge($allPosts, $userPosts);
        }
        usort($allPosts, function ($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });
        if ($limit) {
            return array_slice($allPosts, $offset, $limit);
        } else {
            return $allPosts;
        }
    }

    /**
     * Retrieves the posts of a user by their user ID.
     * And includes the username of the user who created the post
     * With an optional limit on the number of posts
     *
     * @param int $userId
     * @param int $offset
     * @param int|null $limit
     *
     * @return array
     */
    public function getUserPosts(int $userId, int $offset = 0, int $limit = null): array {
        return $this->postRepository->getUserPosts($this->connection, $userId, $offset, $limit);
    }

    /**
     * Retrieves a post by its ID.
     *
     * @param int $postId
     *
     * @return Post|null
     */
    public function show(int $postId): ?Post {
        return $this->postRepository->getPostById($this->connection, $postId);
    }

    /**
     * Creates a new post.
     *
     * @param int $userId
     * @param string $title
     * @param string $content
     * @param string|null $media
     *
     * @return Post|null
     */
    public function create(int $userId, string $title, string $content, string $media = null): ?Post {
        $post = new Post((int)null, $userId, $title, $content, $media);
        return $this->postRepository->createPost($this->connection, $post);
    }

    /**
     * Updates a post by its ID and the owner's ID.
     *
     * @param int $postId
     * @param int $ownerId
     * @param string $title
     * @param string $content
     * @param string|null $media
     *
     * @return Post|null
     */
    public function update(int $postId, int $ownerId, string $title, string $content, string $media = null): ?Post {
        $updates = [
            'title' => $title,
            'content' => $content,
            'media' => $media
        ];
        return $this->postRepository->updatePost($this->connection, $postId, $ownerId, $updates);
    }

    /**
     * Soft deletes a post by its ID and the owner's ID.
     *
     * @param int $postId
     * @param int $ownerId
     *
     * @return bool
     */
    public function delete(int $postId, int $ownerId): bool {
        return $this->postRepository->softDeletePost($this->connection, $postId, $ownerId);
    }
}

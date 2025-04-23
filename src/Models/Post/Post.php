<?php

namespace Models\Post;

/**
 * Class Post
 * Represents a post in the system.
 * This class provides methods to manage post data and interactions with the database.
 *
 * @package Models\Post
 */
class Post {
    private int $postId;
    private int $userId;
    private string $title;
    private string $content;
    private ?string $media;
    private ?array $likes;
    private ?string $createdAt;
    private bool $isDeleted;
    private ?string $creatorUsername;

    /**
     * Post constructor.
     *
     * @param int $postId Post ID
     * @param int $userId User ID of the post creator
     * @param string $title Post title
     * @param string $content Post content
     * @param string|null $media Post media (optional)
     * @param array|null $likes Post likes - array of ints (optional)
     * @param string|null $createdAt Post creation date (optional)
     * @param bool $isDeleted Indicates if the post is deleted
     * @param string|null $creatorUsername Username of the post creator (optional)
     */
    public function __construct(int $postId, int $userId, string $title, string $content, ?string $media = null, ?array $likes = null, ?string $createdAt = null, bool $isDeleted = false, ?string $creatorUsername = null) {
        $this->postId = $postId;
        $this->userId = $userId;
        $this->title = $title;
        $this->content = $content;
        $this->media = $media;
        $this->likes = $likes ? array_map('intval', $likes) : null;
        $this->createdAt = $createdAt;
        $this->isDeleted = $isDeleted;
        $this->creatorUsername = $creatorUsername;
    }

    /**
     * Get the post ID.
     *
     * @return int
     */
    public function getPostId(): int {
        return $this->postId;
    }

    /**
     * Get the user ID of the post creator.
     *
     * @return int
     */
    public function getUserId(): int {
        return $this->userId;
    }

    /**
     * Get the title of the post.
     *
     * @return string
     */
    public function getTitle(): string {
        return $this->title;
    }

    /**
     * Get the content of the post.
     *
     * @return string
     */
    public function getContent(): string {
        return $this->content;
    }

    /**
     * Get the media of the post.
     *
     * @return string|null
     */
    public function getMedia(): ?string {
        return $this->media;
    }

    /**
     * Get the likes of the post.
     *
     * @return array|null
     */
    public function getLikes(): ?array {
        return $this->likes;
    }

    /**
     * Get the creation date of the post.
     *
     * @return string|null
     */
    public function getCreatedAt(): ?string {
        return $this->createdAt;
    }

    /**
     * Check if the post is deleted.
     *
     * @return bool
     */
    public function getIsDeleted(): bool {
        return $this->isDeleted;
    }

    /**
     * Get the username of the post creator.
     *
     * @return string|null
     */
    public function getCreatorUsername(): ?string {
        return $this->creatorUsername;
    }
}

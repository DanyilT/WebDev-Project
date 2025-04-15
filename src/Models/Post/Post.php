<?php

namespace Models\Post;

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
     * @param int $postId
     * @param int $userId
     * @param string $title
     * @param string $content
     * @param string|null $media
     * @param array|null $likes
     * @param string|null $createdAt
     * @param bool $isDeleted
     * @param string|null $creatorUsername
     */
    public function __construct(int $postId, int $userId, string $title, string $content, string $media = null, array $likes = null, string $createdAt = null, bool $isDeleted = false, string $creatorUsername = null) {
        $this->postId = $postId;
        $this->userId = $userId;
        $this->title = $title;
        $this->content = $content;
        $this->media = $media;
        $this->likes = $likes;
        $this->createdAt = $createdAt;
        $this->isDeleted = $isDeleted;
        $this->creatorUsername = $creatorUsername;
    }

    /**
     * @return int
     */
    public function getPostId(): int {
        return $this->postId;
    }

    /**
     * @return int
     */
    public function getUserId(): int {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function getTitle(): string {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getContent(): string {
        return $this->content;
    }

    /**
     * @return string|null
     */
    public function getMedia(): ?string {
        return $this->media;
    }

    /**
     * @return array|null
     */
    public function getLikes(): ?array {
        return $this->likes;
    }

    /**
     * @return string|null
     */
    public function getCreatedAt(): ?string {
        return $this->createdAt;
    }

    /**
     * @return bool
     */
    public function getIsDeleted(): bool {
        return $this->isDeleted;
    }

    /**
     * @return string|null
     */
    public function getCreatorUsername(): ?string {
        return $this->creatorUsername;
    }
}

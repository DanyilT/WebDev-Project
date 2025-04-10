<?php

namespace Models\Post;

class Post {
    private $postId;
    private $userId;
    private $title;
    private $content;
    private $media;
    private $likes;
    private $createdAt;
    private $isDeleted;
    private $creatorUsername;

    public function __construct($postId, $userId, $title, $content, $media, $likes = null, $createdAt = null, $isDeleted = false, $creatorUsername = null) {
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
     * @return mixed
     */
    public function getPostId()
    {
        return $this->postId;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return mixed
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * @return mixed
     */
    public function getLikes()
    {
        return $this->likes;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getIsDeleted(): mixed
    {
        return $this->isDeleted;
    }

    public function getCreatorUsername(): mixed
    {
        return $this->creatorUsername;
    }
}

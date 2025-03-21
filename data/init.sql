-- DDL for initializing the database schema

-- CREATE DATABASE IF NOT EXISTS qwertyDB;
USE qwertyDB;

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL, -- Store hashed passwords only
    email VARCHAR(100) NOT NULL,
    name VARCHAR(100) NOT NULL,
    bio TEXT,
    profile_pic VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_deleted BOOLEAN DEFAULT FALSE,
    data_changes_history JSON
);

CREATE VIEW active_users AS SELECT * FROM users WHERE is_deleted = FALSE;

CREATE TABLE followers (
    follower_id INT NOT NULL,
    following_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_following BOOLEAN DEFAULT TRUE,
    following_history JSON,
    PRIMARY KEY (follower_id, following_id),
    FOREIGN KEY (follower_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (following_id) REFERENCES users(user_id) ON DELETE CASCADE,
    CHECK (follower_id <> following_id)
);

CREATE VIEW active_followers AS SELECT * FROM followers WHERE is_following = TRUE;

CREATE TABLE posts (
    post_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    media VARCHAR(255),
    likes JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_deleted BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE VIEW active_posts AS SELECT * FROM posts WHERE is_deleted = FALSE;

CREATE TABLE comments (
    comment_id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_deleted BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (post_id) REFERENCES posts(post_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE VIEW active_comments AS SELECT * FROM comments WHERE is_deleted = FALSE;

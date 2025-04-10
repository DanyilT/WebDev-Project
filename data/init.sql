-- DDL for initializing the database schema

-- CREATE DATABASE IF NOT EXISTS qwertyDB;
USE qwertyDB;

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(20) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL, -- Store hashed passwords only
    email VARCHAR(100) NOT NULL,
    name VARCHAR(100) NOT NULL,
    bio TEXT DEFAULT NULL,
    profile_pic VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_deleted BOOLEAN DEFAULT FALSE,
    data_changes_history JSON DEFAULT ('{"origin": {"username": "", "email": "", "name": "", "bio": "", "profile_pic": "", "created_at": "CURRENT_TIMESTAMP"}}')
);
CREATE VIEW active_users AS SELECT * FROM users WHERE is_deleted = FALSE;

CREATE TABLE followers (
    follower_id INT NOT NULL,
    following_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_following BOOLEAN DEFAULT TRUE,
    following_history JSON DEFAULT ('[{"action": "follow", "timestamp": CURRENT_TIMESTAMP}]'),
    PRIMARY KEY (follower_id, following_id),
    FOREIGN KEY (follower_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (following_id) REFERENCES users(user_id) ON DELETE CASCADE,
    CHECK (follower_id <> following_id)
);
CREATE VIEW active_followers AS SELECT f.*, u.user_id AS follower_user_id, u2.user_id AS following_user_id FROM followers f JOIN active_users u ON f.follower_id = u.user_id JOIN active_users u2 ON f.following_id = u2.user_id WHERE f.is_following = TRUE;

CREATE TABLE posts (
    post_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    media VARCHAR(255) DEFAULT NULL,
    likes JSON DEFAULT ('[]'),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_deleted BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);
CREATE VIEW active_posts AS SELECT p.*, u.user_id AS post_user_id FROM posts p JOIN active_users u ON p.user_id = u.user_id WHERE p.is_deleted = FALSE;

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
CREATE VIEW active_comments AS SELECT c.*, u.user_id AS comment_user_id, p.post_id AS comment_post_id FROM comments c JOIN active_users u ON c.user_id = u.user_id JOIN active_posts p ON c.post_id = p.post_id WHERE c.is_deleted = FALSE;

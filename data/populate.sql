-- DML script to populate the database with sample data

-- Populate users table
INSERT INTO users (username, password, email, name, bio, profile_pic)
VALUES
    ('@user1', 'password_hash_1', 'user1@example.com', 'User1 Name', 'Bio of User1', 'profile_pic_1.jpg'),
    ('@user2', 'password_hash_2', 'user2@example.com', 'User2 Name', 'Bio of User2', 'profile_pic_2.jpg'),
    ('@user3', 'password_hash_3', 'user3@example.com', 'User3 Name', 'Bio of User3', 'profile_pic_3.jpg');

-- Populate followers table
INSERT INTO followers (follower_id, following_id, is_following, following_history)
VALUES
    (1, 2, TRUE, '[{"action": "follow", "timestamp": "2025-03-20 04:00:00"}]'),
    (1, 3, TRUE, '[{"action": "follow", "timestamp": "2025-03-20 05:00:00"}]'),
    (2, 1, TRUE, '[{"action": "follow", "timestamp": "2025-03-20 06:00:00"}]'),
    (3, 1, TRUE, '[{"action": "follow", "timestamp": "2025-03-20 07:00:00"}]');

-- Populate posts table
INSERT INTO posts (user_id, title, content, media)
VALUES
    (1, 'First Post', 'Content of the first post', 'media_1.jpg'),
    (2, 'Second Post', 'Content of the second post', 'media_2.jpg'),
    (3, 'Third Post', 'Content of the third post', 'media_3.jpg');

-- Populate comments table
INSERT INTO comments (post_id, user_id, content)
VALUES
    (1, 2, 'Comment on the first post by @user2'),
    (1, 3, 'Comment on the first post by @user3'),
    (2, 1, 'Comment on the second post by @user1');

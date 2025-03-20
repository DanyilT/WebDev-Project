<?php
session_start();
require 'lib/profile_data.php';

$username = $_GET['username'];
if (!$username) {
    header('Location: index.php');
    exit();
}

// Fetch user profile information
$user = getUserProfile($connection, $username);
if (!$user) {
    echo "User not found.";
    exit();
}

$userId = $user['user_id'];
$thisSessionUsername = isset($_SESSION['username']) ? $_SESSION['username'] : null;

// Fetch followers and following counts
$followersCount = getFollowersCount($connection, $userId);
$followingCount = getFollowingCount($connection, $userId);

// Check if the current user is following this profile
$isFollowing = isFollowing($connection, $thisSessionUsername, $userId);

// Fetch user posts
$posts = getUserPosts($connection, $userId);
?>

<?php
$title = $user['username'] . "'s Profile";
$styles = '<link rel="stylesheet" href="css/pages/profile.css">';
include 'layout/header.php';
?>

<main>
    <section class="profile">
        <h2 class="username">@<?php echo htmlspecialchars($user['username']); ?></h2>
        <img class="profile-pic" src="<?php echo htmlspecialchars(isset($user['profile_pic']) ? $user['profile_pic'] : 'img/icons/user-profile-default-pic-iconly.png'); ?>" alt="Profile Picture">
        <p class="name">Name: <?php echo htmlspecialchars($user['name']); ?></p>
        <p class="body">Bio: <?php echo htmlspecialchars($user['bio'] ?: ''); ?></p>
        <p class="date">
            How long it is stored in database?:
            <?php
            $createdDate = new DateTime($user['created_at']);
            $interval = $createdDate->diff(new DateTime());

            if ($interval->y > 0) {
                echo htmlspecialchars($interval->y . ' year' . ($interval->y > 1 ? 's' : '') . ' ago');
            } elseif ($interval->m > 0) {
                echo htmlspecialchars($interval->m . ' month' . ($interval->m > 1 ? 's' : '') . ' ago');
            } elseif ($interval->d >= 7) {
                echo htmlspecialchars(floor($interval->d / 7) . ' week' . (floor($interval->d / 7) > 1 ? 's' : '') . ' ago');
            } elseif ($interval->d > 0) {
                echo htmlspecialchars($interval->d . ' day' . ($interval->d > 1 ? 's' : '') . ' ago');
            } elseif ($interval->h > 0) {
                echo htmlspecialchars($interval->h . ' hour' . ($interval->h > 1 ? 's' : '') . ' ago');
            } elseif ($interval->i > 0) {
                echo htmlspecialchars($interval->i . ' minute' . ($interval->i > 1 ? 's' : '') . ' ago');
            } else {
                echo htmlspecialchars($interval->s . ' second' . ($interval->s > 1 ? 's' : '') . ' ago');
            }
            ?>
        </p>
        <!-- I don't care how you think about this class naming -->
        <p id="followers-count" class="follow-ers-or-ing">Followers: <span><?php echo $followersCount; ?></span></p>
        <p id="following-count" class="follow-ers-or-ing">Following: <span><?php echo $followingCount; ?></span></p>
        <?php if ($thisSessionUsername === $username): ?>
            <form action="lib/process_logout.php" method="post">
                <button type="submit">Logout</button>
            </form>
        <?php else: ?>
            <button class="<?php echo $isFollowing ? 'unfollow-btn' : 'follow-btn'; ?>" id="follow-btn"><?php echo $isFollowing ? 'Unfollow' : 'Follow'; ?></button>
        <?php endif; ?>
    </section>

    <section class="posts">
        <h2>Posts</h2>
        <?php foreach ($posts as $post): ?>
            <article class="post">
                <p class="username">@<?php echo htmlspecialchars($user['username']); ?></p>
                <h3 class="title"><?php echo htmlspecialchars($post['title']); ?></h3>
                <hr>
                <p class="body"><?php echo htmlspecialchars($post['content']); ?></p>
<!--                <img class="media" src="--><?php //echo htmlspecialchars($post['media']); ?><!--" alt="Post Media">-->
                <span class="likes">Likes: <?php echo htmlspecialchars($post['likes'] ?: 0); ?></span>
                <p class="date">Date: <?php echo htmlspecialchars($post['created_at']); ?></p>
            </article>
        <?php endforeach; ?>
    </section>
</main>

<!-- Modals for followers and following -->
<dialog id="followers-modal" class="modal followers">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Followers</h2>
            <span id="close-followers" class="close">&times;</span>
        </div>
        <ul id="followers-list"></ul>
    </div>
</dialog>

<dialog id="following-modal" class="modal following">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Following</h2>
            <span id="close-following" class="close">&times;</span>
        </div>
        <ul id="following-list"></ul>
    </div>
</dialog>

<?php include 'layout/footer.php'; ?>

<script>
    // Handle modals
    document.addEventListener("DOMContentLoaded", function () {
        // Get the modals
        const followersModal = document.getElementById('followers-modal');
        const followingModal = document.getElementById('following-modal');

        // Get the button that opens the modal
        const followersBtn = document.getElementById('followers-count');
        const followingBtn = document.getElementById('following-count');

        // Get the <span> element that closes the modal
        const closeFollowers = document.getElementById('close-followers');
        const closeFollowing = document.getElementById('close-following');

        // When the user clicks on the followers/following count, open the modal
        followersBtn.addEventListener('click', function() {
            fetch('lib/get_followers.php?user_id=<?php echo $userId; ?>')
                .then(response => response.json())
                .then(data => {
                    const followersList = document.getElementById('followers-list');
                    followersList.innerHTML = '';
                    data.forEach(follower => {
                        const li = document.createElement('li');
                        const link = document.createElement('a');
                        link.href = `profile.php?username=${follower.username}`;
                        link.textContent = follower.username;
                        li.appendChild(link);
                        followersList.appendChild(li);
                    });
                    followersModal.style.display = 'block';
                });
        });
        followingBtn.addEventListener('click', function() {
            fetch('lib/get_following.php?user_id=<?php echo $userId; ?>')
                .then(response => response.json())
                .then(data => {
                    const followingList = document.getElementById('following-list');
                    followingList.innerHTML = '';
                    data.forEach(following => {
                        const li = document.createElement('li');
                        const link = document.createElement('a');
                        link.href = `profile.php?username=${following.username}`;
                        link.textContent = following.username;
                        li.appendChild(link);
                        followingList.appendChild(li);
                    });
                    followingModal.style.display = 'block';
                });
        });

        // When the user clicks on <span> (x), close the modal
        closeFollowers.onclick = function() {
            followersModal.style.display = 'none';
        };
        closeFollowing.onclick = function() {
            followingModal.style.display = 'none';
        };

        // When the user clicks anywhere outside the modal, close it
        window.onclick = function(event) {
            if (event.target === followersModal) {
                followersModal.style.display = 'none';
            } else if (event.target === followingModal) {
                followingModal.style.display = 'none';
            }
        };
    });

    // Handle follow/unfollow
    document.getElementById('follow-btn').addEventListener('click', function() {
        const userId = <?php echo json_encode($userId); ?>;
        fetch('lib/follow.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ user_id: userId })
        })
            .then(response => response.json())
            .then(data => {
                const followersCountElement = document.querySelector('#followers-count span');
                let followersCount = parseInt(followersCountElement.textContent);

                if (data.status === 'followed') {
                    document.getElementById('follow-btn').className = 'unfollow-btn';
                    document.getElementById('follow-btn').textContent = 'Unfollow';
                    followersCountElement.textContent = followersCount + 1;
                } else if (data.status === 'unfollowed') {
                    document.getElementById('follow-btn').className = 'follow-btn';
                    document.getElementById('follow-btn').textContent = 'Follow';
                    followersCountElement.textContent = followersCount - 1;
                }
            })
            .catch(error => console.error('Error:', error));
    });
</script>

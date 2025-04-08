<?php

use Models\Users\UserProfile;

session_start();
require '../src/DBconnect.php';
require '../src/Models/UserProfile.php';

$username = $_GET['username'];
if (!$username) {
    header('Location: index.php');
    exit();
}

$userProfile = new UserProfile($connection);
$user = $userProfile->getUserProfile($username);

if (!$user) {
    echo "User not found.";
    exit();
}

// Fetch user's profile information
$followers = $userProfile->getFollowers($user['user_id']);
$followings = $userProfile->getFollowings($user['user_id']);
$followersCount = $userProfile->getFollowersCount($user['user_id']);
$followingsCount = $userProfile->getFollowingsCount($user['user_id']);
$isFollowing = isset($_SESSION['username']) ? $userProfile->isFollowing($userProfile->getUserProfile($_SESSION['username'])['user_id'], $user['user_id']) : false;
$posts = $userProfile->getUserPosts($user['user_id']);
?>

<?php
$title = $user['username'] . "'s Profile";
$styles = '<link rel="stylesheet" href="css/pages/profile.css">';
include 'layout/header.php';
?>

<main>
    <section class="profile">
        <h2 class="username">@<?php echo htmlspecialchars($user['username']); ?></h2>
        <img class="profile-pic" src="<?php echo htmlspecialchars($user['profile_pic'] ?: 'img/icons/user-profile-default-pic-iconly.png'); ?>" alt="Profile Picture" onerror="this.onerror=null; this.src='img/icons/user-profile-default-pic-iconly.png';">
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
        <p id="following-count" class="follow-ers-or-ing">Following: <span><?php echo $followingsCount; ?></span></p>
        <?php if (isset($_SESSION['username']) && $_SESSION['username'] === $username): ?>
            <button id="edit-profile-btn">Edit Profile</button>
            <form action="lib/process/process_logout.php" method="post" style="width: 100px; justify-self: center;">
                <button type="submit">Logout</button>
            </form>
        <?php else: ?>
            <button id="follow-btn" class="<?php echo $isFollowing ? 'unfollow-btn' : 'follow-btn'; ?>" <?php echo !isset($_SESSION['username']) ? 'onclick="if(confirm(\'Please login to follow this user.\')) { window.location.href = \'account.php#login\'; }"' : ''; ?>><?php echo $isFollowing ? 'Unfollow' : 'Follow'; ?></button>
        <?php endif; ?>
    </section>

    <section class="posts">
        <h2>Posts</h2>
        <?php foreach ($posts as $post): ?>
            <article class="post">
                <p class="username">@<?php echo htmlspecialchars($post['username']); ?></p>
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
        <ul id="followers-list">
            <?php foreach ($followers as $follower): ?>
                <li><a href="profile.php?username=<?php echo $follower['username']; ?>"><?php echo $follower['username']; ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
</dialog>

<dialog id="following-modal" class="modal following">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Following</h2>
            <span id="close-following" class="close">&times;</span>
        </div>
        <ul id="following-list">
            <?php foreach ($followings as $following): ?>
                <li><a href="profile.php?username=<?php echo $following['username']; ?>"><?php echo $following['username']; ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
</dialog>

<?php if ($_SESSION['username'] === $username): ?>
    <dialog id="edit-profile-modal" class="modal edit-profile">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit Profile</h2>
                <span id="close-edit-profile" class="close">&times;</span>
            </div>
            <form id="edit-form" action="lib/process/process_update_profile.php" method="post">
                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['user_id']); ?>">
                <label for="edit-field">Select field to edit:</label>
                <select id="edit-field" name="edit_field" onchange="showEditField()">
                    <option value="username">Username</option>
                    <option value="name">Name</option>
                    <option value="bio">Bio</option>
                </select>

                <div id="edit-username" class="edit-field">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                </div>

                <div id="edit-name" class="edit-field" style="display:none;">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                </div>

                <div id="edit-bio" class="edit-field" style="display:none;">
                    <label for="bio">Bio:</label>
                    <textarea id="bio" name="bio"><?php echo htmlspecialchars($user['bio'] ?: ''); ?></textarea>
                </div>
                <button type="submit">Update Profile</button>
            </form>
            <form action="lib/process/process_update_profile.php" method="post">
                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['user_id']); ?>">
                <label for="current-password">Current Password:</label>
                <input type="password" id="current-password" name="current_password" required>
                <label for="new-password">New Password:</label>
                <input type="password" id="new-password" name="new_password" required>
                <input type="hidden" name="edit_field" value="password">
                <button type="submit">Update Password</button>
            </form>
        </div>
    </dialog>

    <script>
        const editProfileModal = document.getElementById('edit-profile-modal');
        const editProfileBtn = document.getElementById('edit-profile-btn');
        const closeEditProfile = document.getElementById('close-edit-profile');

        editProfileBtn.addEventListener('click', function() {
            editProfileModal.style.display = 'block';
        });

        closeEditProfile.onclick = function() {
            editProfileModal.style.display = 'none';
        };

        editProfileModal.onclick = function(event) {
            if (event.target === editProfileModal) {
                editProfileModal.style.display = 'none';
            }
        };

        function showEditField() {
            const editField = document.getElementById('edit-field').value;
            document.querySelectorAll('.edit-field').forEach(field => field.style.display = 'none');
            document.getElementById('edit-' + editField).style.display = 'block';
        }
    </script>
<?php endif; ?>

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
            followersModal.style.display = 'block';
        });
        followingBtn.addEventListener('click', function() {
            followingModal.style.display = 'block';
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
        const userId = <?php echo json_encode($user['user_id']); ?>;
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

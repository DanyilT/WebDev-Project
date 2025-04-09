<?php

use Models\User\UserRead;

// Assuming DBconnect.php is required in parent file to establish a database connection
// require '../src/Database/DBconnect.php';
// Require the necessary classes if not already loaded
class_exists("Models\User\UserRead") or require_once '../src/Models/UserRead.php';

// Check if the user is logged in, and get the session username if available
$sessionUsernameIsSet = $_SESSION['username'] ?? null;

// Get the username from the URL (GET request)
$username = preg_replace('/[^a-z0-9_]/', '', strtolower(trim($_GET['username'])));
if (!$username) {
    header('Location: index.php');
    exit();
}

// Fetch user's profile information (assuming $connection is a valid PDO connection -- passed from DBconnect.php)
$userRead = new UserRead($connection);
$userProfile = $userRead->getUserProfile($username);

// Check if the user exists
if (!$userProfile) {
    echo "User not found.";
    echo "<a href='/'>Go back</a>";
    exit();
}

// Fetch additional user information
$userId = $userProfile['user_id']; // or $userId = $userRead->getUserId($username);
$followers = $userRead->getFollowers($userId);
$followings = $userRead->getFollowings($userId);
$followersCount = $userRead->getFollowersCount($userId);
$followingsCount = $userRead->getFollowingsCount($userId);
$isFollowing = $userRead->isFollowing($userRead->getUserProfile($sessionUsernameIsSet)['user_id'], $userId);
?>

<!-- HTML -->
<section class="profile">
    <h2 class="username"><?php echo htmlspecialchars($userProfile['username']); ?></h2>
    <img class="profile-pic" src="<?php echo htmlspecialchars($userProfile['profile_pic'] ?: 'img/icons/user-profile-default-pic-iconly.png'); ?>" alt="Profile Picture" onerror="this.onerror=null; this.src='img/icons/user-profile-default-pic-iconly.png';">
    <p class="name">Name: <?php echo htmlspecialchars($userProfile['name']); ?></p>
    <p class="body">Bio: <?php echo htmlspecialchars($userProfile['bio'] ?: ''); ?></p>
    <p class="date">
        How long it is stored in database?:
        <?php
        $createdDate = new DateTime($userProfile['created_at']);
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
    <?php if ($sessionUsernameIsSet === $username): ?>
        <button id="edit-profile-btn">Edit Profile</button>
        <form action="lib/process/process_logout.php" method="post" style="width: 100px; justify-self: center;">
            <button type="submit">Logout</button>
        </form>
    <?php else: ?>
        <button id="follow-btn" class="<?php echo $isFollowing ? 'unfollow-btn' : 'follow-btn'; ?>" <?php echo !isset($_SESSION['username']) ? 'onclick="if(confirm(\'Please login to follow this user.\')) { window.location.href = \'account.php#login\'; }"' : ''; ?>><?php echo $isFollowing ? 'Unfollow' : 'Follow'; ?></button>
    <?php endif; ?>
</section>

<!-- Modals for followers and following -->
<?php include 'profile_follow-ers-and-ing_modal.php'; ?>

<!-- Script -->
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
        fetch('lib/process/process_follow.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ userId: <?php echo json_encode($userId); ?> })
        })
            .then(response => response.json())
            .then(data => {
                const followersCountElement = document.querySelector('#followers-count span');
                let followersCount = parseInt(followersCountElement.textContent);

                if (data.status === 'followed' || data.status === 'new follow') {
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

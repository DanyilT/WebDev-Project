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
?>

<?php if ($_SESSION['username'] == $username): ?>
<dialog id="edit-profile-modal" class="modal edit-profile">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Edit Profile</h2>
            <span id="close-edit-profile" class="close">&times;</span>
        </div>
        <form id="edit-form" action="lib/process/process_update_profile.php" method="post">
            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($userId); ?>">
            <label for="edit-field">Select field to edit:</label>
            <select id="edit-field" name="edit_field" onchange="showEditField()">
                <option value="username">Username</option>
                <option value="name">Name</option>
                <option value="bio">Bio</option>
            </select>

            <div id="edit-username" class="edit-field">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($userProfile['username']); ?>" required>
            </div>

            <div id="edit-name" class="edit-field" style="display:none;">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($userProfile['name']); ?>" required>
            </div>

            <div id="edit-bio" class="edit-field" style="display:none;">
                <label for="bio">Bio:</label>
                <textarea id="bio" name="bio"><?php echo htmlspecialchars($userProfile['bio'] ?: ''); ?></textarea>
            </div>
            <button type="submit">Update Profile</button>
        </form>
        <form action="lib/process/process_update_profile.php" method="post">
            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($userProfile['user_id']); ?>">
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

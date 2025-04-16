<?php
/**
 * Modal: Followers and Following
 * This file contains the modal for displaying followers and following users.
 *
 * @package public/assets/modals
 *
 * @var array $followers
 * @var array $followings
 */
?>

<dialog id="followers-modal" class="modal followers">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Followers</h2>
            <span id="close-followers" class="close">&times;</span>
        </div>
        <ul id="followers-list">
            <?php foreach ($followers as $follower): ?>
                <li><a href="/profile.php?username=<?php echo $follower['username']; ?>"><?php echo $follower['username']; ?></a></li>
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
                <li><a href="/profile.php?username=<?php echo $following['username']; ?>"><?php echo $following['username']; ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
</dialog>

<?php
function get_accounts($filePath) {
    $accountsJson = file_get_contents($filePath);
    return json_decode($accountsJson, true);
}

/**
 * @param array $users
 * @return void
 */
function displaySearchResults(array $users): void {
    try {
        if ($users) {
            echo '<section><h2>Search Results</h2><table border="1"><tr><th>ID</th><th>Username</th><th>Name</th></tr>';
            foreach ($users as $user) {
                echo '<tr><td>' . htmlspecialchars($user['user_id']) . '</td><td><a href="profile.php?username=' . htmlspecialchars($user['username']) . '">' . htmlspecialchars($user['username']) . '</a></td><td>' . htmlspecialchars($user['name']) . '</td></tr>';
            }
            echo '</table></section>';
        } else {
            echo '<section><p>No users found.</p></section>';
        }
    } catch (PDOException $e) {
        echo '<section><p>Error: ' . $e->getMessage() . '</p></section>';
    }
}

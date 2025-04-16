<?php
/**
 * File: functions.php
 * This file contains utility functions for the application.
 *
 * @package public/lib
 */

/**
 * Display a message indicating the time since a given date.
 *
 * @param DateInterval|bool $interval The interval to display.
 * @return void String representation of the time interval.
 */
function time_count_display(DateInterval|bool $interval): void {
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
}

/**
 * Display the search results for users.
 *
 * @param array $users An array of user data, where each user is an associative array with keys 'user_id', 'username', and 'name'.
 * @return void HTML output of the search results.
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

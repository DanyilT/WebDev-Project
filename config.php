<?php
/**
 * For secure reasons, store your database credentials in a separate file.
 * This file should be included in .gitignore to prevent it from being pushed to GitHub.
 * Can't be shown in public repo, so it's just a template, the actual file can be accessed form GitGHub Actions secrets.
 * @file `.github/workflows/create-config.yml` - Takes secrets from GitHub Actions and creates the actual `config.php` file.
 */

return [
    'host' => '',
    'dbname' => '',
    'user' => '',
    'password' => '',
    'options' => [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
];

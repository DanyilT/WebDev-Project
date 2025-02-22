<?php
function getAccounts($filePath) {
    $accountsJson = file_get_contents($filePath);
    return json_decode($accountsJson, true);
}

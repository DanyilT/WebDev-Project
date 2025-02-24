<?php
function getAccounts($filePath) {
    $accountsJson = file_get_contents($filePath);
    return json_decode($accountsJson, true);
}

function setAccounts($filePath, $accounts) {
    $accountsJson = json_encode($accounts, JSON_PRETTY_PRINT);
    file_put_contents($filePath, $accountsJson);
}

<?php
function get_accounts($filePath) {
    $accountsJson = file_get_contents($filePath);
    return json_decode($accountsJson, true);
}

function save_accounts($filePath, $accounts) {
    $json = json_encode($accounts, JSON_PRETTY_PRINT);
    file_put_contents($filePath, $json);
}

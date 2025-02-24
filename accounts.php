<?php
require 'lib/functions.php';

const ACCOUNTS_FILE_PATH = 'data/accounts.json';

$accounts = get_accounts(ACCOUNTS_FILE_PATH);

//var_dump($accounts);
//die();
?>

<?php include_once 'layout/header.php'; ?>

<main>
    <section>
        <h2>Account List</h2>
        <article>
            <ul>
                <?php foreach ($accounts as $account): ?>
                    <li>
                        <strong>Username:</strong> <?php echo htmlspecialchars($account['username']); ?> <br>
                        <strong>Password:</strong> <?php echo htmlspecialchars($account['password']); ?> <br>
                    </li>
                <?php endforeach; ?>
            </ul>
        </article>
    </section>
</main>

<?php include_once 'layout/footer.php'; ?>

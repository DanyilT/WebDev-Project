<?php include_once 'layout/header.php'; ?>

<main>
    <section>
        <h2>Search</h2>
        <form action="search.php" method="get">
            <label for="search">Search for posts:</label>
            <input type="text" id="search" name="search" placeholder="Enter search term...">
            <button type="submit">Search</button>
        </form>
    </section>
</main>

<?php include_once 'layout/footer.php'; ?>

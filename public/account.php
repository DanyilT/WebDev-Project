<?php
session_start();
//if (!isset($_SESSION['username'])) {
//    header('Location: login.php');
//    exit();
//}
?>

<?php include_once 'layout/header.php'; ?>

<main>
    <article>
        <?php if (isset($_SESSION['username'])): ?>
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
            <form action="lib/process_logout.php" method="post">
                <button type="submit">Logout</button>
            </form>
        <?php else: ?>
            <h1>Welcome to the Account Page</h1>
            <button id="loginBtn">Login</button>
            <button id="registerBtn">Register</button>
        <?php endif; ?>
    </article>
    <?php if (isset($_SESSION['username'])): ?>
        <p>This is your account page.</p>
    <?php else: ?>
        <p>Please log in or sign up to access your account information.</p>
    <?php endif; ?>
</main>

<!-- Login Modal & Register Modal -->
<?php require_once 'layout/login.php'; ?>
<?php require_once 'layout/register.php'; ?>

<?php include_once 'layout/footer.php'; ?>

<script>
    // Get the modals
    var loginModal = document.getElementById("loginModal");
    var registerModal = document.getElementById("registerModal");

    // Get the buttons that open the modals
    var loginBtn = document.getElementById("loginBtn");
    var registerBtn = document.getElementById("registerBtn");

    // Get the <span> elements that close the modals
    var closeLogin = document.getElementById("closeLogin");
    var closeRegister = document.getElementById("closeRegister");

    // When the user clicks the button, open the modal
    loginBtn.onclick = function() {
        loginModal.style.display = "block";
    }
    registerBtn.onclick = function() {
        registerModal.style.display = "block";
    }

    // When the user clicks on <span> (x), close the modal
    closeLogin.onclick = function() {
        loginModal.style.display = "none";
    }
    closeRegister.onclick = function() {
        registerModal.style.display = "none";
    }

    // When the user clicks anywhere outside the modal, close it
    window.onclick = function(event) {
        if (event.target === loginModal) {
            loginModal.style.display = "none";
        }
        if (event.target === registerModal) {
            registerModal.style.display = "none";
        }
    }

    // Function to open the modal based on the URL hash
    function openModalBasedOnHash() {
        if (window.location.hash === '#login') {
            registerModal.style.display = "none";
            loginModal.style.display = "block";
        } else if (window.location.hash === '#register') {
            loginModal.style.display = "none";
            registerModal.style.display = "block";
        }
        window.location.hash = '';
    }

    // Check URL hash and open the corresponding modal on page load
    window.onload = openModalBasedOnHash;

    // Check URL hash and open the corresponding modal on hash change
    window.onhashchange = openModalBasedOnHash;
</script>

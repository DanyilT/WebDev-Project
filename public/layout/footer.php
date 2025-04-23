<?php /**
 * Layout: Footer (admin panel)
 * This file contains the footer layout for the application's admin panel.
 *
 * @package public/layout/admin
 */ ?>

<footer>
    <p class="copy">&copy; 2025 QWERTY | Dany</p>
    <a class="link github-repo" href="https://github.com/DanyilT/WebDev-Project">GitHub Repository</a>
    <br><a class="link terms" href="/terms">terms</a>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check URL for success or error parameters
            const urlParams = new URLSearchParams(window.location.search);

            if (urlParams.has('success')) {
                const message = decodeURIComponent(urlParams.get('success'));
                setTimeout(function() {
                    showNotification(message, 'success');
                }, 300);
            }

            if (urlParams.has('error')) {
                const message = decodeURIComponent(urlParams.get('error'));
                setTimeout(function() {
                    showNotification(message, 'error');
                }, 300);
            }
        });
    </script>
</footer>
</body>
</html>

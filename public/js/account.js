// Get the modals
const loginModal = document.getElementById("login-modal");
const registerModal = document.getElementById("register-modal");

// Get the buttons that open the modals
const loginBtn = document.getElementById("login-btn");
const registerBtn = document.getElementById("register-btn");

// Get the <span> elements that close the modals
const closeLogin = document.getElementById("close-login");
const closeRegister = document.getElementById("close-register");

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
    } else if (event.target === registerModal) {
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

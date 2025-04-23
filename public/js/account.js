// Enhanced Account JS with Validation and Improved UX

// Modal handling
const loginModal = document.getElementById("login-modal");
const registerModal = document.getElementById("register-modal");
const loginBtn = document.getElementById("login-btn");
const registerBtn = document.getElementById("register-btn");
const closeLogin = document.getElementById("close-login");
const closeRegister = document.getElementById("close-register");

// Show modals
if (loginBtn) loginBtn.onclick = function() { loginModal.style.display = "block"; }
if (registerBtn) registerBtn.onclick = function() { registerModal.style.display = "block"; }

// Close modals
if (closeLogin) closeLogin.onclick = function() { loginModal.style.display = "none"; }
if (closeRegister) closeRegister.onclick = function() { registerModal.style.display = "none"; }

// Close modals when clicking outside
window.onclick = function(event) {
    if (event.target === loginModal) {
        loginModal.style.display = "none";
    } else if (event.target === registerModal) {
        registerModal.style.display = "none";
    }
}

// Function to open the modal based on URL hash
function openModalBasedOnHash() {
    if (window.location.hash === '#login') {
        if (registerModal) registerModal.style.display = "none";
        if (loginModal) loginModal.style.display = "block";
    } else if (window.location.hash === '#register') {
        if (loginModal) loginModal.style.display = "none";
        if (registerModal) registerModal.style.display = "block";
    }
    window.location.hash = '';
}

// Check URL hash and open corresponding modal
window.onload = function() {
    openModalBasedOnHash();

    // Check for URL parameters
    const urlParams = new URLSearchParams(window.location.search);

    if (urlParams.has('success')) {
        const message = urlParams.get('success');
        if (message) {
            showNotification(message, 'success');
        }
    }

    if (urlParams.has('error')) {
        const message = urlParams.get('error');
        if (message) {
            showNotification(message, 'error');
        }
    }
};

window.onhashchange = openModalBasedOnHash;

// Form validation for Registration
document.addEventListener('DOMContentLoaded', function() {
    // Get the registration form
    const registerForm = document.getElementById('register-form');
    if (!registerForm) return;

    // Get form fields
    const usernameInput = document.getElementById('username');
    const emailInput = document.getElementById('email');
    const nameInput = document.getElementById('name');
    const passwordInput = document.getElementById('password');
    const termsCheckbox = document.getElementById('accept-terms');

    // Validation rules
    const validationRules = {
        username: {
            validate: function(value) {
                if (!value) return { valid: false, message: 'Username is required' };
                if (value.length < 3) return { valid: false, message: 'Username must be at least 3 characters' };
                if (!/^[a-z0-9_]+$/.test(value)) return { valid: false, message: 'Username can only contain lowercase letters, numbers, and underscores' };
                return { valid: true };
            }
        },
        email: {
            validate: function(value) {
                if (!value) return { valid: false, message: 'Email is required' };
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(value)) return { valid: false, message: 'Please enter a valid email address' };
                return { valid: true };
            }
        },
        name: {
            validate: function(value) {
                if (!value) return { valid: false, message: 'Name is required' };
                if (value.length < 2) return { valid: false, message: 'Name must be at least 2 characters' };
                return { valid: true };
            }
        },
        password: {
            validate: function(value) {
                if (!value) return { valid: false, message: 'Password is required' };
                if (value.length < 8) return { valid: false, message: 'Password must be at least 8 characters' };
                if (!/[A-Z]/.test(value)) return { valid: false, message: 'Password must contain at least one uppercase letter' };
                if (!/[a-z]/.test(value)) return { valid: false, message: 'Password must contain at least one lowercase letter' };
                if (!/[0-9]/.test(value)) return { valid: false, message: 'Password must contain at least one number' };
                return { valid: true };
            }
        },
        terms: {
            validate: function(checked) {
                if (!checked) return { valid: false, message: 'You must accept the Terms and Conditions' };
                return { valid: true };
            }
        }
    };

    // Real-time validation for username
    if (usernameInput) {
        usernameInput.addEventListener('input', function() {
            validateField(this, 'username');
            updateRequirementsList('username');
        });
        usernameInput.addEventListener('blur', function() {
            validateField(this, 'username');
            updateRequirementsList('username');
        });
    }

    // Real-time validation for email
    if (emailInput) {
        emailInput.addEventListener('input', function() {
            validateField(this, 'email');
        });
        emailInput.addEventListener('blur', function() {
            validateField(this, 'email');
        });
    }

    // Real-time validation for name
    if (nameInput) {
        nameInput.addEventListener('input', function() {
            validateField(this, 'name');
        });
        nameInput.addEventListener('blur', function() {
            validateField(this, 'name');
        });
    }

    // Real-time validation for password
    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            validateField(this, 'password');
            updatePasswordRequirements();
        });
        passwordInput.addEventListener('blur', function() {
            validateField(this, 'password');
            updatePasswordRequirements();
        });
    }

    // Real-time validation for terms
    if (termsCheckbox) {
        termsCheckbox.addEventListener('change', function() {
            validateField(this, 'terms');
        });
    }

    // Form submission handler
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            // Prevent default form submission
            e.preventDefault();

            // Validate all fields
            let isValid = true;

            if (usernameInput) {
                isValid = validateField(usernameInput, 'username') && isValid;
            }

            if (emailInput) {
                isValid = validateField(emailInput, 'email') && isValid;
            }

            if (nameInput) {
                isValid = validateField(nameInput, 'name') && isValid;
            }

            if (passwordInput) {
                isValid = validateField(passwordInput, 'password') && isValid;
            }

            if (termsCheckbox) {
                isValid = validateField(termsCheckbox, 'terms') && isValid;
            }

            // If form is valid, submit it
            if (isValid) {
                // Show loading indicator
                const loadingIndicator = document.getElementById('loading-indicator');
                if (loadingIndicator) {
                    loadingIndicator.style.display = 'block';
                }

                // Disable submit button
                const submitButton = registerForm.querySelector('button[type="submit"]');
                if (submitButton) {
                    submitButton.disabled = true;
                }

                // Submit the form
                this.submit();
            } else {
                // Show error message
                const errorContainer = document.getElementById('register-error');
                if (errorContainer) {
                    errorContainer.textContent = 'Please correct the errors and try again.';
                    errorContainer.style.display = 'block';

                    // Focus on the first invalid field
                    const firstInvalid = registerForm.querySelector('.is-invalid');
                    if (firstInvalid) {
                        firstInvalid.focus();
                    }

                    // Scroll to the error message
                    errorContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }
        });
    }

    /**
     * Validate a form field
     * @param {HTMLElement} element - The input element to validate
     * @param {string} fieldType - The type of field (username, email, etc.)
     * @returns {boolean} - Whether the field is valid
     */
    function validateField(element, fieldType) {
        if (!element || !validationRules[fieldType]) return false;

        let value;

        // Handle checkbox differently
        if (element.type === 'checkbox') {
            value = element.checked;
        } else {
            value = element.value.trim();
        }

        const validationResult = validationRules[fieldType].validate(value);
        const messageContainer = document.getElementById(`${fieldType}-validation`);

        if (!validationResult.valid) {
            element.classList.add('is-invalid');
            element.classList.remove('is-valid');

            if (messageContainer) {
                messageContainer.textContent = validationResult.message;
                messageContainer.style.display = 'block';
            }
            return false;
        } else {
            element.classList.remove('is-invalid');
            element.classList.add('is-valid');

            if (messageContainer) {
                messageContainer.textContent = '';
                messageContainer.style.display = 'none';
            }
            return true;
        }
    }

    /**
     * Update the password requirements list based on current password value
     */
    function updatePasswordRequirements() {
        if (!passwordInput) return;

        const value = passwordInput.value;

        // Check each requirement
        const lengthReq = document.getElementById('password-length');
        const upperReq = document.getElementById('password-uppercase');
        const lowerReq = document.getElementById('password-lowercase');
        const numberReq = document.getElementById('password-number');

        // Update classes based on requirements
        if (lengthReq) {
            if (value.length >= 8) {
                lengthReq.classList.add('met');
                lengthReq.classList.remove('unmet');
            } else {
                lengthReq.classList.add('unmet');
                lengthReq.classList.remove('met');
            }
        }

        if (upperReq) {
            if (/[A-Z]/.test(value)) {
                upperReq.classList.add('met');
                upperReq.classList.remove('unmet');
            } else {
                upperReq.classList.add('unmet');
                upperReq.classList.remove('met');
            }
        }

        if (lowerReq) {
            if (/[a-z]/.test(value)) {
                lowerReq.classList.add('met');
                lowerReq.classList.remove('unmet');
            } else {
                lowerReq.classList.add('unmet');
                lowerReq.classList.remove('met');
            }
        }

        if (numberReq) {
            if (/[0-9]/.test(value)) {
                numberReq.classList.add('met');
                numberReq.classList.remove('unmet');
            } else {
                numberReq.classList.add('unmet');
                numberReq.classList.remove('met');
            }
        }
    }

    /**
     * Update the username requirements list
     */
    function updateRequirementsList(fieldType) {
        if (fieldType !== 'username' || !usernameInput) return;

        const value = usernameInput.value.trim();

        // Check each requirement
        const lengthReq = document.getElementById('username-length');
        const charsReq = document.getElementById('username-chars');

        // Update classes based on requirements
        if (lengthReq) {
            if (value.length >= 3) {
                lengthReq.classList.add('met');
                lengthReq.classList.remove('unmet');
            } else {
                lengthReq.classList.add('unmet');
                lengthReq.classList.remove('met');
            }
        }

        if (charsReq) {
            if (/^[a-z0-9_]+$/.test(value)) {
                charsReq.classList.add('met');
                charsReq.classList.remove('unmet');
            } else {
                charsReq.classList.add('unmet');
                charsReq.classList.remove('met');
            }
        }
    }
});

// Login form validation
document.addEventListener('DOMContentLoaded', function() {
    // Get the login form
    const loginForm = document.getElementById('login-form');
    if (!loginForm) return;

    // Get form fields
    const usernameInput = document.getElementById('login-username');
    const passwordInput = document.getElementById('login-password');

    // Add validation on input
    if (usernameInput) {
        usernameInput.addEventListener('input', function() {
            validateLoginField(this, 'username');
        });

        usernameInput.addEventListener('blur', function() {
            validateLoginField(this, 'username');
        });
    }

    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            validateLoginField(this, 'password');
        });

        passwordInput.addEventListener('blur', function() {
            validateLoginField(this, 'password');
        });
    }

    // Form submission handler
    loginForm.addEventListener('submit', function(e) {
        // Prevent default form submission
        e.preventDefault();

        // Validate all fields
        let isValid = true;

        if (usernameInput) {
            isValid = validateLoginField(usernameInput, 'username') && isValid;
        }

        if (passwordInput) {
            isValid = validateLoginField(passwordInput, 'password') && isValid;
        }

        // If form is valid, submit it
        if (isValid) {
            // Show loading indicator
            const loadingIndicator = document.getElementById('login-loading-indicator');
            if (loadingIndicator) {
                loadingIndicator.style.display = 'block';
            }

            // Disable submit button
            const submitButton = loginForm.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.disabled = true;
            }

            e.preventDefault();
            setTimeout(() => {
                this.submit();
            }, 5000);
            // Submit the form
            this.submit();

        } else {
            // Show error message
            const errorContainer = document.getElementById('login-error');
            if (errorContainer) {
                errorContainer.textContent = 'Please enter both username and password.';
                errorContainer.style.display = 'block';

                // Focus on the first invalid field
                const firstInvalid = loginForm.querySelector('.is-invalid');
                if (firstInvalid) {
                    firstInvalid.focus();
                }
            }
        }
    });

    /**
     * Validate a login form field
     * @param {HTMLElement} element - The input element to validate
     * @param {string} fieldType - The type of field (username or password)
     * @returns {boolean} - Whether the field is valid
     */
    function validateLoginField(element, fieldType) {
        if (!element) return false;

        const value = element.value.trim();
        const messageContainer = document.getElementById(`login-${fieldType}-validation`);

        // Simple validation - just check if empty
        if (!value) {
            element.classList.add('is-invalid');
            element.classList.remove('is-valid');

            if (messageContainer) {
                messageContainer.textContent = `${fieldType.charAt(0).toUpperCase() + fieldType.slice(1)} is required`;
                messageContainer.style.display = 'block';
            }
            return false;
        } else {
            element.classList.remove('is-invalid');
            element.classList.add('is-valid');

            if (messageContainer) {
                messageContainer.textContent = '';
                messageContainer.style.display = 'none';
            }
            return true;
        }
    }
});

// Notification System
/**
 * Shows a notification message
 * @param {string} message - The message to display
 * @param {string} type - The type of notification: 'success', 'error', 'info', 'warning'
 * @param {number} duration - How long to show the notification in milliseconds (0 for no auto-close)
 */
function showNotification(message, type = 'info', duration = 5000) {
    // Create notification container if it doesn't exist
    let container = document.getElementById('notification-container');

    if (!container) {
        container = document.createElement('div');
        container.id = 'notification-container';
        container.style.position = 'fixed';
        container.style.top = '20px';
        container.style.right = '20px';
        container.style.zIndex = '9999'; // Increase z-index
        document.body.appendChild(container);
    }

    // Create notification element
    const notification = document.createElement('div');
    notification.className = 'notification ' + type;
    notification.style.padding = '15px 20px';
    notification.style.marginBottom = '10px';
    notification.style.borderRadius = '5px';
    notification.style.boxShadow = '0 4px 8px rgba(0,0,0,0.3)'; // More pronounced shadow
    notification.style.display = 'flex';
    notification.style.alignItems = 'center';
    notification.style.justifyContent = 'space-between';
    notification.style.minWidth = '300px'; // Wider notification
    notification.style.animation = 'fadeIn 0.3s';

    // Set background color based on type
    switch (type) {
        case 'success':
            notification.style.backgroundColor = '#4CAF50';
            notification.style.color = 'white';
            break;
        case 'error':
            notification.style.backgroundColor = '#f44336';
            notification.style.color = 'white';
            break;
        case 'warning':
            notification.style.backgroundColor = '#ff9800';
            notification.style.color = 'white';
            break;
        case 'info':
        default:
            notification.style.backgroundColor = '#2196F3';
            notification.style.color = 'white';
            break;
    }

    // Add message
    const messageSpan = document.createElement('span');
    messageSpan.textContent = message;
    notification.appendChild(messageSpan);

    // Add close button
    const closeBtn = document.createElement('span');
    closeBtn.textContent = '×';
    closeBtn.style.marginLeft = '10px';
    closeBtn.style.cursor = 'pointer';
    closeBtn.style.fontWeight = 'bold';
    closeBtn.style.fontSize = '20px';
    closeBtn.onclick = function() {
        container.removeChild(notification);
    };
    notification.appendChild(closeBtn);

    // Add to container
    container.appendChild(notification);

    // Auto-close after duration (if not 0)
    if (duration > 0) {
        setTimeout(function() {
            if (notification.parentNode === container) {
                container.removeChild(notification);
            }
        }, duration);
    }

    // Add animation keyframes if they don't exist
    if (!document.getElementById('notification-keyframes')) {
        const style = document.createElement('style');
        style.id = 'notification-keyframes';
        style.textContent = `
            @keyframes fadeIn {
                from { opacity: 0; transform: translateX(50px); }
                to { opacity: 1; transform: translateX(0); }
            }
        `;
        document.head.appendChild(style);
    }
}
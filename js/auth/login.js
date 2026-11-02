/* ========================================
   LOGIN (mock). Requires js/auth/auth.js
   Password toggles are bound in auth.js, not here.
======================================== */
document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('loginForm');

    if (!loginForm || !window.FoliiooAuth) return;

    const emailInput = document.getElementById('loginEmail');
    const passwordInput = document.getElementById('loginPassword');
    const rememberMe = document.getElementById('rememberMe');

    const emailError = document.getElementById('loginEmailError');
    const passwordError = document.getElementById('loginPasswordError');
    const loginMessage = document.getElementById('loginMessage');

    const submitButton = loginForm.querySelector('button[type="submit"]');
    const submitLabel = submitButton.innerHTML;

    function clearErrors() {
        emailError.textContent = '';
        passwordError.textContent = '';
        loginMessage.textContent = '';
        loginMessage.hidden = true;
        loginMessage.className = 'auth-message';
    }

    function showMessage(text, type) {
        loginMessage.textContent = text;
        loginMessage.className = `auth-message ${type}`;
        loginMessage.hidden = false;
    }

    emailInput.addEventListener('input', () => {
        emailError.textContent = '';
    });

    passwordInput.addEventListener('input', () => {
        passwordError.textContent = '';
    });

    // A signed-in visitor does not need the login form.
    if (FoliiooAuth.isSignedIn()) {
        const session = FoliiooAuth.getSession();

        showMessage(
            `You are already logged in as ${session.fullName}. Continuing...`,
            'success'
        );

        setTimeout(() => {
            window.location.href = FoliiooAuth.afterAuthUrl();
        }, 900);
    }

    loginForm.addEventListener('submit', async event => {
        event.preventDefault();

        clearErrors();

        const email = emailInput.value.trim().toLowerCase();
        const password = passwordInput.value;

        let isValid = true;

        if (!email) {
            emailError.textContent = 'Please enter your email address.';
            isValid = false;
        } else if (!emailInput.validity.valid) {
            emailError.textContent = 'Please enter a valid email address.';
            isValid = false;
        }

        if (!password) {
            passwordError.textContent = 'Please enter your password.';
            isValid = false;
        }

        if (!isValid) return;

        submitButton.disabled = true;
        submitButton.textContent = 'Logging in...';

        try {
            const account = await FoliiooAuth.verifyLogin(email, password);

            const session = FoliiooAuth.startSession(account, rememberMe.checked);

            showMessage(`Welcome back, ${session.fullName}! Login successful.`, 'success');

            submitButton.innerHTML = '<i class="fa-solid fa-check"></i> Login successful';

            setTimeout(() => {
                window.location.href = FoliiooAuth.afterAuthUrl();
            }, 1000);
        } catch (error) {
            showMessage(error.message || 'Unable to log in. Please try again.', 'error');
            submitButton.disabled = false;
            submitButton.innerHTML = submitLabel;
        }
    });
});
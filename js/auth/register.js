/* ========================================
   REGISTER (mock). Requires js/auth/auth.js
   Password toggles are bound in auth.js, not here.
======================================== */
document.addEventListener('DOMContentLoaded', () => {
    const registerForm = document.getElementById('registerForm');

    if (!registerForm || !window.FoliiooAuth) return;

    const fullNameInput = document.getElementById('fullName');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirmPassword');
    const termsCheckbox = document.getElementById('acceptTerms');

    const registerMessage = document.getElementById('registerMessage');
    const submitButton = registerForm.querySelector('button[type="submit"]');
    const submitLabel = submitButton.innerHTML;

    const errors = {
        fullName: document.getElementById('fullNameError'),
        email: document.getElementById('emailError'),
        password: document.getElementById('passwordError'),
        confirmPassword: document.getElementById('confirmPasswordError'),
        terms: document.getElementById('termsError')
    };

    function showError(input, errorElement, message) {
        errorElement.textContent = message;
        input.setAttribute('aria-invalid', 'true');
    }

    function clearError(input, errorElement) {
        errorElement.textContent = '';
        input.removeAttribute('aria-invalid');
    }

    function clearAllErrors() {
        clearError(fullNameInput, errors.fullName);
        clearError(emailInput, errors.email);
        clearError(passwordInput, errors.password);
        clearError(confirmPasswordInput, errors.confirmPassword);
        errors.terms.textContent = '';
    }

    function showMessage(text, type) {
        registerMessage.textContent = text;
        registerMessage.className = `auth-message ${type}`;
        registerMessage.hidden = false;
    }

    // Clear individual errors while typing
    fullNameInput.addEventListener('input', () => clearError(fullNameInput, errors.fullName));
    emailInput.addEventListener('input', () => clearError(emailInput, errors.email));
    passwordInput.addEventListener('input', () => clearError(passwordInput, errors.password));
    confirmPasswordInput.addEventListener('input', () =>
        clearError(confirmPasswordInput, errors.confirmPassword)
    );
    termsCheckbox.addEventListener('change', () => {
        errors.terms.textContent = '';
    });

    registerForm.addEventListener('submit', async event => {
        event.preventDefault();

        clearAllErrors();
        registerMessage.hidden = true;
        registerMessage.textContent = '';
        registerMessage.className = 'auth-message';

        const fullName = fullNameInput.value.trim();
        const email = emailInput.value.trim().toLowerCase();
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;

        let isValid = true;

        if (fullName.length < 2) {
            showError(fullNameInput, errors.fullName, 'Please enter your full name.');
            isValid = false;
        }

        if (!email) {
            showError(emailInput, errors.email, 'Please enter your email address.');
            isValid = false;
        } else if (!emailInput.validity.valid) {
            showError(emailInput, errors.email, 'Please enter a valid email address.');
            isValid = false;
        }

        if (password.length < 8) {
            showError(
                passwordInput,
                errors.password,
                'Your password must contain at least 8 characters.'
            );
            isValid = false;
        }

        if (!confirmPassword) {
            showError(
                confirmPasswordInput,
                errors.confirmPassword,
                'Please confirm your password.'
            );
            isValid = false;
        } else if (password !== confirmPassword) {
            showError(
                confirmPasswordInput,
                errors.confirmPassword,
                'Your passwords do not match.'
            );
            isValid = false;
        }

        if (!termsCheckbox.checked) {
            errors.terms.textContent =
                'You must agree to the Terms of Service and Privacy Policy.';
            isValid = false;
        }

        if (!isValid) return;

        if (FoliiooAuth.findAccount(email)) {
            showMessage('An account with this email already exists. Please log in.', 'error');
            return;
        }

        submitButton.disabled = true;

        try {
            const account = await FoliiooAuth.createAccount({ fullName, email, password });

            FoliiooAuth.startSession(account, false);

            showMessage(
                `Welcome to Foliioo, ${fullName}! Your creator account has been created.`,
                'success'
            );

            submitButton.innerHTML = '<i class="fa-solid fa-check"></i> Account Created';

            // Continue the journey the visitor started (for example, publishing).
            setTimeout(() => {
                window.location.href = FoliiooAuth.afterAuthUrl();
            }, 1200);
        } catch (error) {
            showMessage(error.message || 'Unable to create your account. Please try again.', 'error');
            submitButton.disabled = false;
            submitButton.innerHTML = submitLabel;
        }
    });
});
/* ========================================
   FORGOT PASSWORD (simulated). Requires js/auth/auth.js
   No email is sent. The "simulated link" stands in for the emailed link.
======================================== */
document.addEventListener('DOMContentLoaded', () => {
    const forgotForm = document.getElementById('forgotForm');

    if (!forgotForm || !window.FoliiooAuth) return;

    const resetForm = document.getElementById('resetForm');
    const intro = document.getElementById('forgotIntro');

    const emailInput = document.getElementById('forgotEmail');
    const emailError = document.getElementById('forgotEmailError');
    const forgotMessage = document.getElementById('forgotMessage');
    const simulatedEmail = document.getElementById('simulatedEmail');
    const simulatedLink = document.getElementById('simulatedResetLink');

    const newPassword = document.getElementById('newPassword');
    const confirmNewPassword = document.getElementById('confirmNewPassword');
    const newPasswordError = document.getElementById('newPasswordError');
    const confirmError = document.getElementById('confirmNewPasswordError');
    const resetMessage = document.getElementById('resetMessage');

    let resetEmail = '';

    function show(element, text, type) {
        element.textContent = text;
        element.className = `auth-message ${type}`;
        element.hidden = false;
    }

    emailInput.addEventListener('input', () => {
        emailError.textContent = '';
    });

    newPassword.addEventListener('input', () => {
        newPasswordError.textContent = '';
    });

    confirmNewPassword.addEventListener('input', () => {
        confirmError.textContent = '';
    });

    // Step 1: ask for the email
    forgotForm.addEventListener('submit', event => {
        event.preventDefault();

        emailError.textContent = '';
        forgotMessage.hidden = true;
        simulatedEmail.hidden = true;

        const email = emailInput.value.trim().toLowerCase();

        if (!email) {
            emailError.textContent = 'Please enter your email address.';
            return;
        }

        if (!emailInput.validity.valid) {
            emailError.textContent = 'Please enter a valid email address.';
            return;
        }

        // Same message whether or not the account exists.
        show(
            forgotMessage,
            'If an account exists for that email, a reset link would be sent to it.',
            'success'
        );

        if (FoliiooAuth.findAccount(email)) {
            resetEmail = email;
            simulatedEmail.hidden = false;
        }
    });

    // Opening the simulated link reveals the new-password form
    simulatedLink.addEventListener('click', event => {
        event.preventDefault();

        forgotForm.hidden = true;
        resetForm.hidden = false;
        intro.textContent = `Choose a new password for ${resetEmail}.`;
        newPassword.focus();
    });

    // Step 2: set the new password
    resetForm.addEventListener('submit', async event => {
        event.preventDefault();

        newPasswordError.textContent = '';
        confirmError.textContent = '';
        resetMessage.hidden = true;

        let isValid = true;

        if (newPassword.value.length < 8) {
            newPasswordError.textContent = 'Your password must contain at least 8 characters.';
            isValid = false;
        }

        if (!confirmNewPassword.value) {
            confirmError.textContent = 'Please confirm your new password.';
            isValid = false;
        } else if (newPassword.value !== confirmNewPassword.value) {
            confirmError.textContent = 'Your passwords do not match.';
            isValid = false;
        }

        if (!isValid) return;

        const submit = resetForm.querySelector('button[type="submit"]');

        submit.disabled = true;

        try {
            await FoliiooAuth.resetPassword(resetEmail, newPassword.value);

            show(resetMessage, 'Password updated. Taking you to log in...', 'success');

            setTimeout(() => {
                window.location.href = 'login.php';
            }, 1400);
        } catch (error) {
            show(resetMessage, error.message || 'Unable to reset your password.', 'error');
            submit.disabled = false;
        }
    });
});

<?php
$pageTitle = 'Reset Your Password — Foliioo';
$pageDescription = 'Reset the password for your Foliioo creator account.';

require_once __DIR__ . '/_inc/head.inc.php';
?>

<main class="auth-page">
    <div class="auth-container">

        <!-- Brand -->
        <a href="index.php" class="auth-logo">
            <span class="site-logo-mark">
                <i class="fa-solid fa-leaf"></i>
            </span>
            <span>foliioo<span class="site-logo-period">.</span></span>
        </a>

        <section class="auth-card">

            <div class="auth-heading">
                <span class="eyebrow">ACCOUNT HELP</span>

                <h1 class="font-display">Reset your password</h1>

                <p id="forgotIntro">
                    Enter the email address you registered with and we will
                    help you set a new password.
                </p>
            </div>

            <!-- Step 1: email -->
            <form id="forgotForm" class="auth-form" novalidate>
                <div class="form-group">
                    <label for="forgotEmail" class="form-label">
                        Email address
                    </label>

                    <input
                        type="email"
                        id="forgotEmail"
                        name="email"
                        class="form-input"
                        placeholder="you@example.com"
                        autocomplete="email"
                        required
                    >

                    <small class="auth-error" id="forgotEmailError"></small>
                </div>

                <div id="forgotMessage" class="auth-message" role="status" hidden></div>

                <!-- Prototype only: stands in for the link we would email -->
                <div id="simulatedEmail" class="auth-message success" hidden>
                    <strong>Prototype only.</strong>
                    No email is sent in this version.
                    <a href="#" id="simulatedResetLink" class="auth-inline-link">
                        Open the simulated reset link
                    </a>
                </div>

                <button type="submit" class="btn btn-primary btn-block auth-submit">
                    Send reset link
                    <i class="fa-solid fa-arrow-right"></i>
                </button>
            </form>

            <!-- Step 2: new password (shown after the simulated link is opened) -->
            <form id="resetForm" class="auth-form" novalidate hidden>
                <div class="form-group">
                    <label for="newPassword" class="form-label">
                        New password
                    </label>

                    <div class="auth-password-wrapper">
                        <input
                            type="password"
                            id="newPassword"
                            name="newPassword"
                            class="form-input"
                            placeholder="Create a new password"
                            autocomplete="new-password"
                            required
                        >

                        <button
                            type="button"
                            class="password-toggle"
                            data-password-toggle="newPassword"
                            aria-label="Show password"
                        >
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>

                    <small class="form-help">Use at least 8 characters.</small>
                    <small class="auth-error" id="newPasswordError"></small>
                </div>

                <div class="form-group">
                    <label for="confirmNewPassword" class="form-label">
                        Confirm new password
                    </label>

                    <div class="auth-password-wrapper">
                        <input
                            type="password"
                            id="confirmNewPassword"
                            name="confirmNewPassword"
                            class="form-input"
                            placeholder="Re-enter your new password"
                            autocomplete="new-password"
                            required
                        >

                        <button
                            type="button"
                            class="password-toggle"
                            data-password-toggle="confirmNewPassword"
                            aria-label="Show password"
                        >
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>

                    <small class="auth-error" id="confirmNewPasswordError"></small>
                </div>

                <div id="resetMessage" class="auth-message" role="status" hidden></div>

                <button type="submit" class="btn btn-primary btn-block auth-submit">
                    Save new password
                    <i class="fa-solid fa-check"></i>
                </button>
            </form>

            <div class="auth-divider">
                <span>Remembered it?</span>
            </div>

            <a href="login.php" class="btn btn-secondary btn-block">
                Back to log in
            </a>

        </section>

        <p class="auth-footer-text">
            Prototype note: accounts are stored in this browser only, so
            password reset works only on the browser where you registered.
        </p>

    </div>
</main>

<script src="js/auth/forgot-password.js" defer></script>

<?php require_once __DIR__ . '/_inc/footer.php'; ?>

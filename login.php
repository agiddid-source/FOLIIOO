
<?php
$pageTitle = 'Log In — Foliioo';
$pageDescription = 'Log in to your Foliioo creator account to manage your events.';

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

        <!-- Login Card -->
        <section class="auth-card">

            <div class="auth-heading">
                <span class="eyebrow">WELCOME BACK</span>

                <h1 class="font-display">
                    Good to see you again.
                </h1>

                <p>
                    Log in to continue creating beautiful event experiences.
                </p>
            </div>

            <form id="loginForm" class="auth-form" novalidate>

                <!-- Email -->
                <div class="form-group">
                    <label for="loginEmail" class="form-label">
                        Email address
                    </label>

                    <input
                        type="email"
                        id="loginEmail"
                        name="email"
                        class="form-input"
                        placeholder="you@example.com"
                        autocomplete="email"
                        required
                    >

                    <small class="auth-error" id="loginEmailError"></small>
                </div>

                <!-- Password -->
                <div class="form-group">
                    <div class="auth-field-heading">
                        <label for="loginPassword" class="form-label">
                            Password
                        </label>

                        <a href="forgot-password.php" class="auth-inline-link">
                            Forgot password?
                        </a>
                    </div>

                    <div class="auth-password-wrapper">
                        <input
                            type="password"
                            id="loginPassword"
                            name="password"
                            class="form-input"
                            placeholder="Enter your password"
                            autocomplete="current-password"
                            required
                        >

                        <button
                            type="button"
                            class="password-toggle"
                            data-password-toggle="loginPassword"
                            aria-label="Show password"
                        >
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>

                    <small class="auth-error" id="loginPasswordError"></small>
                </div>

                <!-- Remember Me -->
                <label class="auth-remember">
                    <input type="checkbox" id="rememberMe">

                    <span>Remember me</span>
                </label>

                <!-- Feedback -->
                <div id="loginMessage" class="auth-message" hidden></div>

                <!-- Submit -->
                <button
                    type="submit"
                    class="btn btn-primary btn-block auth-submit"
                >
                    Log in
                    <i class="fa-solid fa-arrow-right"></i>
                </button>

            </form>

            <div class="auth-divider">
                <span>New to Foliioo?</span>
            </div>

            <a href="register.php" class="btn btn-secondary btn-block">
                Create an account
            </a>

        </section>

        <p class="auth-footer-text">
            Your events, your creativity, all in one place. Prototype note: accounts are stored in this browser only.
        </p>

    </div>
</main>

<script src="js/auth/login.js" defer></script>

<?php require_once __DIR__ . '/_inc/footer.php'; ?>

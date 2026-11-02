
<?php
$pageTitle = 'Create Your Account — Foliioo';
$pageDescription = 'Create your Foliioo creator account and start bringing your events to life.';

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

        <!-- Registration Card -->
        <section class="auth-card">

            <div class="auth-heading">
                <span class="eyebrow">WELCOME TO FOLIIOO</span>

                <h1 class="font-display">
                    Create your account
                </h1>

                <p>
                    Your beautiful events deserve a beautiful home.
                    Let's get you started.
                </p>
            </div>

            <form id="registerForm" class="auth-form" novalidate>

                <div class="form-group">
                    <label for="fullName" class="form-label">
                        Full name
                    </label>

                    <input
                        type="text"
                        id="fullName"
                        name="fullName"
                        class="form-input"
                        placeholder="Enter your full name"
                        autocomplete="name"
                        required
                    >

                    <small class="auth-error" id="fullNameError"></small>
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">
                        Email address
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-input"
                        placeholder="you@example.com"
                        autocomplete="email"
                        required
                    >

                    <small class="auth-error" id="emailError"></small>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">
                        Password
                    </label>

                    <div class="auth-password-wrapper">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-input"
                            placeholder="Create a password"
                            autocomplete="new-password"
                            required
                        >

                        <button
                            type="button"
                            class="password-toggle"
                            data-password-toggle="password"
                            aria-label="Show password"
                        >
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>

                    <small class="form-help">
                        Use at least 8 characters.
                    </small>

                    <small class="auth-error" id="passwordError"></small>
                </div>

                <div class="form-group">
                    <label for="confirmPassword" class="form-label">
                        Confirm password
                    </label>

                    <div class="auth-password-wrapper">
                        <input
                            type="password"
                            id="confirmPassword"
                            name="confirmPassword"
                            class="form-input"
                            placeholder="Re-enter your password"
                            autocomplete="new-password"
                            required
                        >

                        <button
                            type="button"
                            class="password-toggle"
                            data-password-toggle="confirmPassword"
                            aria-label="Show password"
                        >
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>

                    <small class="auth-error" id="confirmPasswordError"></small>
                </div>

                <label class="auth-terms">
                    <input type="checkbox" id="acceptTerms" required>

                    <span>
                        I agree to Foliioo's
                        <a href="#" class="auth-inline-link">Terms of Service</a>
                        and
                        <a href="#" class="auth-inline-link">Privacy Policy</a>.
                    </span>
                </label>

                <small class="auth-error" id="termsError"></small>

                <div id="registerMessage" class="auth-message" hidden></div>

                <button type="submit" class="btn btn-primary btn-block auth-submit">
                    Create account
                    <i class="fa-solid fa-arrow-right"></i>
                </button>

            </form>

            <div class="auth-divider">
                <span>Already have an account?</span>
            </div>

            <a href="login.php" class="btn btn-secondary btn-block">
                Log in
            </a>

        </section>

        <p class="auth-footer-text">
            By joining Foliioo, you're one step closer to creating
            unforgettable event experiences. Prototype note: accounts are stored in this browser only.
        </p>

    </div>
</main>

<script src="js/auth/register.js" defer></script>

<?php require_once __DIR__ . '/_inc/footer.php'; ?>


<?php
$currentPage = $currentPage ?? '';
?>

<header class="site-header">
    <nav class="container-main site-nav" aria-label="Main navigation">

        <!-- Brand -->
        <a href="index.php" class="site-logo" aria-label="Foliioo home">
            <span class="site-logo-mark">
                <i class="fa-solid fa-leaf"></i>
            </span>
            <span>foliioo<span class="site-logo-period">.</span></span>
        </a>

        <!-- Desktop Navigation -->
        <div class="site-nav-links">
            <a href="templates.php"
               class="site-nav-link <?= $currentPage === 'templates' ? 'active' : '' ?>">
                Templates
            </a>

            <a href="pricing.php"
               class="site-nav-link <?= $currentPage === 'pricing' ? 'active' : '' ?>">
                Pricing
            </a>

            <a href="#how-it-works"
               class="site-nav-link">
                How it works
            </a>
        </div>

        <!-- Desktop Actions -->
        <div class="site-nav-actions">
            <a href="login.php" class="site-login-link">Log in</a>

            <a href="create-event.php" class="btn btn-primary btn-sm">
                Create an event
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <!-- Mobile Menu Toggle -->
        <button
            type="button"
            class="mobile-menu-toggle"
            id="mobileMenuToggle"
            aria-label="Open navigation menu"
            aria-expanded="false"
            aria-controls="mobileMenu"
        >
            <i class="fa-solid fa-bars"></i>
        </button>

    </nav>

    <!-- Mobile Navigation -->
    <div class="mobile-menu" id="mobileMenu" hidden>
        <div class="container-main mobile-menu-inner">

            <a href="templates.php" class="mobile-menu-link">
                Templates
            </a>

            <a href="pricing.php" class="mobile-menu-link">
                Pricing
            </a>

            <a href="index.php#how-it-works" class="mobile-menu-link">
                How it works
            </a>

            <div class="divider my-2"></div>

            <a href="login.php" class="mobile-menu-link">
                Log in
            </a>

            <a href="create-event.php" class="btn btn-primary btn-block">
                Create an event
                <i class="fa-solid fa-arrow-right"></i>
            </a>

        </div>
    </div>
</header>

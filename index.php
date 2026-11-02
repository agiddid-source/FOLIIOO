
<?php
$pageTitle = 'Foliioo — Create a Beautiful Home for Your Event';
$pageDescription = 'Create a personalized event website, manage RSVPs, coordinate your programme, and bring your guests together with Foliioo.';

$currentPage = 'home';

require_once __DIR__ . '/_inc/head.inc.php';
require_once __DIR__ . '/_inc/header.php';
?>

<main>

    <!-- =====================================
         HERO SECTION
    ====================================== -->
    <section class="hero-section">
        <div class="container-main hero-grid">

            <div class="hero-content">
                <span class="eyebrow">
                    <i class="fa-solid fa-sparkles"></i>
                    YOUR MOMENT. YOUR STORY.
                </span>

                <h1 class="hero-title">
                    Every special moment
                    <span>deserves a beautiful home.</span>
                </h1>

                <p class="hero-description">
                    From the first invitation to the final memory, bring
                    your event together with a website that feels uniquely
                    yours.
                </p>

                <div class="hero-actions">
                    <a href="create-event.php" class="btn btn-primary btn-lg">
                        Create your event
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>

                    <a href="templates.php" class="btn btn-secondary btn-lg">
                        Explore templates
                    </a>
                </div>

                <div class="hero-trust">
                    <div class="hero-trust-icon">
                        <i class="fa-solid fa-heart"></i>
                    </div>

                    <p>
                        Made for weddings, birthdays, celebrations
                        and everything worth remembering.
                    </p>
                </div>
            </div>

            <!-- Visual event website preview -->
            <div class="hero-visual">
                <div class="hero-visual-glow"></div>

                <div class="event-preview-card">
                    <div class="event-preview-image">
                        <img src="assets/images/hero/Temi_&_Michael.jfif">

                        <span class="preview-label">
                            EVENT WEBSITE PREVIEW
                        </span>
                    </div>

                    <div class="event-preview-content">
                        <span class="preview-overline">
                            TOGETHER WITH THEIR FAMILIES
                        </span>

                        <h2>Olivia & James</h2>

                        <p class="preview-event-date">
                            SATURDAY, JUNE 19 · 2027
                        </p>

                        <div class="preview-divider"></div>

                        <p class="preview-event-message">
                            We'd love for you to be part of our
                            beginning.
                        </p>

                        <span class="preview-rsvp">
                            KINDLY RSVP
                        </span>
                    </div>
                </div>

                <div class="floating-note floating-note-top">
                    <span class="floating-note-icon">
                        <i class="fa-solid fa-calendar-check"></i>
                    </span>

                    <span>
                        <strong>All the details</strong>
                        <small>Beautifully organized</small>
                    </span>
                </div>

                <div class="floating-note floating-note-bottom">
                    <span class="floating-note-icon sage-icon">
                        <i class="fa-solid fa-envelope-open-text"></i>
                    </span>

                    <span>
                        <strong>Your guests, together</strong>
                        <small>One link. One special day.</small>
                    </span>
                </div>
            </div>

        </div>
    </section>

    <!-- =====================================
         OCCASIONS
    ====================================== -->
    <section class="occasion-section">
        <div class="container-main">
            <p class="occasion-intro">
                ONE HOME FOR EVERY OCCASION
            </p>

            <div class="occasion-list">
                <span><i class="fa-solid fa-ring"></i> Weddings</span>
                <span><i class="fa-solid fa-cake-candles"></i> Birthdays</span>
                <span><i class="fa-solid fa-champagne-glasses"></i> Anniversaries</span>
                <span><i class="fa-solid fa-graduation-cap"></i> Graduations</span>
                <span><i class="fa-solid fa-building"></i> Corporate events</span>
            </div>
        </div>
    </section>

    <!-- =====================================
         TEMPLATE SHOWCASE
    ====================================== -->
    <section class="section-padding template-section" id="templates">
        <div class="container-main">

            <div class="section-intro">
                <span class="eyebrow">DESIGNED FOR YOUR MOMENT</span>

                <h2 class="section-heading">
                    Your event should feel like you.
                </h2>

                <p class="section-description">
                    Start with a thoughtfully designed template, then
                    make it your own with your details, photographs and style.
                </p>
            </div>

            <div class="template-grid">

                <!-- Elegant -->
                <article class="template-card">
                    <a href="templates.php" class="template-image template-elegant">
                        <img
                            src="https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=800&q=80"
                            alt="Elegant wedding venue with romantic floral details"
                            loading="lazy"
                        >

                        <span class="template-image-label">
                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            Preview style
                        </span>
                    </a>

                    <div class="template-card-info">
                        <div>
                            <h3>Elegant</h3>
                            <p>Timeless details. Effortless beauty.</p>
                        </div>

                        <span class="template-number">01</span>
                    </div>
                </article>

                <!-- Modern -->
                <article class="template-card">
                    <a href="templates.php" class="template-image template-modern">
                        <img
                            src="https://images.unsplash.com/photo-1511795409834-ef04bbd61622?auto=format&fit=crop&w=800&q=80"
                            alt="Contemporary event table styling"
                            loading="lazy"
                        >

                        <span class="template-image-label">
                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            Preview style
                        </span>
                    </a>

                    <div class="template-card-info">
                        <div>
                            <h3>Modern</h3>
                            <p>Clean lines. A confident statement.</p>
                        </div>

                        <span class="template-number">02</span>
                    </div>
                </article>

                <!-- Floral -->
                <article class="template-card">
                    <a href="templates.php" class="template-image template-floral">
                        <img
                            src="https://images.unsplash.com/photo-1464366400600-7168b8af9bc3?auto=format&fit=crop&w=800&q=80"
                            alt="Floral event tablescape with soft romantic details"
                            loading="lazy"
                        >

                        <span class="template-image-label">
                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            Preview style
                        </span>
                    </a>

                    <div class="template-card-info">
                        <div>
                            <h3>Floral</h3>
                            <p>Soft touches. A little romance.</p>
                        </div>

                        <span class="template-number">03</span>
                    </div>
                </article>

            </div>

            <div class="section-bottom-action">
                <a href="templates.php" class="btn btn-secondary">
                    Discover all templates
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>

        </div>
    </section>

    <!-- =====================================
         HOW IT WORKS
    ====================================== -->
    <section class="section-padding how-section" id="how-it-works">
        <div class="container-main">

            <div class="section-intro">
                <span class="eyebrow">SIMPLE FROM THE START</span>

                <h2 class="section-heading">
                    From an idea to an event
                    worth remembering.
                </h2>

                <p class="section-description">
                    You bring the occasion. We'll help you bring
                    everything together.
                </p>
            </div>

            <div class="steps-grid">

                <article class="step-card">
                    <span class="step-number">01</span>

                    <div class="icon-box">
                        <i class="fa-solid fa-pen-ruler"></i>
                    </div>

                    <h3>Make it yours</h3>

                    <p>
                        Choose a template, add your event details
                        and personalize your website.
                    </p>
                </article>

                <article class="step-card">
                    <span class="step-number">02</span>

                    <div class="icon-box icon-box-sage">
                        <i class="fa-solid fa-link"></i>
                    </div>

                    <h3>Bring everyone together</h3>

                    <p>
                        Share one event link so guests can find
                        the details, RSVP and prepare for the day.
                    </p>
                </article>

                <article class="step-card">
                    <span class="step-number">03</span>

                    <div class="icon-box">
                        <i class="fa-solid fa-heart"></i>
                    </div>

                    <h3>Enjoy the moment</h3>

                    <p>
                        Coordinate your guests, manage event-day
                        details and preserve memories afterward.
                    </p>
                </article>

            </div>
        </div>
    </section>

    <!-- =====================================
         FEATURES
    ====================================== -->
    <section class="section-padding features-section">
        <div class="container-main features-grid">

            <div class="features-content">
                <span class="eyebrow">MORE THAN AN INVITATION</span>

                <h2 class="section-heading features-heading">
                    The little details.
                    The bigger picture.
                    All in one place.
                </h2>

                <p class="features-description">
                    Give your guests a better experience while keeping
                    your event organized behind the scenes.
                </p>

                <div class="feature-list">

                    <div class="feature-item">
                        <span class="feature-check">
                            <i class="fa-solid fa-check"></i>
                        </span>

                        <div>
                            <h3>A website that feels personal</h3>
                            <p>
                                Share your story, venue, schedule
                                and important event information.
                            </p>
                        </div>
                    </div>

                    <div class="feature-item">
                        <span class="feature-check">
                            <i class="fa-solid fa-check"></i>
                        </span>

                        <div>
                            <h3>Guest management made simpler</h3>
                            <p>
                                Keep RSVPs, attendance and guest
                                information organized.
                            </p>
                        </div>
                    </div>

                    <div class="feature-item">
                        <span class="feature-check">
                            <i class="fa-solid fa-check"></i>
                        </span>

                        <div>
                            <h3>More meaningful memories</h3>
                            <p>
                                Share event photos and give your
                                celebration a place to live on.
                            </p>
                        </div>
                    </div>

                </div>

                <a href="create-event.php" class="btn btn-primary btn-lg">
                    Bring your event to life
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>

            <div class="features-visual">
                <div class="feature-photo-main">
                    <img
                        src="https://images.unsplash.com/photo-1519225421980-715cb0215aed?auto=format&fit=crop&w=1000&q=85"
                        alt="Beautifully styled celebration table"
                        loading="lazy"
                    >
                </div>

                <div class="feature-stat-card">
                    <span class="feature-stat-icon">
                        <i class="fa-solid fa-calendar-days"></i>
                    </span>

                    <div>
                        <strong>Your day, your way.</strong>
                        <p>Details that come together beautifully.</p>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- =====================================
         FINAL CALL TO ACTION
    ====================================== -->
    <section class="final-cta-section">
        <div class="container-main final-cta">

            <span class="eyebrow">YOUR STORY STARTS HERE</span>

            <h2>
                Something special is
                <span>worth celebrating beautifully.</span>
            </h2>

            <p>
                Start with an idea. Create a home for your event.
                Bring your people together.
            </p>

            <a href="create-event.php" class="btn btn-primary btn-lg">
                Create your event
                <i class="fa-solid fa-arrow-right"></i>
            </a>

            <span class="final-cta-note">
                Choose a style that feels like you.
            </span>

        </div>
    </section>

</main>

<?php require_once __DIR__ . '/_inc/footer.php'; ?>

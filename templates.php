
<?php
$pageTitle = 'Explore Event Website Templates | Foliioo';
$pageDescription = 'Explore Elegant, Modern and Floral event website templates for weddings, birthdays, anniversaries and more.';

$currentPage = 'templates';

require_once __DIR__ . '/_inc/head.inc.php';
require_once __DIR__ . '/_inc/header.php';
?>

<main>

    <!-- Page Introduction -->
    <section class="gallery-hero">
        <div class="container-main gallery-hero-content">
            <span class="eyebrow">
                <i class="fa-solid fa-wand-magic-sparkles"></i>
                THE FOLIIOO COLLECTION
            </span>

            <h1>
                Find the style that
                <span>feels like your event.</span>
            </h1>

            <p>
                Thoughtfully designed starting points for your special
                moments. Choose a style, make it yours, and share your story.
            </p>

            <div class="gallery-hero-note">
                <i class="fa-solid fa-circle-check"></i>
                <span>Three distinctive styles. Your personal touch.</span>
            </div>
        </div>
    </section>

    <!-- Template Filters -->
    <section class="gallery-section">
        <div class="container-main">

            <div class="gallery-toolbar">
                <div class="gallery-results">
                    <h2>Explore templates</h2>
                    <p id="templateCount">Showing 3 styles</p>
                </div>

                <div class="gallery-filters" aria-label="Filter templates">
                    <button
                        type="button"
                        class="gallery-filter active"
                        data-filter="all"
                        aria-pressed="true"
                    >
                        All styles
                    </button>

                    <button
                        type="button"
                        class="gallery-filter"
                        data-filter="elegant"
                        aria-pressed="false"
                    >
                        Elegant
                    </button>

                    <button
                        type="button"
                        class="gallery-filter"
                        data-filter="modern"
                        aria-pressed="false"
                    >
                        Modern
                    </button>

                    <button
                        type="button"
                        class="gallery-filter"
                        data-filter="floral"
                        aria-pressed="false"
                    >
                        Floral
                    </button>
                </div>
            </div>

            <!-- Template Cards -->
            <div class="gallery-grid" id="templateGrid">

                <!-- Elegant -->
                <article class="gallery-card" data-template="elegant">
                    <div class="gallery-card-visual">
                        <img
                            src="https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=1000&q=85"
                            alt="Elegant wedding celebration"
                            loading="lazy"
                        >

                        <span class="gallery-style-badge">
                            <i class="fa-regular fa-gem"></i>
                            TIMELESS
                        </span>

                        <button
                            type="button"
                            class="gallery-preview-button"
                            data-preview="elegant"
                        >
                            <i class="fa-regular fa-eye"></i>
                            Preview style
                        </button>
                    </div>

                    <div class="gallery-card-body">
                        <div class="gallery-card-title-row">
                            <div>
                                <h3>Elegant</h3>
                                <p class="gallery-card-subtitle">
                                    Grace in every detail.
                                </p>
                            </div>

                            <span class="gallery-card-number">01</span>
                        </div>

                        <p class="gallery-card-description">
                            Refined typography, warm neutrals and a
                            timeless atmosphere for meaningful occasions.
                        </p>

                        <div class="gallery-tags">
                            <span>Weddings</span>
                            <span>Anniversaries</span>
                            <span>Formal events</span>
                        </div>

                        <a
                            href="create-event.php?template=elegant"
                            class="btn btn-primary btn-block gallery-use-button"
                        >
                            Use this style
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </article>

                <!-- Modern -->
                <article class="gallery-card" data-template="modern">
                    <div class="gallery-card-visual">
                        <img
                            src="https://images.unsplash.com/photo-1511795409834-ef04bbd61622?auto=format&fit=crop&w=1000&q=85"
                            alt="Modern event setting"
                            loading="lazy"
                        >

                        <span class="gallery-style-badge modern-badge">
                            <i class="fa-solid fa-shapes"></i>
                            CONTEMPORARY
                        </span>

                        <button
                            type="button"
                            class="gallery-preview-button"
                            data-preview="modern"
                        >
                            <i class="fa-regular fa-eye"></i>
                            Preview style
                        </button>
                    </div>

                    <div class="gallery-card-body">
                        <div class="gallery-card-title-row">
                            <div>
                                <h3>Modern</h3>
                                <p class="gallery-card-subtitle">
                                    Simple. Bold. Distinctive.
                                </p>
                            </div>

                            <span class="gallery-card-number">02</span>
                        </div>

                        <p class="gallery-card-description">
                            Clean compositions and confident styling
                            for celebrations with a contemporary feel.
                        </p>

                        <div class="gallery-tags">
                            <span>Birthdays</span>
                            <span>Graduations</span>
                            <span>Corporate events</span>
                        </div>

                        <a
                            href="create-event.php?template=modern"
                            class="btn btn-primary btn-block gallery-use-button"
                        >
                            Use this style
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </article>

                <!-- Floral -->
                <article class="gallery-card" data-template="floral">
                    <div class="gallery-card-visual">
                        <img
                            src="https://images.unsplash.com/photo-1464366400600-7168b8af9bc3?auto=format&fit=crop&w=1000&q=85"
                            alt="Romantic floral celebration table"
                            loading="lazy"
                        >

                        <span class="gallery-style-badge floral-badge">
                            <i class="fa-solid fa-seedling"></i>
                            ROMANTIC
                        </span>

                        <button
                            type="button"
                            class="gallery-preview-button"
                            data-preview="floral"
                        >
                            <i class="fa-regular fa-eye"></i>
                            Preview style
                        </button>
                    </div>

                    <div class="gallery-card-body">
                        <div class="gallery-card-title-row">
                            <div>
                                <h3>Floral</h3>
                                <p class="gallery-card-subtitle">
                                    A softer kind of celebration.
                                </p>
                            </div>

                            <span class="gallery-card-number">03</span>
                        </div>

                        <p class="gallery-card-description">
                            Gentle tones, romantic details and an
                            expressive look for joyful gatherings.
                        </p>

                        <div class="gallery-tags">
                            <span>Weddings</span>
                            <span>Birthdays</span>
                            <span>Anniversaries</span>
                        </div>

                        <a
                            href="create-event.php?template=floral"
                            class="btn btn-primary btn-block gallery-use-button"
                        >
                            Use this style
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </article>

            </div>

            <div class="gallery-empty-state" id="galleryEmptyState" hidden>
                <i class="fa-solid fa-magnifying-glass"></i>
                <h3>No templates found</h3>
                <p>Try selecting a different style.</p>
            </div>

        </div>
    </section>

    <!-- Help Section -->
    <section class="gallery-help-section">
        <div class="container-main gallery-help">
            <div class="gallery-help-icon">
                <i class="fa-regular fa-lightbulb"></i>
            </div>

            <div>
                <h2>Not sure which style to choose?</h2>
                <p>
                    Start with the feeling you want your guests to
                    experience. You can explore the options before
                    committing to a design.
                </p>
            </div>

            <a href="create-event.php" class="btn btn-secondary">
                Start with your event
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
    </section>

    <!-- Preview Modal -->
    <div
        class="template-modal"
        id="templateModal"
        role="dialog"
        aria-modal="true"
        aria-labelledby="templateModalTitle"
        aria-hidden="true"
        hidden
    >
        <div class="template-modal-backdrop" data-close-modal></div>

        <div class="template-modal-panel">
            <button
                type="button"
                class="template-modal-close"
                data-close-modal
                aria-label="Close preview"
            >
                <i class="fa-solid fa-xmark"></i>
            </button>

            <div class="template-modal-image">
                <img id="templateModalImage" src="" alt="">
            </div>

            <div class="template-modal-content">
                <span class="eyebrow" id="templateModalEyebrow">
                    TEMPLATE PREVIEW
                </span>

                <h2 id="templateModalTitle">Elegant</h2>

                <p id="templateModalDescription"></p>

                <a
                    href="create-event.php"
                    class="btn btn-primary btn-block"
                    id="templateModalUse"
                >
                    Use this style
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

</main>

<?php require_once __DIR__ . '/_inc/footer.php'; ?>

<?php
$pageTitle = 'Preview Your Event | Foliioo';
$pageDescription = 'Review your event website before publishing it.';

require_once __DIR__ . '/_inc/head.inc.php';

$allowedTemplates = ['elegant', 'modern', 'floral'];
$template = $_GET['template'] ?? 'elegant';

if (!in_array($template, $allowedTemplates, true)) {
    $template = 'elegant';
}
?>

<main class="preview-page">
    <header class="preview-topbar">
        <a href="index.php" class="site-logo">
            <span class="site-logo-mark"><i class="fa-solid fa-leaf"></i></span>
            <span>foliioo<span class="site-logo-period">.</span></span>
        </a>

        <span class="preview-status">
            <i class="fa-solid fa-eye"></i> Private preview
        </span>

        <a href="customize-event.php?template=<?= urlencode($template) ?>"
           class="btn btn-secondary btn-sm">
            <i class="fa-solid fa-pen"></i> Edit design
        </a>
    </header>

    <section class="preview-intro container-main">
        <span class="eyebrow">One last look</span>
        <h1 class="font-display">Your event, coming to life.</h1>
        <p>Review how your event website looks before you publish it.</p>
    </section>

    <section class="event-preview-shell container-main">
        <div class="event-preview-banner" id="finalPreview">
            <div class="event-preview-image-wrap">
                <img
                    id="finalCoverImage"
                    src="https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&amp;fit=crop&amp;w=1200&amp;q=85"
                    alt="Event cover"
                >
                <span class="event-preview-template" id="finalTemplateLabel">
                    Elegant collection
                </span>
            </div>

            <div class="event-preview-content">
                <span class="event-preview-type" id="finalEventType">
                    A SPECIAL OCCASION
                </span>

                <h2 class="font-display" id="finalEventTitle">
                    Your event title
                </h2>

                <p class="event-preview-subtitle" id="finalEventSubtitle">
                    A day to remember
                </p>

                <div class="event-preview-details">
                    <p>
                        <i class="fa-regular fa-calendar"></i>
                        <span id="finalEventDate">Date to be announced</span>
                    </p>
                    <p>
                        <i class="fa-solid fa-location-dot"></i>
                        <span id="finalEventLocation">Location to be announced</span>
                    </p>
                </div>

                <div class="event-preview-divider"></div>

                <p class="event-preview-message" id="finalEventMessage">
                    Your event welcome message will appear here.
                </p>

                <span class="event-preview-rsvp">
                    <i class="fa-regular fa-envelope"></i>
                    Guest RSVP section will appear on your published website.
                </span>
            </div>
        </div>
    </section>

    
<section class="preview-actions container-main">
    <div>
        <h2 class="font-display">Happy with how it looks?</h2>
        <p>
            Your event website is ready. Publish it when you're happy
            with the design, or go back to make further changes.
        </p>
    </div>

    <div class="preview-action-buttons">
        <a href="customize-event.php?template=<?= urlencode($template) ?>"
           class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left"></i> Back to editor
        </a>

        <button type="button" class="btn btn-primary" id="publishEvent">
            <i class="fa-solid fa-globe"></i> Publish Event
        </button>
    </div>

    
<p id="previewPageMessage" class="preview-page-message" hidden></p>

<div id="publishedEventActions" class="published-event-actions" hidden>
    <div class="published-event-link">
        <i class="fa-solid fa-link"></i>
        <span id="publishedEventUrl"></span>
    </div>

    <div class="preview-action-buttons">
        <button type="button" class="btn btn-secondary" id="copyEventLink">
            <i class="fa-regular fa-copy"></i> Copy Event Link
        </button>

        <a href="#" class="btn btn-primary" id="viewPublicEvent">
            <i class="fa-solid fa-arrow-up-right-from-square"></i>
            View Public Website
        </a>
    </div>
</div>

</section>

</main>

<script>
    window.foliiooPreviewConfig = {
        template: <?= json_encode($template) ?>
    };
</script>
<script src="js/modules/preview-event.js" defer></script>

<?php require_once __DIR__ . '/_inc/footer.php'; ?>
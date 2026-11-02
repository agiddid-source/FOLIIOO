
<?php
$pageTitle = 'Event Website — Foliioo';
$pageDescription = 'View this event website, explore the details and connect with the host.';

require_once __DIR__ . '/_inc/head.inc.php';

$template = $_GET['template'] ?? 'elegant';

$allowedTemplates = ['elegant', 'modern', 'floral'];

if (!in_array($template, $allowedTemplates, true)) {
    $template = 'elegant';
}
?>

<style>
    .public-event-page {
        min-height: 100vh;
        padding-bottom: 60px;
    }

    .public-event-topbar {
        padding: 18px 0;
        border-bottom: 1px solid var(--color-border);
        background: #fff;
    }

    .public-event-topbar-inner {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
    }

    .public-event-brand {
        font-family: var(--font-display);
        font-size: 24px;
        font-weight: 700;
    }

    .public-event-status {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 7px 12px;
        border-radius: 999px;
        background: #EEF0EA;
        color: #53654A;
        font-size: 12px;
        font-weight: 600;
    }

    .public-event-container {
        width: min(900px, calc(100% - 32px));
        margin: 48px auto 0;
    }

    .public-event-card {
        overflow: hidden;
        border: 1px solid var(--color-border);
        border-radius: 20px;
        background: #fff;
        box-shadow: var(--shadow-soft);
    }

    .public-event-cover {
        position: relative;
        height: 420px;
        background: #E9E4DD;
        overflow: hidden;
    }

    .public-event-cover img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .public-event-cover-placeholder {
        display: grid;
        place-items: center;
        height: 100%;
        color: var(--color-muted);
        font-size: 14px;
    }

    .public-event-content {
        padding: 55px 35px;
        text-align: center;
    }

    .public-event-eyebrow {
        color: var(--color-accent);
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 3px;
        text-transform: uppercase;
    }

    .public-event-title {
        margin: 16px 0 10px;
        font-family: var(--font-display);
        font-size: clamp(32px, 6vw, 52px);
        line-height: 1.2;
    }

    .public-event-subtitle {
        color: var(--color-muted);
        font-size: 17px;
    }

    .public-event-message {
        max-width: 620px;
        margin: 28px auto;
        color: var(--color-muted);
        white-space: pre-line;
        line-height: 1.9;
    }

    .public-event-details {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 30px;
        margin-top: 35px;
        padding-top: 30px;
        border-top: 1px solid var(--color-border);
    }

    .public-event-detail {
        min-width: 140px;
    }

    .public-event-detail i {
        display: block;
        margin-bottom: 9px;
        color: var(--color-accent);
        font-size: 19px;
    }

    .public-event-detail strong {
        display: block;
        font-size: 13px;
    }

    .public-event-detail span {
        color: var(--color-muted);
        font-size: 13px;
    }

    .public-event-footer {
        margin-top: 25px;
        color: var(--color-muted);
        font-size: 12px;
        text-align: center;
    }

    .public-event-error {
        padding: 70px 20px;
        text-align: center;
    }

    .public-event-error i {
        margin-bottom: 18px;
        color: var(--color-accent);
        font-size: 35px;
    }

    @media (max-width: 600px) {
        .public-event-cover {
            height: 260px;
        }

        .public-event-content {
            padding: 38px 20px;
        }

        .public-event-details {
            gap: 22px;
        }
    }
</style>

<div class="public-event-page">

    <header class="public-event-topbar">
        <div class="container-main public-event-topbar-inner">
            <a href="index.php" class="public-event-brand">
                foliioo<span style="color: var(--color-accent);">.</span>
            </a>

            <span class="public-event-status">
                <i class="fa-solid fa-circle-check"></i>
                Published Event
            </span>
        </div>
    </header>

    <main class="public-event-container">

        <article class="public-event-card" id="publicEventCard" hidden>

            <div class="public-event-cover">
                <img
                    id="publicEventCover"
                    src=""
                    alt="Event cover image"
                    hidden
                >
                <div
                    class="public-event-cover-placeholder"
                    id="publicEventCoverPlaceholder"
                >
                    <i class="fa-regular fa-image"></i>
                    &nbsp; Event cover image
                </div>
            </div>

            <div class="public-event-content">

                <p class="public-event-eyebrow" id="publicEventType">
                    SPECIAL OCCASION
                </p>

                <h1 class="public-event-title" id="publicEventTitle">
                    Your Event Title
                </h1>

                <p class="public-event-subtitle" id="publicEventSubtitle"></p>

                <p class="public-event-message" id="publicEventMessage"></p>

                <div class="public-event-details">

                    <div class="public-event-detail">
                        <i class="fa-regular fa-calendar"></i>
                        <strong>Date</strong>
                        <span id="publicEventDate">Not specified</span>
                    </div>

                    <div class="public-event-detail">
                        <i class="fa-solid fa-location-dot"></i>
                        <strong>Location</strong>
                        <span id="publicEventLocation">Not specified</span>
                    </div>

                </div>

            </div>

        </article>

        <section class="public-event-error" id="publicEventError" hidden>
            <i class="fa-regular fa-calendar-xmark"></i>
            <h1 class="font-display">Event unavailable</h1>
            <p id="publicEventErrorMessage">
                This event could not be found.
            </p>
            <a href="index.php" class="btn btn-primary">
                Return to Foliioo
            </a>
        </section>

        <footer class="public-event-footer">
            Beautifully brought together with Foliioo.
        </footer>

    </main>

</div>

<script>
    window.foliiooPublicEventConfig = {
        template: <?= json_encode($template) ?>
    };
</script>

<script src="js/modules/public-event.js" defer></script>

<?php require_once __DIR__ . '/_inc/footer.php'; ?>

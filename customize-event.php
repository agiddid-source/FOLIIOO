
<?php
$pageTitle = 'Customize Your Event — Foliioo';
$pageDescription = 'Personalize your event website with your own words and style.';

require_once __DIR__ . '/_inc/head.inc.php';

$allowedTemplates = ['elegant', 'modern', 'floral'];
$template = strtolower($_GET['template'] ?? 'elegant');

if (!in_array($template, $allowedTemplates, true)) {
    $template = 'elegant';
}

$templateNames = [
    'elegant' => 'Elegant',
    'modern' => 'Modern',
    'floral' => 'Floral',
];

$initialTemplateName = $templateNames[$template];
?>

<main class="customize-page">
    <section class="customize-topbar">
        <div class="container-main customize-topbar-inner">
            <a href="create-event.php" class="customize-back-link">
                <i class="fa-solid fa-arrow-left"></i>
                Event details
            </a>

            <a href="index.php" class="site-logo" aria-label="Foliioo home">
                <span class="site-logo-mark"><i class="fa-solid fa-leaf"></i></span>
                <span>foliioo<span class="site-logo-period">.</span></span>
            </a>

            <span class="customize-draft-status">
                <i class="fa-regular fa-floppy-disk"></i>
                Browser draft
            </span>
        </div>
    </section>

    <section class="customize-heading-section">
        <div class="container-main">
            <div class="create-progress" aria-label="Event creation progress">
                <div class="create-progress-step">
                    <span class="create-step-number"><i class="fa-solid fa-check"></i></span>
                    <span>Event details</span>
                </div>
                <div class="create-progress-line"></div>
                <div class="create-progress-step active">
                    <span class="create-step-number">2</span>
                    <span>Customize</span>
                </div>
                <div class="create-progress-line"></div>
                <div class="create-progress-step">
                    <span class="create-step-number">3</span>
                    <span>Preview</span>
                </div>
            </div>

            <div class="customize-heading-copy">
                <span class="eyebrow">Make it yours</span>
                <h1 class="font-display">Give your event its <span>own personality.</span></h1>
                <p>Shape the look and feel of your event website. Your changes appear in the preview as you edit.</p>
            </div>
        </div>
    </section>

    <section class="customize-workspace">
        <div class="container-main customize-layout">

            <div class="customize-controls">
                <div class="customize-panel">
                    <div class="customize-panel-heading">
                        <span class="customize-panel-icon"><i class="fa-regular fa-pen-to-square"></i></span>
                        <div>
                            <h2>Event content</h2>
                            <p>Choose the words your guests will see.</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="customEventTitle" class="form-label">Website headline</label>
                        <input id="customEventTitle" class="form-input" type="text" maxlength="100" placeholder="Your event title">
                    </div>

                    <div class="form-group">
                        <label for="customEventSubtitle" class="form-label">Subtitle</label>
                        <input id="customEventSubtitle" class="form-input" type="text" maxlength="140" placeholder="A day to remember">
                    </div>

                    <div class="form-group">
                        <label for="customEventMessage" class="form-label">Welcome message</label>
                        <textarea id="customEventMessage" class="form-input form-textarea" rows="4" maxlength="500" placeholder="Write a warm welcome for your guests..."></textarea>
                    </div>
                </div>

                <div class="customize-panel">
                    <div class="customize-panel-heading">
                        <span class="customize-panel-icon"><i class="fa-solid fa-palette"></i></span>
                        <div>
                            <h2>Color palette</h2>
                            <p>Select the colors that suit your occasion.</p>
                        </div>
                    </div>

                    <div class="customize-palette-grid">
                        <button type="button" class="customize-palette active" data-palette="earth" aria-pressed="true">
                            <span class="palette-swatches">
                                <span style="--swatch:#FAF5EF"></span>
                                <span style="--swatch:#9A5F44"></span>
                                <span style="--swatch:#3F493D"></span>
                            </span>
                            <span>Earth &amp; Clay</span>
                        </button>

                        <button type="button" class="customize-palette" data-palette="sage" aria-pressed="false">
                            <span class="palette-swatches">
                                <span style="--swatch:#F5F6F0"></span>
                                <span style="--swatch:#7D8C70"></span>
                                <span style="--swatch:#303C32"></span>
                            </span>
                            <span>Sage Garden</span>
                        </button>

                        <button type="button" class="customize-palette" data-palette="rose" aria-pressed="false">
                            <span class="palette-swatches">
                                <span style="--swatch:#FFF5F5"></span>
                                <span style="--swatch:#C27C83"></span>
                                <span style="--swatch:#754D56"></span>
                            </span>
                            <span>Rose Blush</span>
                        </button>

                        <button type="button" class="customize-palette" data-palette="midnight" aria-pressed="false">
                            <span class="palette-swatches">
                                <span style="--swatch:#F4F3EF"></span>
                                <span style="--swatch:#343C56"></span>
                                <span style="--swatch:#B9A477"></span>
                            </span>
                            <span>Midnight</span>
                        </button>
                    </div>
                </div>

                <div class="customize-panel">
                    <div class="customize-panel-heading">
                        <span class="customize-panel-icon"><i class="fa-solid fa-font"></i></span>
                        <div>
                            <h2>Typography</h2>
                            <p>Choose a headline style for your website.</p>
                        </div>
                    </div>

                    <div class="customize-font-options">
                        <label class="customize-font-option active">
                            <input type="radio" name="fontStyle" value="serif" checked>
                            <span class="font-option-preview font-option-serif">Aa</span>
                            <span class="font-option-copy">
                                <strong>Classic serif</strong>
                                <small>Elegant and editorial</small>
                            </span>
                        </label>

                        <label class="customize-font-option">
                            <input type="radio" name="fontStyle" value="sans">
                            <span class="font-option-preview font-option-sans">Aa</span>
                            <span class="font-option-copy">
                                <strong>Modern sans</strong>
                                <small>Clean and contemporary</small>
                            </span>
                        </label>
                    </div>
                </div>

                <div class="customize-panel">
                    <div class="customize-panel-heading">
                        <span class="customize-panel-icon"><i class="fa-regular fa-image"></i></span>
                        <div>
                            <h2>Cover image</h2>
                            <p>Use an image to set the scene.</p>
                        </div>
                    </div>

                    
<div class="form-group">
    <label for="coverImageFile" class="form-label">
        Event cover image
    </label>

    <input
        type="file"
        id="coverImageFile"
        class="form-input"
        accept="image/jpeg,image/png,image/webp,image/gif"
    >

    <p class="form-help">
        Choose a JPG, PNG, WebP, or GIF image from your device.
        For best results, use an image under 5 MB.
    </p>

    <button
        type="button"
        id="removeCoverImage"
        class="btn btn-secondary btn-sm"
        style="margin-top: 10px;"
    >
        <i class="fa-solid fa-trash-can"></i>
        Remove cover image
    </button>
</div>

                </div>

                <div class="customize-controls-actions">
                    <button type="button" id="resetCustomization" class="btn btn-secondary">
                        <i class="fa-solid fa-rotate-left"></i>
                        Reset changes
                    </button>

                    <button type="button" id="saveCustomization" class="btn btn-primary">
                        Save &amp; continue
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </div>

                <p id="customizeMessage" class="customize-message" role="status" hidden></p>
            </div>

            <aside class="customize-preview-column">
                <div class="customize-preview-toolbar">
                    <div>
                        <span class="customize-preview-label">LIVE PREVIEW</span>
                        <span class="customize-preview-device">Website view</span>
                    </div>
                    <span class="customize-preview-template" id="previewTemplateName">
                        <?= htmlspecialchars($initialTemplateName, ENT_QUOTES, 'UTF-8') ?> style
                    </span>
                </div>

                <div class="event-live-preview" id="eventLivePreview" data-palette="earth">
                    <div class="live-preview-cover">
                        <img id="previewCoverImage" src="https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=1200&q=85" alt="Event cover preview">
                        <div class="live-preview-cover-shade"></div>
                        <span class="live-preview-template-tag" id="previewTemplateTag">
                            <?= htmlspecialchars($initialTemplateName, ENT_QUOTES, 'UTF-8') ?> collection
                        </span>
                    </div>

                    <div class="live-preview-content">
                        <span class="live-preview-eyebrow" id="previewEventType">A SPECIAL OCCASION</span>
                        <h2 class="font-display" id="previewEventTitle">Your event title</h2>
                        <p class="live-preview-subtitle" id="previewEventSubtitle">A day to remember</p>
                        <div class="live-preview-divider"><span>✦</span></div>
                        <p class="live-preview-message" id="previewEventMessage">We look forward to sharing this special moment with you. More details will be added as your event takes shape.</p>

                        <div class="live-preview-event-meta">
                            <div>
                                <i class="fa-regular fa-calendar"></i>
                                <span id="previewEventDate">Date to be announced</span>
                            </div>
                            <div>
                                <i class="fa-solid fa-location-dot"></i>
                                <span id="previewEventLocation">Location to be announced</span>
                            </div>
                        </div>

                        <div class="live-preview-footer">MADE WITH FOLIIOO</div>
                    </div>
                </div>

                <p class="customize-preview-disclaimer">
                    <i class="fa-regular fa-eye"></i>
                    Preview only. Your public event website has not been published.
                </p>
            </aside>

        </div>
    </section>
</main>

<script>
    window.foliiooCustomizationConfig = {
        template: <?= json_encode($template, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>
    };
</script>
<script src="js/modules/customize-event.js" defer></script>

<?php require_once __DIR__ . '/_inc/footer.php'; ?>


<?php
$pageTitle = 'Create Your Event — Foliioo';
$pageDescription = 'Start creating your personalized event website with Foliioo.';

require_once __DIR__ . '/_inc/head.inc.php';

$allowedTemplates = ['elegant', 'modern', 'floral'];
$allowedPlans = ['basic', 'essential', 'premium', 'custom'];

$template = strtolower($_GET['template'] ?? 'elegant');
$plan = strtolower($_GET['plan'] ?? 'basic');

if (!in_array($template, $allowedTemplates, true)) {
    $template = 'elegant';
}

if (!in_array($plan, $allowedPlans, true)) {
    $plan = 'basic';
}

$templateDetails = [
    'elegant' => [
        'name' => 'Elegant',
        'description' => 'Timeless layouts with refined typography.',
        'image' => 'https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=900&q=85',
    ],
    'modern' => [
        'name' => 'Modern',
        'description' => 'A clean, contemporary event experience.',
        'image' => 'https://images.unsplash.com/photo-1519167758481-83f550bb49b3?auto=format&fit=crop&w=900&q=85',
    ],
    'floral' => [
        'name' => 'Floral',
        'description' => 'Soft details and romantic botanical styling.',
        'image' => 'https://images.unsplash.com/photo-1464366400600-7168b8af9bc3?auto=format&fit=crop&w=900&q=85',
    ],
];

$planNames = [
    'basic' => 'Basic',
    'essential' => 'Essential',
    'premium' => 'Premium',
    'custom' => 'Custom',
];

$selectedTemplate = $templateDetails[$template];
$selectedPlan = $planNames[$plan];
?>

<main class="create-event-page">
    <section class="create-event-header">
        <div class="container-main">
            <a href="index.php" class="create-back-link">
                <i class="fa-solid fa-arrow-left"></i>
                Back to home
            </a>

            <div class="create-heading">
                <span class="eyebrow">Your event starts here</span>
                <h1 class="font-display">
                    Let's bring your <span>event to life.</span>
                </h1>
                <p>
                    Start with a few details. You can refine your event website
                    as you go.
                </p>
            </div>

            <div class="create-progress" aria-label="Event creation progress">
                <div class="create-progress-step active">
                    <span class="create-step-number">1</span>
                    <span>Event details</span>
                </div>
                <div class="create-progress-line"></div>
                <div class="create-progress-step">
                    <span class="create-step-number">2</span>
                    <span>Customize</span>
                </div>
                <div class="create-progress-line"></div>
                <div class="create-progress-step">
                    <span class="create-step-number">3</span>
                    <span>Preview</span>
                </div>
            </div>
        </div>
    </section>

    <section class="create-event-content">
        <div class="container-main create-event-layout">

            <div class="create-form-column">
                <div class="create-form-card">
                    <div class="create-card-heading">
                        <span class="create-section-icon">
                            <i class="fa-regular fa-calendar"></i>
                        </span>
                        <div>
                            <h2 class="font-display">Tell us about your event</h2>
                            <p>These details will help shape your event website.</p>
                        </div>
                    </div>

                    <form id="createEventForm">
                        <div class="form-group">
                            <label for="eventName" class="form-label">
                                Event name <span class="required-mark">*</span>
                            </label>
                            <input
                                type="text"
                                id="eventName"
                                name="eventName"
                                class="form-input"
                                placeholder="e.g. Ada & David's Wedding"
                                maxlength="100"
                                required
                            >
                            <small class="form-hint">
                                Choose a name your guests will recognize.
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="eventType" class="form-label">
                                What are you celebrating?
                                <span class="required-mark">*</span>
                            </label>
                            <select id="eventType" name="eventType" class="form-input" required>
                                <option value="">Select an event type</option>
                                <option value="wedding">Wedding</option>
                                <option value="birthday">Birthday</option>
                                <option value="anniversary">Anniversary</option>
                                <option value="graduation">Graduation</option>
                                <option value="conference">Conference</option>
                                <option value="corporate">Corporate event</option>
                                <option value="other">Other celebration</option>
                            </select>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="eventDate" class="form-label">
                                    Event date <span class="required-mark">*</span>
                                </label>
                                <input
                                    type="date"
                                    id="eventDate"
                                    name="eventDate"
                                    class="form-input"
                                    required
                                >
                            </div>

                            <div class="form-group">
                                <label for="eventLocation" class="form-label">
                                    Event location
                                </label>
                                <input
                                    type="text"
                                    id="eventLocation"
                                    name="eventLocation"
                                    class="form-input"
                                    placeholder="e.g. Lagos, Nigeria"
                                    maxlength="150"
                                >
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="eventDescription" class="form-label">
                                A little about your event
                                <span class="optional-label">Optional</span>
                            </label>
                            <textarea
                                id="eventDescription"
                                name="eventDescription"
                                class="form-input form-textarea"
                                rows="4"
                                maxlength="600"
                                placeholder="Share a few words about the occasion..."
                            ></textarea>
                            <small class="form-hint">
                                <span id="descriptionCount">0</span>/600 characters
                            </small>
                        </div>

                        <div class="create-form-message" id="createFormMessage" role="status" hidden></div>

                        <button type="submit" class="btn btn-primary btn-lg btn-block create-continue-btn">
                            Continue to customization
                            <i class="fa-solid fa-arrow-right"></i>
                        </button>

                        <p class="create-form-footnote">
                            <i class="fa-solid fa-lock"></i>
                            This is a prototype. Your entries stay in this browser.
                        </p>
                    </form>
                </div>
            </div>

            <aside class="create-summary-column">
                <div class="create-summary-card">
                    <div class="create-summary-top">
                        <span class="eyebrow">Your starting point</span>
                        <h2 class="font-display">Make it yours.</h2>
                        <p>You can review these selections as you build your event.</p>
                    </div>

                    <div class="create-selected-template">
                        <div class="create-template-image">
                            <img
                                src="<?= htmlspecialchars($selectedTemplate['image'], ENT_QUOTES, 'UTF-8') ?>"
                                alt="<?= htmlspecialchars($selectedTemplate['name'], ENT_QUOTES, 'UTF-8') ?> event template preview"
                            >
                            <span class="create-template-overlay">
                                <i class="fa-solid fa-check"></i>
                                Selected template
                            </span>
                        </div>

                        <div class="create-template-info">
                            <div>
                                <span class="create-summary-label">Website style</span>
                                <h3><?= htmlspecialchars($selectedTemplate['name'], ENT_QUOTES, 'UTF-8') ?></h3>
                            </div>
                            <a href="templates.php" class="create-change-link">Change</a>
                        </div>

                        <p class="create-template-description">
                            <?= htmlspecialchars($selectedTemplate['description'], ENT_QUOTES, 'UTF-8') ?>
                        </p>
                    </div>

                    <div class="create-plan-summary">
                        <div class="create-plan-icon">
                            <i class="fa-solid fa-layer-group"></i>
                        </div>
                        <div class="create-plan-copy">
                            <span class="create-summary-label">Selected plan</span>
                            <strong><?= htmlspecialchars($selectedPlan, ENT_QUOTES, 'UTF-8') ?></strong>
                            <span>Plan details and pricing are provisional.</span>
                        </div>
                        <a href="pricing.php" class="create-change-link">Change</a>
                    </div>

                    <div class="create-summary-note">
                        <i class="fa-regular fa-lightbulb"></i>
                        <p>
                            Your event details, template and plan will guide the
                            next stages of this prototype.
                        </p>
                    </div>
                </div>

                <div class="create-privacy-note">
                    <i class="fa-solid fa-shield-halved"></i>
                    <p>
                        No account or payment is required at this stage.
                        Saving and publishing will be simulated.
                    </p>
                </div>
            </aside>

        </div>
    </section>
</main>

<script src="js/modules/create-event.js" defer></script>

<?php require_once __DIR__ . '/_inc/footer.php'; ?>

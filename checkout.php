<?php
$pageTitle = 'Checkout — Foliioo';
$pageDescription = 'Review your plan and complete a simulated checkout for your Foliioo event.';
$currentPage = 'checkout';

require_once __DIR__ . '/_inc/head.inc.php';
require_once __DIR__ . '/_inc/header.php';

// Plan names and features come from the shared mock data file.
$planData = json_decode(
    file_get_contents(__DIR__ . '/js/data/plans.json'),
    true
);

$plans = $planData['plans'] ?? [];
$payablePlans = array_values(array_filter($plans, fn ($plan) => $plan['id'] !== 'custom'));
?>

<main class="checkout-page">
    <section class="checkout-section">
        <div class="container-main">

            <a href="pricing.php" class="create-back-link">
                <i class="fa-solid fa-arrow-left"></i>
                Back to plans
            </a>

            <div class="checkout-heading">
                <span class="eyebrow">Checkout</span>
                <h1 class="font-display">Choose your plan and confirm</h1>
                <p>
                    This is a prototype checkout. No real payment is taken and
                    no card details are collected.
                </p>
            </div>

            <!-- No event in progress -->
            <div class="checkout-state" id="checkoutEmpty" hidden>
                <div class="checkout-state-icon">
                    <i class="fa-regular fa-calendar"></i>
                </div>
                <h2 class="font-display">No event in progress</h2>
                <p>Start by creating your event, then come back here to choose a plan.</p>
                <a href="create-event.php" class="btn btn-primary">
                    Create an event
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>

            <!-- Custom plan: consultation, not checkout -->
            <div class="checkout-state" id="checkoutCustom" hidden>
                <div class="checkout-state-icon">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                </div>
                <h2 class="font-display">Custom plans start with a conversation</h2>
                <p>
                    Tell us what your event needs and we will prepare a
                    tailored proposal. No checkout is needed.
                </p>
                <div class="checkout-actions">
                    <a
                        href="mailto:hello@foliioo.com?subject=Foliioo%20Custom%20Plan%20Enquiry"
                        class="btn btn-primary"
                    >
                        Request a proposal
                    </a>
                    <a href="checkout.php?plan=essential" class="btn btn-secondary">
                        Choose a standard plan
                    </a>
                </div>
            </div>

            <!-- Main checkout -->
            <div class="checkout-grid" id="checkoutMain" hidden>

                <form id="checkoutForm" class="checkout-card" novalidate>

                    <fieldset class="checkout-fieldset">
                        <legend class="checkout-legend">1. Select a plan</legend>

                        <div class="checkout-plans">
                            <?php foreach ($payablePlans as $plan): ?>
                                <label class="checkout-plan">
                                    <input
                                        type="radio"
                                        name="plan"
                                        value="<?= htmlspecialchars($plan['id'], ENT_QUOTES, 'UTF-8') ?>"
                                        data-plan-name="<?= htmlspecialchars($plan['name'], ENT_QUOTES, 'UTF-8') ?>"
                                    >
                                    <span class="checkout-plan-body">
                                        <span class="checkout-plan-icon">
                                            <i class="fa-solid <?= htmlspecialchars($plan['icon'], ENT_QUOTES, 'UTF-8') ?>"></i>
                                        </span>
                                        <span class="checkout-plan-copy">
                                            <strong><?= htmlspecialchars($plan['name'], ENT_QUOTES, 'UTF-8') ?></strong>
                                            <span><?= htmlspecialchars($plan['description'], ENT_QUOTES, 'UTF-8') ?></span>
                                        </span>
                                    </span>
                                </label>
                            <?php endforeach; ?>
                        </div>

                        <p class="checkout-note">
                            <i class="fa-solid fa-circle-info"></i>
                            Plan prices and final feature limits are being finalized.
                        </p>
                    </fieldset>

                    <fieldset class="checkout-fieldset">
                        <legend class="checkout-legend">2. Payment method</legend>

                        <div class="checkout-methods">
                            <label class="checkout-method">
                                <input type="radio" name="method" value="card" checked>
                                <span><i class="fa-regular fa-credit-card"></i> Card</span>
                            </label>
                            <label class="checkout-method">
                                <input type="radio" name="method" value="transfer">
                                <span><i class="fa-solid fa-building-columns"></i> Bank transfer</span>
                            </label>
                            <label class="checkout-method">
                                <input type="radio" name="method" value="mobile">
                                <span><i class="fa-solid fa-mobile-screen"></i> Mobile money</span>
                            </label>
                        </div>
                    </fieldset>

                    <fieldset class="checkout-fieldset checkout-tester">
                        <legend class="checkout-legend">
                            Prototype control
                        </legend>

                        <p class="checkout-note">
                            Choose what the simulated payment should do. This
                            control would not exist in the real checkout.
                        </p>

                        <div class="checkout-methods">
                            <label class="checkout-method">
                                <input type="radio" name="outcome" value="success" checked>
                                <span><i class="fa-solid fa-circle-check"></i> Succeed</span>
                            </label>
                            <label class="checkout-method">
                                <input type="radio" name="outcome" value="failed">
                                <span><i class="fa-solid fa-circle-xmark"></i> Fail</span>
                            </label>
                            <label class="checkout-method">
                                <input type="radio" name="outcome" value="cancelled">
                                <span><i class="fa-solid fa-ban"></i> Cancel</span>
                            </label>
                        </div>
                    </fieldset>

                    <button type="submit" class="btn btn-primary btn-lg btn-block" id="payButton">
                        Confirm simulated payment
                        <i class="fa-solid fa-lock"></i>
                    </button>
                </form>

                <!-- Order summary -->
                <aside class="checkout-card checkout-summary" aria-labelledby="orderSummaryTitle">
                    <h2 class="font-display" id="orderSummaryTitle">Order summary</h2>

                    <dl class="checkout-lines">
                        <div>
                            <dt>Event</dt>
                            <dd id="sumEvent">—</dd>
                        </div>
                        <div>
                            <dt>Date</dt>
                            <dd id="sumDate">—</dd>
                        </div>
                        <div>
                            <dt>Website style</dt>
                            <dd id="sumTemplate">—</dd>
                        </div>
                        <div>
                            <dt>Plan</dt>
                            <dd id="sumPlan">—</dd>
                        </div>
                        <div>
                            <dt>Account</dt>
                            <dd id="sumAccount">—</dd>
                        </div>
                    </dl>

                    <div class="checkout-total">
                        <span>Total</span>
                        <strong>To be confirmed</strong>
                    </div>

                    <p class="checkout-note">
                        <i class="fa-solid fa-shield-halved"></i>
                        Simulation only. Nothing is charged.
                    </p>
                </aside>
            </div>

            <!-- Processing -->
            <div class="checkout-state" id="checkoutProcessing" role="status" hidden>
                <div class="checkout-state-icon">
                    <i class="fa-solid fa-circle-notch fa-spin"></i>
                </div>
                <h2 class="font-display">Processing your payment</h2>
                <p>Please wait a moment. This is a simulation.</p>
            </div>

            <!-- Success -->
            <div class="checkout-state checkout-state-success" id="checkoutSuccess" role="status" hidden>
                <div class="checkout-state-icon">
                    <i class="fa-solid fa-check"></i>
                </div>
                <h2 class="font-display">Payment confirmed</h2>
                <p>
                    Your simulated payment for the
                    <strong id="successPlan">selected</strong> plan went through.
                </p>

                <dl class="checkout-lines checkout-receipt">
                    <div>
                        <dt>Reference</dt>
                        <dd id="successReference">—</dd>
                    </div>
                    <div>
                        <dt>Event</dt>
                        <dd id="successEvent">—</dd>
                    </div>
                    <div>
                        <dt>Date paid</dt>
                        <dd id="successDate">—</dd>
                    </div>
                </dl>

                <a href="preview-event.php" class="btn btn-primary btn-lg" id="continuePublish">
                    Continue to publish
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>

            <!-- Failed -->
            <div class="checkout-state checkout-state-failed" id="checkoutFailed" role="alert" hidden>
                <div class="checkout-state-icon">
                    <i class="fa-solid fa-xmark"></i>
                </div>
                <h2 class="font-display">Payment did not go through</h2>
                <p>
                    The simulated payment was declined. No charge was made, and
                    your event details are safe.
                </p>
                <div class="checkout-actions">
                    <button type="button" class="btn btn-primary" data-checkout-retry>
                        Try again
                    </button>
                    <a href="pricing.php" class="btn btn-secondary">View plans</a>
                </div>
            </div>

            <!-- Cancelled -->
            <div class="checkout-state" id="checkoutCancelled" role="status" hidden>
                <div class="checkout-state-icon">
                    <i class="fa-solid fa-ban"></i>
                </div>
                <h2 class="font-display">Payment cancelled</h2>
                <p>You cancelled the payment. No charge was made.</p>
                <div class="checkout-actions">
                    <button type="button" class="btn btn-primary" data-checkout-retry>
                        Return to checkout
                    </button>
                    <a href="customize-event.php" class="btn btn-secondary">Back to editor</a>
                </div>
            </div>

        </div>
    </section>
</main>

<script src="js/modules/payments-demo.js" defer></script>

<?php require_once __DIR__ . '/_inc/footer.php'; ?>
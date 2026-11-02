
<?php
$pageTitle = 'Pricing — Foliioo';
$pageDescription = 'Explore Foliioo plans for creating and managing beautiful event websites.';
$currentPage = 'pricing';

require_once __DIR__ . '/_inc/head.inc.php';
require_once __DIR__ . '/_inc/header.php';

$plans = [
    [
        'name' => 'Basic',
        'description' => 'A simple starting point for bringing your event online.',
        'icon' => 'fa-seedling',
        'class' => 'plan-basic',
        'featured' => false,
        'label' => 'Get started',
        'features' => [
            'Create an event website',
            'Choose from available templates',
            'Share your event link',
        ],
    ],
    [
        'name' => 'Essential',
        'description' => 'More flexibility for planning and managing your event.',
        'icon' => 'fa-feather',
        'class' => 'plan-essential',
        'featured' => true,
        'label' => 'Explore Essential',
        'features' => [
            'Everything in Basic',
            'Expanded event customization',
            'More tools for planning your event',
        ],
    ],
    [
        'name' => 'Premium',
        'description' => 'A richer event experience with more ways to personalize.',
        'icon' => 'fa-gem',
        'class' => 'plan-premium',
        'featured' => false,
        'label' => 'Explore Premium',
        'features' => [
            'Everything in Essential',
            'Enhanced event presentation',
            'Additional event experience options',
        ],
    ],
    [
        'name' => 'Custom',
        'description' => 'A tailored solution for unique or more complex event needs.',
        'icon' => 'fa-wand-magic-sparkles',
        'class' => 'plan-custom',
        'featured' => false,
        'label' => 'Discuss your needs',
        'features' => [
            'A solution tailored to your event',
            'Discuss your preferred features',
            'Request a customized proposal',
        ],
    ],
];
?>

<main>
    <section class="pricing-hero">
        <div class="container-main pricing-hero-inner">
            <span class="eyebrow">
                <i class="fa-solid fa-heart"></i>
                Simple plans, meaningful moments
            </span>

            <h1 class="pricing-title font-display">
                Your event deserves
                <span>its own kind of magic.</span>
            </h1>

            <p class="pricing-intro">
                From a simple event page to a more complete event experience,
                find the right starting point for bringing your plans to life.
            </p>

            <a href="#plans" class="btn btn-primary btn-lg">
                Explore our plans
                <i class="fa-solid fa-arrow-down"></i>
            </a>

            <p class="pricing-note">
                <i class="fa-solid fa-circle-info"></i>
                Plan prices and final feature limits are being finalized.
            </p>
        </div>
    </section>

    <section class="pricing-section" id="plans">
        <div class="container-main">
            <div class="section-heading-wrap">
                <span class="eyebrow">Choose your experience</span>
                <h2 class="section-heading font-display">
                    A plan for every kind of celebration
                </h2>
                <p class="section-description">
                    Start with what you need. Explore the possibilities
                    as your event takes shape.
                </p>
            </div>

            <div class="pricing-grid">
                <?php foreach ($plans as $plan): ?>
                    <article class="pricing-card <?= htmlspecialchars($plan['class']) ?> <?= $plan['featured'] ? 'pricing-card-featured' : '' ?>">
                        <?php if ($plan['featured']): ?>
                            <span class="pricing-popular">
                                <i class="fa-solid fa-star"></i>
                                Featured plan
                            </span>
                        <?php endif; ?>

                        <div class="pricing-card-top">
                            <div class="pricing-plan-icon">
                                <i class="fa-solid <?= htmlspecialchars($plan['icon']) ?>"></i>
                            </div>

                            <h3 class="pricing-plan-name font-display">
                                <?= htmlspecialchars($plan['name']) ?>
                            </h3>

                            <p class="pricing-plan-description">
                                <?= htmlspecialchars($plan['description']) ?>
                            </p>
                        </div>

                        <div class="pricing-amount">
                            <span class="pricing-amount-label">
                                Pricing to be announced
                            </span>
                            <span class="pricing-amount-note">
                                Final details will be confirmed before launch.
                            </span>
                        </div>

                        <div class="pricing-divider"></div>

                        <p class="pricing-features-title">Planned highlights</p>

                        <ul class="pricing-features">
                            <?php foreach ($plan['features'] as $feature): ?>
                                <li>
                                    <i class="fa-solid fa-check"></i>
                                    <span><?= htmlspecialchars($feature) ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>

                        <?php if ($plan['name'] === 'Custom'): ?>
                            <a
                                href="mailto:hello@foliioo.com?subject=Foliioo%20Custom%20Plan%20Enquiry"
                                class="btn <?= $plan['featured'] ? 'btn-primary' : 'btn-secondary' ?> btn-block pricing-cta"
                            >
                                <?= htmlspecialchars($plan['label']) ?>
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        <?php else: ?>
                            <a
                                href="create-event.php?plan=<?= strtolower($plan['name']) ?>"
                                class="btn <?= $plan['featured'] ? 'btn-primary' : 'btn-secondary' ?> btn-block pricing-cta"
                            >
                                <?= htmlspecialchars($plan['label']) ?>
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            </div>

            <p class="pricing-disclaimer">
                Plan names, highlights, pricing, and feature availability are
                provisional for this prototype. No payment is collected on this page.
            </p>
        </div>
    </section>

    <section class="pricing-help-section">
        <div class="container-main">
            <div class="pricing-help">
                <div class="pricing-help-icon">
                    <i class="fa-regular fa-comments"></i>
                </div>

                <div class="pricing-help-copy">
                    <span class="eyebrow">Need something different?</span>
                    <h2 class="font-display">Let's plan something personal.</h2>
                    <p>
                        Have a unique event or specific requirements?
                        The Custom option is intended for a more tailored conversation.
                    </p>
                </div>

                <a
                    href="mailto:hello@foliioo.com?subject=Foliioo%20Custom%20Plan%20Enquiry"
                    class="btn btn-dark"
                >
                    Talk to us
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>
</main>

<?php require_once __DIR__ . '/_inc/footer.php'; ?>

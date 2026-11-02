document.addEventListener('DOMContentLoaded', () => {
    const preview = document.getElementById('finalPreview');
    if (!preview) return;

    const config = window.foliiooPreviewConfig || {};

    const templateLabels = {
        elegant: 'Elegant collection',
        modern: 'Modern collection',
        floral: 'Floral collection'
    };

    const defaultCover = 'https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=1200&q=85';

    const templateImages = {
        elegant: defaultCover,
        modern: defaultCover,
        floral: defaultCover
    };

    let draft = {};

    try {
        draft = JSON.parse(
            sessionStorage.getItem('foliiooEventDraft') || '{}'
        );
    } catch (error) {
        draft = {};
    }

    const template = config.template || draft.template || 'elegant';

    document.getElementById('finalTemplateLabel').textContent =
        templateLabels[template] || templateLabels.elegant;

    document.getElementById('finalEventTitle').textContent =
        draft.customTitle || draft.eventName || 'Your event title';

    document.getElementById('finalEventSubtitle').textContent =
        draft.customSubtitle || 'A day to remember';

    document.getElementById('finalEventMessage').textContent =
        draft.customMessage || draft.eventDescription ||
        'Your event welcome message will appear here.';

    document.getElementById('finalEventType').textContent =
        draft.eventType
            ? draft.eventType.replaceAll('-', ' ').toUpperCase()
            : 'A SPECIAL OCCASION';

    document.getElementById('finalEventDate').textContent =
        draft.eventDate
            ? new Date(`${draft.eventDate}T12:00:00`).toLocaleDateString(
                undefined,
                {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                }
            )
            : 'Date to be announced';

    document.getElementById('finalEventLocation').textContent =
        draft.eventLocation || 'Location to be announced';

    // Cover image
    const coverImage = document.getElementById('finalCoverImage');
    const chosenImage = draft.coverImage?.trim();
    const fallbackImage = templateImages[template] || templateImages.elegant;

    if (chosenImage && chosenImage.startsWith('data:image/')) {
        coverImage.src = chosenImage;
    } else if (chosenImage) {
        try {
            const parsedUrl = new URL(chosenImage);

            coverImage.src = ['https:', 'http:'].includes(parsedUrl.protocol)
                ? parsedUrl.href
                : fallbackImage;

        } catch (error) {
            coverImage.src = fallbackImage;
        }
    } else {
        coverImage.src = fallbackImage;
    }

    // Colour palettes
    const palettes = {
        earth: {
            background: '#FAF5EF',
            accent: '#9A5F44',
            ink: '#3F493D'
        },
        sage: {
            background: '#F5F6F0',
            accent: '#7D8C70',
            ink: '#303C32'
        },
        rose: {
            background: '#FFF5F5',
            accent: '#C27C83',
            ink: '#754D56'
        },
        midnight: {
            background: '#F4F3EF',
            accent: '#343C56',
            ink: '#343C56'
        }
    };

    const palette = palettes[draft.palette] || palettes.earth;

    preview.style.setProperty(
        '--preview-background',
        palette.background
    );

    preview.style.setProperty(
        '--preview-accent',
        palette.accent
    );

    preview.style.setProperty(
        '--preview-ink',
        palette.ink
    );

    if (draft.fontStyle === 'sans') {
        preview.classList.add('preview-font-sans');
    }

    // Publish event
    // Publishing needs a completed (simulated) checkout for this event.
    function readPaidOrder(eventName) {
        try {
            const order = JSON.parse(sessionStorage.getItem('foliiooOrder') || 'null');
            const session = window.FoliiooAuth ? FoliiooAuth.getSession() : null;

            return order
                && order.status === 'paid'
                && order.eventName === eventName
                && session
                && order.email === session.email
                ? order
                : null;
        } catch (error) {
            return null;
        }
    }

    const publishButton = document.getElementById('publishEvent');
    const publishMessage = document.getElementById('previewPageMessage');

    function showPublishMessage(message, type) {
        publishMessage.textContent = message;
        publishMessage.className = `preview-page-message ${type}`;
        publishMessage.hidden = false;
    }

    if (publishButton && publishMessage) {
        publishButton.addEventListener('click', () => {
            // Publishing needs a creator account (mock). Send visitors to
            // register or log in, then bring them back to this preview.
            if (window.FoliiooAuth && !FoliiooAuth.isSignedIn()) {
                const next = `preview-event.php${window.location.search}`;

                showPublishMessage(
                    'Create a free creator account or log in to save and publish your event. Your design is kept.',
                    'info'
                );

                if (!document.getElementById('publishAuthPrompt')) {
                    const prompt = document.createElement('div');

                    prompt.id = 'publishAuthPrompt';
                    prompt.className = 'preview-action-buttons';

                    const create = document.createElement('a');
                    create.className = 'btn btn-primary';
                    create.href = FoliiooAuth.withNext('register.php', next);
                    create.textContent = 'Create an account';

                    const login = document.createElement('a');
                    login.className = 'btn btn-secondary';
                    login.href = FoliiooAuth.withNext('login.php', next);
                    login.textContent = 'Log in';

                    prompt.append(create, login);
                    publishMessage.after(prompt);
                }

                return;
            }

            try {
                const savedDraft = sessionStorage.getItem(
                    'foliiooEventDraft'
                );

                if (!savedDraft) {
                    showPublishMessage(
                        'Your event details could not be found. Please return to event creation.',
                        'error'
                    );
                    return;
                }

                const eventDraft = JSON.parse(savedDraft);

                if (!eventDraft.eventName || !eventDraft.eventDate) {
                    showPublishMessage(
                        'Please ensure your event name and date are available before publishing.',
                        'error'
                    );
                    return;
                }

                // Publishing needs a paid (simulated) order for this event.
                const order = readPaidOrder(eventDraft.eventName);

                if (!order) {
                    showPublishMessage(
                        'Choose a plan and complete checkout to publish your event. Your design is kept.',
                        'info'
                    );

                    if (!document.getElementById('publishCheckoutPrompt')) {
                        const prompt = document.createElement('div');
                        const checkout = document.createElement('a');
                        const planQuery = eventDraft.plan
                            ? `?plan=${encodeURIComponent(eventDraft.plan)}`
                            : '';

                        prompt.id = 'publishCheckoutPrompt';
                        prompt.className = 'preview-action-buttons';

                        checkout.className = 'btn btn-primary';
                        checkout.href = `checkout.php${planQuery}`;
                        checkout.innerHTML = '<i class="fa-solid fa-credit-card"></i> Continue to checkout';

                        prompt.append(checkout);
                        publishMessage.after(prompt);
                    }

                    return;
                }

                // Generate event URL
                const eventSlug = eventDraft.eventName
                    .trim()
                    .toLowerCase()
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/^-|-$/g, '');

                const publicUrl = new URL(
                    'event.php',
                    window.location.href
                );

                publicUrl.searchParams.set('event', eventSlug);
                publicUrl.searchParams.set('template', template);

                // Published event data
                const publishedEvent = {
                    ...eventDraft,
                    status: 'published',
                    creatorEmail: window.FoliiooAuth && FoliiooAuth.getSession()
                        ? FoliiooAuth.getSession().email
                        : '',
                    creatorName: window.FoliiooAuth && FoliiooAuth.getSession()
                        ? FoliiooAuth.getSession().fullName
                        : '',
                    plan: order.plan,
                    orderReference: order.reference,
                    publishedAt: new Date().toISOString(),
                    publicUrl: publicUrl.href
                };

                sessionStorage.setItem(
                    'foliiooPublishedEvent',
                    JSON.stringify(publishedEvent)
                );

                showPublishMessage(
                    'Congratulations! Your event website has been published successfully.',
                    'success'
                );

                // Display published event actions
                const publishedActions = document.getElementById(
                    'publishedEventActions'
                );

                const publishedUrl = document.getElementById(
                    'publishedEventUrl'
                );

                const copyButton = document.getElementById(
                    'copyEventLink'
                );

                const viewButton = document.getElementById(
                    'viewPublicEvent'
                );

                if (
                    publishedActions &&
                    publishedUrl &&
                    copyButton &&
                    viewButton
                ) {
                    publishedUrl.textContent = publishedEvent.publicUrl;
                    viewButton.href = publishedEvent.publicUrl;
                    publishedActions.hidden = false;

                    // Copy event link
                    copyButton.addEventListener('click', async () => {
                        try {
                            await navigator.clipboard.writeText(
                                publishedEvent.publicUrl
                            );

                            copyButton.innerHTML =
                                '<i class="fa-solid fa-check"></i> Link Copied';

                        } catch (error) {
                            showPublishMessage(
                                'Unable to copy automatically. Please copy the event link displayed above.',
                                'error'
                            );
                        }
                    });
                }

                publishButton.innerHTML =
                    '<i class="fa-solid fa-check"></i> Published';

                publishButton.disabled = true;

            } catch (error) {
                showPublishMessage(
                    'Something went wrong while publishing. Please try again.',
                    'error'
                );
            }
        });
    }
});
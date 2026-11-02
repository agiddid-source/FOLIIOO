
document.addEventListener('DOMContentLoaded', () => {
    const eventCard = document.getElementById('publicEventCard');
    const errorSection = document.getElementById('publicEventError');
    const errorMessage = document.getElementById('publicEventErrorMessage');

    const title = document.getElementById('publicEventTitle');
    const subtitle = document.getElementById('publicEventSubtitle');
    const message = document.getElementById('publicEventMessage');
    const type = document.getElementById('publicEventType');
    const date = document.getElementById('publicEventDate');
    const location = document.getElementById('publicEventLocation');

    const cover = document.getElementById('publicEventCover');
    const coverPlaceholder = document.getElementById('publicEventCoverPlaceholder');

    function showError(text) {
        eventCard.hidden = true;
        errorMessage.textContent = text;
        errorSection.hidden = false;
    }

    let publishedEvent;

    try {
        const storedEvent = sessionStorage.getItem('foliiooPublishedEvent');

        if (!storedEvent) {
            showError('This event has not been published in this browser session.');
            return;
        }

        publishedEvent = JSON.parse(storedEvent);

    } catch (error) {
        showError('We could not load this event. Please try publishing it again.');
        return;
    }

    if (publishedEvent.status !== 'published') {
        showError('This event is not currently published.');
        return;
    }

    title.textContent =
        publishedEvent.customTitle || publishedEvent.eventName || 'Untitled Event';

    subtitle.textContent =
        publishedEvent.customSubtitle || '';

    message.textContent =
        publishedEvent.customMessage || publishedEvent.eventDescription || '';

    type.textContent =
        publishedEvent.eventType || 'SPECIAL OCCASION';

    date.textContent =
        publishedEvent.eventDate || 'Not specified';

    location.textContent =
        publishedEvent.eventLocation || 'Not specified';

    const coverImage = publishedEvent.coverImage;

    if (
        typeof coverImage === 'string' &&
        coverImage.startsWith('data:image/')
    ) {
        cover.src = coverImage;
        cover.hidden = false;
        coverPlaceholder.hidden = true;

    } else {
        cover.hidden = true;
        coverPlaceholder.hidden = false;
    }

    const palettes = {
        earth: {
            accent: '#9A5F44',
            background: '#FAF8F5',
            text: '#252321'
        },
        sage: {
            accent: '#87927B',
            background: '#EEF0EA',
            text: '#252321'
        },
        rose: {
            accent: '#B97882',
            background: '#F8ECEE',
            text: '#252321'
        },
        midnight: {
            accent: '#68758C',
            background: '#E9EDF4',
            text: '#252321'
        }
    };

    const palette = palettes[publishedEvent.palette] || palettes.earth;

    document.documentElement.style.setProperty(
        '--color-accent',
        palette.accent
    );

    document.body.style.backgroundColor = palette.background;
    document.body.style.color = palette.text;

    const selectedTemplate = window.foliiooPublicEventConfig?.template;

    const templateFonts = {
        modern: "'DM Sans', sans-serif",
        elegant: "'Cormorant Garamond', Georgia, serif",
        floral: "'DM Serif Display', Georgia, serif"
    };

    title.style.fontFamily =
        templateFonts[selectedTemplate] || templateFonts.elegant;

    eventCard.hidden = false;
});

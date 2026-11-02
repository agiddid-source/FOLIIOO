document.addEventListener('DOMContentLoaded', () => {
    const preview = document.getElementById('eventLivePreview');
    if (!preview) return;

    const titleInput = document.getElementById('customEventTitle');
    const subtitleInput = document.getElementById('customEventSubtitle');
    const messageInput = document.getElementById('customEventMessage');
    const imageInput = document.getElementById('coverImageFile');
    const removeImageButton = document.getElementById('removeCoverImage');
    const statusMessage = document.getElementById('customizeMessage');

    const previewTitle = document.getElementById('previewEventTitle');
    const previewSubtitle = document.getElementById('previewEventSubtitle');
    const previewMessage = document.getElementById('previewEventMessage');
    const previewImage = document.getElementById('previewCoverImage');

    const palettes = {
        earth: { background: '#FAF5EF', accent: '#9A5F44', ink: '#3F493D' },
        sage: { background: '#F5F6F0', accent: '#7D8C70', ink: '#303C32' },
        rose: { background: '#FFF5F5', accent: '#C27C83', ink: '#754D56' },
        midnight: { background: '#F4F3EF', accent: '#343C56', ink: '#343C56' }
    };

    const defaultImage = previewImage.src;
    const query = new URLSearchParams(window.location.search);
    const config = window.foliiooCustomizationConfig || {};
    const template = config.template || query.get('template') || 'elegant';

    let draft = {};

    try {
        draft = JSON.parse(sessionStorage.getItem('foliiooEventDraft') || '{}');
    } catch (error) {
        draft = {};
    }

    // Send the user back to the details form with their template and plan intact.
    const backLink = document.querySelector('.customize-back-link');

    if (backLink) {
        const backParams = new URLSearchParams({
            template,
            plan: draft.plan || 'basic'
        });

        backLink.href = `create-event.php?${backParams.toString()}`;
    }

    const templateLabels = {
        elegant: 'Elegant',
        modern: 'Modern',
        floral: 'Floral'
    };

    const templateLabel = templateLabels[template] || 'Elegant';

    document.getElementById('previewTemplateName').textContent = `${templateLabel} style`;
    document.getElementById('previewTemplateTag').textContent = `${templateLabel} collection`;

    titleInput.value = draft.customTitle || draft.eventName || '';
    subtitleInput.value = draft.customSubtitle || '';
    messageInput.value = draft.customMessage || draft.eventDescription || '';
    imageInput.value = '';

    function renderPreview() {
        previewTitle.textContent = titleInput.value.trim() || 'Your event title';
        previewSubtitle.textContent = subtitleInput.value.trim() || 'A day to remember';
        previewMessage.textContent = messageInput.value.trim()
            || 'We look forward to sharing this special moment with you. More details will be added as your event takes shape.';

        document.getElementById('previewEventType').textContent =
            draft.eventType ? draft.eventType.replaceAll('-', ' ').toUpperCase() : 'A SPECIAL OCCASION';

        document.getElementById('previewEventDate').textContent = draft.eventDate
            ? new Date(`${draft.eventDate}T12:00:00`).toLocaleDateString(undefined, {
                year: 'numeric', month: 'long', day: 'numeric'
            })
            : 'Date to be announced';

        document.getElementById('previewEventLocation').textContent =
            draft.eventLocation || 'Location to be announced';

// The selected local image is previewed using an object URL.
if (imageInput.dataset.previewUrl) {
    previewImage.src = imageInput.dataset.previewUrl;
} else if (draft.coverImage && draft.coverImage.startsWith('data:image/')) {
    previewImage.src = draft.coverImage;
} else {
    previewImage.src = defaultImage;
}
    }

    function applyPalette(name) {
        const palette = palettes[name] || palettes.earth;

        preview.dataset.palette = name in palettes ? name : 'earth';
        preview.style.setProperty('--preview-background', palette.background);
        preview.style.setProperty('--preview-accent', palette.accent);
        preview.style.setProperty('--preview-ink', palette.ink);

        document.querySelectorAll('[data-palette]').forEach((button) => {
            if (!button.matches('button')) return;

            const active = button.dataset.palette === name;
            button.classList.toggle('active', active);
            button.setAttribute('aria-pressed', String(active));
        });
    }

    function showMessage(text, type) {
        statusMessage.textContent = text;
        statusMessage.className = `customize-message ${type}`;
        statusMessage.hidden = false;
    }

   [titleInput, subtitleInput, messageInput].forEach((input) => {
    input.addEventListener('input', renderPreview);
});

imageInput.addEventListener('change', () => {
    const file = imageInput.files[0];

    if (!file) return;

    if (!['image/jpeg', 'image/png', 'image/webp', 'image/gif'].includes(file.type)) {
        showMessage('Please choose a JPG, PNG, WebP, or GIF image.', 'error');
        imageInput.value = '';
        return;
    }

    if (file.size > 5 * 1024 * 1024) {
        showMessage('Please choose an image smaller than 5 MB.', 'error');
        imageInput.value = '';
        return;
    }

    if (imageInput.dataset.previewUrl) {
        URL.revokeObjectURL(imageInput.dataset.previewUrl);
    }

    const objectUrl = URL.createObjectURL(file);
    imageInput.dataset.previewUrl = objectUrl;

    previewImage.src = objectUrl;
    showMessage('Cover image selected successfully.', 'success');
});

removeImageButton.addEventListener('click', () => {
    if (imageInput.dataset.previewUrl) {
        URL.revokeObjectURL(imageInput.dataset.previewUrl);
        delete imageInput.dataset.previewUrl;
    }

    imageInput.value = '';
    delete draft.coverImage;

    previewImage.src = defaultImage;
    showMessage('Cover image removed.', 'info');
});

    document.querySelectorAll('button[data-palette]').forEach((button) => {
        button.addEventListener('click', () => applyPalette(button.dataset.palette));
    });

    document.querySelectorAll('input[name="fontStyle"]').forEach((input) => {
        input.addEventListener('change', () => {
            preview.classList.toggle('preview-font-sans', input.value === 'sans' && input.checked);

            document.querySelectorAll('.customize-font-option').forEach((option) => {
                option.classList.toggle('active', option.contains(input) && input.checked);
            });
        });
    });

    document.getElementById('resetCustomization').addEventListener('click', () => {
        titleInput.value = draft.customTitle || draft.eventName || '';
        subtitleInput.value = draft.customSubtitle || '';
        messageInput.value = draft.customMessage || draft.eventDescription || '';
        if (imageInput.dataset.previewUrl) {
            URL.revokeObjectURL(imageInput.dataset.previewUrl);
            delete imageInput.dataset.previewUrl;
        }
        imageInput.value = '';

        document.querySelector('input[name="fontStyle"][value="serif"]').checked = true;
        preview.classList.remove('preview-font-sans');

        document.querySelectorAll('.customize-font-option').forEach((option, index) => {
            option.classList.toggle('active', index === 0);
        });

        applyPalette('earth');
        renderPreview();
        showMessage('Customization reset to its starting values.', 'info');
    });

    document.getElementById('saveCustomization').addEventListener('click', async () => {
    const selectedFont =
        document.querySelector('input[name="fontStyle"]:checked')?.value || 'serif';

    const selectedPalette = preview.dataset.palette || 'earth';
    let savedCoverImage = draft.coverImage || '';

    try {
        const file = imageInput.files[0];

        if (file) {
            // Load the selected image.
            const objectUrl = URL.createObjectURL(file);

            try {
                const image = new Image();

                await new Promise((resolve, reject) => {
                    image.onload = resolve;
                    image.onerror = reject;
                    image.src = objectUrl;
                });

                // Resize large images to keep browser storage manageable.
                const maxWidth = 1600;
                const maxHeight = 1200;
                const scale = Math.min(
                    1,
                    maxWidth / image.naturalWidth,
                    maxHeight / image.naturalHeight
                );

                const canvas = document.createElement('canvas');
                canvas.width = Math.round(image.naturalWidth * scale);
                canvas.height = Math.round(image.naturalHeight * scale);

                const context = canvas.getContext('2d');
                context.drawImage(image, 0, 0, canvas.width, canvas.height);

                savedCoverImage = canvas.toDataURL('image/jpeg', 0.82);
            } finally {
                URL.revokeObjectURL(objectUrl);
            }
        }

        const customization = {
            ...draft,
            template,
            customTitle: titleInput.value.trim(),
            customSubtitle: subtitleInput.value.trim(),
            customMessage: messageInput.value.trim(),
            coverImage: savedCoverImage,
            palette: selectedPalette,
            fontStyle: selectedFont,
            updatedAt: new Date().toISOString()
        };

        sessionStorage.setItem(
            'foliiooEventDraft',
            JSON.stringify(customization)
        );

        // Update the in-memory draft as well.
        draft = customization;

        window.location.href =
            `preview-event.php?template=${encodeURIComponent(template)}`;

    } catch (error) {
        showMessage(
            'Could not save your customization. Try a smaller image or another browser image.',
            'error'
        );
    }
});

    // Restore the previously saved palette and font.
const savedPalette = palettes[draft.palette] ? draft.palette : 'earth';
const savedFont = draft.fontStyle === 'sans' ? 'sans' : 'serif';

applyPalette(savedPalette);

document.querySelector(
    `input[name="fontStyle"][value="${savedFont}"]`
).checked = true;

preview.classList.toggle('preview-font-sans', savedFont === 'sans');

document.querySelectorAll('.customize-font-option').forEach((option) => {
    const input = option.querySelector('input[name="fontStyle"]');
    option.classList.toggle('active', input?.value === savedFont);
});

renderPreview();
});
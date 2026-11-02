
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('createEventForm');

    if (!form) return;

    const DRAFT_KEY = 'foliiooEventDraft';
    const VALID_TEMPLATES = ['elegant', 'modern', 'floral'];
    const VALID_PLANS = ['basic', 'essential', 'premium', 'custom'];

    const fields = {
        eventName: document.getElementById('eventName'),
        eventType: document.getElementById('eventType'),
        eventDate: document.getElementById('eventDate'),
        eventLocation: document.getElementById('eventLocation'),
        eventDescription: document.getElementById('eventDescription')
    };

    const description = fields.eventDescription;
    const descriptionCount = document.getElementById('descriptionCount');
    const eventDate = fields.eventDate;
    const message = document.getElementById('createFormMessage');

    /* ---------- Draft helpers ---------- */
    function readDraft() {
        try {
            return JSON.parse(sessionStorage.getItem(DRAFT_KEY) || '{}') || {};
        } catch (error) {
            return {};
        }
    }

    function writeDraft(draft) {
        try {
            sessionStorage.setItem(DRAFT_KEY, JSON.stringify(draft));
            return true;
        } catch (error) {
            return false;
        }
    }

    const savedDraft = readDraft();
    const params = new URLSearchParams(window.location.search);

    /* ---------- Keep template and plan when the URL is missing them ----------
       Coming back from "Change template" or "Change plan" only carries one of
       the two in the URL, so fill the other from the saved draft. */
    const urlTemplate = params.get('template');
    const urlPlan = params.get('plan');

    const template = VALID_TEMPLATES.includes(urlTemplate)
        ? urlTemplate
        : (VALID_TEMPLATES.includes(savedDraft.template) ? savedDraft.template : 'elegant');

    const plan = VALID_PLANS.includes(urlPlan)
        ? urlPlan
        : (VALID_PLANS.includes(savedDraft.plan) ? savedDraft.plan : 'basic');

    if (urlTemplate !== template || urlPlan !== plan) {
        params.set('template', template);
        params.set('plan', plan);
        window.location.replace(`${window.location.pathname}?${params.toString()}`);
        return;
    }

    /* ---------- Restore what the user already typed ---------- */
    Object.entries(fields).forEach(([key, input]) => {
        if (input && typeof savedDraft[key] === 'string') {
            input.value = savedDraft[key];
        }
    });

    descriptionCount.textContent = description.value.length;

    // Prevent choosing a date in the past.
    const today = new Date();
    const localToday = [
        today.getFullYear(),
        String(today.getMonth() + 1).padStart(2, '0'),
        String(today.getDate()).padStart(2, '0')
    ].join('-');

    eventDate.min = localToday;

    /* ---------- Autosave as the user types, so nothing is lost when they
       leave to change the template or plan ---------- */
    function currentFields() {
        return {
            eventName: fields.eventName.value.trim(),
            eventType: fields.eventType.value,
            eventDate: eventDate.value,
            eventLocation: fields.eventLocation.value.trim(),
            eventDescription: description.value.trim()
        };
    }

    function saveDraft() {
        const previous = readDraft();
        const next = { ...previous, ...currentFields(), template, plan };

        // Keep customised copy in step with the details unless the user
        // has deliberately written something different.
        if (previous.customTitle && previous.customTitle === previous.eventName) {
            delete next.customTitle;
        }

        if (previous.customMessage && previous.customMessage === previous.eventDescription) {
            delete next.customMessage;
        }

        next.updatedAt = new Date().toISOString();

        return writeDraft(next);
    }

    form.addEventListener('input', saveDraft);
    form.addEventListener('change', saveDraft);
    window.addEventListener('pagehide', saveDraft);

    saveDraft();

    description.addEventListener('input', () => {
        descriptionCount.textContent = description.value.length;
    });

    form.addEventListener('submit', (event) => {
        event.preventDefault();

        message.hidden = true;
        message.textContent = '';

        if (!form.reportValidity()) return;

        if (eventDate.value < localToday) {
            message.textContent = 'Please choose today or a future date.';
            message.className = 'create-form-message error';
            message.hidden = false;
            eventDate.focus();
            return;
        }

        if (!saveDraft()) {
            message.textContent =
                'Browser storage is unavailable. Your details have not been saved.';
            message.className = 'create-form-message error';
            message.hidden = false;
            return;
        }

        window.location.href =
            `customize-event.php?template=${encodeURIComponent(template)}`;
    });
});

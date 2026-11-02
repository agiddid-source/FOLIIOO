/* ========================================
   CHECKOUT (simulated). Requires js/auth/auth.js
   No real payment is taken and no card details are collected.
   The order is kept in sessionStorage under "foliiooOrder".
======================================== */
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('checkoutForm');

    if (!form || !window.FoliiooAuth) return;

    const DRAFT_KEY = 'foliiooEventDraft';
    const ORDER_KEY = 'foliiooOrder';
    const PAYABLE_PLANS = ['basic', 'essential', 'premium'];
    const TEMPLATE_NAMES = { elegant: 'Elegant', modern: 'Modern', floral: 'Floral' };

    const views = {
        empty: document.getElementById('checkoutEmpty'),
        custom: document.getElementById('checkoutCustom'),
        main: document.getElementById('checkoutMain'),
        processing: document.getElementById('checkoutProcessing'),
        success: document.getElementById('checkoutSuccess'),
        failed: document.getElementById('checkoutFailed'),
        cancelled: document.getElementById('checkoutCancelled')
    };

    function showView(name) {
        Object.entries(views).forEach(([key, element]) => {
            element.hidden = key !== name;
        });

        // The "Choose your plan" intro only belongs above the checkout form.
        const intro = document.querySelector('.checkout-heading');

        if (intro) intro.hidden = name !== 'main';

        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function readJSON(key) {
        try {
            return JSON.parse(sessionStorage.getItem(key) || 'null');
        } catch (error) {
            return null;
        }
    }

    function writeJSON(key, value) {
        try {
            sessionStorage.setItem(key, JSON.stringify(value));
            return true;
        } catch (error) {
            return false;
        }
    }

    /* ---------- Must be signed in ---------- */
    if (!FoliiooAuth.isSignedIn()) {
        window.location.replace(
            FoliiooAuth.withNext('login.php', `checkout.php${window.location.search}`)
        );
        return;
    }

    const session = FoliiooAuth.getSession();
    const draft = readJSON(DRAFT_KEY);

    if (!draft || !draft.eventName) {
        showView('empty');
        return;
    }

    const template = TEMPLATE_NAMES[draft.template] ? draft.template : 'elegant';

    const urlPlan = new URLSearchParams(window.location.search).get('plan');
    const requestedPlan = urlPlan || draft.plan || 'basic';

    if (requestedPlan === 'custom') {
        showView('custom');
        return;
    }

    let plan = PAYABLE_PLANS.includes(requestedPlan) ? requestedPlan : 'basic';

    const planInputs = [...form.querySelectorAll('input[name="plan"]')];
    const payButton = document.getElementById('payButton');

    function planName() {
        const selected = planInputs.find(input => input.value === plan);

        return selected ? selected.dataset.planName : plan;
    }

    /* ---------- Order summary ---------- */
    function formatDate(value) {
        if (!value) return 'To be confirmed';

        const date = new Date(`${value}T12:00:00`);

        return Number.isNaN(date.getTime())
            ? value
            : date.toLocaleDateString(undefined, {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
    }

    function renderSummary() {
        document.getElementById('sumEvent').textContent = draft.eventName;
        document.getElementById('sumDate').textContent = formatDate(draft.eventDate);
        document.getElementById('sumTemplate').textContent = TEMPLATE_NAMES[template];
        document.getElementById('sumPlan').textContent = planName();
        document.getElementById('sumAccount').textContent = session.email;
    }

    function selectPlan(id) {
        plan = id;

        planInputs.forEach(input => {
            input.checked = input.value === plan;
        });

        draft.plan = plan;
        writeJSON(DRAFT_KEY, draft);

        const params = new URLSearchParams(window.location.search);

        params.set('plan', plan);
        window.history.replaceState(null, '', `checkout.php?${params.toString()}`);

        renderSummary();
    }

    planInputs.forEach(input => {
        input.addEventListener('change', () => selectPlan(input.value));
    });

    selectPlan(plan);

    // Links that depend on the chosen template
    const backToEditor = document.querySelector('#checkoutCancelled a[href^="customize-event.php"]');

    if (backToEditor) {
        backToEditor.href = `customize-event.php?template=${encodeURIComponent(template)}`;
    }

    /* ---------- Already paid for this event and plan ---------- */
    function currentOrder() {
        const order = readJSON(ORDER_KEY);

        return order
            && order.status === 'paid'
            && order.eventName === draft.eventName
            && order.plan === plan
            && order.email === session.email
            ? order
            : null;
    }

    function showSuccess(order) {
        document.getElementById('successPlan').textContent = order.planName;
        document.getElementById('successReference').textContent = order.reference;
        document.getElementById('successEvent').textContent = order.eventName;
        document.getElementById('successDate').textContent = new Date(order.paidAt)
            .toLocaleString(undefined, { dateStyle: 'medium', timeStyle: 'short' });
        document.getElementById('continuePublish').href =
            `preview-event.php?template=${encodeURIComponent(template)}`;

        showView('success');
    }

    const existing = currentOrder();

    if (existing) {
        showSuccess(existing);
    } else {
        showView('main');
    }

    /* ---------- Simulated payment ---------- */
    function makeReference() {
        const random = Math.random().toString(36).slice(2, 6).toUpperCase();

        return `FOL-${Date.now().toString(36).toUpperCase()}-${random}`;
    }

    form.addEventListener('submit', event => {
        event.preventDefault();

        const outcome = form.querySelector('input[name="outcome"]:checked').value;
        const method = form.querySelector('input[name="method"]:checked').value;

        payButton.disabled = true;
        showView('processing');

        window.setTimeout(() => {
            payButton.disabled = false;

            if (outcome === 'failed') {
                showView('failed');
                return;
            }

            if (outcome === 'cancelled') {
                showView('cancelled');
                return;
            }

            const order = {
                reference: makeReference(),
                status: 'paid',
                simulated: true,
                plan,
                planName: planName(),
                method,
                eventName: draft.eventName,
                template,
                email: session.email,
                paidAt: new Date().toISOString()
            };

            if (!writeJSON(ORDER_KEY, order)) {
                showView('failed');
                return;
            }

            showSuccess(order);
        }, 1600);
    });

    document.querySelectorAll('[data-checkout-retry]').forEach(button => {
        button.addEventListener('click', () => showView('main'));
    });
});
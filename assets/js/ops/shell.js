/** Controls the compact navigation drawer used on small screens. */
const mobileMenuButtons = document.querySelectorAll('[data-mobile-menu]');
const mobilePanel = document.querySelector('[data-mobile-panel]');
let mobileMenuTrigger = null;

const setMobileMenu = (isOpen) => {
    if (!(mobilePanel instanceof HTMLElement)) return;

    if (isOpen) {
        mobileMenuTrigger = document.activeElement instanceof HTMLElement ? document.activeElement : null;
        mobilePanel.hidden = false;
        document.body.classList.add('workspace-menu-open');
        mobileMenuButtons.forEach((button) => button.setAttribute('aria-expanded', 'true'));
        mobilePanel.querySelector('button[aria-label="Close menu"]')?.focus();
        return;
    }

    mobilePanel.hidden = true;
    document.body.classList.remove('workspace-menu-open');
    mobileMenuButtons.forEach((button) => button.setAttribute('aria-expanded', 'false'));
    mobileMenuTrigger?.focus();
    mobileMenuTrigger = null;
};

mobileMenuButtons.forEach((button) => {
    button.addEventListener('click', () => setMobileMenu(mobilePanel?.hidden === true));
});

mobilePanel?.addEventListener('click', (event) => { if (event.target === mobilePanel) setMobileMenu(false); });

document.addEventListener('keydown', (event) => {
    if (!(mobilePanel instanceof HTMLElement) || mobilePanel.hidden) return;

    if (event.key === 'Escape') {
        setMobileMenu(false);
        return;
    }

    if (event.key !== 'Tab') return;

    const focusableItems = Array.from(mobilePanel.querySelectorAll('a[href], button:not(:disabled)'));
    const firstItem = focusableItems[0];
    const lastItem = focusableItems[focusableItems.length - 1];

    if (event.shiftKey && document.activeElement === firstItem) {
        event.preventDefault();
        lastItem?.focus();
    } else if (!event.shiftKey && document.activeElement === lastItem) {
        event.preventDefault();
        firstItem?.focus();
    }
});

/**
 * Copies the public event address on the manage-event page.
 */

const copyAddressButton = document.querySelector('[data-copy-text]');
const copyStatus = document.querySelector('#copy-status');

if (copyAddressButton instanceof HTMLButtonElement && copyStatus instanceof HTMLElement) {
    copyAddressButton.addEventListener('click', async () => {
        const address = copyAddressButton.dataset.copyText;

        if (!address) {
            copyStatus.textContent = 'The event address is unavailable.';
            return;
        }

        try {
            await navigator.clipboard.writeText(address);
            copyStatus.textContent = 'Event address copied.';
        } catch {
            copyStatus.textContent = 'Copy was blocked. Select and copy the event address manually.';
        }
    });
}

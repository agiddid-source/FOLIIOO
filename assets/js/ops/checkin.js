/**
 * Handles simulated pass scans and manual guest check-in forms.
 */

document.querySelectorAll('form[data-checkin]').forEach((form) => {
    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const submitButton = form.querySelector('button[type="submit"]');
        const resultPanel = document.querySelector('[data-checkin-result]');
        const endpoint = form.dataset.endpoint;

        if (!(submitButton instanceof HTMLButtonElement) || !endpoint) {
            return;
        }

        const originalLabel = submitButton.textContent || 'Check in';
        submitButton.disabled = true;
        submitButton.textContent = submitButton.dataset.loadingLabel || 'Checking…';

        if (resultPanel instanceof HTMLElement) {
            resultPanel.classList.remove('hidden');
            resultPanel.textContent = 'Checking pass…';
            resultPanel.setAttribute('aria-busy', 'true');
        }

        try {
            const response = await fetch(endpoint, {
                method: 'POST',
                body: new FormData(form),
                headers: { Accept: 'application/json' },
            });
            const result = await response.json();

            if (!response.ok || result.ok !== true) {
                throw new Error(result.message || 'Check-in could not be completed.');
            }

            if (resultPanel instanceof HTMLElement) {
                resultPanel.dataset.result = result.result || 'invalid';
                resultPanel.innerHTML = '';

                const heading = document.createElement('strong');
                heading.textContent = result.result === 'valid'
                    ? 'Valid pass'
                    : result.result === 'already_used'
                        ? 'Already used'
                        : 'Invalid pass';

                const message = document.createElement('p');
                message.className = 'mt-1 leading-6';
                message.textContent = result.message || '';
                resultPanel.append(heading, message);

                if (result.guest?.name) {
                    const guestName = document.createElement('p');
                    guestName.className = 'mt-2 font-semibold';
                    guestName.textContent = result.guest.name;
                    resultPanel.append(guestName);
                }

                if (result.guest?.checked_in_at) {
                    const timestamp = document.createElement('time');
                    timestamp.className = 'mt-1 block text-xs opacity-80';
                    timestamp.dateTime = result.guest.checked_in_at;
                    timestamp.textContent = new Date(result.guest.checked_in_at).toLocaleString();
                    resultPanel.append(timestamp);
                }

                resultPanel.removeAttribute('aria-busy');
            }

            if (result.result === 'valid') {
                form.reset();
                if (form.querySelector('[name="guest_id"]')) {
                    window.location.reload();
                } else {
                    window.setTimeout(() => window.location.reload(), 900);
                }
            }
        } catch (error) {
            if (resultPanel instanceof HTMLElement) {
                resultPanel.dataset.result = 'error';
                resultPanel.textContent = error instanceof Error ? error.message : 'Check-in could not be completed.';
                resultPanel.removeAttribute('aria-busy');
            }
        } finally {
            submitButton.disabled = false;
            submitButton.textContent = originalLabel;
        }
    });
});

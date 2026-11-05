/**
 * Shared progressive enhancement for confirmation and loading states.
 */

const confirmationDialog = document.querySelector('.ght-confirm-dialog');
const confirmationTitle = confirmationDialog?.querySelector('.ght-confirm-title');
const confirmationMessage = confirmationDialog?.querySelector('.ght-confirm-message');
let pendingConfirmationForm = null;
let pendingSubmitter = null;

document.querySelectorAll('form[data-confirm]').forEach((form) => {
    form.addEventListener('submit', (event) => {
        if (form.dataset.confirmApproved === 'true') {
            delete form.dataset.confirmApproved;
            return;
        }

        if (!(confirmationDialog instanceof HTMLDialogElement)) {
            return;
        }

        event.preventDefault();
        pendingConfirmationForm = form;
        pendingSubmitter = event.submitter instanceof HTMLElement ? event.submitter : null;

        if (confirmationTitle instanceof HTMLElement) {
            confirmationTitle.textContent = form.dataset.confirmTitle || 'Confirm this action';
        }
        if (confirmationMessage instanceof HTMLElement) {
            confirmationMessage.textContent = form.dataset.confirm || 'Are you sure you want to continue?';
        }

        confirmationDialog.showModal();
    });
});

confirmationDialog?.addEventListener('close', () => {
    if (confirmationDialog.returnValue !== 'confirm' || !pendingConfirmationForm) {
        pendingConfirmationForm = null;
        pendingSubmitter = null;
        return;
    }

    const form = pendingConfirmationForm;
    const submitter = pendingSubmitter;
    form.dataset.confirmApproved = 'true';
    pendingConfirmationForm = null;
    pendingSubmitter = null;

    if (typeof form.requestSubmit === 'function') {
        form.requestSubmit(submitter || undefined);
        return;
    }

    form.submit();
});

document.querySelectorAll('form[data-loading]').forEach((form) => {
    form.addEventListener('submit', (event) => {
        const submitter = event.submitter;

        if (!(submitter instanceof HTMLButtonElement)) {
            return;
        }

        if (event.defaultPrevented) {
            return;
        }

        if (submitter.name) {
            const existingInput = Array.from(form.elements).find((element) => (
                element instanceof HTMLInputElement
                && element.type === 'hidden'
                && element.name === submitter.name
            ));

            if (existingInput instanceof HTMLInputElement) {
                existingInput.value = submitter.value;
            } else {
                const submittedValue = document.createElement('input');
                submittedValue.type = 'hidden';
                submittedValue.name = submitter.name;
                submittedValue.value = submitter.value;
                form.append(submittedValue);
            }
        }

        submitter.disabled = true;
        submitter.dataset.originalLabel = submitter.textContent?.trim() || 'Save';
        submitter.textContent = submitter.dataset.loadingLabel || 'Saving…';
        submitter.setAttribute('aria-busy', 'true');
    });
});

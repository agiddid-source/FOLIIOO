/**
 * Keeps postponement notification copy aligned with the selected form values.
 */

const postponeForm = document.querySelector('form[data-confirm] input[name="action"][value="postpone-event"]')?.form;
const notificationPreview = document.querySelector('[data-notification-message]');

if (postponeForm instanceof HTMLFormElement && notificationPreview instanceof HTMLElement) {
    const updateNotificationPreview = () => {
        const newDate = postponeForm.querySelector('[name="new_date"]');
        const newTime = postponeForm.querySelector('[name="new_time"]');
        const reason = postponeForm.querySelector('[name="reason"]');
        const formattedDate = newDate instanceof HTMLInputElement && newDate.value
            ? new Date(`${newDate.value}T12:00:00`).toLocaleDateString(undefined, {
                day: 'numeric',
                month: 'long',
                year: 'numeric',
            })
            : 'the new date';
        const formattedTime = newTime instanceof HTMLInputElement && newTime.value
            ? new Date(`1970-01-01T${newTime.value}:00`).toLocaleTimeString(undefined, {
                hour: 'numeric',
                minute: '2-digit',
            })
            : 'the new time';
        const reasonText = reason instanceof HTMLTextAreaElement && reason.value.trim()
            ? ` Reason: ${reason.value.trim()}`
            : '';

        const eventTitle = document.querySelector('main h1')?.textContent?.trim() || 'The event';
        notificationPreview.textContent = `${eventTitle} has been rescheduled to ${formattedDate} at ${formattedTime}.${reasonText}`;
    };

    postponeForm.addEventListener('input', updateNotificationPreview);
    updateNotificationPreview();
}

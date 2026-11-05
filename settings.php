<?php
/**
 * settings.php (Module 13)
 * Updates event details, plan-governed features, and event notifications.
 * Includes: bootstrap.php, shared UI components, dashboard-shell.php.
 * Reads: events.json, guests.json, plans.json, notifications.json.
 * Writes: events.json and notifications.json through JSON helpers.
 * Animations: shared confirmation dialog only.
 */

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$ghtCsrfToken = ghtGetCsrfToken();
$ghtRequestedEventId = filter_input(INPUT_GET, 'event', FILTER_UNSAFE_RAW);
$ghtRequestedEventId = is_string($ghtRequestedEventId) ? trim($ghtRequestedEventId) : '';
$ghtDataError = '';
$ghtActionError = '';
$ghtNotice = filter_input(INPUT_GET, 'notice', FILTER_UNSAFE_RAW);
$ghtNotice = is_string($ghtNotice) ? $ghtNotice : '';
$ghtEvents = [];
$ghtGuests = [];
$ghtPlans = [];
$ghtNotifications = [];
$ghtEvent = null;
$ghtEventIndex = null;
$ghtPlan = [];
$ghtConfirmedGuests = 0;
$ghtReason = '';
$ghtNewDate = '';
$ghtNewTime = '';
$ghtRequestedAction = '';

try {
    $ghtEvents = ghtReadJson('events.json');
    $ghtGuests = ghtReadJson('guests.json');
    $ghtPlans = ghtReadJson('plans.json');
    $ghtNotifications = ghtReadJson('notifications.json');
} catch (RuntimeException $exception) {
    error_log('Foliioo settings data load failed: ' . $exception->getMessage());
    $ghtDataError = 'Settings data could not be loaded. Check the data files and reload this page.';
}

$ghtEventLookup = $ghtDataError === '' && $ghtRequestedEventId !== ''
    ? ghtFindEvent($ghtEvents, $ghtRequestedEventId)
    : null;

if ($ghtEventLookup !== null) {
    $ghtEventIndex = $ghtEventLookup['index'];
    $ghtEvent = $ghtEventLookup['event'];
    foreach ($ghtGuests as $ghtGuest) {
        if (is_array($ghtGuest)
            && ($ghtGuest['event_id'] ?? '') === $ghtRequestedEventId
            && ($ghtGuest['response'] ?? '') === 'attending'
        ) {
            $ghtConfirmedGuests++;
        }
    }

    foreach ($ghtPlans as $ghtPlanRecord) {
        if (is_array($ghtPlanRecord) && ($ghtPlanRecord['id'] ?? '') === ($ghtEvent['plan'] ?? '')) {
            $ghtPlan = $ghtPlanRecord;
            break;
        }
    }
}

$ghtFormValues = [
    'title' => (string) ($ghtEvent['title'] ?? ''),
    'host_names' => (string) ($ghtEvent['host_names'] ?? ''),
    'description' => (string) ($ghtEvent['description'] ?? ''),
    'date' => (string) ($ghtEvent['date'] ?? ''),
    'time' => (string) ($ghtEvent['time'] ?? ''),
    'venue' => (string) ($ghtEvent['venue'] ?? ''),
    'expected_guests' => (string) ($ghtEvent['expected_guests'] ?? ''),
    'ignore_guest_limit' => (bool) ($ghtEvent['enforce_guest_limit'] ?? false) === false,
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $ghtEvent !== null) {
    $ghtSubmittedToken = $_POST['csrf_token'] ?? '';
    $ghtRequestedAction = isset($_POST['action']) && is_string($_POST['action'])
        ? $_POST['action']
        : '';
    $ghtReason = isset($_POST['reason']) && is_string($_POST['reason']) ? trim($_POST['reason']) : '';
    $ghtNewDate = isset($_POST['new_date']) && is_string($_POST['new_date']) ? trim($_POST['new_date']) : '';
    $ghtNewTime = isset($_POST['new_time']) && is_string($_POST['new_time']) ? trim($_POST['new_time']) : '';

    foreach (['title', 'host_names', 'description', 'date', 'time', 'venue', 'expected_guests'] as $ghtField) {
        if (isset($_POST[$ghtField]) && is_string($_POST[$ghtField])) {
            $ghtFormValues[$ghtField] = trim($_POST[$ghtField]);
        }
    }
    $ghtFormValues['ignore_guest_limit'] = isset($_POST['ignore_guest_limit']);

    if (!ghtValidateCsrfToken($ghtSubmittedToken)) {
        $ghtActionError = 'Your session expired or this form was invalid. Reload the page and try again.';
    } elseif ($ghtRequestedAction === 'save-details') {
        if ($ghtFormValues['title'] === '' || strlen($ghtFormValues['title']) > 120) {
            $ghtActionError = 'Enter an event title between 1 and 120 characters.';
        } elseif ($ghtFormValues['host_names'] === '' || strlen($ghtFormValues['host_names']) > 180) {
            $ghtActionError = 'Enter the host name or names (up to 180 characters).';
        } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $ghtFormValues['date'])
            || ghtFormatDate($ghtFormValues['date']) === 'Date not set'
        ) {
            $ghtActionError = 'Choose a valid event date.';
        } elseif (!preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d$/', $ghtFormValues['time'])) {
            $ghtActionError = 'Choose a valid event time.';
        } elseif ($ghtFormValues['venue'] === '' || strlen($ghtFormValues['venue']) > 240) {
            $ghtActionError = 'Enter an event venue (up to 240 characters).';
        } elseif (strlen($ghtFormValues['description']) > 1000) {
            $ghtActionError = 'Keep the description to 1,000 characters or fewer.';
        } elseif (!ctype_digit($ghtFormValues['expected_guests'])
            || (int) $ghtFormValues['expected_guests'] < 1
            || (int) $ghtFormValues['expected_guests'] > 100000
        ) {
            $ghtActionError = 'Expected guests must be a whole number between 1 and 100,000.';
        } else {
            $ghtEvents[$ghtEventIndex]['title'] = $ghtFormValues['title'];
            $ghtEvents[$ghtEventIndex]['host_names'] = $ghtFormValues['host_names'];
            $ghtEvents[$ghtEventIndex]['description'] = $ghtFormValues['description'];
            $ghtEvents[$ghtEventIndex]['date'] = $ghtFormValues['date'];
            $ghtEvents[$ghtEventIndex]['time'] = $ghtFormValues['time'];
            $ghtEvents[$ghtEventIndex]['venue'] = $ghtFormValues['venue'];
            $ghtEvents[$ghtEventIndex]['expected_guests'] = (int) $ghtFormValues['expected_guests'];
            $ghtEvents[$ghtEventIndex]['enforce_guest_limit'] = !$ghtFormValues['ignore_guest_limit'];
            $ghtEvents[$ghtEventIndex]['features'] = [];

            foreach (GHT_FEATURE_KEYS as $ghtFeatureKey) {
                $ghtAllowedByPlan = (bool) ($ghtPlan['features'][$ghtFeatureKey] ?? false);
                $ghtRequestedEnabled = isset($_POST['features'][$ghtFeatureKey]);
                $ghtEvents[$ghtEventIndex]['features'][$ghtFeatureKey] = $ghtAllowedByPlan && $ghtRequestedEnabled;
            }
        }
    } elseif ($ghtRequestedAction === 'postpone-event' || $ghtRequestedAction === 'cancel-event') {
        $ghtTargetDate = $ghtNewDate;
        $ghtTargetTime = $ghtNewTime;

        if (($ghtEvent['exception'] ?? 'none') === 'cancelled') {
            $ghtActionError = 'A cancelled event cannot be changed. Contact the project administrator to restore it.';
        } elseif ($ghtReason === '' || strlen($ghtReason) > 500) {
            $ghtActionError = 'Enter a reason between 1 and 500 characters.';
        } elseif ($ghtRequestedAction === 'postpone-event'
            && (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $ghtTargetDate)
                || ghtFormatDate($ghtTargetDate) === 'Date not set'
                || $ghtTargetDate <= date('Y-m-d'))
        ) {
            $ghtActionError = 'Choose a valid new date in the future.';
        } elseif ($ghtRequestedAction === 'postpone-event'
            && !preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d$/', $ghtTargetTime)
        ) {
            $ghtActionError = 'Choose a valid new time.';
        } else {
            $ghtOriginalEvent = $ghtEvents[$ghtEventIndex];
            $ghtOriginalNotifications = $ghtNotifications;
            $ghtEvents[$ghtEventIndex]['original_date'] = $ghtEvent['original_date'] ?? $ghtEvent['date'] ?? null;
            $ghtEvents[$ghtEventIndex]['exception'] = $ghtRequestedAction === 'postpone-event' ? 'postponed' : 'cancelled';
            $ghtEvents[$ghtEventIndex]['publication'] = 'unpublished';
            $ghtEvents[$ghtEventIndex]['phase'] = 'pre_event';

            if ($ghtRequestedAction === 'postpone-event') {
                $ghtEvents[$ghtEventIndex]['date'] = $ghtTargetDate;
                $ghtEvents[$ghtEventIndex]['time'] = $ghtTargetTime;
                $ghtEvents[$ghtEventIndex]['postponed_to'] = $ghtTargetDate;
                $ghtNotificationMessage = sprintf(
                    '%s has been rescheduled to %s at %s. Reason: %s',
                    $ghtEvent['title'] ?? 'This event',
                    ghtFormatDate($ghtTargetDate),
                    ghtFormatTime($ghtTargetTime),
                    $ghtReason
                );
                $ghtNotificationType = 'postponement';
            } else {
                $ghtNotificationMessage = sprintf(
                    '%s has been cancelled. Reason: %s',
                    $ghtEvent['title'] ?? 'This event',
                    $ghtReason
                );
                $ghtNotificationType = 'cancellation';
            }

            $ghtNotifications[] = [
                'id' => 'ntf_' . bin2hex(random_bytes(6)),
                'event_id' => $ghtRequestedEventId,
                'type' => $ghtNotificationType,
                'channel' => 'email',
                'message' => $ghtNotificationMessage,
                'reason' => $ghtReason,
                'fee' => GHT_MOCK_NOTIFICATION_FEE,
                'currency' => GHT_MONEY_CURRENCY,
                'status' => 'simulated',
                'created_at' => date(DATE_ATOM),
                'mock' => true,
            ];

            try {
                ghtWriteJson('events.json', $ghtEvents);
                try {
                    ghtWriteJson('notifications.json', $ghtNotifications);
                } catch (RuntimeException $notificationException) {
                    ghtWriteJson('events.json', array_replace($ghtEvents, [$ghtEventIndex => $ghtOriginalEvent]));
                    $ghtNotifications = $ghtOriginalNotifications;
                    throw $notificationException;
                }

                header(
                    'Location: settings.php?event=' . rawurlencode($ghtRequestedEventId)
                        . '&notice=' . rawurlencode($ghtNotificationType),
                    true,
                    303
                );
                exit;
            } catch (RuntimeException $exception) {
                error_log('Foliioo event notification update failed: ' . $exception->getMessage());
                $ghtActionError = 'The event update could not be saved. The original event details were restored; please try again.';
                $ghtEvents[$ghtEventIndex] = $ghtOriginalEvent;
            }
        }
    } else {
        $ghtActionError = 'Choose a valid settings action.';
    }

    if ($ghtActionError === '' && $ghtRequestedAction === 'save-details') {
        try {
            ghtWriteJson('events.json', $ghtEvents);
            header('Location: settings.php?event=' . rawurlencode($ghtRequestedEventId) . '&notice=saved', true, 303);
            exit;
        } catch (RuntimeException $exception) {
            error_log('Foliioo event settings write failed: ' . $exception->getMessage());
            $ghtActionError = 'Settings could not be saved. Your form values are still shown; please try again.';
            $ghtEvents[$ghtEventIndex] = $ghtEvent;
        }
    }

    if ($ghtActionError !== '') {
        $ghtEvent = $ghtEvents[$ghtEventIndex];
    }
}

$ghtEventNotFound = $ghtDataError === '' && $ghtEvent === null;
if ($ghtEventNotFound) {
    http_response_code($ghtRequestedEventId === '' ? 400 : 404);
}

$ghtFeatureValues = is_array($ghtEvent['features'] ?? null) ? $ghtEvent['features'] : [];
if ($_SERVER['REQUEST_METHOD'] === 'POST'
    && $ghtRequestedAction === 'save-details'
    && is_array($_POST['features'] ?? null)
) {
    foreach (GHT_FEATURE_KEYS as $ghtFeatureKey) {
        $ghtFeatureValues[$ghtFeatureKey] = (bool) ($ghtPlan['features'][$ghtFeatureKey] ?? false)
            && isset($_POST['features'][$ghtFeatureKey]);
    }
}
$ghtGuestLimitExceeded = (int) ($ghtEvent['expected_guests'] ?? 0) > 0
    && $ghtConfirmedGuests > (int) $ghtEvent['expected_guests'];
$ghtPageContent = '';
ob_start();
?>
<?php if ($ghtDataError !== ''): ?>
    <?= ghtComponentAlert($ghtDataError, 'error') ?>
<?php elseif ($ghtEventNotFound): ?>
    <?= ghtComponentEmptyState('Event not found', 'Return to your dashboard and choose an event to update.', 'fa-gear', 'Go to dashboard', 'dashboard.php') ?>
<?php else: ?>
    <?php if ($ghtActionError !== ''): ?>
        <div class="mb-5"><?= ghtComponentAlert($ghtActionError, 'error') ?></div>
    <?php elseif ($ghtNotice === 'saved'): ?>
        <div class="mb-5"><?= ghtComponentAlert('Event settings saved.', 'success') ?></div>
    <?php elseif ($ghtNotice === 'postponement'): ?>
        <div class="mb-5"><?= ghtComponentAlert('The event has been postponed. A mock guest notification was added.', 'warning') ?></div>
    <?php elseif ($ghtNotice === 'cancellation'): ?>
        <div class="mb-5"><?= ghtComponentAlert('The event has been cancelled. A mock guest notification was added.', 'success') ?></div>
    <?php endif; ?>

    <?php if ($ghtGuestLimitExceeded): ?>
        <div class="mb-6"><?= ghtComponentAlert(
            'Confirmed guest entries exceed the expected limit of ' . (int) $ghtEvent['expected_guests'] . '. New RSVPs are still accepted because the limit is ignored.',
            'warning'
        ) ?></div>
    <?php endif; ?>

    <div class="settings-intro mb-5">
        <p class="text-xs font-semibold uppercase tracking-[0.12em] text-[#6B675F]">Event configuration</p>
        <h2 class="mt-1 font-display text-2xl font-semibold">Update event details and guest access</h2>
        <p class="mt-2 max-w-2xl text-sm leading-6 text-[#6B675F]">
            Update the event information and choose which guest features are available on the <?= ghtEscape($ghtPlan['name'] ?? 'current') ?> plan.
        </p>
    </div>

    <nav class="settings-jump-links" aria-label="Settings sections">
        <a href="#event-details-heading">Event details</a>
        <a href="#guest-features-heading">Guest access</a>
        <a href="#notification-preview-heading">Guest messages</a>
        <a href="#postpone-heading">Change event date</a>
        <a href="#cancel-heading">Cancel event</a>
    </nav>

    <div class="settings-workspace-grid grid gap-6 xl:grid-cols-[minmax(0,1.35fr)_minmax(300px,0.65fr)]">
        <section class="settings-details-card rounded-xl border border-[#E9E4DD] bg-white p-5 sm:p-7" aria-labelledby="event-details-heading">
            <form method="post" action="settings.php?event=<?= rawurlencode($ghtRequestedEventId) ?>" data-loading>
            <input type="hidden" name="csrf_token" value="<?= ghtEscape($ghtCsrfToken) ?>">
            <p class="text-xs font-semibold uppercase tracking-[0.12em] text-[#6B675F]">Event information</p>
            <h3 id="event-details-heading" class="mt-1 font-display text-2xl font-semibold">Details guests see</h3>
            <div class="mt-6 grid gap-5 sm:grid-cols-2">
                <div class="sm:col-span-2"><?= ghtComponentField('event-title', 'Event title', $ghtFormValues['title'], 'text', '', [
                    'name' => 'title', 'required' => 'true', 'maxlength' => '120',
                ]) ?></div>
                <div><?= ghtComponentField('event-hosts', 'Host names', $ghtFormValues['host_names'], 'text', '', [
                    'name' => 'host_names', 'required' => 'true', 'maxlength' => '180',
                ]) ?></div>
                <div><?= ghtComponentField('event-venue', 'Venue', $ghtFormValues['venue'], 'text', '', [
                    'name' => 'venue', 'required' => 'true', 'maxlength' => '240',
                ]) ?></div>
                <div><?= ghtComponentField('event-date', 'Date', $ghtFormValues['date'], 'date', '', [
                    'name' => 'date', 'required' => 'true',
                ]) ?></div>
                <div><?= ghtComponentField('event-time', 'Time', $ghtFormValues['time'], 'time', '', [
                    'name' => 'time', 'required' => 'true',
                ]) ?></div>
                <div class="sm:col-span-2 space-y-1.5">
                    <label class="block text-sm font-semibold text-[#252321]" for="event-description">Description</label>
                    <textarea class="min-h-28 w-full rounded-lg border border-[#E9E4DD] bg-white px-3 py-2 text-sm focus:border-[#9A5F44] focus:outline-none focus:ring-2 focus:ring-[#9A5F44]/20"
                        id="event-description" name="description" maxlength="1000"><?= ghtEscape($ghtFormValues['description']) ?></textarea>
                    <p class="text-xs text-[#6B675F]">Up to 1,000 characters.</p>
                </div>
            </div>

            <div class="mt-8 border-t border-[#E9E4DD] pt-6">
                <h3 class="font-display text-xl font-semibold">Guest capacity</h3>
                <p class="mt-1 text-sm leading-5 text-[#6B675F]">
                    <?= $ghtConfirmedGuests ?> confirmed guest entries against an expected limit of <?= (int) ($ghtEvent['expected_guests'] ?? 0) ?>.
                </p>
                <div class="mt-4 grid gap-4 sm:grid-cols-[minmax(0,220px)_1fr] sm:items-end">
                    <?= ghtComponentField('expected-guests', 'Expected guests', $ghtFormValues['expected_guests'], 'number', '', [
                        'name' => 'expected_guests', 'required' => 'true', 'min' => '1', 'max' => '100000', 'step' => '1',
                    ]) ?>
                    <label class="flex min-h-11 items-center gap-3 rounded-lg bg-[#F3EDE5] px-4 py-3 text-sm font-medium">
                        <input class="h-4 w-4 accent-[#9A5F44]" type="checkbox" name="ignore_guest_limit" value="1"
                            <?= $ghtFormValues['ignore_guest_limit'] ? 'checked' : '' ?>>
                        Ignore the expected guest limit
                    </label>
                </div>
            </div>

            <div class="mt-8 border-t border-[#E9E4DD] pt-6">
                <div class="flex flex-wrap items-end justify-between gap-3">
                    <div>
                        <h3 id="guest-features-heading" class="font-display text-xl font-semibold">Guest access</h3>
                        <p class="mt-1 text-sm text-[#6B675F]">Locked features stay visible so you can see what's included.</p>
                    </div>
                    <span class="rounded-full bg-[#EEF0EA] px-3 py-1 text-xs font-semibold text-[#59634F]">
                        <?= ghtEscape($ghtPlan['name'] ?? 'Unassigned') ?> plan
                    </span>
                </div>
                <ul class="mt-4 divide-y divide-[#E9E4DD]">
                    <?php foreach (GHT_FEATURE_KEYS as $ghtFeatureKey): ?>
                        <?php
                        $ghtFeatureAllowed = (bool) ($ghtPlan['features'][$ghtFeatureKey] ?? false);
                        $ghtFeatureEnabled = (bool) ($ghtFeatureValues[$ghtFeatureKey] ?? false);
                        ?>
                        <li class="flex items-start justify-between gap-4 py-4">
                            <div>
                                <p class="text-sm font-semibold"><?= ghtEscape(GHT_FEATURE_LABELS[$ghtFeatureKey]) ?></p>
                                <?php if (!$ghtFeatureAllowed): ?>
                                    <p class="mt-1 text-xs text-[#6B675F]">Not included in this plan. Upgrade to unlock this feature.</p>
                                <?php endif; ?>
                            </div>
                            <?php if ($ghtFeatureAllowed): ?>
                                <label class="inline-flex min-h-11 items-center gap-2 text-sm font-medium">
                                    <input class="h-4 w-4 accent-[#9A5F44]" type="checkbox" name="features[<?= ghtEscape($ghtFeatureKey) ?>]" value="1"
                                        <?= $ghtFeatureEnabled ? 'checked' : '' ?>>
                                    <span><?= $ghtFeatureEnabled ? 'On' : 'Off' ?></span>
                                </label>
                            <?php else: ?>
                                <span class="inline-flex min-h-11 items-center gap-2 text-xs font-semibold text-[#6B675F]">
                                    <i class="fa-solid fa-lock" aria-hidden="true"></i>Locked
                                </span>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="mt-6 flex flex-wrap gap-3 border-t border-[#E9E4DD] pt-6">
                <button class="<?= ghtEscape(ghtComponentButtonClasses('primary')) ?>"
                    type="submit" name="action" value="save-details" data-loading-label="Saving…">Save event settings</button>
                <a class="<?= ghtEscape(ghtComponentButtonClasses('secondary')) ?>"
                    href="manage.php?event=<?= rawurlencode($ghtRequestedEventId) ?>">Back to event</a>
            </div>
            </form>
        </section>

        <aside class="settings-side-panel space-y-6">
            <section class="rounded-xl border border-[#E9E4DD] bg-white p-5 sm:p-6" aria-labelledby="notification-preview-heading">
                <p class="text-xs font-semibold uppercase tracking-[0.12em] text-[#6B675F]">Guest communication</p>
                <h3 id="notification-preview-heading" class="mt-1 font-display text-xl font-semibold">Notification preview</h3>
                <div class="mt-4 rounded-lg bg-[#F3EDE5] p-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-[#6B675F]">Sample email</p>
                    <p class="mt-2 text-sm font-semibold"><?= ghtEscape($ghtEvent['title'] ?? '') ?></p>
                    <p class="mt-2 text-sm leading-6 text-[#6B675F]">
                        Event date: <?= ghtEscape(ghtFormatDate($ghtEvent['date'] ?? null)) ?> at <?= ghtEscape(ghtFormatTime($ghtEvent['time'] ?? null)) ?>.
                        We'll share an update if your plans change.
                    </p>
                </div>
                <p class="mt-3 text-xs leading-5 text-[#6B675F]">Notifications are simulated. No email or SMS is sent.</p>
            </section>

            <section class="rounded-xl border border-[#E9E4DD] bg-white p-5 sm:p-6" aria-labelledby="postpone-heading">
                <p class="text-xs font-semibold uppercase tracking-[0.12em] text-[#6B675F]">Change the date</p>
                <h3 id="postpone-heading" class="mt-1 font-display text-xl font-semibold">Postpone event</h3>
                <?php if (($ghtEvent['exception'] ?? 'none') === 'cancelled'): ?>
                    <p class="mt-3 text-sm leading-6 text-[#6B675F]">A cancelled event cannot be postponed in this prototype.</p>
                <?php else: ?>
                    <form class="mt-4 space-y-4" method="post" action="settings.php?event=<?= rawurlencode($ghtRequestedEventId) ?>"
                        data-loading data-confirm-title="Postpone this event?" data-confirm="This will update the event date shown to guests and save a simulated notification.">
                        <input type="hidden" name="csrf_token" value="<?= ghtEscape($ghtCsrfToken) ?>">
                        <input type="hidden" name="action" value="postpone-event">
                        <div><?= ghtComponentField('new-event-date', 'New date', $ghtNewDate !== '' ? $ghtNewDate : (string) ($ghtEvent['postponed_to'] ?? ''), 'date', '', [
                            'name' => 'new_date', 'required' => 'true',
                        ]) ?></div>
                        <div><?= ghtComponentField('new-event-time', 'New time', $ghtNewTime !== '' ? $ghtNewTime : (string) ($ghtEvent['time'] ?? ''), 'time', '', [
                            'name' => 'new_time', 'required' => 'true',
                        ]) ?></div>
                        <div class="space-y-1.5">
                            <label class="block text-sm font-semibold" for="postpone-reason">Reason</label>
                            <textarea class="min-h-20 w-full rounded-lg border border-[#E9E4DD] bg-white px-3 py-2 text-sm focus:border-[#9A5F44] focus:outline-none focus:ring-2 focus:ring-[#9A5F44]/20"
                                id="postpone-reason" name="reason" maxlength="500" required><?= ghtEscape($ghtReason) ?></textarea>
                        </div>
                        <p class="rounded-lg bg-[#F3EDE5] p-3 text-sm leading-5 text-[#6B675F]">
                            Mock notification fee: <strong><?= ghtEscape(ghtFormatNaira(GHT_MOCK_NOTIFICATION_FEE)) ?></strong>
                        </p>
                        <p class="text-xs leading-5 text-[#6B675F]" data-notification-message>
                            The notification preview will include the selected date, time, and reason.
                        </p>
                        <button class="<?= ghtEscape(ghtComponentButtonClasses('primary')) ?>"
                            type="submit" data-loading-label="Saving…">Postpone event</button>
                    </form>
                <?php endif; ?>
            </section>

            <section class="rounded-xl border border-[#E9E4DD] bg-white p-5 sm:p-6" aria-labelledby="cancel-heading">
                <p class="text-xs font-semibold uppercase tracking-[0.12em] text-[#B54747]">Sensitive action</p>
                <h3 id="cancel-heading" class="mt-1 font-display text-xl font-semibold">Cancel event</h3>
                <?php if (($ghtEvent['exception'] ?? 'none') === 'cancelled'): ?>
                    <p class="mt-3 text-sm leading-6 text-[#6B675F]">This event is cancelled. Its event page is unpublished.</p>
                <?php else: ?>
                    <form class="mt-4 space-y-4" method="post" action="settings.php?event=<?= rawurlencode($ghtRequestedEventId) ?>"
                        data-loading data-confirm-title="Cancel this event?" data-confirm="The public event page will be unpublished and a simulated notification will be saved.">
                        <input type="hidden" name="csrf_token" value="<?= ghtEscape($ghtCsrfToken) ?>">
                        <input type="hidden" name="action" value="cancel-event">
                        <div class="space-y-1.5">
                            <label class="block text-sm font-semibold" for="cancel-reason">Reason</label>
                            <textarea class="min-h-20 w-full rounded-lg border border-[#E9E4DD] bg-white px-3 py-2 text-sm focus:border-[#9A5F44] focus:outline-none focus:ring-2 focus:ring-[#9A5F44]/20"
                                id="cancel-reason" name="reason" maxlength="500" required><?= ghtEscape($ghtReason) ?></textarea>
                        </div>
                        <p class="text-xs leading-5 text-[#6B675F]">A sample guest notification will be saved. No email or SMS is sent.</p>
                        <button class="<?= ghtEscape(ghtComponentButtonClasses('danger')) ?>"
                            type="submit" data-loading-label="Saving…">Cancel event</button>
                    </form>
                <?php endif; ?>
            </section>
        </aside>
    </div>
<?php endif; ?>
<?php
$ghtPageContent = (string) ob_get_clean();
ghtRenderPage('Settings', 'settings', $ghtEvent, $ghtPageContent, 'assets/js/ops/settings.js');

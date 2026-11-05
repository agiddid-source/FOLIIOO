<?php
/**
 * manage.php (Module 5)
 * Summarizes one event and confirms phase changes before saving them.
 * Includes: bootstrap.php, dashboard-shell.php.
 * Reads: events.json, guests.json, donations.json, programme.json.
 * Writes: events.json through ghtWriteJson(). Animations: none.
 */

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

if (session_status() === PHP_SESSION_NONE && !session_start()) {
    throw new RuntimeException('The event-management session could not be started.');
}

if (!isset($_SESSION['ght_csrf_token']) || !is_string($_SESSION['ght_csrf_token'])) {
    $_SESSION['ght_csrf_token'] = bin2hex(random_bytes(32));
}

$ghtCsrfToken = $_SESSION['ght_csrf_token'];
$ghtRequestedEventId = filter_input(INPUT_GET, 'event', FILTER_UNSAFE_RAW);
$ghtRequestedEventId = is_string($ghtRequestedEventId) ? trim($ghtRequestedEventId) : '';
$ghtDataError = '';
$ghtEvents = [];
$ghtGuests = [];
$ghtDonations = [];
$ghtProgramme = [];
$ghtEventIndex = null;
$ghtEvent = null;
$ghtConfirmPhase = '';
$ghtActionError = '';
$ghtPhaseUpdated = filter_input(INPUT_GET, 'updated', FILTER_VALIDATE_INT) === 1;

try {
    $ghtEvents = ghtReadJson('events.json');
    $ghtGuests = ghtReadJson('guests.json');
    $ghtDonations = ghtReadJson('donations.json');
    $ghtProgramme = ghtReadJson('programme.json');
} catch (RuntimeException $exception) {
    error_log('Foliioo event management data load failed: ' . $exception->getMessage());
    $ghtDataError = 'We could not load this event. Check that the data files are available, then reload the page.';
}

if ($ghtDataError === '' && $ghtRequestedEventId !== '') {
    foreach ($ghtEvents as $ghtIndex => $ghtEventRecord) {
        if (is_array($ghtEventRecord)
            && isset($ghtEventRecord['id'])
            && hash_equals((string) $ghtEventRecord['id'], $ghtRequestedEventId)
        ) {
            $ghtEventIndex = $ghtIndex;
            $ghtEvent = $ghtEventRecord;
            break;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $ghtEvent !== null && $ghtDataError === '') {
    $ghtSubmittedToken = $_POST['csrf_token'] ?? '';
    $ghtAction = $_POST['action'] ?? '';
    $ghtNextPhase = $_POST['next_phase'] ?? '';

    if (!is_string($ghtSubmittedToken) || !hash_equals($ghtCsrfToken, $ghtSubmittedToken)) {
        $ghtActionError = 'Your session expired or the form was invalid. Reload this page and try again.';
    } elseif (!is_string($ghtAction) || !is_string($ghtNextPhase)
        || !in_array($ghtNextPhase, GHT_EVENT_PHASES, true)
    ) {
        $ghtActionError = 'Choose a valid event phase before continuing.';
    } elseif (($ghtEvent['exception'] ?? 'none') !== 'none') {
        $ghtActionError = 'A postponed or cancelled event cannot change phase until its event status is resolved.';
    } elseif ($ghtNextPhase === ($ghtEvent['phase'] ?? 'pre_event')) {
        $ghtActionError = 'This event is already in that phase.';
    } elseif ($ghtAction === 'review-phase') {
        $ghtConfirmPhase = $ghtNextPhase;
    } elseif ($ghtAction === 'confirm-phase') {
        $ghtEvents[$ghtEventIndex]['phase'] = $ghtNextPhase;

        try {
            ghtWriteJson('events.json', $ghtEvents);
            $ghtRedirectUrl = 'manage.php?event=' . rawurlencode($ghtRequestedEventId) . '&updated=1';
            header('Location: ' . $ghtRedirectUrl, true, 303);
            exit;
        } catch (RuntimeException $exception) {
            error_log('Foliioo event phase write failed: ' . $exception->getMessage());
            $ghtEvents[$ghtEventIndex]['phase'] = $ghtEvent['phase'];
            $ghtActionError = 'We could not save the phase change. Your event is unchanged; please try again.';
            $ghtConfirmPhase = $ghtNextPhase;
        }
    } else {
        $ghtActionError = 'That event action is not available.';
    }
}

$ghtEventNotFound = $ghtDataError === '' && $ghtEvent === null;

if ($ghtEventNotFound) {
    http_response_code($ghtRequestedEventId === '' ? 400 : 404);
}

$ghtConfirmedGuests = 0;
$ghtRsvpEntries = 0;
$ghtCheckedInGuests = 0;
$ghtPartySize = 0;
$ghtDonationTotal = 0;
$ghtCurrentProgrammeItem = null;

if ($ghtEvent !== null) {
    foreach ($ghtGuests as $ghtGuest) {
        if (!is_array($ghtGuest) || ($ghtGuest['event_id'] ?? '') !== $ghtRequestedEventId) {
            continue;
        }

        $ghtRsvpEntries++;
        if (($ghtGuest['response'] ?? '') !== 'attending') {
            continue;
        }

        $ghtConfirmedGuests++;
        $ghtPartySize += max(0, (int) ($ghtGuest['party_size'] ?? 0));
        if ((bool) ($ghtGuest['checked_in'] ?? false)) {
            $ghtCheckedInGuests++;
        }
    }

    foreach ($ghtDonations as $ghtDonation) {
        if (is_array($ghtDonation)
            && ($ghtDonation['event_id'] ?? '') === $ghtRequestedEventId
            && ($ghtDonation['status'] ?? '') === 'successful'
            && (is_int($ghtDonation['amount'] ?? null) || ctype_digit((string) ($ghtDonation['amount'] ?? '')))
        ) {
            $ghtDonationTotal += (int) $ghtDonation['amount'];
        }
    }

    foreach ($ghtProgramme as $ghtProgrammeItem) {
        if (is_array($ghtProgrammeItem)
            && ($ghtProgrammeItem['event_id'] ?? '') === $ghtRequestedEventId
            && ($ghtProgrammeItem['status'] ?? '') === 'in_progress'
        ) {
            $ghtCurrentProgrammeItem = $ghtProgrammeItem;
            break;
        }
    }
}

$ghtAttendanceRate = $ghtConfirmedGuests > 0
    ? (int) round(($ghtCheckedInGuests / $ghtConfirmedGuests) * 100)
    : 0;
$ghtEventStatus = is_array($ghtEvent) ? ghtGetEventStatus($ghtEvent) : 'draft';
$ghtStatusPresentation = ghtGetEventStatusPresentation($ghtEventStatus);
$ghtCurrentPhase = is_array($ghtEvent) && in_array($ghtEvent['phase'] ?? '', GHT_EVENT_PHASES, true)
    ? $ghtEvent['phase']
    : 'pre_event';
$ghtPageTitle = 'Manage event';
$ghtActivePage = 'manage';
$ghtPageScript = 'assets/js/ops/manage.js';
$ghtPageContent = '';
$ghtEventTools = [
    ['label' => 'Programme', 'description' => 'Plan and control the event schedule.', 'path' => 'programme.php', 'icon' => 'fa-list-check'],
    ['label' => 'Guests and RSVPs', 'description' => 'View guest responses and attendance.', 'path' => 'guests.php', 'icon' => 'fa-users'],
    ['label' => 'Check-in', 'description' => 'Welcome guests and record arrivals.', 'path' => 'check-in.php', 'icon' => 'fa-ticket'],
    ['label' => 'Gallery', 'description' => 'Curate event photos and albums.', 'path' => 'gallery.php', 'icon' => 'fa-images'],
    ['label' => 'Settings', 'description' => 'Update event details and guest options.', 'path' => 'settings.php', 'icon' => 'fa-gear'],
    ['label' => 'Edit website', 'description' => 'Update the public event page.', 'path' => 'builder.php', 'icon' => 'fa-globe'],
];

foreach ($ghtEventTools as &$ghtEventTool) {
    $ghtEventTool['available'] = is_file(__DIR__ . DIRECTORY_SEPARATOR . $ghtEventTool['path']);
    $ghtEventTool['href'] = $ghtEventTool['path'] . '?event=' . rawurlencode($ghtRequestedEventId);
}
unset($ghtEventTool);
$ghtPublicEventAddress = is_array($ghtEvent)
    ? 'http://' . ($_SERVER['HTTP_HOST'] ?? 'localhost')
        . rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/')
        . '/event.php?slug=' . rawurlencode((string) ($ghtEvent['slug'] ?? ''))
    : '';
$ghtExpectedGuests = is_array($ghtEvent) ? max(0, (int) ($ghtEvent['expected_guests'] ?? 0)) : 0;
$ghtGuestLimitExceeded = $ghtExpectedGuests > 0 && $ghtConfirmedGuests > $ghtExpectedGuests;

ob_start();
?>
<?php if ($ghtDataError !== ''): ?>
    <section class="rounded-xl border border-[#E9E4DD] bg-white p-6" role="alert">
        <h2 class="font-display text-xl font-semibold">Event data unavailable</h2>
        <p class="mt-2 text-sm leading-6 text-[#6B675F]"><?= ghtEscape($ghtDataError) ?></p>
        <a class="mt-4 inline-flex min-h-11 items-center rounded-lg border border-[#E9E4DD] px-4 py-2 text-sm font-semibold hover:bg-[#FAF8F5]"
            href="dashboard.php">Return to dashboard</a>
    </section>
<?php elseif ($ghtEventNotFound): ?>
    <section class="rounded-xl border border-[#E9E4DD] bg-white p-6" role="alert">
        <span class="flex h-11 w-11 items-center justify-center rounded-full bg-[#F8E6E6] text-[#B54747]" aria-hidden="true">
            <i class="fa-solid fa-circle-exclamation"></i>
        </span>
        <h2 class="mt-4 font-display text-xl font-semibold">
            <?= $ghtRequestedEventId === '' ? 'Choose an event to manage' : 'Event not found' ?>
        </h2>
        <p class="mt-2 text-sm leading-6 text-[#6B675F]">
            <?= $ghtRequestedEventId === ''
                ? 'Open Manage event from an event on your dashboard.'
                : 'That event could not be found. It may have been removed from the sample data.' ?>
        </p>
        <a class="mt-4 inline-flex min-h-11 items-center rounded-lg border border-[#E9E4DD] px-4 py-2 text-sm font-semibold transition-colors hover:bg-[#FAF8F5]"
            href="dashboard.php">Return to dashboard</a>
    </section>
<?php else: ?>
    <?php if ($ghtPhaseUpdated): ?>
        <p class="mb-5 flex items-center gap-2 rounded-lg bg-[#E8F1EA] px-4 py-3 text-sm font-medium text-[#3F6B48]" role="status">
            <i class="fa-solid fa-circle-check" aria-hidden="true"></i>
            Event phase updated successfully.
        </p>
    <?php endif; ?>

    <?php if ($ghtActionError !== ''): ?>
        <p class="mb-5 flex items-start gap-2 rounded-lg bg-[#F8E6E6] px-4 py-3 text-sm leading-5 text-[#8F3939]" role="alert">
            <i class="fa-solid fa-triangle-exclamation mt-0.5" aria-hidden="true"></i>
            <?= ghtEscape($ghtActionError) ?>
        </p>
    <?php endif; ?>

    <section class="manage-primary-grid mb-8 grid gap-6 lg:grid-cols-[minmax(0,1.35fr)_minmax(260px,0.65fr)]">
        <div class="manage-event-card rounded-2xl border border-[#E9E4DD] bg-white p-6 sm:p-8">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <p class="text-sm font-semibold uppercase tracking-[0.12em] text-[#6B675F]">Event overview</p>
                <span class="inline-flex min-h-8 items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold <?= ghtEscape($ghtStatusPresentation['classes']) ?>">
                    <i class="fa-solid <?= ghtEscape($ghtStatusPresentation['icon']) ?>" aria-hidden="true"></i>
                    <?= ghtEscape($ghtStatusPresentation['label']) ?>
                </span>
            </div>
            <h2 class="mt-5 max-w-3xl break-words font-display text-3xl font-semibold leading-tight sm:text-4xl">
                <?= ghtEscape($ghtEvent['title'] ?? 'Untitled event') ?>
            </h2>
            <p class="mt-3 max-w-2xl text-base leading-7 text-[#6B675F]">
                <?= ghtEscape($ghtEvent['description'] ?? 'Event details have not been added yet.') ?>
            </p>
            <dl class="mt-7 grid gap-x-8 gap-y-5 border-t border-[#E9E4DD] pt-6 sm:grid-cols-2">
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-[#6B675F]">Date and time</dt>
                    <dd class="mt-1 text-sm font-semibold">
                        <?= ghtEscape(ghtFormatDate($ghtEvent['date'] ?? null)) ?>
                        <?php if (isset($ghtEvent['time'])): ?>
                            · <?= ghtEscape(ghtFormatTime($ghtEvent['time'])) ?>
                        <?php endif; ?>
                    </dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-[#6B675F]">Host</dt>
                    <dd class="mt-1 text-sm font-semibold"><?= ghtEscape($ghtEvent['host_names'] ?? 'Host not set') ?></dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-xs font-semibold uppercase tracking-wide text-[#6B675F]">Venue</dt>
                    <dd class="mt-1 flex items-start gap-2 text-sm font-semibold">
                        <i class="fa-solid fa-location-dot mt-0.5 text-[#87927B]" aria-hidden="true"></i>
                        <?= ghtEscape($ghtEvent['venue'] ?? 'Venue not set') ?>
                    </dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-xs font-semibold uppercase tracking-wide text-[#6B675F]">Public event address</dt>
                    <dd class="mt-1 break-all font-mono text-sm text-[#6B675F]">
                        <span id="public-event-address"><?= ghtEscape($ghtPublicEventAddress) ?></span>
                        <button class="ml-2 inline-flex min-h-11 items-center gap-2 rounded-lg border border-[#E9E4DD] px-3 py-2 font-sans font-semibold text-[#252321] transition-colors hover:bg-[#FAF8F5] focus-visible:outline focus-visible:outline-2 focus-visible:outline-[#9A5F44]"
                            type="button" data-copy-text="<?= ghtEscape($ghtPublicEventAddress) ?>">
                            <i class="fa-regular fa-copy" aria-hidden="true"></i>Copy
                        </button>
                    </dd>
                </div>
            </dl>
            <p class="mt-2 min-h-5 text-xs text-[#6B675F]" id="copy-status" role="status" aria-live="polite"></p>
        </div>

        <aside class="manage-phase-card rounded-2xl bg-[#252321] p-6 text-white sm:p-7" aria-labelledby="phase-heading">
            <p class="text-xs font-semibold uppercase tracking-[0.14em] text-white/65">Event lifecycle</p>
            <h2 id="phase-heading" class="mt-2 font-display text-2xl font-semibold">Phase control</h2>
            <p class="mt-2 text-sm leading-6 text-white/75">Choose what guests should see for this event now.</p>
            <ol class="mt-6 space-y-3">
                <?php foreach (GHT_EVENT_PHASES as $ghtPhase): ?>
                    <?php $ghtPhaseIsCurrent = $ghtPhase === $ghtCurrentPhase; ?>
                    <li class="flex items-center gap-3 rounded-lg px-3 py-3 <?= $ghtPhaseIsCurrent ? 'bg-white/10' : '' ?>">
                        <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full border <?= $ghtPhaseIsCurrent ? 'border-[#D6A98F] bg-[#9A5F44] text-white' : 'border-white/30 text-white/65' ?>">
                            <?php if ($ghtPhaseIsCurrent): ?>
                                <i class="fa-solid fa-check text-xs" aria-hidden="true"></i>
                            <?php else: ?>
                                <span class="sr-only">Not current: </span>
                                <i class="fa-regular fa-circle text-xs" aria-hidden="true"></i>
                            <?php endif; ?>
                        </span>
                        <span class="text-sm font-semibold"><?= ghtEscape(GHT_EVENT_PHASE_LABELS[$ghtPhase]) ?></span>
                        <?php if ($ghtPhaseIsCurrent): ?>
                            <span class="ml-auto text-xs text-white/65">Current phase</span>
                        <?php endif; ?>

                    </li>
                <?php endforeach; ?>
            </ol>
            <?php if ($ghtGuestLimitExceeded): ?>
                <p class="mt-5 flex items-start gap-2 rounded-lg bg-[#FBF1DC] px-4 py-3 text-sm leading-5 text-[#8F5F12]" role="status">
                    <i class="fa-solid fa-triangle-exclamation mt-0.5" aria-hidden="true"></i>
                    Confirmed guests exceed the expected limit of <?= $ghtExpectedGuests ?>.
                </p>
            <?php endif; ?>
            <?php if (($ghtEvent['exception'] ?? 'none') !== 'none'): ?>
                <p class="mt-5 rounded-lg bg-white/10 p-3 text-sm leading-5 text-white/85">
                    Resolve the <?= ghtEscape((string) $ghtEvent['exception']) ?> event status before changing its phase.
                </p>
            <?php elseif ($ghtConfirmPhase !== ''): ?>
                <div class="mt-5 rounded-xl bg-white p-4 text-[#252321]" role="group" aria-labelledby="confirm-phase-heading">
                    <h3 id="confirm-phase-heading" class="font-semibold">
                        Move event to <?= ghtEscape(GHT_EVENT_PHASE_LABELS[$ghtConfirmPhase]) ?>?
                    </h3>
                    <p class="mt-2 text-sm leading-5 text-[#6B675F]">
                        <?= ghtEscape(GHT_EVENT_PHASE_GUEST_IMPACTS[$ghtConfirmPhase]) ?>
                    </p>
                    <form class="mt-4 flex flex-wrap gap-2" method="post" action="manage.php?event=<?= rawurlencode($ghtRequestedEventId) ?>">
                        <input type="hidden" name="csrf_token" value="<?= ghtEscape($ghtCsrfToken) ?>">
                        <input type="hidden" name="next_phase" value="<?= ghtEscape($ghtConfirmPhase) ?>">
                        <button class="min-h-11 rounded-lg bg-[#9A5F44] px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-[#7E4A33] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#9A5F44]"
                            type="submit" name="action" value="confirm-phase">Confirm phase change</button>
                        <a class="inline-flex min-h-11 items-center rounded-lg border border-[#E9E4DD] px-4 py-2 text-sm font-semibold transition-colors hover:bg-[#FAF8F5]"
                            href="manage.php?event=<?= rawurlencode($ghtRequestedEventId) ?>">Keep current phase</a>
                    </form>
                </div>
            <?php else: ?>
                <form class="mt-5 border-t border-white/15 pt-5" method="post" action="manage.php?event=<?= rawurlencode($ghtRequestedEventId) ?>">
                    <input type="hidden" name="csrf_token" value="<?= ghtEscape($ghtCsrfToken) ?>">
                    <label class="mb-2 block text-sm font-semibold" for="next-phase">Change event phase</label>
                    <select class="min-h-11 w-full rounded-lg border border-white/20 bg-[#343230] px-3 py-2 text-sm text-white focus:border-[#D6A98F] focus:outline-none focus:ring-2 focus:ring-[#D6A98F]/30"
                        id="next-phase" name="next_phase">
                        <?php foreach (GHT_EVENT_PHASES as $ghtPhase): ?>
                            <option value="<?= ghtEscape($ghtPhase) ?>" <?= $ghtPhase === $ghtCurrentPhase ? 'selected disabled' : '' ?>>
                                <?= ghtEscape(GHT_EVENT_PHASE_LABELS[$ghtPhase]) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button class="mt-3 min-h-11 w-full rounded-lg bg-[#9A5F44] px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-[#7E4A33] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white"
                        type="submit" name="action" value="review-phase">Review phase change</button>
                </form>
            <?php endif; ?>
        </aside>
    </section>

    <section class="module-summary mb-8" aria-labelledby="event-stats-heading">
        <div class="mb-4 flex items-end justify-between gap-3">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.12em] text-[#6B675F]">At a glance</p>
                <h2 id="event-stats-heading" class="mt-1 font-display text-2xl font-semibold">Guest and contribution totals</h2>
            </div>
            <span class="hidden text-sm text-[#6B675F] sm:inline">Sample data</span>
        </div>
        <dl class="grid gap-px overflow-hidden rounded-xl border border-[#E9E4DD] bg-[#E9E4DD] sm:grid-cols-2 xl:grid-cols-4">
            <div class="bg-white p-5">
                <dt class="text-sm text-[#6B675F]">RSVP entries</dt>
                <dd class="mt-2 font-display text-3xl font-semibold"><?= $ghtRsvpEntries ?></dd>
            </div>
            <div class="bg-white p-5">
                <dt class="text-sm text-[#6B675F]">Confirmed guests</dt>
                <dd class="mt-2 font-display text-3xl font-semibold"><?= $ghtConfirmedGuests ?></dd>
                <p class="mt-1 text-xs text-[#6B675F]"><?= $ghtPartySize ?> total seats</p>
            </div>
            <div class="bg-white p-5">
                <dt class="text-sm text-[#6B675F]">Checked in</dt>
                <dd class="mt-2 font-display text-3xl font-semibold"><?= $ghtCheckedInGuests ?></dd>
                <p class="mt-1 text-xs text-[#6B675F]"><?= $ghtAttendanceRate ?>% of confirmed entries</p>
            </div>
            <div class="bg-white p-5">
                <dt class="text-sm text-[#6B675F]">Donations</dt>
                <dd class="mt-2 font-display text-3xl font-semibold"><?= ghtEscape(ghtFormatNaira($ghtDonationTotal)) ?></dd>
                <p class="mt-1 text-xs text-[#6B675F]">Successful sample contributions</p>
            </div>
        </dl>
    </section>

    <section aria-labelledby="event-tools-heading">
        <div class="mb-4">
            <p class="text-xs font-semibold uppercase tracking-[0.12em] text-[#6B675F]">Event tools</p>
            <h2 id="event-tools-heading" class="mt-1 font-display text-2xl font-semibold">Choose an event task</h2>
        </div>
        <?php if (is_array($ghtCurrentProgrammeItem)): ?>
            <div class="mb-4 flex items-start gap-3 rounded-xl border border-[#E9E4DD] bg-white p-4">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#F5E9E2] text-[#7E4A33]" aria-hidden="true">
                    <i class="fa-solid fa-list-check"></i>
                </span>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-[#6B675F]">Happening now</p>
                    <p class="mt-1 font-semibold"><?= ghtEscape($ghtCurrentProgrammeItem['title'] ?? 'Programme item') ?></p>
                    <p class="mt-1 text-sm text-[#6B675F]"><?= ghtEscape($ghtCurrentProgrammeItem['time'] ?? '') ?></p>
                </div>
            </div>
        <?php endif; ?>
        <ul class="manage-tools-grid grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
            <?php foreach ($ghtEventTools as $ghtTool): ?>
                <li class="rounded-xl border border-[#E9E4DD] bg-white p-4">
                    <div class="flex items-start gap-3">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-[#F3EDE5] text-[#9A5F44]" aria-hidden="true">
                            <i class="fa-solid <?= ghtEscape($ghtTool['icon']) ?>"></i>
                        </span>
                        <div class="min-w-0">
                            <h3 class="font-semibold"><?= ghtEscape($ghtTool['label']) ?></h3>
                            <p class="mt-1 text-sm leading-5 text-[#6B675F]"><?= ghtEscape($ghtTool['description']) ?></p>
                            <?php if ($ghtTool['available']): ?>
                                <a class="mt-3 inline-flex min-h-11 items-center gap-2 text-sm font-semibold text-[#9A5F44] underline decoration-transparent underline-offset-4 transition duration-200 hover:text-[#7E4A33] hover:decoration-current focus-visible:outline focus-visible:outline-2 focus-visible:outline-[#9A5F44]"
                                    href="<?= ghtEscape($ghtTool['href']) ?>">
                                    Open <?= ghtEscape($ghtTool['label']) ?>
                                    <i class="fa-solid fa-arrow-right text-xs" aria-hidden="true"></i>
                                </a>
                            <?php else: ?>
                                <p class="mt-3 inline-flex min-h-11 items-center text-sm font-medium text-[#6B675F]">
                                    Available when this module is built
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>
<?php endif; ?>
<?php
$ghtPageContent = (string) ob_get_clean();

require __DIR__ . '/includes/dashboard-shell.php';

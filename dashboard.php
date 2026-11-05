<?php
/**
 * dashboard.php (Module 5)
 * Shows workspace totals and searchable, filterable event records.
 * Includes: bootstrap.php, dashboard-shell.php.
 * Reads: events.json, guests.json, donations.json.
 * Writes: nothing. Animations: restrained card and action hover feedback.
 */

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

/**
 * Counts guest records matching an event and optional response/check-in state.
 *
 * @param array<int, mixed> $guests
 * @param array<string, mixed> $event
 */
function ghtDashboardGuestCount(array $guests, array $event, ?string $response = null, ?bool $checkedIn = null): int
{
    $count = 0;
    $eventId = (string) ($event['id'] ?? '');

    foreach ($guests as $guest) {
        if (!is_array($guest) || (string) ($guest['event_id'] ?? '') !== $eventId) {
            continue;
        }

        if ($response !== null && ($guest['response'] ?? '') !== $response) {
            continue;
        }

        if ($checkedIn !== null && (bool) ($guest['checked_in'] ?? false) !== $checkedIn) {
            continue;
        }

        $count++;
    }

    return $count;
}

$ghtDataError = '';
$ghtEvents = [];
$ghtGuests = [];
$ghtDonations = [];

try {
    $ghtEvents = ghtReadJson('events.json');
    $ghtGuests = ghtReadJson('guests.json');
    $ghtDonations = ghtReadJson('donations.json');
} catch (RuntimeException $exception) {
    error_log('Foliioo dashboard data load failed: ' . $exception->getMessage());
    $ghtDataError = 'We could not load your event data. Check that the data files are available, then reload the page.';
}

$ghtSearchInput = filter_input(INPUT_GET, 'q', FILTER_UNSAFE_RAW);
$ghtSearchQuery = is_string($ghtSearchInput) ? trim($ghtSearchInput) : '';
$ghtStatusInput = filter_input(INPUT_GET, 'status', FILTER_UNSAFE_RAW);
$ghtStatusFilter = is_string($ghtStatusInput) ? $ghtStatusInput : 'all';

if ($ghtStatusFilter !== 'all' && !in_array($ghtStatusFilter, GHT_DASHBOARD_STATUS_FILTERS, true)) {
    $ghtStatusFilter = 'all';
}

$ghtEventCounts = [];
$ghtConfirmedCount = 0;
$ghtCheckedInCount = 0;
$ghtRsvpCount = 0;
$ghtDonationTotal = 0;

foreach ($ghtGuests as $ghtGuest) {
    if (!is_array($ghtGuest)) {
        continue;
    }

    $ghtRsvpCount++;

    if (($ghtGuest['response'] ?? '') !== 'attending') {
        continue;
    }

    $ghtConfirmedCount++;

    if ((bool) ($ghtGuest['checked_in'] ?? false)) {
        $ghtCheckedInCount++;
    }
}

foreach ($ghtDonations as $ghtDonation) {
    if (!is_array($ghtDonation) || ($ghtDonation['status'] ?? '') !== 'successful') {
        continue;
    }

    $ghtAmount = $ghtDonation['amount'] ?? 0;

    if (is_int($ghtAmount) || (is_string($ghtAmount) && ctype_digit($ghtAmount))) {
        $ghtDonationTotal += (int) $ghtAmount;
    }
}

foreach ($ghtEvents as $ghtEventRecord) {
    if (!is_array($ghtEventRecord) || !isset($ghtEventRecord['id'])) {
        continue;
    }

    $ghtStatus = ghtGetEventStatus($ghtEventRecord);

    if ($ghtSearchQuery !== '') {
        $ghtSearchableText = strtolower(implode(' ', [
            (string) ($ghtEventRecord['title'] ?? ''),
            (string) ($ghtEventRecord['host_names'] ?? ''),
            (string) ($ghtEventRecord['venue'] ?? ''),
        ]));

        if (strpos($ghtSearchableText, strtolower($ghtSearchQuery)) === false) {
            continue;
        }
    }

    if ($ghtStatusFilter !== 'all' && $ghtStatus !== $ghtStatusFilter) {
        continue;
    }

    $ghtEventCounts[] = [
        'event' => $ghtEventRecord,
        'status' => $ghtStatus,
        'confirmed' => ghtDashboardGuestCount($ghtGuests, $ghtEventRecord, 'attending'),
        'checked_in' => ghtDashboardGuestCount($ghtGuests, $ghtEventRecord, 'attending', true),
        'manage_available' => is_file(__DIR__ . DIRECTORY_SEPARATOR . 'manage.php'),
        'public_available' => is_file(__DIR__ . DIRECTORY_SEPARATOR . 'event.php'),
    ];
}

usort($ghtEventCounts, static function (array $first, array $second): int {
    $statusOrder = [
        'live' => 0,
        'published' => 1,
        'draft' => 2,
        'unpublished' => 3,
        'postponed' => 4,
        'completed' => 5,
        'cancelled' => 6,
    ];
    $firstOrder = $statusOrder[$first['status']] ?? count($statusOrder);
    $secondOrder = $statusOrder[$second['status']] ?? count($statusOrder);

    if ($firstOrder !== $secondOrder) {
        return $firstOrder <=> $secondOrder;
    }

    return strcmp(
        (string) ($first['event']['date'] ?? ''),
        (string) ($second['event']['date'] ?? '')
    );
});

$ghtNextEvent = null;
$ghtToday = new DateTimeImmutable('today');

foreach ($ghtEvents as $ghtCandidateEvent) {
    if (!is_array($ghtCandidateEvent)
        || ($ghtCandidateEvent['publication'] ?? '') !== 'published'
        || ($ghtCandidateEvent['phase'] ?? '') !== 'pre_event'
        || ($ghtCandidateEvent['exception'] ?? 'none') !== 'none'
    ) {
        continue;
    }

    $ghtCandidateDate = DateTimeImmutable::createFromFormat('!Y-m-d', (string) ($ghtCandidateEvent['date'] ?? ''));

    if ($ghtCandidateDate === false || $ghtCandidateDate < $ghtToday) {
        continue;
    }

    if ($ghtNextEvent === null || $ghtCandidateDate < $ghtNextEvent['date_object']) {
        $ghtNextEvent = [
            'event' => $ghtCandidateEvent,
            'date_object' => $ghtCandidateDate,
        ];
    }
}

$ghtAttendanceRate = $ghtConfirmedCount > 0
    ? (int) round(($ghtCheckedInCount / $ghtConfirmedCount) * 100)
    : 0;
$ghtPageTitle = 'Dashboard';
$ghtActivePage = 'dashboard';
$ghtEvent = null;

ob_start();
?>
<?php if ($ghtDataError !== ''): ?>
    <section class="rounded-xl border border-[#E9E4DD] bg-white p-6" role="alert">
        <div class="flex items-start gap-4">
            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-[#F8E6E6] text-[#B54747]" aria-hidden="true">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </span>
            <div>
                <h2 class="font-display text-xl font-semibold">Dashboard data unavailable</h2>
                <p class="mt-2 text-sm leading-6 text-[#6B675F]"><?= ghtEscape($ghtDataError) ?></p>
                <a class="mt-4 inline-flex min-h-11 items-center rounded-lg border border-[#E9E4DD] px-4 py-2 text-sm font-semibold text-[#252321] transition duration-200 hover:-translate-y-0.5 hover:bg-[#FAF8F5] active:translate-y-px focus-visible:outline focus-visible:outline-2 focus-visible:outline-[#9A5F44]"
                    href="dashboard.php">Reload dashboard</a>
            </div>
        </div>
    </section>
<?php else: ?>
    <section class="dashboard-intro" aria-label="Dashboard summary">
        <p>See what is happening across your events. Choose an event to manage its programme, guests, check-in, gallery, and settings.</p>
        <span class="dashboard-event-count"><i class="fa-regular fa-calendar" aria-hidden="true"></i> <?= count($ghtEvents) ?> <?= count($ghtEvents) === 1 ? 'event' : 'events' ?></span>
    </section>

    <?php if (is_array($ghtNextEvent)): ?>
        <?php $ghtNextEventRecord = $ghtNextEvent['event']; ?>
        <section class="dashboard-next-event mb-8 grid overflow-hidden rounded-2xl border border-[#E9E4DD] bg-[#F3EDE5] md:grid-cols-[minmax(0,1fr)_auto] md:items-center"
            aria-labelledby="next-event-heading">
            <div class="p-6 sm:p-8">
                <p class="text-xs font-semibold uppercase tracking-[0.14em] text-[#6B675F]">Next published event</p>
                <h2 id="next-event-heading" class="mt-2 max-w-3xl break-words font-display text-2xl font-semibold">
                    <?= ghtEscape($ghtNextEventRecord['title'] ?? 'Upcoming event') ?>
                </h2>
                <p class="mt-2 text-sm leading-6 text-[#6B675F]">
                    <?= ghtEscape(ghtFormatDate($ghtNextEventRecord['date'] ?? null)) ?>
                    <?php if (isset($ghtNextEventRecord['time'])): ?>
                        · <?= ghtEscape(ghtFormatTime($ghtNextEventRecord['time'])) ?>
                    <?php endif; ?>
                    <span aria-hidden="true">·</span>
                    <?= ghtEscape($ghtNextEventRecord['venue'] ?? 'Venue not set') ?>
                </p>
            </div>
            <div class="border-t border-[#E9E4DD] p-6 md:border-l md:border-t-0 md:px-8">
                <?php if (is_file(__DIR__ . DIRECTORY_SEPARATOR . 'manage.php')): ?>
                    <a class="inline-flex min-h-12 w-full items-center justify-center gap-2 rounded-lg bg-[#9A5F44] px-5 py-3 text-sm font-semibold text-white transition duration-200 hover:-translate-y-0.5 hover:bg-[#7E4A33] active:translate-y-px focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#9A5F44] md:w-auto"
                        href="manage.php?event=<?= rawurlencode((string) $ghtNextEventRecord['id']) ?>">
                        Open event
                        <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                    </a>
                <?php else: ?>
                    <span class="inline-flex min-h-12 items-center text-sm font-medium text-[#6B675F]">Event tools unavailable</span>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>

    <section class="mb-10" aria-label="Workspace summary">
        <dl class="grid gap-px overflow-hidden rounded-xl border border-[#E9E4DD] bg-[#E9E4DD] sm:grid-cols-2 xl:grid-cols-4">
            <div class="bg-white p-5">
                <dt class="flex items-center gap-2 text-sm font-medium text-[#6B675F]">
                    <i class="fa-regular fa-calendar text-[#9A5F44]" aria-hidden="true"></i>
                    Events
                </dt>
                <dd class="mt-3 font-display text-3xl font-semibold"><?= count($ghtEvents) ?></dd>
                <p class="mt-1 text-sm text-[#6B675F]">Across every event state</p>
            </div>
            <div class="bg-white p-5">
                <dt class="flex items-center gap-2 text-sm font-medium text-[#6B675F]">
                    <i class="fa-solid fa-users text-[#87927B]" aria-hidden="true"></i>
                    RSVP entries
                </dt>
                <dd class="mt-3 font-display text-3xl font-semibold"><?= $ghtRsvpCount ?></dd>
                <p class="mt-1 text-sm text-[#6B675F]"><?= $ghtConfirmedCount ?> confirmed guest entries</p>
            </div>
            <div class="bg-white p-5">
                <dt class="flex items-center gap-2 text-sm font-medium text-[#6B675F]">
                    <i class="fa-solid fa-hand-holding-heart text-[#9A5F44]" aria-hidden="true"></i>
                    Donations received
                </dt>
                <dd class="mt-3 font-display text-3xl font-semibold"><?= ghtEscape(ghtFormatNaira($ghtDonationTotal)) ?></dd>
                <p class="mt-1 text-sm text-[#6B675F]">Successful mock contributions</p>
            </div>
            <div class="bg-white p-5">
                <dt class="flex items-center gap-2 text-sm font-medium text-[#6B675F]">
                    <i class="fa-solid fa-door-open text-[#87927B]" aria-hidden="true"></i>
                    Attendance
                </dt>
                <dd class="mt-4 font-display text-3xl font-semibold"><?= $ghtCheckedInCount ?><span class="text-xl font-medium text-[#6B675F]"> / <?= $ghtConfirmedCount ?></span></dd>
                <p class="mt-1 text-sm text-[#6B675F]"><?= $ghtAttendanceRate ?>% of confirmed entries checked in</p>
                <progress class="ghost-progress mt-3 block h-1.5 w-full overflow-hidden rounded-full" max="100" value="<?= $ghtAttendanceRate ?>"
                    aria-label="<?= $ghtAttendanceRate ?> percent of confirmed guest entries checked in"><?= $ghtAttendanceRate ?>%</progress>
            </div>
        </dl>
    </section>

    <section aria-labelledby="events-heading">
        <div class="mb-5 flex flex-col gap-4 border-b border-[#E9E4DD] pb-5 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.12em] text-[#6B675F]">Event directory</p>
                <h2 id="events-heading" class="mt-1 font-display text-2xl font-semibold">All events</h2>
            </div>
            <form class="grid gap-3 lg:grid-cols-[minmax(220px,1fr)_200px_auto]" action="dashboard.php" method="get">
                <div>
                    <label class="mb-1.5 block text-xs font-semibold text-[#6B675F]" for="event-search">Search events</label>
                    <div class="relative">
                        <i class="fa-solid fa-magnifying-glass pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-[#6B675F]" aria-hidden="true"></i>
                        <input class="min-h-11 w-full rounded-lg border border-[#E9E4DD] bg-white py-2 pl-10 pr-3 text-sm text-[#252321] placeholder:text-[#6B675F] focus:border-[#9A5F44] focus:outline-none focus:ring-2 focus:ring-[#9A5F44]/20"
                            id="event-search" name="q" type="search" value="<?= ghtEscape($ghtSearchQuery) ?>"
                            placeholder="Search title, host or venue">
                    </div>
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-semibold text-[#6B675F]" for="event-status">Status</label>
                    <select class="min-h-11 w-full rounded-lg border border-[#E9E4DD] bg-white px-3 py-2 text-sm text-[#252321] focus:border-[#9A5F44] focus:outline-none focus:ring-2 focus:ring-[#9A5F44]/20"
                        id="event-status" name="status">
                        <option value="all" <?= $ghtStatusFilter === 'all' ? 'selected' : '' ?>>All statuses</option>
                        <?php foreach (GHT_DASHBOARD_STATUS_FILTERS as $ghtFilterOption): ?>
                            <?php $ghtFilterPresentation = ghtGetEventStatusPresentation($ghtFilterOption); ?>
                            <option value="<?= ghtEscape($ghtFilterOption) ?>" <?= $ghtStatusFilter === $ghtFilterOption ? 'selected' : '' ?>>
                                <?= ghtEscape($ghtFilterPresentation['label']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button class="min-h-11 rounded-lg border border-[#E9E4DD] bg-white px-4 py-2 text-sm font-semibold text-[#252321] transition duration-200 hover:-translate-y-0.5 hover:bg-[#FAF8F5] active:translate-y-px focus-visible:outline focus-visible:outline-2 focus-visible:outline-[#9A5F44]"
                    type="submit">Apply filters</button>
            </form>
        </div>

        <?php if ($ghtEvents === []): ?>
            <div class="rounded-xl border border-[#E9E4DD] bg-white px-6 py-12 text-center">
                <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-[#EEF0EA] text-[#59634F]" aria-hidden="true">
                    <i class="fa-regular fa-calendar-plus text-lg"></i>
                </span>
                <h3 class="mt-4 font-display text-xl font-semibold">No events yet</h3>
                <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-[#6B675F]">
                    Create an event to start collecting RSVPs and preparing your event workspace.
                </p>
                <?php if (is_file(__DIR__ . DIRECTORY_SEPARATOR . 'builder.php')): ?>
                    <a class="mt-5 inline-flex min-h-11 items-center justify-center gap-2 rounded-lg bg-[#9A5F44] px-5 py-2.5 text-sm font-semibold text-white transition duration-200 hover:-translate-y-0.5 hover:bg-[#7E4A33] active:translate-y-px focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#9A5F44]"
                        href="builder.php"><i class="fa-solid fa-plus" aria-hidden="true"></i>Create an event</a>
                <?php else: ?>
                    <p class="mt-5 text-sm font-semibold text-[#6B675F]">The event builder will be available when its page is added.</p>
                <?php endif; ?>
            </div>
        <?php elseif ($ghtEventCounts === []): ?>
            <div class="rounded-xl border border-[#E9E4DD] bg-white px-6 py-12 text-center" role="status">
                <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-[#F3EDE5] text-[#6B675F]" aria-hidden="true">
                    <i class="fa-solid fa-magnifying-glass text-lg"></i>
                </span>
                <h3 class="mt-4 font-display text-xl font-semibold">No matching events</h3>
                <p class="mt-2 text-sm leading-6 text-[#6B675F]">Try a different search or choose another status.</p>
                <a class="mt-4 inline-flex min-h-11 items-center justify-center rounded-lg px-4 py-2 text-sm font-semibold text-[#9A5F44] underline decoration-transparent underline-offset-4 transition duration-200 hover:decoration-current focus-visible:outline focus-visible:outline-2 focus-visible:outline-[#9A5F44]"
                    href="dashboard.php">Clear search and filters</a>
            </div>
        <?php else: ?>
            <ul class="dashboard-event-list grid gap-4">
                <?php foreach ($ghtEventCounts as $ghtEventRow): ?>
                    <?php
                    $ghtCardEvent = $ghtEventRow['event'];
                    $ghtPresentation = ghtGetEventStatusPresentation($ghtEventRow['status']);
                    $ghtEventId = (string) $ghtCardEvent['id'];
                    $ghtPublicUrl = 'event.php?slug=' . rawurlencode((string) ($ghtCardEvent['slug'] ?? ''));
                    $ghtGuestOverflow = (int) ($ghtCardEvent['expected_guests'] ?? 0) > 0
                        && $ghtEventRow['confirmed'] > (int) $ghtCardEvent['expected_guests'];
                    ?>
                    <li class="dashboard-event-card rounded-xl border border-[#E9E4DD] bg-white p-5 transition duration-200 sm:p-6">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                            <div class="min-w-0">
                                <span class="inline-flex min-h-7 items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold <?= ghtEscape($ghtPresentation['classes']) ?>">
                                    <i class="fa-solid <?= ghtEscape($ghtPresentation['icon']) ?>" aria-hidden="true"></i>
                                    <?= ghtEscape($ghtPresentation['label']) ?>
                                </span>
                                <h3 class="mt-3 break-words font-display text-xl font-semibold leading-snug">
                                    <?= ghtEscape($ghtCardEvent['title'] ?? 'Untitled event') ?>
                                </h3>
                                <p class="mt-1 text-sm text-[#6B675F]"><?= ghtEscape($ghtCardEvent['host_names'] ?? 'Host not set') ?></p>
                            </div>
                            <p class="shrink-0 text-sm font-semibold text-[#252321]">
                                <i class="fa-regular fa-calendar mr-1 text-[#6B675F]" aria-hidden="true"></i>
                                <?= ghtEscape(ghtFormatDate($ghtCardEvent['date'] ?? null)) ?>
                            </p>
                        </div>

                        <p class="mt-4 flex items-start gap-2 text-sm leading-6 text-[#6B675F]">
                            <i class="fa-solid fa-location-dot mt-1 text-[#87927B]" aria-hidden="true"></i>
                            <span><?= ghtEscape($ghtCardEvent['venue'] ?? 'Venue not set') ?></span>
                        </p>

                        <div class="mt-5 grid grid-cols-2 gap-3 border-y border-[#E9E4DD] py-4">
                            <div>
                                <p class="text-xs font-medium text-[#6B675F]">Confirmed</p>
                                <p class="mt-1 text-lg font-semibold"><?= (int) $ghtEventRow['confirmed'] ?></p>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-[#6B675F]">Checked in</p>
                                <p class="mt-1 text-lg font-semibold"><?= (int) $ghtEventRow['checked_in'] ?></p>
                            </div>
                        </div>

                        <?php if ($ghtGuestOverflow): ?>
                            <p class="mt-4 flex items-start gap-2 rounded-lg bg-[#FBF1DC] p-3 text-sm leading-5 text-[#8F5F12]" role="status">
                                <i class="fa-solid fa-triangle-exclamation mt-0.5" aria-hidden="true"></i>
                                Confirmed guests exceed the expected limit of <?= (int) $ghtCardEvent['expected_guests'] ?>.
                            </p>
                        <?php endif; ?>

                        <div class="mt-5 flex flex-wrap items-center gap-3">
                            <?php if ($ghtEventRow['manage_available']): ?>
                                <a class="inline-flex min-h-11 items-center justify-center gap-2 rounded-lg bg-[#9A5F44] px-4 py-2 text-sm font-semibold text-white transition duration-200 hover:-translate-y-0.5 hover:bg-[#7E4A33] active:translate-y-px focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#9A5F44]"
                                    href="manage.php?event=<?= rawurlencode($ghtEventId) ?>">
                                    Manage event
                                    <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                                </a>
                            <?php else: ?>
                                <button class="inline-flex min-h-11 cursor-not-allowed items-center justify-center gap-2 rounded-lg bg-[#9A5F44] px-4 py-2 text-sm font-semibold text-white opacity-60"
                                    type="button" disabled title="The event management page has not been built yet.">
                                    Manage event
                                    <span class="sr-only">Unavailable until the event management page is added.</span>
                                </button>
                            <?php endif; ?>
                            <?php if ($ghtEventRow['public_available']
                                && ($ghtCardEvent['publication'] ?? '') === 'published'
                                && $ghtEventRow['status'] !== 'cancelled'
                            ): ?>
                                <a class="inline-flex min-h-11 items-center justify-center gap-2 rounded-lg px-3 py-2 text-sm font-semibold text-[#6B675F] transition duration-200 hover:text-[#252321] focus-visible:outline focus-visible:outline-2 focus-visible:outline-[#9A5F44]"
                                    href="<?= ghtEscape($ghtPublicUrl) ?>">
                                    View event site
                                    <i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i>
                                </a>
                            <?php else: ?>
                                <span class="inline-flex min-h-11 items-center gap-2 px-3 py-2 text-sm text-[#6B675F]" aria-disabled="true"
                                    title="The public event page is not available in this project yet.">
                                    <i class="fa-solid fa-globe" aria-hidden="true"></i>
                                    Event site not connected
                                </span>
                            <?php endif; ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </section>
<?php endif; ?>
<?php
$ghtPageContent = (string) ob_get_clean();

require __DIR__ . '/includes/dashboard-shell.php';

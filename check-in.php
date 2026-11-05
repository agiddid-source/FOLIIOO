<?php
/**
 * check-in.php (Module 11)
 * Supports guest lookup and touch-friendly pass check-in for one event.
 * Includes: bootstrap.php, shared UI components, dashboard-shell.php.
 * Reads: events.json, guests.json. Writes: guests.json through api/checkin.php.
 * Animations: check-in result transition only.
 */

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$ghtCsrfToken = ghtGetCsrfToken();
$ghtRequestedEventId = filter_input(INPUT_GET, 'event', FILTER_UNSAFE_RAW);
$ghtRequestedEventId = is_string($ghtRequestedEventId) ? trim($ghtRequestedEventId) : '';
$ghtSearchInput = filter_input(INPUT_GET, 'q', FILTER_UNSAFE_RAW);
$ghtSearchQuery = is_string($ghtSearchInput) ? trim($ghtSearchInput) : '';
$ghtEvents = [];
$ghtGuests = [];
$ghtEvent = null;
$ghtDataError = '';
$ghtMatches = [];

try {
    $ghtEvents = ghtReadJson('events.json');
    $ghtEventLookup = $ghtRequestedEventId !== '' ? ghtFindEvent($ghtEvents, $ghtRequestedEventId) : null;
    $ghtEvent = $ghtEventLookup['event'] ?? null;

    if ($ghtEvent !== null) {
        $ghtGuests = ghtReadJson('guests.json');
    }
} catch (RuntimeException $exception) {
    error_log('Foliioo check-in page data load failed: ' . $exception->getMessage());
    $ghtDataError = 'Guest records could not be loaded. Check the data files and reload this page.';
}

$ghtConfirmedCount = 0;
$ghtCheckedInCount = 0;
$ghtOutstandingCount = 0;
$ghtPartySize = 0;
$ghtRecentCheckIns = [];
$ghtEventGuestCount = 0;

if ($ghtEvent !== null && $ghtDataError === '') {
    foreach ($ghtGuests as $ghtGuest) {
        if (!is_array($ghtGuest) || ($ghtGuest['event_id'] ?? '') !== $ghtRequestedEventId) {
            continue;
        }

        $ghtEventGuestCount++;

        if (($ghtGuest['response'] ?? '') !== 'attending') {
            continue;
        }

        $ghtConfirmedCount++;
        $ghtPartySize += max(0, (int) ($ghtGuest['party_size'] ?? 0));

        if ((bool) ($ghtGuest['checked_in'] ?? false)) {
            $ghtCheckedInCount++;
            $ghtRecentCheckIns[] = $ghtGuest;
        } else {
            $ghtOutstandingCount++;
        }

        if ($ghtSearchQuery !== '') {
            $ghtSearchableText = strtolower(implode(' ', [
                (string) ($ghtGuest['name'] ?? ''),
                (string) ($ghtGuest['phone'] ?? ''),
                (string) ($ghtGuest['pass_token'] ?? ''),
            ]));

            if (strpos($ghtSearchableText, strtolower($ghtSearchQuery)) !== false) {
                $ghtMatches[] = $ghtGuest;
            }
        }
    }

    usort($ghtRecentCheckIns, static function (array $first, array $second): int {
        return strcmp((string) ($second['checked_in_at'] ?? ''), (string) ($first['checked_in_at'] ?? ''));
    });
}

$ghtEventNotFound = $ghtDataError === '' && $ghtEvent === null;
if ($ghtEventNotFound) {
    http_response_code($ghtRequestedEventId === '' ? 400 : 404);
}

$ghtPageContent = '';
ob_start();
?>
<?php if ($ghtDataError !== ''): ?>
    <?= ghtComponentAlert($ghtDataError, 'error') ?>
<?php elseif ($ghtEventNotFound): ?>
    <?= ghtComponentEmptyState(
        'Choose an event first',
        'Open Check-in from the event workspace you want to use.',
        'fa-ticket',
        'Go to dashboard',
        'dashboard.php'
    ) ?>
<?php else: ?>
    <?php if (($ghtEvent['phase'] ?? '') !== 'live'): ?>
        <div class="mb-6 flex flex-col gap-3 rounded-xl border border-[#E9E4DD] bg-[#FBF1DC] p-4 text-[#8F5F12] sm:flex-row sm:items-center sm:justify-between">
            <p class="flex items-start gap-3 text-sm leading-5">
                <i class="fa-solid fa-triangle-exclamation mt-0.5" aria-hidden="true"></i>
                <span>Event phase is <?= ghtEscape(GHT_EVENT_PHASE_LABELS[$ghtEvent['phase'] ?? 'pre_event'] ?? 'Pre-Event') ?>. Check-in can still be recorded in this prototype.</span>
            </p>
            <a class="<?= ghtEscape(ghtComponentButtonClasses('secondary')) ?> shrink-0"
                href="manage.php?event=<?= rawurlencode($ghtRequestedEventId) ?>">Open phase control</a>
        </div>
    <?php endif; ?>

    <section class="checkin-primary-grid mb-8 grid gap-5 lg:grid-cols-[minmax(0,1.3fr)_minmax(280px,0.7fr)]">
        <div class="checkin-scan-card rounded-2xl bg-[#252321] p-6 text-white sm:p-8">
            <p class="text-xs font-semibold uppercase tracking-[0.14em] text-white/65">Guest arrival</p>
            <h2 class="mt-2 font-display text-3xl font-semibold">Check in a guest</h2>
            <p class="mt-2 max-w-xl text-sm leading-6 text-white/75">Enter the pass reference below. This prototype simulates a scan; it does not use a camera.</p>
            <form class="mt-6 grid gap-3 sm:grid-cols-[minmax(0,1fr)_auto]" data-checkin data-endpoint="api/checkin.php">
                <input type="hidden" name="event_id" value="<?= ghtEscape($ghtRequestedEventId) ?>">
                <input type="hidden" name="csrf_token" value="<?= ghtEscape($ghtCsrfToken) ?>">
                <label class="sr-only" for="pass-token">Pass reference</label>
                <input class="min-h-12 w-full rounded-lg border border-white/20 bg-white px-4 py-3 text-base font-semibold uppercase tracking-wider text-[#252321] placeholder:font-normal placeholder:normal-case placeholder:tracking-normal focus:border-[#D6A98F] focus:outline-none focus:ring-2 focus:ring-[#D6A98F]/30"
                    id="pass-token" name="pass_token" type="text" inputmode="text" autocomplete="off"
                    placeholder="For example, FOL-7K2M9Q" maxlength="20" required>
                <button class="<?= ghtEscape(ghtComponentButtonClasses('primary', 'lg')) ?> min-w-36"
                    type="submit" data-loading-label="Checking…">Simulate scan</button>
            </form>
            <div class="mt-4 hidden rounded-lg border border-white/20 bg-white/10 p-4 text-sm" data-checkin-result role="status" aria-live="polite"></div>
        </div>

        <aside class="checkin-attendance-card rounded-2xl border border-[#E9E4DD] bg-white p-6">
            <p class="text-xs font-semibold uppercase tracking-[0.12em] text-[#6B675F]">Today's attendance</p>
            <dl class="mt-4 grid grid-cols-2 gap-4">
                <div><dt class="text-sm text-[#6B675F]">Confirmed</dt><dd class="mt-1 font-display text-3xl font-semibold"><?= $ghtConfirmedCount ?></dd></div>
                <div><dt class="text-sm text-[#6B675F]">Checked in</dt><dd class="mt-1 font-display text-3xl font-semibold"><?= $ghtCheckedInCount ?></dd></div>
                <div><dt class="text-sm text-[#6B675F]">Outstanding</dt><dd class="mt-1 text-xl font-semibold"><?= $ghtOutstandingCount ?></dd></div>
                <div><dt class="text-sm text-[#6B675F]">Total party size</dt><dd class="mt-1 text-xl font-semibold"><?= $ghtPartySize ?></dd></div>
            </dl>
            <p class="mt-5 border-t border-[#E9E4DD] pt-4 text-xs leading-5 text-[#6B675F]">
                <?= $ghtEventGuestCount ?> guest records in this event · <?= $ghtConfirmedCount ?> confirmed RSVP entries
            </p>
        </aside>
    </section>

    <section class="checkin-lists-grid grid gap-6 xl:grid-cols-[minmax(0,1fr)_minmax(0,1fr)]">
        <div class="checkin-lookup-panel rounded-xl border border-[#E9E4DD] bg-white p-5 sm:p-6">
            <div class="mb-4">
                <p class="text-xs font-semibold uppercase tracking-[0.12em] text-[#6B675F]">Find a guest</p>
                <h2 class="mt-1 font-display text-2xl font-semibold">Manual lookup</h2>
            </div>
            <form class="mb-4 flex flex-col gap-3 sm:flex-row" action="check-in.php" method="get">
                <input type="hidden" name="event" value="<?= ghtEscape($ghtRequestedEventId) ?>">
                <label class="sr-only" for="guest-lookup">Search name, phone or pass reference</label>
                <input class="min-h-12 flex-1 rounded-lg border border-[#E9E4DD] bg-white px-3 py-2 text-sm focus:border-[#9A5F44] focus:outline-none focus:ring-2 focus:ring-[#9A5F44]/20"
                    id="guest-lookup" name="q" value="<?= ghtEscape($ghtSearchQuery) ?>" type="search" placeholder="Name, phone or pass reference">
                <button class="<?= ghtEscape(ghtComponentButtonClasses('secondary', 'lg')) ?>" type="submit">Search guests</button>
            </form>

            <?php if ($ghtSearchQuery === ''): ?>
                <?= ghtComponentEmptyState('Search the guest list', 'Find a confirmed guest by name, phone number or pass reference.', 'fa-magnifying-glass') ?>
            <?php elseif ($ghtMatches === []): ?>
                <?= ghtComponentEmptyState('No matching guests', 'Check the spelling or pass reference and search again.', 'fa-user-slash') ?>
            <?php else: ?>
                <ul class="divide-y divide-[#E9E4DD] overflow-hidden rounded-xl border border-[#E9E4DD] bg-white">
                    <?php foreach ($ghtMatches as $ghtGuest): ?>
                        <?php $ghtGuestIsCheckedIn = (bool) ($ghtGuest['checked_in'] ?? false); ?>
                        <li class="flex flex-col gap-3 p-4 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <h3 class="font-semibold"><?= ghtEscape($ghtGuest['name'] ?? 'Guest') ?></h3>
                                <p class="mt-1 text-sm text-[#6B675F]"><?= ghtEscape($ghtGuest['phone'] ?? '') ?> · <?= ghtEscape($ghtGuest['pass_token'] ?? '') ?></p>
                                <p class="mt-1 text-xs text-[#6B675F]">
                                    <?= ghtEscape(ucfirst((string) ($ghtGuest['response'] ?? 'pending'))) ?>
                                    · party of <?= max(0, (int) ($ghtGuest['party_size'] ?? 0)) ?>
                                </p>
                            </div>
                            <?php if ($ghtGuestIsCheckedIn): ?>
                                <span class="inline-flex min-h-11 items-center gap-2 rounded-full bg-[#E8F1EA] px-3 text-sm font-semibold text-[#3F6B48]">
                                    <i class="fa-solid fa-circle-check" aria-hidden="true"></i>Checked in
                                </span>
                            <?php elseif (($ghtGuest['response'] ?? '') !== 'attending'): ?>
                                <span class="inline-flex min-h-11 items-center rounded-full bg-[#F3EDE5] px-3 text-sm font-semibold text-[#6B675F]">Not confirmed</span>
                            <?php else: ?>
                                <form data-checkin data-endpoint="api/checkin.php">
                                    <input type="hidden" name="event_id" value="<?= ghtEscape($ghtRequestedEventId) ?>">
                                    <input type="hidden" name="csrf_token" value="<?= ghtEscape($ghtCsrfToken) ?>">
                                    <input type="hidden" name="guest_id" value="<?= ghtEscape($ghtGuest['id'] ?? '') ?>">
                                    <button class="<?= ghtEscape(ghtComponentButtonClasses('primary', 'lg')) ?>"
                                        type="submit" data-loading-label="Checking…">Check in</button>
                                </form>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <aside class="checkin-recent-panel rounded-xl border border-[#E9E4DD] bg-white p-5 sm:p-6">
            <div class="mb-4">
                <p class="text-xs font-semibold uppercase tracking-[0.12em] text-[#6B675F]">Latest arrivals</p>
                <h2 class="mt-1 font-display text-2xl font-semibold">Recent check-ins</h2>
            </div>
            <?php if ($ghtRecentCheckIns === []): ?>
                <?= ghtComponentEmptyState('No arrivals yet', 'Confirmed guest check-ins will appear here.', 'fa-door-closed') ?>
            <?php else: ?>
                <ul class="divide-y divide-[#E9E4DD] overflow-hidden rounded-xl border border-[#E9E4DD] bg-white">
                    <?php foreach (array_slice($ghtRecentCheckIns, 0, 5) as $ghtGuest): ?>
                        <li class="flex items-center justify-between gap-4 p-4">
                            <div class="min-w-0">
                                <p class="truncate font-semibold"><?= ghtEscape($ghtGuest['name'] ?? 'Guest') ?></p>
                                <p class="mt-1 text-sm text-[#6B675F]"><?= ghtEscape($ghtGuest['pass_token'] ?? '') ?></p>
                            </div>
                            <time class="shrink-0 text-sm text-[#6B675F]">
                                <?= isset($ghtGuest['checked_in_at']) ? ghtEscape(date('g:i A', strtotime((string) $ghtGuest['checked_in_at']) ?: 0)) : '' ?>
                            </time>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </aside>
    </section>
<?php endif; ?>
<?php
$ghtPageContent = (string) ob_get_clean();
ghtRenderPage('Check-in', 'check-in', $ghtEvent, $ghtPageContent, 'assets/js/ops/checkin.js');

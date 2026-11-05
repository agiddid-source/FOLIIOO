<?php
/**
 * programme.php (Module 10)
 * Lists and controls one event's ordered programme.
 * Includes: bootstrap.php, shared UI components, dashboard-shell.php.
 * Reads: events.json, programme.json. Writes: both files through JSON helpers.
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
$ghtProgramme = [];
$ghtEvent = null;
$ghtEventIndex = null;
$ghtItems = [];
$ghtFormValues = [
    'title' => '',
    'time' => '',
    'description' => '',
];
$ghtEditItemId = '';

try {
    $ghtEvents = ghtReadJson('events.json');
    $ghtProgramme = ghtReadJson('programme.json');
} catch (RuntimeException $exception) {
    error_log('Foliioo programme data load failed: ' . $exception->getMessage());
    $ghtDataError = 'Programme data could not be loaded. Check the JSON files and reload this page.';
}

$ghtEventLookup = $ghtDataError === '' && $ghtRequestedEventId !== ''
    ? ghtFindEvent($ghtEvents, $ghtRequestedEventId)
    : null;

if ($ghtEventLookup !== null) {
    $ghtEventIndex = $ghtEventLookup['index'];
    $ghtEvent = $ghtEventLookup['event'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $ghtEvent !== null) {
    $ghtAction = $_POST['action'] ?? '';
    $ghtSubmittedToken = $_POST['csrf_token'] ?? '';
    $ghtEditItemId = isset($_POST['item_id']) && is_string($_POST['item_id']) ? trim($_POST['item_id']) : '';
    $ghtFormValues = [
        'title' => isset($_POST['title']) && is_string($_POST['title']) ? trim($_POST['title']) : '',
        'time' => isset($_POST['time']) && is_string($_POST['time']) ? trim($_POST['time']) : '',
        'description' => isset($_POST['description']) && is_string($_POST['description']) ? trim($_POST['description']) : '',
    ];

    if (!ghtValidateCsrfToken($ghtSubmittedToken)) {
        $ghtActionError = 'Your session expired or this form was invalid. Reload the page and try again.';
    } elseif (!is_string($ghtAction)) {
        $ghtActionError = 'Choose a valid programme action.';
    } else {
        $ghtEventItems = array_values(array_filter(
            $ghtProgramme,
            static function ($item) use ($ghtRequestedEventId): bool {
                return is_array($item) && ($item['event_id'] ?? '') === $ghtRequestedEventId;
            }
        ));
        usort($ghtEventItems, static fn(array $first, array $second): int => (int) ($first['order'] ?? 0) <=> (int) ($second['order'] ?? 0));

        if ($ghtAction === 'save-item') {
            $ghtIsEditing = $ghtEditItemId !== '';

            if ($ghtFormValues['title'] === '' || strlen($ghtFormValues['title']) > 120) {
                $ghtActionError = 'Enter a programme title between 1 and 120 characters.';
            } elseif (!preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d$/', $ghtFormValues['time'])) {
                $ghtActionError = 'Enter a valid time using the 24-hour format.';
            } elseif (strlen($ghtFormValues['description']) > 500) {
                $ghtActionError = 'Keep the description to 500 characters or fewer.';
            } else {
                if ($ghtIsEditing) {
                    $ghtItemIndex = null;
                    foreach ($ghtProgramme as $ghtIndex => $ghtItem) {
                        if (is_array($ghtItem)
                            && ($ghtItem['event_id'] ?? '') === $ghtRequestedEventId
                            && ($ghtItem['id'] ?? '') === $ghtEditItemId
                        ) {
                            $ghtItemIndex = $ghtIndex;
                            break;
                        }
                    }

                    if ($ghtItemIndex === null) {
                        $ghtActionError = 'That programme item no longer exists. Reload the page before editing.';
                    } else {
                        $ghtProgramme[$ghtItemIndex]['title'] = $ghtFormValues['title'];
                        $ghtProgramme[$ghtItemIndex]['time'] = $ghtFormValues['time'];
                        $ghtProgramme[$ghtItemIndex]['description'] = $ghtFormValues['description'];
                    }
                } else {
                    $ghtProgramme[] = [
                        'id' => 'prg_' . bin2hex(random_bytes(6)),
                        'event_id' => $ghtRequestedEventId,
                        'order' => count($ghtEventItems) + 1,
                        'time' => $ghtFormValues['time'],
                        'title' => $ghtFormValues['title'],
                        'description' => $ghtFormValues['description'],
                        'status' => 'upcoming',
                        'started_at' => null,
                        'completed_at' => null,
                    ];
                }
            }
        } elseif ($ghtAction === 'remove-item') {
            if ($ghtEditItemId === '') {
                $ghtActionError = 'Choose a programme item to remove.';
            } else {
                $ghtProgramme = array_values(array_filter(
                    $ghtProgramme,
                    static function ($item) use ($ghtRequestedEventId, $ghtEditItemId): bool {
                        return !(
                            is_array($item)
                            && ($item['event_id'] ?? '') === $ghtRequestedEventId
                            && ($item['id'] ?? '') === $ghtEditItemId
                        );
                    }
                ));
            }
        } elseif ($ghtAction === 'move-item') {
            $ghtDirection = $_POST['direction'] ?? '';
            $ghtTargetPosition = null;

            foreach ($ghtEventItems as $ghtPosition => $ghtItem) {
                if (($ghtItem['id'] ?? '') === $ghtEditItemId) {
                    $ghtTargetPosition = $ghtPosition;
                    break;
                }
            }

            if ($ghtTargetPosition === null || !in_array($ghtDirection, ['up', 'down'], true)) {
                $ghtActionError = 'Choose a valid item and order action.';
            } else {
                $ghtSwapPosition = $ghtTargetPosition + ($ghtDirection === 'up' ? -1 : 1);
                if (isset($ghtEventItems[$ghtSwapPosition])) {
                    [$ghtEventItems[$ghtTargetPosition], $ghtEventItems[$ghtSwapPosition]] =
                        [$ghtEventItems[$ghtSwapPosition], $ghtEventItems[$ghtTargetPosition]];
                    foreach ($ghtProgramme as $ghtIndex => $ghtItem) {
                        foreach ($ghtEventItems as $ghtOrder => $ghtOrderedItem) {
                            if (($ghtItem['id'] ?? '') === ($ghtOrderedItem['id'] ?? '')) {
                                $ghtProgramme[$ghtIndex]['order'] = $ghtOrder + 1;
                            }
                        }
                    }
                }
            }
        } elseif ($ghtAction === 'start-next') {
            $ghtHasCurrentItem = false;
            foreach ($ghtEventItems as $ghtItem) {
                if (($ghtItem['status'] ?? '') === 'in_progress') {
                    $ghtHasCurrentItem = true;
                    break;
                }
            }

            $ghtNextItemId = '';
            foreach ($ghtEventItems as $ghtItem) {
                if (($ghtItem['status'] ?? '') === 'upcoming') {
                    $ghtNextItemId = (string) ($ghtItem['id'] ?? '');
                    break;
                }
            }

            if ((bool) ($ghtEvent['programme_paused'] ?? false)) {
                $ghtActionError = 'Resume the programme before starting the next item.';
            } elseif ($ghtHasCurrentItem) {
                $ghtActionError = 'Complete the current item before starting another.';
            } elseif ($ghtNextItemId === '') {
                $ghtActionError = 'There are no upcoming programme items to start.';
            } else {
                foreach ($ghtProgramme as $ghtIndex => $ghtItem) {
                    if (($ghtItem['event_id'] ?? '') === $ghtRequestedEventId && ($ghtItem['id'] ?? '') === $ghtNextItemId) {
                        $ghtProgramme[$ghtIndex]['status'] = 'in_progress';
                        $ghtProgramme[$ghtIndex]['started_at'] = date(DATE_ATOM);
                        break;
                    }
                }
            }
        } elseif ($ghtAction === 'complete-current') {
            $ghtCurrentId = '';
            foreach ($ghtEventItems as $ghtItem) {
                if (($ghtItem['status'] ?? '') === 'in_progress') {
                    $ghtCurrentId = (string) ($ghtItem['id'] ?? '');
                    break;
                }
            }

            if ($ghtCurrentId === '') {
                $ghtActionError = 'There is no programme item in progress.';
            } else {
                foreach ($ghtProgramme as $ghtIndex => $ghtItem) {
                    if (($ghtItem['event_id'] ?? '') === $ghtRequestedEventId && ($ghtItem['id'] ?? '') === $ghtCurrentId) {
                        $ghtProgramme[$ghtIndex]['status'] = 'completed';
                        $ghtProgramme[$ghtIndex]['completed_at'] = date(DATE_ATOM);
                        break;
                    }
                }

                if (!(bool) ($ghtEvent['programme_paused'] ?? false)) {
                    foreach ($ghtEventItems as $ghtItem) {
                        if (($ghtItem['status'] ?? '') === 'upcoming') {
                            foreach ($ghtProgramme as $ghtIndex => $ghtStoredItem) {
                                if (($ghtStoredItem['event_id'] ?? '') === $ghtRequestedEventId
                                    && ($ghtStoredItem['id'] ?? '') === ($ghtItem['id'] ?? '')
                                ) {
                                    $ghtProgramme[$ghtIndex]['status'] = 'in_progress';
                                    $ghtProgramme[$ghtIndex]['started_at'] = date(DATE_ATOM);
                                    break 2;
                                }
                            }
                        }
                    }
                }
            }
        } elseif ($ghtAction === 'toggle-pause') {
            $ghtEvents[$ghtEventIndex]['programme_paused'] = !(bool) ($ghtEvent['programme_paused'] ?? false);
        } else {
            $ghtActionError = 'That programme action is not available.';
        }

        if ($ghtActionError === '') {
            $ghtProgrammeByOrder = [];
            foreach ($ghtProgramme as $ghtIndex => $ghtItem) {
                if (is_array($ghtItem) && ($ghtItem['event_id'] ?? '') === $ghtRequestedEventId) {
                    $ghtProgrammeByOrder[] = ['index' => $ghtIndex, 'item' => $ghtItem];
                }
            }
            usort($ghtProgrammeByOrder, static fn(array $first, array $second): int => (int) ($first['item']['order'] ?? 0) <=> (int) ($second['item']['order'] ?? 0));
            foreach ($ghtProgrammeByOrder as $ghtOrder => $ghtOrderedRow) {
                $ghtProgramme[$ghtOrderedRow['index']]['order'] = $ghtOrder + 1;
            }

            try {
                ghtWriteJson('programme.json', $ghtProgramme);
                if ($ghtAction === 'toggle-pause') {
                    ghtWriteJson('events.json', $ghtEvents);
                }
                header('Location: programme.php?event=' . rawurlencode($ghtRequestedEventId) . '&notice=saved', true, 303);
                exit;
            } catch (RuntimeException $exception) {
                error_log('Foliioo programme write failed: ' . $exception->getMessage());
                $ghtActionError = 'The programme could not be saved. Please try again; your form values are still shown.';
            }
        }
    }

    $ghtItems = array_values(array_filter(
        $ghtProgramme,
        static function ($item) use ($ghtRequestedEventId): bool {
            return is_array($item) && ($item['event_id'] ?? '') === $ghtRequestedEventId;
        }
    ));
} elseif ($ghtEvent !== null) {
    $ghtItems = array_values(array_filter(
        $ghtProgramme,
        static function ($item) use ($ghtRequestedEventId): bool {
            return is_array($item) && ($item['event_id'] ?? '') === $ghtRequestedEventId;
        }
    ));
}

usort($ghtItems, static fn(array $first, array $second): int => (int) ($first['order'] ?? 0) <=> (int) ($second['order'] ?? 0));

if ($ghtEditItemId !== '' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    foreach ($ghtItems as $ghtItem) {
        if (($ghtItem['id'] ?? '') === $ghtEditItemId) {
            $ghtFormValues = [
                'title' => (string) ($ghtItem['title'] ?? ''),
                'time' => (string) ($ghtItem['time'] ?? ''),
                'description' => (string) ($ghtItem['description'] ?? ''),
            ];
            break;
        }
    }
}

$ghtEventNotFound = $ghtDataError === '' && $ghtEvent === null;
if ($ghtEventNotFound) {
    http_response_code($ghtRequestedEventId === '' ? 400 : 404);
}

$ghtCurrentItem = null;
$ghtUpcomingCount = 0;
foreach ($ghtItems as $ghtItem) {
    if (($ghtItem['status'] ?? '') === 'in_progress') {
        $ghtCurrentItem = $ghtItem;
    }
    if (($ghtItem['status'] ?? '') === 'upcoming') {
        $ghtUpcomingCount++;
    }
}

$ghtPageContent = '';
ob_start();
?>
<?php if ($ghtDataError !== ''): ?>
    <?= ghtComponentAlert($ghtDataError, 'error') ?>
<?php elseif ($ghtEventNotFound): ?>
    <?= ghtComponentEmptyState(
        $ghtRequestedEventId === '' ? 'Choose an event first' : 'Event not found',
        'Return to your dashboard and open Programme from the event you want to manage.',
        'fa-circle-exclamation',
        'Go to dashboard',
        'dashboard.php'
    ) ?>
<?php else: ?>
    <?php if ($ghtActionError !== ''): ?>
        <div class="mb-5"><?= ghtComponentAlert($ghtActionError, 'error') ?></div>
    <?php elseif ($ghtNotice === 'saved'): ?>
        <div class="mb-5"><?= ghtComponentAlert('Programme changes saved.', 'success') ?></div>
    <?php endif; ?>

    <?php if ((bool) ($ghtEvent['programme_paused'] ?? false)): ?>
        <div class="mb-6"><?= ghtComponentAlert('Programme paused. Guest-facing schedule will not advance until you resume it.', 'warning') ?></div>
    <?php endif; ?>

    <section class="programme-control-grid mb-8 grid gap-5 lg:grid-cols-[minmax(0,1.3fr)_minmax(260px,0.7fr)]">
        <div class="programme-now-card rounded-2xl border border-[#E9E4DD] bg-white p-6 sm:p-8">
            <p class="text-xs font-semibold uppercase tracking-[0.14em] text-[#6B675F]">Programme control</p>
            <h2 class="mt-2 font-display text-3xl font-semibold">Control the programme</h2>
            <p class="mt-3 max-w-2xl text-sm leading-6 text-[#6B675F]">
                Start an item when it begins and complete it when it ends. Guest-facing status updates with each action.
            </p>
            <?php if (is_array($ghtCurrentItem)): ?>
                <div class="mt-6 border-l-4 border-[#9A5F44] bg-[#F3EDE5] p-5">
                    <p class="text-xs font-semibold uppercase tracking-wide text-[#7E4A33]">Happening now</p>
                    <h3 class="mt-2 font-display text-2xl font-semibold"><?= ghtEscape($ghtCurrentItem['title'] ?? '') ?></h3>
                    <p class="mt-1 text-sm text-[#6B675F]"><?= ghtEscape(ghtFormatTime($ghtCurrentItem['time'] ?? null)) ?></p>
                    <p class="mt-3 text-sm leading-6 text-[#6B675F]"><?= ghtEscape($ghtCurrentItem['description'] ?? '') ?></p>
                </div>
            <?php else: ?>
                <div class="mt-6 border-l-4 border-[#87927B] bg-[#EEF0EA] p-5">
                    <p class="text-sm font-semibold">No item is in progress</p>
                    <p class="mt-1 text-sm leading-6 text-[#6B675F]">
                        <?= $ghtUpcomingCount > 0 ? 'Start the next item when you are ready.' : 'Add programme items to plan the event.' ?>
                    </p>
                </div>
            <?php endif; ?>
            <div class="mt-5 flex flex-wrap gap-3">
                <?php if ($ghtCurrentItem !== null): ?>
                    <form method="post" action="programme.php?event=<?= rawurlencode($ghtRequestedEventId) ?>" data-loading>
                        <input type="hidden" name="csrf_token" value="<?= ghtEscape($ghtCsrfToken) ?>">
                        <button class="<?= ghtEscape(ghtComponentButtonClasses('primary', 'lg')) ?>"
                            type="submit" name="action" value="complete-current" data-loading-label="Saving…">
                            Complete item<?= $ghtUpcomingCount > 0 && !(bool) ($ghtEvent['programme_paused'] ?? false) ? ' and start next' : '' ?>
                        </button>
                    </form>
                <?php elseif ($ghtUpcomingCount > 0 && !(bool) ($ghtEvent['programme_paused'] ?? false)): ?>
                    <form method="post" action="programme.php?event=<?= rawurlencode($ghtRequestedEventId) ?>" data-loading>
                        <input type="hidden" name="csrf_token" value="<?= ghtEscape($ghtCsrfToken) ?>">
                        <button class="<?= ghtEscape(ghtComponentButtonClasses('primary', 'lg')) ?>"
                            type="submit" name="action" value="start-next" data-loading-label="Starting…">Start next item</button>
                    </form>
                <?php endif; ?>
                <form method="post" action="programme.php?event=<?= rawurlencode($ghtRequestedEventId) ?>">
                    <input type="hidden" name="csrf_token" value="<?= ghtEscape($ghtCsrfToken) ?>">
                    <button class="<?= ghtEscape(ghtComponentButtonClasses('secondary')) ?>"
                        type="submit" name="action" value="toggle-pause">
                        <?= (bool) ($ghtEvent['programme_paused'] ?? false) ? 'Resume programme' : 'Pause programme' ?>
                    </button>
                </form>
            </div>
        </div>
        <aside class="programme-count-card rounded-2xl bg-[#252321] p-6 text-white sm:p-7">
            <p class="text-xs font-semibold uppercase tracking-[0.14em] text-white/65">On this event</p>
            <p class="mt-3 font-display text-4xl font-semibold"><?= count($ghtItems) ?></p>
            <p class="text-sm text-white/75">programme items</p>
            <div class="mt-5 border-t border-white/15 pt-5">
                <p class="text-sm leading-6 text-white/80">
                    <?= (int) ($ghtEvent['programme_paused'] ?? false) === 1
                        ? 'The schedule is paused. Resume it when you are ready to continue.'
                        : 'The public schedule updates when you change an item status.' ?>
                </p>
            </div>
        </aside>
    </section>

    <section class="programme-planner-grid grid gap-6 xl:grid-cols-[minmax(0,1.2fr)_minmax(300px,0.8fr)]">
        <div class="programme-timeline">
            <div class="mb-4 flex items-end justify-between gap-3">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.12em] text-[#6B675F]">Event timeline</p>
                    <h2 class="mt-1 font-display text-2xl font-semibold">Programme items</h2>
                </div>
                <span class="text-sm text-[#6B675F]"><?= count($ghtItems) ?> total</span>
            </div>

            <?php if ($ghtItems === []): ?>
                <?= ghtComponentEmptyState(
                    'Your programme starts here',
                    'Add the key moments for the day. You can reorder them whenever plans change.',
                    'fa-list-check'
                ) ?>
            <?php else: ?>
                <ol class="divide-y divide-[#E9E4DD] overflow-hidden rounded-xl border border-[#E9E4DD] bg-white">
                    <?php foreach ($ghtItems as $ghtPosition => $ghtItem): ?>
                        <?php
                        $ghtItemStatus = (string) ($ghtItem['status'] ?? 'upcoming');
                        $ghtItemBadge = [
                            'upcoming' => ['Upcoming', 'bg-[#EEF0EA] text-[#59634F]', 'fa-circle'],
                            'in_progress' => ['Happening now', 'bg-[#F5E9E2] text-[#7E4A33]', 'fa-play'],
                            'completed' => ['Completed', 'bg-[#F3EDE5] text-[#6B675F]', 'fa-check'],
                        ][$ghtItemStatus] ?? ['Upcoming', 'bg-[#EEF0EA] text-[#59634F]', 'fa-circle'];
                        ?>
                        <li class="flex gap-4 p-4 sm:p-5">
                            <div class="w-16 shrink-0 pt-1 text-sm font-semibold text-[#6B675F]">
                                <?= ghtEscape(ghtFormatTime($ghtItem['time'] ?? null)) ?>
                            </div>
                            <div class="min-w-0 flex-1">
                                <span class="inline-flex min-h-7 items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold <?= ghtEscape($ghtItemBadge[1]) ?>">
                                    <i class="fa-solid <?= ghtEscape($ghtItemBadge[2]) ?>" aria-hidden="true"></i><?= ghtEscape($ghtItemBadge[0]) ?>
                                </span>
                                <h3 class="mt-2 break-words font-semibold"><?= ghtEscape($ghtItem['title'] ?? 'Untitled item') ?></h3>
                                <?php if (($ghtItem['description'] ?? '') !== ''): ?>
                                    <p class="mt-1 text-sm leading-5 text-[#6B675F]"><?= ghtEscape($ghtItem['description']) ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="flex shrink-0 flex-wrap items-start justify-end gap-1">
                                <a class="<?= ghtEscape(ghtComponentButtonClasses('ghost', 'sm')) ?>"
                                    href="programme.php?event=<?= rawurlencode($ghtRequestedEventId) ?>&edit=<?= rawurlencode((string) $ghtItem['id']) ?>"
                                    aria-label="Edit <?= ghtEscape($ghtItem['title'] ?? 'programme item') ?>">Edit</a>
                                <form method="post" action="programme.php?event=<?= rawurlencode($ghtRequestedEventId) ?>"
                                    data-confirm="Remove this programme item? This change cannot be undone.">
                                    <input type="hidden" name="csrf_token" value="<?= ghtEscape($ghtCsrfToken) ?>">
                                    <input type="hidden" name="item_id" value="<?= ghtEscape($ghtItem['id'] ?? '') ?>">
                                    <button class="<?= ghtEscape(ghtComponentButtonClasses('ghost', 'sm')) ?>"
                                        type="submit" name="action" value="remove-item">Remove</button>
                                </form>
                                <form class="flex" method="post" action="programme.php?event=<?= rawurlencode($ghtRequestedEventId) ?>">
                                    <input type="hidden" name="csrf_token" value="<?= ghtEscape($ghtCsrfToken) ?>">
                                    <input type="hidden" name="item_id" value="<?= ghtEscape($ghtItem['id'] ?? '') ?>">
                                    <button class="<?= ghtEscape(ghtComponentButtonClasses('secondary', 'sm')) ?>"
                                        type="submit" name="direction" value="up" aria-label="Move <?= ghtEscape($ghtItem['title'] ?? 'item') ?> up"
                                        <?= $ghtPosition === 0 ? 'disabled' : '' ?>><i class="fa-solid fa-arrow-up" aria-hidden="true"></i></button>
                                    <input type="hidden" name="action" value="move-item">
                                    <button class="<?= ghtEscape(ghtComponentButtonClasses('secondary', 'sm')) ?>"
                                        type="submit" name="direction" value="down" aria-label="Move <?= ghtEscape($ghtItem['title'] ?? 'item') ?> down"
                                        <?= $ghtPosition === count($ghtItems) - 1 ? 'disabled' : '' ?>><i class="fa-solid fa-arrow-down" aria-hidden="true"></i></button>
                                </form>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ol>
            <?php endif; ?>
        </div>

        <aside class="programme-item-form h-fit rounded-xl border border-[#E9E4DD] bg-white p-5 sm:p-6">
            <p class="text-xs font-semibold uppercase tracking-[0.12em] text-[#6B675F]">
                <?= $ghtEditItemId !== '' ? 'Edit an item' : 'Build the timeline' ?>
            </p>
            <h2 class="mt-1 font-display text-xl font-semibold">
                <?= $ghtEditItemId !== '' ? 'Update programme item' : 'Add programme item' ?>
            </h2>
            <form class="mt-5 space-y-4" method="post" action="programme.php?event=<?= rawurlencode($ghtRequestedEventId) ?>" data-loading>
                <input type="hidden" name="csrf_token" value="<?= ghtEscape($ghtCsrfToken) ?>">
                <input type="hidden" name="item_id" value="<?= ghtEscape($ghtEditItemId) ?>">
                <div><?= ghtComponentField('programme-title', 'Title', $ghtFormValues['title'], 'text', '', [
                    'name' => 'title',
                    'required' => 'true',
                    'maxlength' => '120',
                    'placeholder' => 'For example, welcome reception',
                ]) ?></div>
                <div><?= ghtComponentField('programme-time', 'Scheduled time', $ghtFormValues['time'], 'time', 'Times are a guide and do not automatically advance the programme.', [
                    'name' => 'time',
                    'required' => 'true',
                ]) ?></div>
                <div class="space-y-1.5">
                    <label class="block text-sm font-semibold text-[#252321]" for="programme-description">Description <span class="font-normal text-[#6B675F]">(optional)</span></label>
                    <textarea class="min-h-24 w-full rounded-lg border border-[#E9E4DD] bg-white px-3 py-2 text-sm text-[#252321] focus:border-[#9A5F44] focus:outline-none focus:ring-2 focus:ring-[#9A5F44]/20"
                        id="programme-description" name="description" maxlength="500"><?= ghtEscape($ghtFormValues['description']) ?></textarea>
                    <p class="text-xs text-[#6B675F]">Up to 500 characters.</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <button class="<?= ghtEscape(ghtComponentButtonClasses('primary')) ?>"
                        type="submit" name="action" value="save-item" data-loading-label="Saving…">
                        <?= $ghtEditItemId !== '' ? 'Save changes' : 'Add to programme' ?>
                    </button>
                    <?php if ($ghtEditItemId !== ''): ?>
                        <a class="<?= ghtEscape(ghtComponentButtonClasses('secondary')) ?>"
                            href="programme.php?event=<?= rawurlencode($ghtRequestedEventId) ?>">Cancel edit</a>
                    <?php endif; ?>
                </div>
            </form>
        </aside>
    </section>
<?php endif; ?>
<?php
$ghtPageContent = (string) ob_get_clean();
ghtRenderPage('Programme', 'programme', $ghtEvent, $ghtPageContent);

<?php
/**
 * gallery.php (Module 12)
 * Manages event photo metadata and previews selected local images in the browser.
 * Includes: bootstrap.php, shared UI components, dashboard-shell.php.
 * Reads and writes: media.json; reads events.json and plans.json.
 * Local file previews are never uploaded or persisted.
 */

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$ghtCsrfToken = ghtGetCsrfToken();
$ghtRequestedEventId = filter_input(INPUT_GET, 'event', FILTER_UNSAFE_RAW);
$ghtRequestedEventId = is_string($ghtRequestedEventId) ? trim($ghtRequestedEventId) : '';
$ghtAlbum = filter_input(INPUT_GET, 'album', FILTER_UNSAFE_RAW);
$ghtAlbum = is_string($ghtAlbum) && in_array($ghtAlbum, GHT_MEDIA_ALBUMS, true) ? $ghtAlbum : 'pre_event';
$ghtDataError = '';
$ghtActionError = '';
$ghtNotice = filter_input(INPUT_GET, 'notice', FILTER_UNSAFE_RAW);
$ghtNotice = is_string($ghtNotice) ? $ghtNotice : '';
$ghtEvents = [];
$ghtPlans = [];
$ghtMedia = [];
$ghtEvent = null;
$ghtPlan = [];
$ghtEventMedia = [];
$ghtAlbumFeatureKey = $ghtAlbum === 'pre_event' ? 'gallery_pre' : 'gallery_post';
$ghtAlbumAllowed = false;

try {
    $ghtEvents = ghtReadJson('events.json');
    $ghtPlans = ghtReadJson('plans.json');
    $ghtEventLookup = $ghtRequestedEventId !== '' ? ghtFindEvent($ghtEvents, $ghtRequestedEventId) : null;
    $ghtEvent = $ghtEventLookup['event'] ?? null;

    if ($ghtEvent !== null) {
        $ghtMedia = ghtReadJson('media.json');
        foreach ($ghtPlans as $ghtPlanRecord) {
            if (is_array($ghtPlanRecord) && ($ghtPlanRecord['id'] ?? '') === ($ghtEvent['plan'] ?? '')) {
                $ghtPlan = $ghtPlanRecord;
                break;
            }
        }
        $ghtAlbumAllowed = (bool) ($ghtPlan['features'][$ghtAlbumFeatureKey] ?? false)
            && (bool) ($ghtEvent['features'][$ghtAlbumFeatureKey] ?? false);
    }
} catch (RuntimeException $exception) {
    error_log('Foliioo gallery data load failed: ' . $exception->getMessage());
    $ghtDataError = 'Gallery data could not be loaded. Check the data files and reload this page.';
}

if ($ghtEvent !== null && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $ghtSubmittedToken = $_POST['csrf_token'] ?? '';
    $ghtAction = isset($_POST['action']) && is_string($_POST['action']) ? $_POST['action'] : '';
    $ghtMediaId = isset($_POST['media_id']) && is_string($_POST['media_id']) ? trim($_POST['media_id']) : '';
    $ghtMediaIndex = null;

    foreach ($ghtMedia as $ghtIndex => $ghtMediaItem) {
        if (is_array($ghtMediaItem)
            && ($ghtMediaItem['event_id'] ?? '') === $ghtRequestedEventId
            && ($ghtMediaItem['id'] ?? '') === $ghtMediaId
        ) {
            $ghtMediaIndex = $ghtIndex;
            break;
        }
    }

    if (!ghtValidateCsrfToken($ghtSubmittedToken)) {
        $ghtActionError = 'Your session expired or the form was invalid. Reload this page and try again.';
    } elseif (!$ghtAlbumAllowed) {
        $ghtActionError = 'This gallery album is locked by the event plan or its current feature settings.';
    } elseif ($ghtMediaIndex === null) {
        $ghtActionError = 'That photo could not be found. Reload the gallery and try again.';
    } elseif ($ghtAction === 'save-caption') {
        $ghtCaption = isset($_POST['caption']) && is_string($_POST['caption']) ? trim($_POST['caption']) : '';
        if (strlen($ghtCaption) > 160) {
            $ghtActionError = 'Keep photo captions to 160 characters or fewer.';
        } else {
            $ghtMedia[$ghtMediaIndex]['caption'] = $ghtCaption;
        }
    } elseif ($ghtAction === 'toggle-publish') {
        $ghtMedia[$ghtMediaIndex]['published'] = !(bool) ($ghtMedia[$ghtMediaIndex]['published'] ?? false);
    } elseif ($ghtAction === 'remove-photo') {
        $ghtMedia = array_values(array_filter(
            $ghtMedia,
            static function ($item) use ($ghtRequestedEventId, $ghtMediaId): bool {
                return !(
                    is_array($item)
                    && ($item['event_id'] ?? '') === $ghtRequestedEventId
                    && ($item['id'] ?? '') === $ghtMediaId
                );
            }
        ));
    } elseif ($ghtAction === 'move-photo') {
        $ghtDirection = $_POST['direction'] ?? '';
        $ghtAlbumItems = array_values(array_filter(
            $ghtMedia,
            static function ($item) use ($ghtRequestedEventId, $ghtAlbum): bool {
                return is_array($item)
                    && ($item['event_id'] ?? '') === $ghtRequestedEventId
                    && ($item['album'] ?? '') === $ghtAlbum;
            }
        ));
        usort($ghtAlbumItems, static fn(array $first, array $second): int => (int) ($first['order'] ?? 0) <=> (int) ($second['order'] ?? 0));
        $ghtPosition = null;
        foreach ($ghtAlbumItems as $ghtIndex => $ghtItem) {
            if (($ghtItem['id'] ?? '') === $ghtMediaId) {
                $ghtPosition = $ghtIndex;
                break;
            }
        }

        if (!in_array($ghtDirection, ['up', 'down'], true) || $ghtPosition === null) {
            $ghtActionError = 'Choose a valid photo order action.';
        } else {
            $ghtTarget = $ghtPosition + ($ghtDirection === 'up' ? -1 : 1);
            if (isset($ghtAlbumItems[$ghtTarget])) {
                [$ghtAlbumItems[$ghtPosition], $ghtAlbumItems[$ghtTarget]] =
                    [$ghtAlbumItems[$ghtTarget], $ghtAlbumItems[$ghtPosition]];
                foreach ($ghtMedia as $ghtIndex => $ghtItem) {
                    if (!is_array($ghtItem) || ($ghtItem['event_id'] ?? '') !== $ghtRequestedEventId || ($ghtItem['album'] ?? '') !== $ghtAlbum) {
                        continue;
                    }
                    foreach ($ghtAlbumItems as $ghtOrder => $ghtOrderedItem) {
                        if (($ghtItem['id'] ?? '') === ($ghtOrderedItem['id'] ?? '')) {
                            $ghtMedia[$ghtIndex]['order'] = $ghtOrder + 1;
                        }
                    }
                }
            }
        }
    } else {
        $ghtActionError = 'That gallery action is not available.';
    }

    if ($ghtActionError === '') {
        $ghtEventMediaIndexes = [];
        foreach ($ghtMedia as $ghtIndex => $ghtMediaItem) {
            if (is_array($ghtMediaItem) && ($ghtMediaItem['event_id'] ?? '') === $ghtRequestedEventId) {
                $ghtEventMediaIndexes[] = $ghtIndex;
            }
        }

        $ghtAlbumMediaIndexes = [];
        foreach ($ghtMedia as $ghtIndex => $ghtMediaItem) {
            if (is_array($ghtMediaItem)
                && ($ghtMediaItem['event_id'] ?? '') === $ghtRequestedEventId
                && ($ghtMediaItem['album'] ?? '') === $ghtAlbum
            ) {
                $ghtAlbumMediaIndexes[] = $ghtIndex;
            }
        }

        usort($ghtAlbumMediaIndexes, static function (int $first, int $second) use ($ghtMedia): int {
            return (int) ($ghtMedia[$first]['order'] ?? 0) <=> (int) ($ghtMedia[$second]['order'] ?? 0);
        });
        foreach ($ghtAlbumMediaIndexes as $ghtOrder => $ghtIndex) {
            $ghtMedia[$ghtIndex]['order'] = $ghtOrder + 1;
        }

        try {
            ghtWriteJson('media.json', $ghtMedia);
            header(
                'Location: gallery.php?event=' . rawurlencode($ghtRequestedEventId)
                    . '&album=' . rawurlencode($ghtAlbum) . '&notice=saved',
                true,
                303
            );
            exit;
        } catch (RuntimeException $exception) {
            error_log('Foliioo gallery write failed: ' . $exception->getMessage());
            $ghtActionError = 'The gallery could not be saved. Your photo changes were not applied.';
        }
    }
}

$ghtEventNotFound = $ghtDataError === '' && $ghtEvent === null;
if ($ghtEventNotFound) {
    http_response_code($ghtRequestedEventId === '' ? 400 : 404);
}

if ($ghtEvent !== null) {
    foreach ($ghtMedia as $ghtMediaItem) {
        if (is_array($ghtMediaItem) && ($ghtMediaItem['event_id'] ?? '') === $ghtRequestedEventId) {
            $ghtEventMedia[] = $ghtMediaItem;
        }
    }
}

$ghtAlbumMedia = array_values(array_filter(
    $ghtEventMedia,
    static function (array $item) use ($ghtAlbum): bool {
        return ($item['album'] ?? '') === $ghtAlbum;
    }
));
usort($ghtAlbumMedia, static fn(array $first, array $second): int => (int) ($first['order'] ?? 0) <=> (int) ($second['order'] ?? 0));
$ghtPhotoLimit = max(0, (int) ($ghtPlan['photo_limit'] ?? 0));
$ghtRemainingPhotos = max(0, $ghtPhotoLimit - count($ghtEventMedia));
$ghtPageContent = '';
ob_start();
?>
<?php if ($ghtDataError !== ''): ?>
    <?= ghtComponentAlert($ghtDataError, 'error') ?>
<?php elseif ($ghtEventNotFound): ?>
    <?= ghtComponentEmptyState('Event not found', 'Return to your dashboard and choose an event gallery.', 'fa-images', 'Go to dashboard', 'dashboard.php') ?>
<?php else: ?>
    <?php if ($ghtActionError !== ''): ?>
        <div class="mb-5"><?= ghtComponentAlert($ghtActionError, 'error') ?></div>
    <?php elseif ($ghtNotice === 'saved'): ?>
        <div class="mb-5"><?= ghtComponentAlert('Gallery changes saved.', 'success') ?></div>
    <?php endif; ?>

    <section class="gallery-intro mb-7 flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.12em] text-[#6B675F]">Guest photos</p>
            <h2 class="mt-1 font-display text-2xl font-semibold">Choose what guests can see</h2>
            <p class="mt-2 text-sm leading-6 text-[#6B675F]">
                Arrange photos, write captions, and choose which images guests can see.
            </p>
        </div>
        <div class="rounded-xl border border-[#E9E4DD] bg-white px-5 py-4">
            <p class="text-xs font-semibold uppercase tracking-wide text-[#6B675F]"><?= ghtEscape($ghtPlan['name'] ?? 'Plan') ?> photo allowance</p>
            <p class="mt-1 font-display text-2xl font-semibold"><?= count($ghtEventMedia) ?> <span class="text-base font-medium text-[#6B675F]">/ <?= $ghtPhotoLimit ?></span></p>
        </div>
    </section>

    <div class="gallery-album-tabs mb-6 flex flex-wrap gap-2" role="group" aria-label="Gallery album">
        <?php foreach (GHT_MEDIA_ALBUMS as $ghtAlbumOption): ?>
            <?php $ghtAlbumLabel = $ghtAlbumOption === 'pre_event' ? 'Before the event' : 'After the event'; ?>
            <a class="<?= ghtEscape(ghtComponentButtonClasses($ghtAlbum === $ghtAlbumOption ? 'dark' : 'secondary')) ?>"
                href="gallery.php?event=<?= rawurlencode($ghtRequestedEventId) ?>&album=<?= ghtEscape($ghtAlbumOption) ?>"
                <?= $ghtAlbum === $ghtAlbumOption ? 'aria-current="page"' : '' ?>>
                <?= ghtEscape($ghtAlbumLabel) ?>
            </a>
        <?php endforeach; ?>
    </div>
    <?php if (!$ghtAlbumAllowed): ?>
        <div class="mb-6"><?= ghtComponentAlert('This album is locked. Choose an eligible plan and enable the feature in Settings to manage or publish its photos.', 'warning') ?></div>
    <?php endif; ?>

    <section class="gallery-workspace-grid mb-8 grid gap-6 xl:grid-cols-[minmax(0,1.3fr)_minmax(300px,0.7fr)]">
        <div>
            <div class="mb-4 flex flex-wrap items-end justify-between gap-3">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.12em] text-[#6B675F]"><?= ghtEscape($ghtAlbum === 'pre_event' ? 'Before the event' : 'After the event') ?></p>
                    <h3 class="mt-1 font-display text-2xl font-semibold">Your photos</h3>
                </div>
                <span class="text-sm text-[#6B675F]"><?= count($ghtAlbumMedia) ?> in this album</span>
            </div>

            <?php if ($ghtAlbumMedia === []): ?>
                <?= ghtComponentEmptyState('No photos in this album', 'Choose local images below to preview them in this browser, or manage the seeded gallery items.', 'fa-images') ?>
            <?php else: ?>
                <ul class="gallery-photo-list divide-y divide-[#E9E4DD] overflow-hidden rounded-xl border border-[#E9E4DD] bg-white">
                    <?php foreach ($ghtAlbumMedia as $ghtPosition => $ghtPhoto): ?>
                        <li class="gallery-photo-row grid gap-4 p-4 sm:p-5">
                            <div class="aspect-[4/3] overflow-hidden rounded-lg bg-[#F3EDE5]">
                                <img class="h-full w-full object-cover" src="<?= ghtEscape($ghtPhoto['src'] ?? '') ?>"
                                    alt="<?= ghtEscape($ghtPhoto['caption'] ?? 'Event gallery photo') ?>" loading="lazy">
                            </div>
                            <div class="min-w-0">
                                <div class="mb-3 flex flex-wrap items-center justify-between gap-2">
                                    <span class="inline-flex min-h-7 items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold <?= ($ghtPhoto['published'] ?? false) ? 'bg-[#E8F1EA] text-[#3F6B48]' : 'bg-[#F3EDE5] text-[#6B675F]' ?>">
                                        <i class="fa-solid <?= ($ghtPhoto['published'] ?? false) ? 'fa-eye' : 'fa-eye-slash' ?>" aria-hidden="true"></i>
                                        <?= ($ghtPhoto['published'] ?? false) ? 'Visible to guests' : 'Hidden from guests' ?>
                                    </span>
                                    <span class="text-xs text-[#6B675F]">Photo <?= $ghtPosition + 1 ?></span>
                                </div>
                                <?php if ($ghtAlbumAllowed): ?>
                                <form class="flex flex-col gap-3 sm:flex-row sm:items-end" method="post"
                                    action="gallery.php?event=<?= rawurlencode($ghtRequestedEventId) ?>&album=<?= ghtEscape($ghtAlbum) ?>" data-loading>
                                    <input type="hidden" name="csrf_token" value="<?= ghtEscape($ghtCsrfToken) ?>">
                                    <input type="hidden" name="media_id" value="<?= ghtEscape($ghtPhoto['id'] ?? '') ?>">
                                    <input type="hidden" name="action" value="save-caption">
                                    <div class="min-w-0 flex-1"><?= ghtComponentField(
                                        'caption-' . (string) ($ghtPhoto['id'] ?? $ghtPosition),
                                        'Photo caption',
                                        (string) ($ghtPhoto['caption'] ?? ''),
                                        'text',
                                        '',
                                        ['name' => 'caption', 'maxlength' => '160']
                                    ) ?></div>
                                    <button class="<?= ghtEscape(ghtComponentButtonClasses('secondary')) ?>"
                                        type="submit" data-loading-label="Saving…">Save caption</button>
                                </form>
                                <div class="mt-3 flex flex-wrap gap-2">
                                    <form method="post" action="gallery.php?event=<?= rawurlencode($ghtRequestedEventId) ?>&album=<?= ghtEscape($ghtAlbum) ?>"
                                        <?= ($ghtPhoto['published'] ?? false) ? 'data-confirm-title="Hide this photo?" data-confirm="Guests will no longer see this photo in the event gallery."' : '' ?>>
                                        <input type="hidden" name="csrf_token" value="<?= ghtEscape($ghtCsrfToken) ?>">
                                        <input type="hidden" name="media_id" value="<?= ghtEscape($ghtPhoto['id'] ?? '') ?>">
                                        <button class="<?= ghtEscape(ghtComponentButtonClasses('ghost', 'sm')) ?>"
                                            type="submit" name="action" value="toggle-publish">
                                            <?= ($ghtPhoto['published'] ?? false) ? 'Hide from guests' : 'Publish photo' ?>
                                        </button>
                                    </form>
                                    <form class="flex" method="post" action="gallery.php?event=<?= rawurlencode($ghtRequestedEventId) ?>&album=<?= ghtEscape($ghtAlbum) ?>">
                                        <input type="hidden" name="csrf_token" value="<?= ghtEscape($ghtCsrfToken) ?>">
                                        <input type="hidden" name="media_id" value="<?= ghtEscape($ghtPhoto['id'] ?? '') ?>">
                                        <input type="hidden" name="action" value="move-photo">
                                        <button class="<?= ghtEscape(ghtComponentButtonClasses('secondary', 'sm')) ?>"
                                            type="submit" name="direction" value="up" <?= $ghtPosition === 0 ? 'disabled' : '' ?>>Move up</button>
                                        <button class="<?= ghtEscape(ghtComponentButtonClasses('secondary', 'sm')) ?>"
                                            type="submit" name="direction" value="down" <?= $ghtPosition === count($ghtAlbumMedia) - 1 ? 'disabled' : '' ?>>Move down</button>
                                    </form>
                                    <form method="post" action="gallery.php?event=<?= rawurlencode($ghtRequestedEventId) ?>&album=<?= ghtEscape($ghtAlbum) ?>"
                                        data-confirm="Remove this photo from the event gallery? This cannot be undone.">
                                        <input type="hidden" name="csrf_token" value="<?= ghtEscape($ghtCsrfToken) ?>">
                                        <input type="hidden" name="media_id" value="<?= ghtEscape($ghtPhoto['id'] ?? '') ?>">
                                        <button class="<?= ghtEscape(ghtComponentButtonClasses('ghost', 'sm')) ?>"
                                            type="submit" name="action" value="remove-photo">Remove</button>
                                    </form>
                                </div>
                                <?php endif; ?>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <aside class="gallery-side-panel space-y-6">
            <section class="rounded-xl border border-[#E9E4DD] bg-white p-5 sm:p-6">
                <p class="text-xs font-semibold uppercase tracking-[0.12em] text-[#6B675F]">Local preview</p>
                <h3 class="mt-1 font-display text-xl font-semibold">Choose images to preview</h3>
                <p class="mt-2 text-sm leading-5 text-[#6B675F]">Images stay in your browser and disappear when you leave or refresh this page.</p>
                <label class="mt-4 flex min-h-12 cursor-pointer items-center justify-center gap-2 rounded-lg border border-dashed border-[#D8D1C8] px-4 py-3 text-sm font-semibold text-[#252321] transition-colors hover:bg-[#FAF8F5]">
                    <i class="fa-solid fa-arrow-up-from-bracket" aria-hidden="true"></i>
                    Choose image files
                    <input class="sr-only" type="file" accept="image/*" multiple data-local-gallery-input
                        data-limit="<?= $ghtRemainingPhotos ?>" <?= $ghtAlbumAllowed ? '' : 'disabled' ?>>
                </label>
                <?php if ($ghtRemainingPhotos === 0): ?>
                    <p class="mt-3 text-sm text-[#8F5F12]" role="status">Photo limit reached for the <?= ghtEscape($ghtPlan['name'] ?? 'current') ?> plan. Remove a saved photo or upgrade the plan to make room.</p>
                <?php else: ?>
                    <p class="mt-3 text-xs text-[#6B675F]">Up to <?= $ghtRemainingPhotos ?> more saved-photo slots are available on this plan.</p>
                <?php endif; ?>
                <p class="mt-3 hidden rounded-lg bg-[#FBF1DC] p-3 text-sm text-[#8F5F12]" role="status" data-local-gallery-message></p>
                <ul class="mt-4 grid grid-cols-2 gap-3" data-local-gallery-previews></ul>
            </section>

            <section class="rounded-xl border border-[#E9E4DD] bg-[#F3EDE5] p-5 sm:p-6" aria-labelledby="gallery-preview-heading">
                <p class="text-xs font-semibold uppercase tracking-[0.12em] text-[#6B675F]">Public layout</p>
                <h3 id="gallery-preview-heading" class="mt-1 font-display text-xl font-semibold">Guest gallery preview</h3>
                <?php
                $ghtPublishedPhotos = array_values(array_filter(
                    $ghtAlbumMedia,
                    static function (array $item): bool {
                        return (bool) ($item['published'] ?? false);
                    }
                ));
                ?>
                <?php if ($ghtAlbumAllowed): ?>
                    <ul class="mt-4 grid grid-cols-2 gap-3">
                    <?php foreach (array_slice($ghtPublishedPhotos, 0, 4) as $ghtPhoto): ?>
                        <li class="aspect-square overflow-hidden rounded-lg bg-white">
                            <img class="h-full w-full object-cover" src="<?= ghtEscape($ghtPhoto['src'] ?? '') ?>"
                                alt="<?= ghtEscape($ghtPhoto['caption'] ?? 'Published event gallery photo') ?>" loading="lazy">
                        </li>
                    <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="mt-3 text-sm leading-5 text-[#6B675F]">This album will remain unavailable to guests until the feature is enabled in Settings.</p>
                <?php endif; ?>
                <?php if ($ghtAlbumAllowed && $ghtPublishedPhotos === []): ?>
                    <p class="mt-3 text-sm leading-5 text-[#6B675F]">Published images will appear in the public gallery preview.</p>
                <?php endif; ?>
            </section>
        </aside>
    </section>
<?php endif; ?>
<?php
$ghtPageContent = (string) ob_get_clean();
ghtRenderPage('Gallery', 'gallery', $ghtEvent, $ghtPageContent, 'assets/js/ops/gallery.js');

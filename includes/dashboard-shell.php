<?php
/** Shared responsive shell for every creator workspace page. Reads and writes no data. */
declare(strict_types=1);
require_once __DIR__ . '/bootstrap.php';

$ghtPageTitle = isset($ghtPageTitle) && is_string($ghtPageTitle) ? $ghtPageTitle : 'Workspace';
$ghtActivePage = isset($ghtActivePage) && is_string($ghtActivePage) ? $ghtActivePage : '';
$ghtEvent = isset($ghtEvent) && is_array($ghtEvent) ? $ghtEvent : null;
$ghtPageContent = isset($ghtPageContent) && is_string($ghtPageContent) ? $ghtPageContent : '';
$ghtPageScript = isset($ghtPageScript) && is_string($ghtPageScript) ? $ghtPageScript : '';
$ghtEventId = is_array($ghtEvent) && isset($ghtEvent['id']) ? (string) $ghtEvent['id'] : '';
$ghtOverviewPath = is_file(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'dashboard.php') ? 'dashboard.php' : 'index.php';
$ghtWorkspaceStylesheet = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'workspace.css';
$ghtWorkspaceStyleVersion = is_file($ghtWorkspaceStylesheet) ? (string) hash_file('crc32b', $ghtWorkspaceStylesheet) : '1';
$ghtNavigationItems = [
    ['id' => 'dashboard', 'label' => 'Dashboard', 'path' => $ghtOverviewPath, 'needs_event' => false, 'icon' => 'fa-chart-line', 'group' => 'Workspace'],
    ['id' => 'manage', 'label' => 'Event overview', 'path' => 'manage.php', 'needs_event' => true, 'icon' => 'fa-chart-pie', 'group' => 'Event setup'],
    ['id' => 'website', 'label' => 'Edit website', 'path' => 'builder.php', 'needs_event' => true, 'icon' => 'fa-pen-ruler', 'group' => 'Event setup'],
    ['id' => 'guests', 'label' => 'Guests and RSVPs', 'path' => 'guests.php', 'needs_event' => true, 'icon' => 'fa-users', 'group' => 'Event setup'],
    ['id' => 'settings', 'label' => 'Settings', 'path' => 'settings.php', 'needs_event' => true, 'icon' => 'fa-sliders', 'group' => 'Event setup'],
    ['id' => 'programme', 'label' => 'Programme', 'path' => 'programme.php', 'needs_event' => true, 'icon' => 'fa-list-check', 'group' => 'Event day'],
    ['id' => 'check-in', 'label' => 'Check-in', 'path' => 'check-in.php', 'needs_event' => true, 'icon' => 'fa-qrcode', 'group' => 'Event day'],
    ['id' => 'gallery', 'label' => 'Gallery', 'path' => 'gallery.php', 'needs_event' => true, 'icon' => 'fa-images', 'group' => 'Memories'],
];
foreach ($ghtNavigationItems as &$item) {
    $item['available'] = is_file(dirname(__DIR__) . DIRECTORY_SEPARATOR . $item['path']);
    $item['enabled'] = $item['available'] && (!$item['needs_event'] || $ghtEventId !== '');
    $item['href'] = $item['needs_event'] && $ghtEventId === ''
        ? $ghtOverviewPath . '#events-heading'
        : $item['path'];
    if ($ghtEventId !== '' && $item['needs_event']) $item['href'] .= '?event=' . rawurlencode($ghtEventId);
}
unset($item);
$ghtRenderNavigation = static function (array $items, string $activePage): void {
    $group = '';
    foreach ($items as $item) {
        if ($item['group'] !== $group) {
            if ($group !== '') echo '</div>';
            $group = $item['group'];
            echo '<div class="workspace-nav-group"><p class="workspace-nav-label">' . ghtEscape($group) . '</p>';
        }
        $active = $item['id'] === $activePage;
        $icon = ghtEscape($item['icon']);
        $label = ghtEscape($item['label']);
        if ($item['available']) {
            $class = $active ? ' is-active' : (!$item['enabled'] ? ' is-selection' : '');
            $selectionHint = !$item['enabled'] ? '<small class="workspace-nav-hint">Choose an event</small>' : '';
            echo '<a class="workspace-nav-link' . $class . '" href="' . ghtEscape($item['href']) . '"' . ($active ? ' aria-current="page"' : '') . '><i class="fa-solid ' . $icon . '" aria-hidden="true"></i><span>' . $label . '</span>' . $selectionHint . '</a>';
        } else {
            $reason = 'This page is not available yet';
            echo '<span class="workspace-nav-link is-disabled" aria-disabled="true" title="' . ghtEscape($reason) . '"><i class="fa-solid ' . $icon . '" aria-hidden="true"></i><span>' . $label . '</span><small class="workspace-nav-hint">Coming soon</small><span class="sr-only"> (' . ghtEscape($reason) . ')</span></span>';
        }
    }
    if ($group !== '') echo '</div>';
};
?>
<!doctype html>
<html lang="en" data-type="software"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="page-type" content="software"><meta name="site-kind" content="software"><meta name="theme-color" content="#FAF8F5"><title><?= ghtEscape($ghtPageTitle) ?> &middot; Foliioo</title>
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Fraunces:wght@400;500;600;700&display=swap" rel="stylesheet"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"><link rel="stylesheet" href="assets/css/app.css"><link rel="stylesheet" href="assets/css/workspace.css?v=<?= ghtEscape($ghtWorkspaceStyleVersion) ?>"></head>
<body class="min-h-screen bg-[#FAF8F5] text-[#252321] antialiased workspace-body"><div class="workspace-layout">
<a class="sr-only rounded-lg bg-white px-4 py-3 text-[#252321] focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-50" href="#main-content">Skip to main content</a>
<aside class="workspace-sidebar"><a class="workspace-brand" href="<?= ghtEscape($ghtOverviewPath) ?>" aria-label="Foliioo dashboard"><span class="workspace-brand-mark" aria-hidden="true">F</span><span>Foliioo</span></a>
<?php if ($ghtEventId !== ''): ?><a class="workspace-event-switcher" href="<?= ghtEscape($ghtOverviewPath) ?>" aria-label="Return to dashboard"><span class="workspace-event-switcher-label">Current event</span><span class="workspace-event-switcher-title"><?= ghtEscape($ghtEvent['title'] ?? 'Selected event') ?></span><i class="fa-solid fa-arrow-left" aria-hidden="true"></i></a><?php endif; ?>
<nav class="workspace-navigation" aria-label="Main navigation"><?php $ghtRenderNavigation($ghtNavigationItems, $ghtActivePage); ?></nav><div class="workspace-sidebar-footer"><i class="fa-regular fa-circle-question" aria-hidden="true"></i><span>Prototype workspace<br><small>Payments and messages are simulated</small></span></div></aside>
<header class="workspace-topbar"><div class="workspace-topbar-inner"><span class="workspace-topbar-label"><i class="fa-solid fa-layer-group" aria-hidden="true"></i> Creator workspace</span><a class="workspace-mobile-brand" href="<?= ghtEscape($ghtOverviewPath) ?>"><span class="workspace-brand-mark" aria-hidden="true">F</span> Foliioo</a><?php if ($ghtEventId !== ''): ?><span class="workspace-mobile-event"><?= ghtEscape($ghtEvent['title'] ?? '') ?></span><?php endif; ?><button class="workspace-menu-button" type="button" aria-expanded="false" aria-controls="mobile-navigation" data-mobile-menu><i class="fa-solid fa-bars" aria-hidden="true"></i><span>Menu</span></button></div>
<div class="workspace-mobile-panel" id="mobile-navigation" role="dialog" aria-modal="true" aria-label="Workspace navigation" hidden data-mobile-panel><div class="workspace-mobile-panel-inner"><div class="workspace-mobile-panel-heading"><p>Navigate workspace</p><button type="button" aria-label="Close menu" data-mobile-menu><i class="fa-solid fa-xmark" aria-hidden="true"></i></button></div><nav aria-label="Mobile navigation"><?php $ghtRenderNavigation($ghtNavigationItems, $ghtActivePage); ?></nav></div></div></header>
<main class="workspace-main" id="main-content"><div class="workspace-page-header"><div><?php if (is_array($ghtEvent) && isset($ghtEvent['title'])): ?><a class="workspace-breadcrumb" href="manage.php?event=<?= rawurlencode($ghtEventId) ?>"><i class="fa-solid fa-arrow-left" aria-hidden="true"></i> <?= ghtEscape($ghtEvent['title']) ?></a><?php endif; ?><h1><?= ghtEscape($ghtPageTitle) ?></h1><?php if (is_array($ghtEvent) && isset($ghtEvent['date'])): ?><p class="workspace-page-meta"><i class="fa-regular fa-calendar" aria-hidden="true"></i> <?= ghtEscape(ghtFormatDate($ghtEvent['date'])) ?><?php if (isset($ghtEvent['venue'])): ?> <span aria-hidden="true">&middot;</span> <?= ghtEscape($ghtEvent['venue']) ?><?php endif; ?></p><?php endif; ?></div></div><?= $ghtPageContent ?></main></div>
<?= ghtComponentConfirmationDialog() ?><script src="assets/js/ops/ui.js" defer></script><script src="assets/js/ops/shell.js" defer></script><?php if ($ghtPageScript !== ''): ?><script src="<?= ghtEscape($ghtPageScript) ?>" defer></script><?php endif; ?></body></html>

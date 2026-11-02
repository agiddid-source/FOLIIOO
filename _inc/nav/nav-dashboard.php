<?php
/**
 * Foliioo Design System V1.1 — Creator Dashboard Topbar & Sidebar
 */
$activeTab = $activeTab ?? 'overview';
$creator = $creator ?? [
    'name' => 'Adeleke Family Committee',
    'email' => 'events@adelekegroup.org',
    'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=200&q=80',
    'is_verified' => true
];

$eventTitle = $currentEvent['title'] ?? 'Select or Create an Event';
$eventSlug = $currentEvent['slug'] ?? '';
$eventType = $currentEvent['event_type'] ?? 'general';
$eventStatus = $currentEvent['status'] ?? 'draft';

$statusBadges = [
    'published_pre_event' => ['label' => 'Upcoming', 'class' => 'bg-[#EEF0EA] text-[#87927B] border-[#87927B]/30'],
    'in_progress'         => ['label' => '● Live Now', 'class' => 'bg-[#9A5F44]/15 text-[#9A5F44] border-[#9A5F44]/30 animate-pulse'],
    'completed'           => ['label' => '✓ Completed', 'class' => 'bg-[#F3EDE5] text-[#6B675F] border-[#E9E4DD]'],
    'postponed'           => ['label' => 'Postponed', 'class' => 'bg-[#FBF1DC] text-[#8F5F12] border-[#8F5F12]/30'],
    'cancelled'           => ['label' => 'Cancelled', 'class' => 'bg-[#F8E6E6] text-[#B54747] border-[#B54747]/30'],
    'draft'               => ['label' => 'Draft', 'class' => 'bg-[#F3EDE5] text-[#A19B93] border-[#E9E4DD]'],
];
$badge = $statusBadges[$eventStatus] ?? $statusBadges['draft'];

$navItems = [
    [
        'tab' => 'overview',
        'label' => 'Overview',
        'icon' => 'fa-table-cells-large',
        'badgeBg' => 'bg-[#EEF0EA] text-[#87927B]',
        'badge' => null,
    ],
    [
        'tab' => 'programme',
        'label' => 'Live Programme',
        'icon' => 'fa-clock',
        'badgeBg' => 'bg-[#F3EDE5] text-[#9A5F44]',
        'badge' => ($eventStatus === 'in_progress') ? 'Live' : null,
    ],
    [
        'tab' => 'guests',
        'label' => 'Guests & RSVP',
        'icon' => 'fa-users',
        'badgeBg' => 'bg-[#EEF0EA] text-[#3F6B48]',
        'badge' => null,
    ],
    [
        'tab' => 'checkin',
        'label' => 'QR Check-in',
        'icon' => 'fa-qrcode',
        'badgeBg' => 'bg-[#FBF1DC] text-[#8F5F12]',
        'badge' => null,
    ],
    [
        'tab' => 'ticketing',
        'label' => 'Tickets & Tables',
        'icon' => 'fa-ticket',
        'badgeBg' => 'bg-[#F3EDE5] text-[#9A5F44]',
        'badge' => null,
    ],
    [
        'tab' => 'donations',
        'label' => 'Tributes & Funds',
        'icon' => 'fa-hand-holding-heart',
        'badgeBg' => 'bg-[#F8E6E6] text-[#B54747]',
        'badge' => null,
    ],
    [
        'tab' => 'gallery',
        'label' => 'Media & Gallery',
        'icon' => 'fa-image',
        'badgeBg' => 'bg-[#EEF0EA] text-[#87927B]',
        'badge' => null,
    ],
    [
        'tab' => 'settings',
        'label' => 'Event Settings',
        'icon' => 'fa-sliders',
        'badgeBg' => 'bg-[#FAF8F5] text-[#6B675F]',
        'badge' => null,
    ],
];
?>

<div id="foliioo-dashboard-backdrop" class="fixed inset-0 z-40 bg-black/40 backdrop-blur-xs hidden lg:hidden transition-opacity duration-200"></div>

<header id="foliioo-top-header" class="sticky top-0 z-30 bg-white/95 backdrop-blur-md border-b border-[#E9E4DD] h-16 flex items-center justify-between px-4 sm:px-6 lg:pl-72 transition-all duration-200 font-sans">
  <div class="flex items-center gap-3">
    <button id="foliioo-dashboard-sidebar-toggle" type="button" class="p-2 text-[#6B675F] rounded-lg lg:hidden hover:bg-[#FAF8F5] transition" aria-label="Open sidebar">
      <i class="fa-solid fa-bars text-lg"></i>
    </button>

    <div class="flex items-center gap-3">
      <div class="hidden sm:flex items-center gap-2">
        <span class="text-[11px] font-bold uppercase tracking-wider text-[#A19B93]">Event:</span>
        <span class="text-sm font-bold text-[#252321] truncate max-w-[200px] md:max-w-md" title="<?= htmlspecialchars($eventTitle) ?>">
          <?= htmlspecialchars($eventTitle) ?>
        </span>
      </div>
      <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full border <?= $badge['class'] ?>">
        <?= $badge['label'] ?>
      </span>
    </div>
  </div>

  <div class="flex items-center gap-3">
    <?php if (!empty($eventSlug)): ?>
      <a href="/event.php?slug=<?= urlencode($eventSlug) ?>" target="_blank" class="btn btn-secondary btn-sm text-[#9A5F44] hover:text-[#7E4A33]">
        <span>View Live Site</span>
        <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
      </a>
    <?php endif; ?>

    <div class="relative">
      <button id="foliioo-user-menu-btn" type="button" class="flex items-center gap-2.5 p-1 rounded-lg hover:bg-[#FAF8F5] focus:outline-none transition" aria-expanded="false" aria-haspopup="true">
        <img class="w-8 h-8 rounded-lg object-cover ring-1 ring-[#E9E4DD]" src="<?= htmlspecialchars($creator['avatar']) ?>" alt="Creator Avatar">
        <div class="hidden md:flex flex-col text-left">
          <span class="text-xs font-bold text-[#252321] leading-tight"><?= htmlspecialchars($creator['name']) ?></span>
          <span class="text-[10px] text-[#6B675F] font-medium leading-tight">Organizer</span>
        </div>
        <i class="fa-solid fa-chevron-down text-xs text-[#A19B93]"></i>
      </button>

      <div id="foliioo-user-dropdown" class="hidden absolute right-0 mt-2 w-56 rounded-xl bg-white shadow-[0_8px_30px_rgba(37,35,33,0.06)] border border-[#E9E4DD] py-2 z-50 text-sm">
        <div class="px-4 py-2 border-b border-[#E9E4DD]">
          <p class="text-xs text-[#6B675F]">Signed in as</p>
          <p class="text-xs font-bold text-[#252321] truncate"><?= htmlspecialchars($creator['email']) ?></p>
        </div>
        <a href="/creator-profile.php" class="flex items-center gap-2.5 px-4 py-2 text-[#252321] hover:bg-[#FAF8F5] hover:text-[#9A5F44] transition">
          <i class="fa-solid fa-user w-4 text-[#87927B]"></i>
          <span>Public Profile</span>
        </a>
        <a href="/dashboard.php?tab=settings" class="flex items-center gap-2.5 px-4 py-2 text-[#252321] hover:bg-[#FAF8F5] hover:text-[#9A5F44] transition">
          <i class="fa-solid fa-gear w-4 text-[#87927B]"></i>
          <span>Account Settings</span>
        </a>
        <a href="/dashboard.php?tab=billing" class="flex items-center gap-2.5 px-4 py-2 text-[#252321] hover:bg-[#FAF8F5] hover:text-[#9A5F44] transition">
          <i class="fa-solid fa-credit-card w-4 text-[#87927B]"></i>
          <span>Plan & Receipts</span>
        </a>
        <div class="border-t border-[#E9E4DD] my-1"></div>
        <a href="/login.php?action=logout" class="flex items-center gap-2.5 px-4 py-2 text-[#B54747] hover:bg-[#F8E6E6] font-medium transition">
          <i class="fa-solid fa-arrow-right-from-bracket w-4 text-[#B54747]"></i>
          <span>Sign Out</span>
        </a>
      </div>
    </div>
  </div>
</header>

<aside id="foliioo-dashboard-sidebar" class="fixed z-50 transition-all duration-200 ease-in-out
  inset-y-3 left-3 w-72 max-w-[85vw] rounded-2xl -translate-x-[110%] shadow-2xl
  lg:left-4 lg:top-4 lg:bottom-4 lg:w-64 lg:translate-x-0 lg:shadow-[0_8px_30px_rgba(37,35,33,0.06)]
  bg-white text-[#252321] border border-[#E9E4DD] flex flex-col justify-between overflow-hidden font-sans">
  
  <div class="flex-1 flex flex-col overflow-y-auto no-scrollbar">
    <div class="h-16 flex items-center justify-between px-4 sm:px-5 border-b border-[#E9E4DD] shrink-0">
      <a id="foliioo-sidebar-logo" href="/dashboard.php" class="flex items-center gap-2.5 min-w-0">
        <div class="w-8 h-8 rounded-lg bg-[#9A5F44] flex items-center justify-center text-white font-display text-sm font-bold shadow-xs shrink-0">
          F
        </div>
        <span class="sidebar-text font-display font-bold text-sm tracking-tight text-[#252321] truncate transition-opacity duration-200">
          Workspace
        </span>
      </a>

      <button id="foliioo-sidebar-collapse-btn" type="button" class="p-1.5 rounded-lg border border-[#E9E4DD] hover:border-[#D8D1C8] text-[#6B675F] hover:text-[#252321] transition shrink-0" title="Collapse / Expand Sidebar">
        <i id="foliioo-collapse-icon" class="fa-solid fa-angles-left text-xs hidden lg:block"></i>
        <i class="fa-solid fa-xmark text-sm lg:hidden"></i>
      </button>
    </div>

    <div class="p-3 border-b border-[#E9E4DD] shrink-0 sidebar-dropdown">
      <div class="relative">
        <select onchange="if(this.value) window.location.href='?event=' + this.value + '&tab=<?= htmlspecialchars($activeTab) ?>'" class="form-select text-xs font-semibold pr-8 cursor-pointer truncate">
          <?php if (!empty($allEvents)): ?>
            <?php foreach ($allEvents as $ev): ?>
              <option value="<?= htmlspecialchars($ev['id']) ?>" <?= ($ev['id'] === ($currentEvent['id'] ?? '')) ? 'selected' : '' ?>>
                <?= htmlspecialchars($ev['title']) ?>
              </option>
            <?php endforeach; ?>
          <?php else: ?>
            <option value=""><?= htmlspecialchars($eventTitle) ?></option>
          <?php endif; ?>
        </select>
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-[#6B675F]">
          <i class="fa-solid fa-chevron-down text-xs"></i>
        </div>
      </div>
    </div>

    <nav class="p-2 sm:p-3 space-y-1 text-xs font-medium flex-1">
      <?php foreach ($navItems as $item): ?>
        <?php 
          $isActive = ($activeTab === $item['tab']);
        ?>
        <a href="?tab=<?= $item['tab'] ?>" title="<?= htmlspecialchars($item['label']) ?>" class="group flex items-center justify-between px-2.5 py-2 rounded-lg transition-all duration-200 <?= $isActive ? 'bg-[#FAF8F5] text-[#9A5F44] font-bold border border-[#E9E4DD]' : 'text-[#6B675F] hover:text-[#252321] hover:bg-[#FAF8F5]' ?>">
          <div class="flex items-center gap-3 min-w-0">
            <div class="w-7 h-7 rounded-md flex items-center justify-center shrink-0 transition-colors <?= $item['badgeBg'] ?>">
              <i class="fa-solid <?= $item['icon'] ?> text-xs"></i>
            </div>
            <span class="sidebar-text truncate transition-opacity duration-200"><?= htmlspecialchars($item['label']) ?></span>
          </div>

          <?php if (!empty($item['badge'])): ?>
            <span class="sidebar-text badge badge-accent text-[10px] animate-pulse">
              <?= $item['badge'] ?>
            </span>
          <?php elseif ($isActive): ?>
            <span class="sidebar-text w-1.5 h-1.5 rounded-full bg-[#9A5F44]"></span>
          <?php endif; ?>
        </a>
      <?php endforeach; ?>
    </nav>
  </div>

  <div class="p-3 border-t border-[#E9E4DD] shrink-0 sidebar-bottom-card">
    <div class="bg-[#FAF8F5] border border-[#E9E4DD] rounded-xl p-3.5 relative overflow-hidden group">
      <div class="flex items-center justify-between mb-1">
        <span class="text-[12px] font-bold text-[#252321]">Celebration Plan</span>
        <span class="badge badge-sage text-[10px] py-0 px-1.5">
          Active
        </span>
      </div>
      <p class="text-[11px] text-[#6B675F] mb-2.5 leading-snug">
        Up to 300 guests, live programme control & post-event memories.
      </p>
      
      <a href="/dashboard.php?tab=plans" class="btn btn-secondary btn-sm btn-block text-xs">
        Manage Plan
      </a>
    </div>
  </div>
</aside>

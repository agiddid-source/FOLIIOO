<?php
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
    'published_pre_event' => ['label' => 'Upcoming', 'class' => 'bg-[#320E3B]/10 text-[#320E3B] border-[#320E3B]/20'],
    'in_progress'         => ['label' => '● Live Now', 'class' => 'bg-rose-50 text-rose-700 border-rose-200 animate-pulse'],
    'completed'           => ['label' => '✓ Completed', 'class' => 'bg-[#0D160B] text-white border-[#0D160B]'],
    'postponed'           => ['label' => 'Postponed', 'class' => 'bg-amber-100 text-amber-900 border-amber-300'],
    'draft'               => ['label' => 'Draft', 'class' => 'bg-gray-200 text-gray-700 border-gray-300'],
];
$badge = $statusBadges[$eventStatus] ?? $statusBadges['draft'];

$navItems = [
    [
        'tab' => 'overview',
        'label' => 'Overview',
        'icon' => 'layout-grid',
        'badgeBg' => 'bg-blue-100 text-blue-700 group-hover:bg-blue-200',
        'badge' => null,
    ],
    [
        'tab' => 'programme',
        'label' => 'Live Programme',
        'icon' => 'clock',
        'badgeBg' => 'bg-sky-100 text-sky-700 group-hover:bg-sky-200',
        'badge' => ($eventStatus === 'in_progress') ? 'Live' : null,
    ],
    [
        'tab' => 'guests',
        'label' => 'Guests & RSVP',
        'icon' => 'users',
        'badgeBg' => 'bg-emerald-100 text-emerald-700 group-hover:bg-emerald-200',
        'badge' => null,
    ],
    [
        'tab' => 'checkin',
        'label' => 'QR Check-in',
        'icon' => 'qr-code',
        'badgeBg' => 'bg-amber-100 text-amber-800 group-hover:bg-amber-200',
        'badge' => null,
    ],
    [
        'tab' => 'ticketing',
        'label' => 'Tickets & Tables',
        'icon' => 'ticket',
        'badgeBg' => 'bg-fuchsia-100 text-fuchsia-700 group-hover:bg-fuchsia-200',
        'badge' => null,
    ],
    [
        'tab' => 'donations',
        'label' => 'Tributes & Funds',
        'icon' => 'heart-handshake',
        'badgeBg' => 'bg-rose-100 text-rose-700 group-hover:bg-rose-200',
        'badge' => null,
    ],
    [
        'tab' => 'gallery',
        'label' => 'Media & Gallery',
        'icon' => 'image',
        'badgeBg' => 'bg-teal-100 text-teal-700 group-hover:bg-teal-200',
        'badge' => null,
    ],
    [
        'tab' => 'settings',
        'label' => 'Event Settings',
        'icon' => 'sliders-horizontal',
        'badgeBg' => 'bg-purple-100 text-purple-700 group-hover:bg-purple-200',
        'badge' => null,
    ],
];
?>

<div id="foliioo-dashboard-backdrop" class="fixed inset-0 z-40 bg-black/40 backdrop-blur-md hidden lg:hidden transition-opacity duration-300"></div>

<header id="foliioo-top-header" class="sticky top-0 z-30 bg-white/90 backdrop-blur-md border-b border-gray-200/80 h-16 flex items-center justify-between px-4 sm:px-6 lg:pl-72 transition-all duration-300">
  <div class="flex items-center gap-3">
    <button id="foliioo-dashboard-sidebar-toggle" type="button" class="p-2 text-gray-700 rounded-xl lg:hidden hover:bg-gray-100 transition" aria-label="Open sidebar">
      <i data-lucide="menu" class="w-6 h-6"></i>
    </button>

    <div class="flex items-center gap-3">
      <div class="hidden sm:flex items-center gap-2">
        <span class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Event:</span>
        <span class="text-sm font-extrabold text-gray-900 truncate max-w-[200px] md:max-w-md" title="<?= htmlspecialchars($eventTitle) ?>">
          <?= htmlspecialchars($eventTitle) ?>
        </span>
      </div>
      <span class="text-xs font-bold px-2.5 py-0.5 rounded-full border <?= $badge['class'] ?>">
        <?= $badge['label'] ?>
      </span>
    </div>
  </div>

  <div class="flex items-center gap-3">
    <?php if (!empty($eventSlug)): ?>
      <a href="/event.php?slug=<?= urlencode($eventSlug) ?>" target="_blank" class="hidden sm:inline-flex items-center gap-1.5 text-xs font-bold text-[#320E3B] hover:text-white bg-[#320E3B]/10 hover:bg-[#320E3B] border border-[#320E3B]/20 px-3.5 py-1.5 rounded-xl transition-all shadow-xs">
        <span>View Live Site</span>
        <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
      </a>
    <?php endif; ?>

    <div class="relative">
      <button id="foliioo-user-menu-btn" type="button" class="flex items-center gap-2.5 p-1 rounded-xl hover:bg-gray-100 focus:outline-none transition" aria-expanded="false" aria-haspopup="true">
        <img class="w-8 h-8 rounded-lg object-cover ring-2 ring-gray-200" src="<?= htmlspecialchars($creator['avatar']) ?>" alt="Creator Avatar">
        <div class="hidden md:flex flex-col text-left">
          <span class="text-xs font-bold text-gray-900 leading-tight"><?= htmlspecialchars($creator['name']) ?></span>
          <span class="text-[10px] text-gray-400 font-semibold leading-tight">Organizer</span>
        </div>
        <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400"></i>
      </button>

      <div id="foliioo-user-dropdown" class="hidden absolute right-0 mt-2 w-56 rounded-2xl bg-white shadow-xl border border-gray-150 py-2 z-50 text-sm">
        <div class="px-4 py-2 border-b border-gray-100">
          <p class="text-xs text-gray-400">Signed in as</p>
          <p class="text-xs font-bold text-gray-900 truncate"><?= htmlspecialchars($creator['email']) ?></p>
        </div>
        <a href="/creator-profile.php" class="flex items-center gap-2.5 px-4 py-2 text-gray-700 hover:bg-gray-50 hover:text-[#320E3B]">
          <i data-lucide="user" class="w-4 h-4 text-gray-400"></i>
          <span>Public Profile</span>
        </a>
        <a href="/dashboard.php?tab=settings" class="flex items-center gap-2.5 px-4 py-2 text-gray-700 hover:bg-gray-50 hover:text-[#320E3B]">
          <i data-lucide="settings" class="w-4 h-4 text-gray-400"></i>
          <span>Account Settings</span>
        </a>
        <a href="/dashboard.php?tab=billing" class="flex items-center gap-2.5 px-4 py-2 text-gray-700 hover:bg-gray-50 hover:text-[#320E3B]">
          <i data-lucide="credit-card" class="w-4 h-4 text-gray-400"></i>
          <span>Plan & Receipts</span>
        </a>
        <div class="border-t border-gray-100 my-1"></div>
        <a href="/login.php?action=logout" class="flex items-center gap-2.5 px-4 py-2 text-rose-600 hover:bg-rose-50 font-medium">
          <i data-lucide="log-out" class="w-4 h-4 text-rose-500"></i>
          <span>Sign Out</span>
        </a>
      </div>
    </div>
  </div>
</header>

<aside id="foliioo-dashboard-sidebar" class="fixed z-50 transition-all duration-300 ease-in-out
  inset-y-3 left-3 w-72 max-w-[85vw] rounded-3xl -translate-x-[110%] shadow-2xl
  lg:left-4 lg:top-4 lg:bottom-4 lg:w-64 lg:translate-x-0 lg:shadow-lg
  bg-white text-gray-800 border border-gray-200/90 flex flex-col justify-between overflow-hidden">
  
  <div class="flex-1 flex flex-col overflow-y-auto no-scrollbar">
    <div class="h-16 flex items-center justify-between px-4 sm:px-5 border-b border-gray-100 shrink-0">
      <a id="foliioo-sidebar-logo" href="/dashboard.php" class="flex items-center gap-2.5 min-w-0">
        <div class="w-7 h-7 rounded-lg bg-[#320E3B] flex items-center justify-center text-white font-black text-xs shadow-xs shrink-0">
          F
        </div>
        <span class="sidebar-text font-black text-sm tracking-tight text-gray-900 truncate transition-opacity duration-200">
          Workspace
        </span>
      </a>

      <button id="foliioo-sidebar-collapse-btn" type="button" class="p-1.5 sm:p-1 rounded-md border border-dashed border-gray-300 hover:border-gray-500 text-gray-500 hover:text-gray-900 transition shrink-0" title="Collapse / Expand Sidebar">
        <i id="foliioo-collapse-icon" data-lucide="panel-left-close" class="w-4 h-4 hidden lg:block"></i>
        <i data-lucide="x" class="w-4 h-4 lg:hidden"></i>
      </button>
    </div>

    <div class="p-3 border-b border-gray-100 shrink-0 sidebar-dropdown">
      <div class="relative">
        <select onchange="if(this.value) window.location.href='?event=' + this.value + '&tab=<?= htmlspecialchars($activeTab) ?>'" class="w-full text-xs font-semibold bg-gray-50 border border-gray-200 text-gray-900 rounded-xl px-3 py-2 pr-8 focus:ring-1 focus:ring-[#320E3B] focus:outline-none appearance-none cursor-pointer truncate">
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
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-400">
          <i data-lucide="chevrons-up-down" class="w-3.5 h-3.5"></i>
        </div>
      </div>
    </div>

    <nav class="p-2 sm:p-3 space-y-1 text-xs font-medium flex-1">
      <?php foreach ($navItems as $item): ?>
        <?php 
          $isActive = ($activeTab === $item['tab']);
        ?>
        <a href="?tab=<?= $item['tab'] ?>" title="<?= htmlspecialchars($item['label']) ?>" class="group flex items-center justify-between px-2.5 py-2 rounded-xl transition-all duration-150 <?= $isActive ? 'bg-[#320E3B]/10 text-[#320E3B] font-bold border border-[#320E3B]/20 shadow-xs' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100/70' ?>">
          <div class="flex items-center gap-3 min-w-0">
            <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0 transition-colors <?= $item['badgeBg'] ?>">
              <i data-lucide="<?= $item['icon'] ?>" class="w-4 h-4"></i>
            </div>
            <span class="sidebar-text truncate transition-opacity duration-200"><?= htmlspecialchars($item['label']) ?></span>
          </div>

          <?php if (!empty($item['badge'])): ?>
            <span class="sidebar-text text-[10px] font-extrabold uppercase px-2 py-0.5 rounded-full bg-rose-100 text-rose-800 border border-rose-200 animate-pulse">
              <?= $item['badge'] ?>
            </span>
          <?php elseif ($isActive): ?>
            <span class="sidebar-text w-1.5 h-1.5 rounded-full bg-[#320E3B]"></span>
          <?php endif; ?>
        </a>
      <?php endforeach; ?>
    </nav>
  </div>

  <div class="p-3 border-t border-gray-100 shrink-0 sidebar-bottom-card">
    <div class="bg-gradient-to-br from-gray-50 to-purple-50/40 border border-gray-200/80 rounded-2xl p-3.5 relative overflow-hidden group">
      <div class="flex items-center justify-between mb-1">
        <span class="text-[11px] font-extrabold text-gray-900">Celebration Plan</span>
        <span class="text-[9px] font-black uppercase tracking-wider px-2 py-0.5 rounded-md bg-[#320E3B] text-white">
          Active
        </span>
      </div>
      <p class="text-[10px] text-gray-500 mb-2.5 leading-snug">
        Up to 300 guests, live programme control & post-event memories.
      </p>
      
      <a href="/dashboard.phpplans.php" class="block w-full text-center text-xs font-bold py-1.5 rounded-xl bg-white hover:bg-gray-50 text-gray-800 transition border border-gray-200 shadow-2xs">
        Manage Plan
      </a>
    </div>
  </div>
</aside>

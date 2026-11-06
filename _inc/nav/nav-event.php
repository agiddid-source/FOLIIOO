<?php
/**
 * Foliioo Design System V1.1 — Public Event Dynamic Navbar
 */
if (empty($event)) {
    return;
}

$title = $event['title'] ?? 'Foliioo Event';
$eventType = $event['event_type_label'] ?? ucfirst($event['event_type'] ?? 'Event');
$status = $event['status'] ?? 'published_pre_event';
$features = $event['features_enabled'] ?? [];

// Status mapping strictly adhering to V1.1 palette
$statusConfigs = [
    'published_pre_event' => [
        'label' => 'Upcoming',
        'pill_class' => 'bg-[#EEF0EA] text-[#87927B] border-[#87927B]/30',
        'dot' => 'bg-[#87927B]'
    ],
    'in_progress' => [
        'label' => 'Live Now',
        'pill_class' => 'bg-[#FF502B]/15 text-[#FF502B] border-[#FF502B]/30 animate-pulse',
        'dot' => 'bg-[#FF502B] animate-ping'
    ],
    'completed' => [
        'label' => 'Concluded',
        'pill_class' => 'bg-[#F3EDE5] text-[#63615E] border-[#EAE8E2]',
        'dot' => 'bg-[#63615E]'
    ],
    'postponed' => [
        'label' => 'Postponed',
        'pill_class' => 'bg-[#FEF3C7] text-[#B45309] border-[#B45309]/30',
        'dot' => 'bg-[#B45309]'
    ],
    'cancelled' => [
        'label' => 'Cancelled',
        'pill_class' => 'bg-[#FEE2E2] text-[#DC2626] border-[#DC2626]/30',
        'dot' => 'bg-[#DC2626]'
    ]
];
$cfg = $statusConfigs[$status] ?? $statusConfigs['published_pre_event'];

$calDateClean = str_replace('-', '', $event['date'] ?? '20261128');
$calTimeClean = str_replace(':', '', $event['time'] ?? '1600') . '00';
$calStartIso = $calDateClean . 'T' . $calTimeClean;
$calEndIso = date('Ymd\THis', strtotime(($event['date'] ?? '2026-11-28') . ' ' . ($event['time'] ?? '16:00') . ' +4 hours'));
$gcalUrl = 'https://calendar.google.com/calendar/render?action=TEMPLATE&text=' . urlencode($event['title'] ?? '') . '&dates=' . $calStartIso . '/' . $calEndIso . '&details=' . urlencode($event['postponement_reason'] ?? $event['subtitle'] ?? '') . '&location=' . urlencode(($event['venue']['name'] ?? '') . ', ' . ($event['venue']['address'] ?? ''));
?>

<div class="sticky top-3 z-40 px-3 sm:px-4 max-w-5xl mx-auto transition-all duration-200">
  <nav class="bg-white/90 backdrop-blur-md border border-[#EAE8E2] rounded-2xl px-3 sm:px-6 h-16 flex items-center justify-between shadow-[0_8px_30px_rgba(37,35,33,0.06)] relative">
    
    <!-- Monogram & Event Name -->
    <div class="flex items-center gap-2.5 sm:gap-3 min-w-0">
      <a href="#details" class="flex items-center gap-2 group min-w-0">
        <div class="w-8 h-8 rounded-lg bg-[#FF502B] text-white flex items-center justify-center font-display text-sm font-bold shadow-xs group-hover:scale-105 transition-transform shrink-0">
          <?= strtoupper(substr($title, 0, 1)) ?>
        </div>
        <div class="flex flex-col min-w-0">
          <span class="text-xs sm:text-sm font-bold text-[#0F0F0F] truncate max-w-[120px] xs:max-w-[170px] sm:max-w-xs md:max-w-sm group-hover:text-[#FF502B] transition-colors leading-tight">
            <?= htmlspecialchars($title) ?>
          </span>
          <span class="text-[10px] font-semibold text-[#87927B] uppercase tracking-wider leading-tight truncate max-w-[120px] sm:max-w-none">
            <?= htmlspecialchars($eventType) ?>
          </span>
        </div>
      </a>

      <!-- Status Pill -->
      <div class="hidden lg:inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold border <?= $cfg['pill_class'] ?> shrink-0">
        <span class="w-2 h-2 rounded-full <?= $cfg['dot'] ?>"></span>
        <span><?= $cfg['label'] ?></span>
      </div>
    </div>

    <!-- Quick Links & Actions -->
    <div class="flex items-center gap-1.5 sm:gap-3 shrink-0">
      
      <div class="hidden md:flex items-center space-x-1 lg:space-x-2 text-xs sm:text-sm font-medium text-[#63615E]">
        <a href="#details" class="foliioo-event-nav-link px-2.5 py-1.5 rounded-lg hover:text-[#FF502B] hover:bg-[#FAFBF6] transition">
          Details
        </a>
        
        <?php if (!empty($features['programme'])): ?>
          <a href="#programme" class="foliioo-event-nav-link px-2.5 py-1.5 rounded-lg hover:text-[#FF502B] hover:bg-[#FAFBF6] transition flex items-center gap-1.5">
            <span>Programme</span>
            <?php if ($status === 'in_progress'): ?>
              <span class="w-1.5 h-1.5 rounded-full bg-[#FF502B] animate-pulse"></span>
            <?php endif; ?>
          </a>
        <?php endif; ?>

        <?php if (!empty($features['ticketing']) && $status !== 'cancelled'): ?>
          <a href="#tickets" class="foliioo-event-nav-link px-2.5 py-1.5 rounded-lg hover:text-[#FF502B] hover:bg-[#FAFBF6] transition">
            Tickets
          </a>
        <?php endif; ?>

        <?php if (!empty($features['gallery'])): ?>
          <a href="#gallery" class="foliioo-event-nav-link px-2.5 py-1.5 rounded-lg hover:text-[#FF502B] hover:bg-[#FAFBF6] transition">
            Gallery
          </a>
        <?php endif; ?>

        <?php if (!empty($features['donations'])): ?>
          <a href="#donations" class="foliioo-event-nav-link px-2.5 py-1.5 rounded-lg hover:text-[#FF502B] hover:bg-[#FAFBF6] transition">
            <?= ($event['event_type'] ?? '') === 'burial' ? 'Memorial Fund' : 'Gifts' ?>
          </a>
        <?php endif; ?>
      </div>

      <!-- Action Button Area -->
      <div class="pl-1 sm:pl-2 md:border-l md:border-[#EAE8E2]">
        
        <?php if (!empty($features['rsvp']) && $status === 'published_pre_event'): ?>
          <a href="#rsvp" class="btn btn-primary btn-sm shrink-0">
            <i class="fa-solid fa-calendar-check"></i>
            <span>RSVP</span>
          </a>

        <?php elseif (!empty($features['ticketing']) && $status === 'published_pre_event'): ?>
          <a href="#tickets" class="btn btn-primary btn-sm shrink-0">
            <i class="fa-solid fa-ticket"></i>
            <span>Tickets</span>
          </a>

        <?php elseif ($status === 'in_progress'): ?>
          <a href="#programme" class="btn btn-primary btn-sm shrink-0 animate-pulse">
            <i class="fa-solid fa-tower-broadcast"></i>
            <span>Live</span>
          </a>

        <?php elseif ($status === 'postponed'): ?>
          <div class="relative group/postponed">
            <button id="foliioo-postponed-btn" type="button" class="btn btn-sm bg-[#FEF3C7] text-[#B45309] border border-[#B45309]/30 hover:bg-[#F3E1B9] shrink-0">
              <i class="fa-solid fa-clock"></i>
              <span>Postponed</span>
              <i class="fa-solid fa-chevron-down text-[10px] opacity-75 group-hover/postponed:rotate-180 transition-transform"></i>
            </button>

            <!-- Postponed Popover -->
            <div id="foliioo-postponed-popover" class="hidden group-hover/postponed:block absolute right-0 mt-2 w-[280px] xs:w-80 sm:w-96 max-w-[calc(100vw-2rem)] rounded-xl bg-white border border-[#EAE8E2] shadow-[0_8px_30px_rgba(37,35,33,0.06)] p-4 text-xs z-50 text-[#0F0F0F] transition-all duration-200">
              <div class="flex items-center justify-between pb-2 border-b border-[#EAE8E2]">
                <div class="flex items-center gap-1.5 text-[#B45309] font-bold text-xs">
                  <i class="fa-solid fa-circle-exclamation"></i>
                  <span>Postponement Notice</span>
                </div>
                <span class="badge badge-warning text-[10px]">Notice</span>
              </div>
              
              <div class="py-3 space-y-2.5">
                <p class="font-bold text-[#0F0F0F] text-xs leading-relaxed">
                  <?= htmlspecialchars($event['postponement_reason'] ?? 'This event has been rescheduled to a future date.') ?>
                </p>
                
                <a href="<?= $gcalUrl ?>" target="_blank" rel="noopener noreferrer" title="Click to add new date to Google Calendar" class="flex items-center justify-between gap-2.5 text-xs text-[#B45309] bg-[#FEF3C7]/70 hover:bg-[#FEF3C7] p-2.5 rounded-lg border border-[#B45309]/20 transition group/cal">
                  <div class="flex items-center gap-2 min-w-0">
                    <i class="fa-solid fa-calendar-plus text-[#B45309] shrink-0 group-hover/cal:scale-110 transition-transform"></i>
                    <span class="truncate">New Date: <strong><?= htmlspecialchars($event['date']) ?></strong> at <strong><?= htmlspecialchars($event['time']) ?></strong></span>
                  </div>
                  <span class="text-[10px] font-bold text-[#B45309] bg-[#FEF3C7] px-2 py-0.5 rounded border border-[#B45309]/30 shrink-0 flex items-center gap-1 group-hover/cal:bg-[#B45309] group-hover/cal:text-white transition">
                    <span>Add to Cal</span>
                    <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
                  </span>
                </a>
                
                <p class="text-[11px] text-[#63615E] leading-snug">
                  All confirmed RSVPs and seat reservations remain active for the new date.
                </p>
              </div>
            </div>
          </div>

        <?php elseif ($status === 'completed'): ?>
          <a href="#memories" class="btn btn-secondary btn-sm shrink-0">
            <i class="fa-solid fa-wand-magic-sparkles text-[#87927B]"></i>
            <span>Memories</span>
          </a>

        <?php elseif ($status === 'cancelled'): ?>
          <a href="#notice" class="btn btn-sm bg-[#FEE2E2] text-[#DC2626] border border-[#DC2626]/30 hover:bg-[#F0D0D0] shrink-0">
            <i class="fa-solid fa-circle-xmark"></i>
            <span>Cancelled</span>
          </a>
        <?php endif; ?>

      </div>

      <!-- Mobile Hamburger Button -->
      <button id="foliioo-event-mobile-btn" type="button" class="md:hidden p-2 rounded-lg text-[#63615E] hover:bg-[#FAFBF6] hover:text-[#0F0F0F] focus:outline-none transition" aria-label="Toggle Navigation">
        <i class="fa-solid fa-bars text-sm"></i>
      </button>

    </div>

  </nav>

  <!-- Mobile Dropdown Menu -->
  <div id="foliioo-event-mobile-dropdown" class="hidden md:hidden mt-2 p-3 rounded-xl bg-white border border-[#EAE8E2] shadow-[0_8px_30px_rgba(37,35,33,0.06)] space-y-1 text-xs font-semibold text-[#0F0F0F]">
    <a href="#details" class="flex items-center gap-2.5 px-3 py-2 rounded-lg hover:bg-[#FAFBF6] hover:text-[#FF502B]">
      <i class="fa-solid fa-circle-info w-4 text-[#87927B]"></i>
      <span>Details</span>
    </a>
    <?php if (!empty($features['programme'])): ?>
      <a href="#programme" class="flex items-center gap-2.5 px-3 py-2 rounded-lg hover:bg-[#FAFBF6] hover:text-[#FF502B]">
        <i class="fa-solid fa-clock w-4 text-[#87927B]"></i>
        <span>Programme</span>
      </a>
    <?php endif; ?>
    <?php if (!empty($features['ticketing']) && $status !== 'cancelled'): ?>
      <a href="#tickets" class="flex items-center gap-2.5 px-3 py-2 rounded-lg hover:bg-[#FAFBF6] hover:text-[#FF502B]">
        <i class="fa-solid fa-ticket w-4 text-[#87927B]"></i>
        <span>Tickets</span>
      </a>
    <?php endif; ?>
    <?php if (!empty($features['gallery'])): ?>
      <a href="#gallery" class="flex items-center gap-2.5 px-3 py-2 rounded-lg hover:bg-[#FAFBF6] hover:text-[#FF502B]">
        <i class="fa-solid fa-image w-4 text-[#87927B]"></i>
        <span>Gallery</span>
      </a>
    <?php endif; ?>
    <?php if (!empty($features['donations'])): ?>
      <a href="#donations" class="flex items-center gap-2.5 px-3 py-2 rounded-lg hover:bg-[#FAFBF6] hover:text-[#FF502B]">
        <i class="fa-solid fa-hand-holding-heart w-4 text-[#87927B]"></i>
        <span><?= ($event['event_type'] ?? '') === 'burial' ? 'Memorial Fund' : 'Gifts' ?></span>
      </a>
    <?php endif; ?>
  </div>

</div>

<?php
if (empty($event)) {
    return;
}

$title = $event['title'] ?? 'Foliioo Event';
$eventType = $event['event_type_label'] ?? ucfirst($event['event_type'] ?? 'Event');
$status = $event['status'] ?? 'published_pre_event';
$features = $event['features_enabled'] ?? [];

$statusConfigs = [
    'published_pre_event' => [
        'label' => 'Upcoming',
        'pill_class' => 'bg-emerald-500/15 text-emerald-900 border-emerald-500/30',
        'dot' => 'bg-emerald-600'
    ],
    'in_progress' => [
        'label' => 'Live Now',
        'pill_class' => 'bg-rose-500/15 text-rose-900 border-rose-500/30 animate-pulse',
        'dot' => 'bg-rose-600 animate-ping'
    ],
    'completed' => [
        'label' => 'Concluded',
        'pill_class' => 'bg-gray-800/10 text-gray-800 border-gray-400/30',
        'dot' => 'bg-gray-600'
    ],
    'postponed' => [
        'label' => 'Postponed',
        'pill_class' => 'bg-amber-500/15 text-amber-900 border-amber-500/30',
        'dot' => 'bg-amber-600'
    ]
];
$cfg = $statusConfigs[$status] ?? $statusConfigs['published_pre_event'];
?>

<div class="sticky top-3 z-40 px-3 sm:px-4 max-w-5xl mx-auto transition-all duration-300">
  <nav class="glass-island rounded-2xl px-3 sm:px-6 h-16 flex items-center justify-between shadow-xl relative">
    
    <div class="flex items-center gap-2.5 sm:gap-3 min-w-0">
      <a href="#details" class="flex items-center gap-2 group min-w-0">
        <div class="w-8 h-8 rounded-xl bg-[#320E3B] text-white flex items-center justify-center font-serif text-sm font-bold shadow-md group-hover:scale-105 transition-transform shrink-0">
          <?= strtoupper(substr($title, 0, 1)) ?>
        </div>
        <div class="flex flex-col min-w-0">
          <span class="text-xs sm:text-sm font-extrabold text-gray-900 truncate max-w-[120px] xs:max-w-[170px] sm:max-w-xs md:max-w-sm group-hover:text-[#320E3B] transition-colors leading-tight">
            <?= htmlspecialchars($title) ?>
          </span>
          <span class="text-[9px] sm:text-[10px] font-bold text-[#320E3B] uppercase tracking-wider leading-tight truncate max-w-[120px] sm:max-w-none">
            <?= htmlspecialchars($eventType) ?>
          </span>
        </div>
      </a>

      <div class="hidden lg:inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold border <?= $cfg['pill_class'] ?> backdrop-blur-xs shrink-0">
        <span class="w-2 h-2 rounded-full <?= $cfg['dot'] ?>"></span>
        <span><?= $cfg['label'] ?></span>
      </div>
    </div>

    <div class="flex items-center gap-1.5 sm:gap-3 shrink-0">
      
      <div class="hidden md:flex items-center space-x-1 lg:space-x-2 text-xs sm:text-sm font-bold text-gray-800">
        <a href="#details" class="foliioo-event-nav-link px-2.5 py-1.5 rounded-xl hover:text-[#320E3B] hover:bg-white/60 transition">
          Details
        </a>
        
        <?php if (!empty($features['programme'])): ?>
          <a href="#programme" class="foliioo-event-nav-link px-2.5 py-1.5 rounded-xl hover:text-[#320E3B] hover:bg-white/60 transition flex items-center gap-1.5">
            <span>Programme</span>
            <?php if ($status === 'in_progress'): ?>
              <span class="w-1.5 h-1.5 rounded-full bg-rose-600 animate-pulse"></span>
            <?php endif; ?>
          </a>
        <?php endif; ?>

        <?php if (!empty($features['gallery'])): ?>
          <a href="#gallery" class="foliioo-event-nav-link px-2.5 py-1.5 rounded-xl hover:text-[#320E3B] hover:bg-white/60 transition">
            Gallery
          </a>
        <?php endif; ?>

        <?php if (!empty($features['donations'])): ?>
          <a href="#donations" class="foliioo-event-nav-link px-2.5 py-1.5 rounded-xl hover:text-[#320E3B] hover:bg-white/60 transition">
            <?= ($event['event_type'] ?? '') === 'burial' ? 'Memorial Fund' : 'Gifts' ?>
          </a>
        <?php endif; ?>
      </div>

      <div class="pl-1 sm:pl-2 md:border-l md:border-gray-400/30">
        
        <?php if (!empty($features['rsvp']) && $status === 'published_pre_event'): ?>
          <a href="#rsvp" class="inline-flex items-center gap-1 bg-[#320E3B] hover:bg-[#4A1556] text-white text-xs font-bold px-3 sm:px-4 py-2 rounded-xl shadow-md transition transform active:scale-95 shrink-0">
            <i data-lucide="calendar-check" class="w-3.5 h-3.5"></i>
            <span>RSVP</span>
          </a>

        <?php elseif ($status === 'in_progress'): ?>
          <a href="#programme" class="inline-flex items-center gap-1 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold px-3 sm:px-3.5 py-1.5 rounded-xl shadow-md animate-pulse shrink-0">
            <i data-lucide="radio" class="w-3.5 h-3.5"></i>
            <span>Live</span>
          </a>

        <?php elseif ($status === 'postponed'): ?>
          <div class="relative group/postponed">
            <button id="foliioo-postponed-btn" type="button" class="inline-flex items-center gap-1 bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold px-3 sm:px-3.5 py-1.5 rounded-xl shadow-md transition transform active:scale-95 cursor-pointer shrink-0">
              <i data-lucide="clock" class="w-3.5 h-3.5"></i>
              <span>Postponed</span>
              <i data-lucide="chevron-down" class="w-3 h-3 opacity-80 group-hover/postponed:rotate-180 transition-transform"></i>
            </button>

            <div id="foliioo-postponed-popover" class="hidden group-hover/postponed:block absolute right-0 mt-2 w-[280px] xs:w-80 sm:w-96 max-w-[calc(100vw-2rem)] rounded-2xl bg-white/95 backdrop-blur-xl border border-amber-200/90 shadow-2xl p-4 text-xs z-50 text-gray-800 transition-all duration-200">
              <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                <div class="flex items-center gap-1.5 text-amber-700 font-extrabold text-xs">
                  <i data-lucide="alert-circle" class="w-4 h-4 text-amber-500"></i>
                  <span>Postponement Notice</span>
                </div>
                <span class="text-[10px] font-black uppercase tracking-wider bg-amber-100 text-amber-800 px-2 py-0.5 rounded-full">Notice</span>
              </div>
              
              <div class="py-3 space-y-2.5">
                <p class="font-extrabold text-gray-900 text-xs leading-relaxed">
                  <?= htmlspecialchars($event['postponement_reason'] ?? 'This event has been rescheduled to a future date.') ?>
                </p>
                
                <div class="flex items-center gap-2.5 text-xs text-gray-600 bg-amber-50/70 p-2.5 rounded-xl border border-amber-100">
                  <i data-lucide="calendar" class="w-4 h-4 text-amber-600 shrink-0"></i>
                  <span>New Date: <strong><?= htmlspecialchars($event['date']) ?></strong> at <strong><?= htmlspecialchars($event['time']) ?></strong></span>
                </div>
                
                <p class="text-[11px] text-gray-400 leading-snug">
                  All confirmed RSVPs and seat reservations remain active for the new date.
                </p>
              </div>
            </div>
          </div>

        <?php elseif ($status === 'completed'): ?>
          <a href="#gallery" class="inline-flex items-center gap-1 bg-gray-900 hover:bg-black text-white text-xs font-bold px-3 sm:px-3.5 py-1.5 rounded-xl shadow-md shrink-0">
            <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
            <span>Memories</span>
          </a>
        <?php endif; ?>

      </div>

      <button id="foliioo-event-mobile-btn" type="button" class="md:hidden p-2 rounded-xl text-gray-700 hover:bg-white/60 focus:outline-none transition" aria-label="Toggle Navigation">
        <i data-lucide="menu" class="w-5 h-5"></i>
      </button>

    </div>

  </nav>

  <div id="foliioo-event-mobile-dropdown" class="hidden md:hidden mt-2 p-3 rounded-2xl glass-island shadow-xl border border-white/60 space-y-1 text-xs font-bold text-gray-800">
    <a href="#details" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-white/70 hover:text-[#320E3B]">
      <i data-lucide="info" class="w-4 h-4 text-gray-400"></i>
      <span>Details</span>
    </a>
    <?php if (!empty($features['programme'])): ?>
      <a href="#programme" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-white/70 hover:text-[#320E3B]">
        <i data-lucide="clock" class="w-4 h-4 text-gray-400"></i>
        <span>Programme</span>
      </a>
    <?php endif; ?>
    <?php if (!empty($features['gallery'])): ?>
      <a href="#gallery" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-white/70 hover:text-[#320E3B]">
        <i data-lucide="image" class="w-4 h-4 text-gray-400"></i>
        <span>Gallery</span>
      </a>
    <?php endif; ?>
    <?php if (!empty($features['donations'])): ?>
      <a href="#donations" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-white/70 hover:text-[#320E3B]">
        <i data-lucide="heart-handshake" class="w-4 h-4 text-gray-400"></i>
        <span><?= ($event['event_type'] ?? '') === 'burial' ? 'Memorial Fund' : 'Gifts' ?></span>
      </a>
    <?php endif; ?>
  </div>

</div>

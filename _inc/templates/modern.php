<?php
$title = $event['title'] ?? 'Foliioo Event';
$subtitle = $event['subtitle'] ?? '';
$dateFormatted = date('l, F j, Y', strtotime($event['date'] ?? 'now'));
$venue = $event['venue'] ?? [];
?>

<div class="space-y-12 pb-16 font-sans">

  <!-- Modern Hero -->
  <div class="relative rounded-3xl overflow-hidden shadow-2xl border border-gray-800 bg-[#0D160B] text-white">
    <div class="relative min-h-[460px] sm:min-h-[520px] w-full flex flex-col justify-between p-6 sm:p-10">
      
      <?php if (!empty($event['cover_image'])): ?>
        <img src="<?= htmlspecialchars($event['cover_image']) ?>" class="absolute inset-0 w-full h-full object-cover object-center opacity-30" alt="Event Cover">
      <?php endif; ?>
      <div class="absolute inset-0 bg-gradient-to-t from-[#0D160B] via-[#0D160B]/70 to-[#111827]/80"></div>

      <!-- Top Glass Navbar Container -->
      <div class="relative z-30 -mx-3 sm:mx-0">
        <?php require __DIR__ . '/../nav/nav-event.php'; ?>
      </div>

      <!-- Hero Content -->
      <div class="relative z-20 space-y-4 max-w-3xl pt-12 pb-2">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/20 text-white text-xs font-bold tracking-wider uppercase backdrop-blur-xs">
          <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
          <span><?= htmlspecialchars($event['event_type_label'] ?? 'Event') ?></span>
          <span>•</span>
          <span class="font-mono text-gray-300"><?= $status === 'in_progress' ? 'LIVE NOW' : ($status === 'completed' ? 'CONCLUDED' : 'OFFICIAL PASS') ?></span>
        </div>

        <h1 class="text-3xl/tight sm:text-4xl/[1.2] lg:text-5xl/[1.2] font-bold tracking-tight text-amber-50 drop-shadow-md">
          <?= htmlspecialchars($title) ?>
        </h1>

        <?php if (!empty($subtitle)): ?>
          <p class="text-gray-300 text-sm sm:text-lg font-medium max-w-2xl leading-relaxed">
            <?= htmlspecialchars($subtitle) ?>
          </p>
        <?php endif; ?>

        <div class="pt-2 flex flex-wrap items-center gap-3 sm:gap-4 text-xs sm:text-sm text-gray-200 font-medium">
          <div class="flex items-center gap-2 bg-white/10 backdrop-blur-md px-3.5 py-2 rounded-xl border border-white/10">
            <i data-lucide="calendar" class="w-4 h-4 text-indigo-400"></i>
            <span><?= htmlspecialchars($dateFormatted) ?></span>
          </div>

          <div class="flex items-center gap-2 bg-white/10 backdrop-blur-md px-3.5 py-2 rounded-xl border border-white/10">
            <i data-lucide="clock" class="w-4 h-4 text-indigo-400"></i>
            <span><?= htmlspecialchars($event['time'] ?? '18:00') ?> (<?= htmlspecialchars($event['duration'] ?? '4 hours') ?>)</span>
          </div>

          <?php if (!empty($venue['name'])): ?>
            <div class="flex items-center gap-2 bg-white/10 backdrop-blur-md px-3.5 py-2 rounded-xl border border-white/10">
              <i data-lucide="map-pin" class="w-4 h-4 text-indigo-400"></i>
              <span class="truncate max-w-[200px] sm:max-w-xs"><?= htmlspecialchars($venue['name']) ?></span>
            </div>
          <?php endif; ?>
        </div>

      </div>

    </div>
  </div>

  <!-- Body Content -->
  <main class="max-w-5xl mx-auto px-4 sm:px-6 space-y-12">
    
    <!-- About / Overview Summary -->
    <?php if (!empty($event['tribute_summary'])): ?>
      <section id="details" class="scroll-mt-24 bg-white rounded-3xl border border-gray-200/80 p-6 sm:p-8 shadow-2xs">
        <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">
          <i data-lucide="info" class="w-3.5 h-3.5 text-[#320E3B]"></i>
          <span>Event Overview</span>
        </div>
        <p class="text-base sm:text-lg text-gray-800 leading-relaxed font-normal">
          <?= htmlspecialchars($event['tribute_summary']) ?>
        </p>
      </section>
    <?php endif; ?>

    <!-- Live Programme / Schedule -->
    <?php require __DIR__ . '/../sections/section-programme.php'; ?>

    <!-- RSVP Section (Module 7) -->
    <?php require __DIR__ . '/../sections/section-rsvp.php'; ?>

    <!-- Tickets & Tables (Module 8) -->
    <?php require __DIR__ . '/../sections/section-tickets.php'; ?>

    <!-- Donations & Endowments (Module 9) -->
    <?php require __DIR__ . '/../sections/section-donations.php'; ?>

    <!-- Post-Event Memories & Gallery (Module 14) -->
    <?php require __DIR__ . '/../sections/section-memories.php'; ?>

    <!-- Venue & Directions -->
    <?php require __DIR__ . '/../sections/section-venue.php'; ?>

  </main>

</div>

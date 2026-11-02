<?php
/**
 * Foliioo Design System V1.1 — Modern Template Partial
 * Heading font: DM Sans | Body: DM Sans
 */
$title = $event['title'] ?? 'Foliioo Event';
$subtitle = $event['subtitle'] ?? '';
$dateFormatted = date('l, F j, Y', strtotime($event['date'] ?? 'now'));
$venue = $event['venue'] ?? [];
?>

<div class="space-y-12 pb-16 font-sans">

  <!-- Modern Hero (Dark Section: Deep #252321) -->
  <div class="relative rounded-2xl overflow-hidden shadow-[0_8px_30px_rgba(37,35,33,0.06)] border border-[#E9E4DD] bg-[#252321] text-white">
    <div class="relative min-h-[460px] sm:min-h-[520px] w-full flex flex-col justify-between p-6 sm:p-10">
      
      <?php if (!empty($event['cover_image'])): ?>
        <img src="<?= htmlspecialchars($event['cover_image']) ?>" class="absolute inset-0 w-full h-full object-cover object-center opacity-30" alt="Event Cover">
      <?php endif; ?>
      <div class="absolute inset-0 bg-gradient-to-t from-[#252321] via-[#252321]/60 to-black/40"></div>

      <!-- Top Navbar Container -->
      <div class="relative z-30 -mx-3 sm:mx-0">
        <?php require __DIR__ . '/../nav/nav-event.php'; ?>
      </div>

      <!-- Hero Content -->
      <div class="relative z-20 space-y-4 max-w-3xl pt-12 pb-2">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/20 text-[#EEF0EA] text-xs font-semibold tracking-wider uppercase backdrop-blur-xs">
          <span class="w-2 h-2 rounded-full bg-[#87927B]"></span>
          <span><?= htmlspecialchars($event['event_type_label'] ?? 'Event') ?></span>
          <span>•</span>
          <span class="font-sans text-white"><?= $status === 'in_progress' ? 'LIVE NOW' : ($status === 'completed' ? 'CONCLUDED' : 'OFFICIAL PASS') ?></span>
        </div>

        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold tracking-tight text-white leading-[1.15]">
          <?= htmlspecialchars($title) ?>
        </h1>

        <?php if (!empty($subtitle)): ?>
          <p class="text-[#F3EDE5]/90 text-base sm:text-lg font-normal max-w-2xl leading-relaxed">
            <?= htmlspecialchars($subtitle) ?>
          </p>
        <?php endif; ?>

        <div class="pt-2 flex flex-wrap items-center gap-3 sm:gap-4 text-xs sm:text-sm text-[#F3EDE5]">
          <div class="flex items-center gap-2 bg-white/10 backdrop-blur-md px-3.5 py-2 rounded-lg border border-white/15">
            <i class="fa-solid fa-calendar text-[#87927B]"></i>
            <span><?= htmlspecialchars($dateFormatted) ?></span>
          </div>

          <div class="flex items-center gap-2 bg-white/10 backdrop-blur-md px-3.5 py-2 rounded-lg border border-white/15">
            <i class="fa-solid fa-clock text-[#87927B]"></i>
            <span><?= htmlspecialchars($event['time'] ?? '18:00') ?> (<?= htmlspecialchars($event['duration'] ?? '4 hours') ?>)</span>
          </div>

          <?php if (!empty($venue['name'])): ?>
            <div class="flex items-center gap-2 bg-white/10 backdrop-blur-md px-3.5 py-2 rounded-lg border border-white/15">
              <i class="fa-solid fa-location-dot text-[#87927B]"></i>
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
      <section id="details" class="scroll-mt-24 card p-6 sm:p-8">
        <div class="eyebrow mb-2">
          <i class="fa-solid fa-circle-info"></i>
          <span>Event Overview</span>
        </div>
        <p class="text-base sm:text-lg text-[#252321] leading-relaxed font-normal">
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

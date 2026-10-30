<?php
$title = $event['title'] ?? 'Foliioo Event';
$subtitle = $event['subtitle'] ?? '';
$dateFormatted = date('l, F j, Y', strtotime($event['date'] ?? 'now'));
$venue = $event['venue'] ?? [];
?>

<div class="space-y-12 pb-16">

  <!-- Floral & Romantic Hero -->
  <div class="relative rounded-3xl overflow-hidden shadow-2xl border border-rose-100 bg-gradient-to-tr from-[#320E3B] via-rose-950 to-slate-900 text-white">
    <div class="relative min-h-[460px] sm:min-h-[520px] w-full flex flex-col justify-between p-6 sm:p-10">
      
      <?php if (!empty($event['cover_image'])): ?>
        <img src="<?= htmlspecialchars($event['cover_image']) ?>" class="absolute inset-0 w-full h-full object-cover object-center opacity-40 mix-blend-overlay" alt="Event Cover">
      <?php endif; ?>
      <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-black/30"></div>

      <!-- Top Glass Navbar Container -->
      <div class="relative z-30 font-sans -mx-3 sm:mx-0">
        <?php require __DIR__ . '/../nav/nav-event.php'; ?>
      </div>

      <!-- Hero Content -->
      <div class="relative z-20 space-y-4 max-w-3xl pt-12 pb-2">
        <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-rose-500/20 border border-rose-400/30 text-rose-200 text-xs font-bold tracking-wider uppercase backdrop-blur-xs">
          <i data-lucide="heart" class="w-3.5 h-3.5 text-rose-300"></i>
          <span><?= htmlspecialchars($event['event_type_label'] ?? 'Wedding Celebration') ?></span>
        </div>

        <h1 class="text-3xl/tight sm:text-4xl/[1.2] font-serif lg:text-5xl/[1.2] font-bold tracking-tight text-amber-50 drop-shadow-md">
          <?= htmlspecialchars($title) ?>
        </h1>

        <?php if (!empty($subtitle)): ?>
          <p class="text-rose-100/90 text-sm sm:text-lg italic font-serif max-w-2xl leading-relaxed">
            <?= htmlspecialchars($subtitle) ?>
          </p>
        <?php endif; ?>

        <div class="pt-2 flex flex-wrap items-center gap-3 sm:gap-4 text-xs sm:text-sm text-rose-100">
          <div class="flex items-center gap-2 bg-white/10 backdrop-blur-md px-3.5 py-2 rounded-xl border border-white/15">
            <i data-lucide="calendar" class="w-4 h-4 text-rose-300"></i>
            <span><?= htmlspecialchars($dateFormatted) ?></span>
          </div>

          <div class="flex items-center gap-2 bg-white/10 backdrop-blur-md px-3.5 py-2 rounded-xl border border-white/15">
            <i data-lucide="clock" class="w-4 h-4 text-rose-300"></i>
            <span><?= htmlspecialchars($event['time'] ?? '13:00') ?> (<?= htmlspecialchars($event['duration'] ?? '7 hours') ?>)</span>
          </div>

          <?php if (!empty($venue['name'])): ?>
            <div class="flex items-center gap-2 bg-white/10 backdrop-blur-md px-3.5 py-2 rounded-xl border border-white/15">
              <i data-lucide="map-pin" class="w-4 h-4 text-rose-300"></i>
              <span class="truncate max-w-[200px] sm:max-w-xs"><?= htmlspecialchars($venue['name']) ?></span>
            </div>
          <?php endif; ?>
        </div>

      </div>

    </div>
  </div>

  <!-- Body Content -->
  <main class="max-w-5xl mx-auto px-4 sm:px-6 space-y-12">
    
    <!-- About / Love Story or Welcome -->
    <?php if (!empty($event['tribute_summary'])): ?>
      <section id="details" class="scroll-mt-24 bg-white rounded-3xl border border-rose-100 p-6 sm:p-8 shadow-2xs">
        <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-[#320E3B] mb-2">
          <i data-lucide="sparkles" class="w-3.5 h-3.5 text-rose-500"></i>
          <span>Welcome From The Couple</span>
        </div>
        <p class="text-base sm:text-lg text-gray-800 leading-relaxed font-serif italic">
          <?= htmlspecialchars($event['tribute_summary']) ?>
        </p>
      </section>
    <?php endif; ?>

    <!-- Live Programme / Order of Service -->
    <?php require __DIR__ . '/../sections/section-programme.php'; ?>

    <!-- RSVP Section (Module 7) -->
    <?php require __DIR__ . '/../sections/section-rsvp.php'; ?>

    <!-- Tickets & Tables (Module 8) -->
    <?php require __DIR__ . '/../sections/section-tickets.php'; ?>

    <!-- Donations / Honeymoon Fund (Module 9) -->
    <?php require __DIR__ . '/../sections/section-donations.php'; ?>

    <!-- Post-Event Memories & Gallery (Module 14) -->
    <?php require __DIR__ . '/../sections/section-memories.php'; ?>

    <!-- Venue & Directions -->
    <?php require __DIR__ . '/../sections/section-venue.php'; ?>

  </main>

</div>

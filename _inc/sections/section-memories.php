<?php
$features = $event['features_enabled'] ?? [];
if (empty($features['gallery']) && empty($memories) && $status !== 'completed') {
    return;
}

$memories = $memories ?? MockData::getMemoriesForEvent($event['id']);
$previewMemories = array_slice($memories, 0, 4);
?>

<section id="memories" class="scroll-mt-24 space-y-4">
  <div class="flex items-center justify-between border-b border-gray-200/80 pb-3">
    <div>
      <h2 class="text-xl sm:text-2xl font-extrabold text-gray-900 tracking-tight">
        <?= $status === 'completed' ? 'Event Memories & Tributes' : 'Moments & Photo Highlights' ?>
      </h2>
      <p class="text-xs text-gray-500 mt-0.5">Cherished photos, guest tributes, and memorable moments from the celebration</p>
    </div>

    <a href="/gallery.php?event=<?= urlencode($event['slug']) ?>" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#320E3B] hover:text-[#4A1556] transition">
      <span>View Full Gallery (<?= count($memories) ?>)</span>
      <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
    </a>
  </div>

  <?php if ($status === 'completed' && !empty($event['post_event_message'])): ?>
    <div class="bg-gradient-to-r from-gray-900 via-[#1F2128] to-[#320E3B] text-white rounded-3xl p-6 sm:p-8 shadow-sm">
      <div class="flex items-start gap-4">
        <div class="w-10 h-10 rounded-2xl bg-white/10 flex items-center justify-center shrink-0">
          <i data-lucide="award" class="w-5 h-5 text-amber-300"></i>
        </div>
        <div class="space-y-1">
          <span class="text-[10px] font-bold uppercase tracking-widest text-amber-300">Host Gratitude</span>
          <h3 class="text-lg font-extrabold">Thank You For Celebrating With Us</h3>
          <p class="text-xs text-gray-200 leading-relaxed max-w-2xl">
            <?= htmlspecialchars($event['post_event_message']) ?>
          </p>
        </div>
      </div>
    </div>
  <?php endif; ?>

  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    <?php foreach ($previewMemories as $item): ?>
      <?php
        $hasImage = !empty($item['image_url']);
      ?>
      <div class="bg-white rounded-3xl border border-gray-200/80 overflow-hidden shadow-2xs flex flex-col justify-between hover:shadow-md transition">
        <?php if ($hasImage): ?>
          <div class="relative h-44 w-full bg-gray-100 overflow-hidden">
            <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="Memory photo" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
          </div>
        <?php else: ?>
          <div class="p-5 pb-0">
            <i data-lucide="quote" class="w-6 h-6 text-[#320E3B]/30"></i>
          </div>
        <?php endif; ?>

        <div class="p-4 sm:p-5 flex-1 flex flex-col justify-between space-y-3">
          <p class="text-xs text-gray-700 leading-relaxed italic">
            "<?= htmlspecialchars($item['message']) ?>"
          </p>

          <div class="pt-3 border-t border-gray-100 flex items-center gap-2.5">
            <?php if (!empty($item['avatar'])): ?>
              <img src="<?= htmlspecialchars($item['avatar']) ?>" class="w-7 h-7 rounded-full object-cover" alt="">
            <?php else: ?>
              <div class="w-7 h-7 rounded-full bg-[#320E3B]/10 text-[#320E3B] flex items-center justify-center font-bold text-xs">
                <?= strtoupper(substr($item['author_name'] ?? 'G', 0, 1)) ?>
              </div>
            <?php endif; ?>
            <div class="min-w-0">
              <span class="text-xs font-bold text-gray-900 block truncate"><?= htmlspecialchars($item['author_name'] ?? 'Guest') ?></span>
              <span class="text-[10px] text-gray-400 block truncate"><?= htmlspecialchars($item['author_role'] ?? 'Attendee') ?></span>
            </div>
          </div>
        </div>

      </div>
    <?php endforeach; ?>
  </div>

  <div class="flex items-center justify-between p-4 rounded-2xl bg-gray-50 border border-gray-200 text-xs text-gray-600">
    <div class="flex items-center gap-2">
      <i data-lucide="camera" class="w-4 h-4 text-[#320E3B]"></i>
      <span>Have photos or videos from this event? Share them to the public album.</span>
    </div>
    <a href="/gallery.php?event=<?= urlencode($event['slug']) ?>" class="font-bold text-[#320E3B] hover:underline shrink-0">
      Open Gallery Page →
    </a>
  </div>
</section>

<?php
$features = $event['features_enabled'] ?? [];
if (empty($features['gallery']) && empty($memories) && $status !== 'completed') {
    return;
}

$memories = $memories ?? MockData::getMemoriesForEvent($event['id']);
$previewMemories = array_slice($memories, 0, 4);
?>

<section id="memories" class="scroll-mt-24 space-y-4">
  <div class="flex items-center justify-between border-b border-[#E9E4DD] pb-3">
    <div>
      <h2 class="font-display text-xl sm:text-2xl font-bold text-[#252321] tracking-tight">
        <?= $status === 'completed' ? 'Event Memories & Tributes' : 'Moments & Photo Highlights' ?>
      </h2>
      <p class="text-xs text-[#6B675F] mt-0.5">Cherished photos, guest tributes, and memorable moments from the celebration</p>
    </div>

    <a href="/gallery.php?event=<?= urlencode($event['slug']) ?>" class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#9A5F44] hover:text-[#7E4A33] transition">
      <span>View Full Gallery (<?= count($memories) ?>)</span>
      <i class="fa-solid fa-arrow-right text-xs"></i>
    </a>
  </div>

  <?php if ($status === 'completed' && !empty($event['post_event_message'])): ?>
    <div class="bg-[#252321] text-white rounded-2xl p-6 sm:p-8 shadow-sm">
      <div class="flex items-start gap-4">
        <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center shrink-0">
          <i class="fa-solid fa-award text-lg text-[#87927B]"></i>
        </div>
        <div class="space-y-1">
          <span class="eyebrow text-[#87927B]">Host Gratitude</span>
          <h3 class="font-display text-lg font-bold">Thank You For Celebrating With Us</h3>
          <p class="text-xs text-[#FAF8F5]/80 leading-relaxed max-w-2xl">
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
      <div class="card card-hover overflow-hidden flex flex-col justify-between">
        <?php if ($hasImage): ?>
          <div class="relative h-44 w-full bg-[#F3EDE5] overflow-hidden">
            <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="Memory photo" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
          </div>
        <?php else: ?>
          <div class="p-5 pb-0">
            <i class="fa-solid fa-quote-left text-xl text-[#9A5F44]/30"></i>
          </div>
        <?php endif; ?>

        <div class="p-4 sm:p-5 flex-1 flex flex-col justify-between space-y-3">
          <p class="text-xs text-[#252321] leading-relaxed italic">
            "<?= htmlspecialchars($item['message']) ?>"
          </p>

          <div class="pt-3 border-t border-[#E9E4DD] flex items-center gap-2.5">
            <?php if (!empty($item['avatar'])): ?>
              <img src="<?= htmlspecialchars($item['avatar']) ?>" class="w-7 h-7 rounded-full object-cover" alt="">
            <?php else: ?>
              <div class="w-7 h-7 rounded-full bg-[#EEF0EA] text-[#87927B] flex items-center justify-center font-bold text-xs">
                <?= strtoupper(substr($item['author_name'] ?? 'G', 0, 1)) ?>
              </div>
            <?php endif; ?>
            <div class="min-w-0">
              <span class="text-xs font-semibold text-[#252321] block truncate"><?= htmlspecialchars($item['author_name'] ?? 'Guest') ?></span>
              <span class="text-[10px] text-[#6B675F] block truncate"><?= htmlspecialchars($item['author_role'] ?? 'Attendee') ?></span>
            </div>
          </div>
        </div>

      </div>
    <?php endforeach; ?>
  </div>

  <div class="flex items-center justify-between p-4 rounded-xl bg-[#F3EDE5] border border-[#E9E4DD] text-xs text-[#252321]">
    <div class="flex items-center gap-2">
      <i class="fa-solid fa-camera text-[#9A5F44]"></i>
      <span>Have photos or videos from this event? Share them to the public album.</span>
    </div>
    <a href="/gallery.php?event=<?= urlencode($event['slug']) ?>" class="font-semibold text-[#9A5F44] hover:text-[#7E4A33] shrink-0">
      Open Gallery Page &rarr;
    </a>
  </div>
</section>
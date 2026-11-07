<?php
$features = $event['features_enabled'] ?? [];
if (empty($features['gallery']) && empty($memories) && $status !== 'completed') {
    return;
}

$memories = $memories ?? MockData::getMemoriesForEvent($event['id']);
$photoMemories = array_values(array_filter($memories, fn($m) => !empty($m['image_url'])));
$previewPhotos = array_slice($photoMemories, 0, 4);

if (empty($photoMemories) && empty($event['post_event_message'])) {
    return;
}
?>

<section id="gallery" class="scroll-mt-24 space-y-4">
  <div class="flex items-center justify-between border-b border-[#EAE8E2] pb-3">
    <div>
      <h2 class="font-display text-xl sm:text-2xl font-bold text-[#0F0F0F] tracking-tight">
        <?= $status === 'completed' ? 'Event Photo Gallery' : 'Moments & Photo Highlights' ?>
      </h2>
      <p class="text-xs text-[#63615E] mt-0.5">Curated photographs and highlights from this event</p>
    </div>

    <?php if (!empty($photoMemories)): ?>
      <a href="<?= base_url('/gallery/' . urlencode($event['slug'])) ?>" class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#FF502B] hover:text-[#E03A17] transition">
        <span>View Full Album (<?= count($photoMemories) ?>)</span>
        <i class="fa-solid fa-arrow-right text-xs"></i>
      </a>
    <?php endif; ?>
  </div>

  <?php if ($status === 'completed' && !empty($event['post_event_message'])): ?>
    <div class="bg-[#0F0F0F] text-white rounded-2xl p-6 sm:p-8 shadow-sm">
      <div class="flex items-start gap-4">
        <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center shrink-0">
          <i class="fa-solid fa-award text-lg text-[#87927B]"></i>
        </div>
        <div class="space-y-1">
          <span class="eyebrow text-[#87927B]">Host Gratitude</span>
          <h3 class="font-display text-lg font-bold">Thank You For Celebrating With Us</h3>
          <p class="text-xs text-[#FAFBF6]/80 leading-relaxed max-w-2xl">
            <?= htmlspecialchars($event['post_event_message']) ?>
          </p>
        </div>
      </div>
    </div>
  <?php endif; ?>

  <?php if (!empty($previewPhotos)): ?>
    <!-- Photo-First Grid Preview -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
      <?php foreach ($previewPhotos as $idx => $item): ?>
        <a 
          href="<?= base_url('/gallery/' . urlencode($event['slug'])) ?>" 
          class="aspect-square bg-[#F3F4EE] rounded-xl overflow-hidden border border-[#EAE8E2] cursor-pointer group relative shadow-2xs hover:shadow-md transition-all duration-300 block"
          title="Open event gallery"
        >
          <img 
            src="<?= htmlspecialchars($item['image_url']) ?>" 
            alt="Event moment <?= $idx + 1 ?>" 
            class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
            loading="lazy"
          >
          <div class="absolute inset-0 bg-[#0F0F0F]/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white">
            <i class="fa-solid fa-expand text-xl drop-shadow-md"></i>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>
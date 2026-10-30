<?php
$venue = $event['venue'] ?? [];
if (empty($venue['name'])) {
    return;
}
?>

<section id="venue" class="scroll-mt-24 space-y-4">
  <div class="border-b border-gray-200/80 pb-3">
    <h2 class="text-xl sm:text-2xl font-extrabold text-gray-900 tracking-tight">Venue & Location</h2>
    <p class="text-xs text-gray-500 mt-0.5">Directions, parking, and arrival information</p>
  </div>

  <div class="bg-white rounded-3xl border border-gray-200/80 p-5 sm:p-7 shadow-2xs space-y-5">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div class="space-y-1.5">
        <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#320E3B]/10 text-[#320E3B]">
          <i data-lucide="map-pin" class="w-3 h-3"></i>
          <span>Official Event Venue</span>
        </div>
        <h3 class="text-base sm:text-lg font-extrabold text-gray-900"><?= htmlspecialchars($venue['name']) ?></h3>
        <p class="text-xs text-gray-600 leading-relaxed"><?= htmlspecialchars($venue['address'] ?? '') ?></p>
      </div>

      <?php if (!empty($venue['map_link'])): ?>
        <a href="<?= htmlspecialchars($venue['map_link']) ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-[#320E3B] hover:bg-[#4A1556] text-white text-xs font-bold transition shadow-xs shrink-0 active:scale-95">
          <i data-lucide="navigation" class="w-4 h-4"></i>
          <span>Open in Google Maps</span>
        </a>
      <?php endif; ?>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-4 border-t border-gray-100 text-xs">
      <div class="flex items-start gap-2.5 p-3 rounded-xl bg-gray-50/70 border border-gray-100">
        <i data-lucide="car" class="w-4 h-4 text-gray-500 mt-0.5 shrink-0"></i>
        <div>
          <span class="font-bold text-gray-900 block">Valet & Parking</span>
          <span class="text-gray-500 text-[11px]">Designated parking bays on site for all invited guests.</span>
        </div>
      </div>

      <div class="flex items-start gap-2.5 p-3 rounded-xl bg-gray-50/70 border border-gray-100">
        <i data-lucide="shield-check" class="w-4 h-4 text-gray-500 mt-0.5 shrink-0"></i>
        <div>
          <span class="font-bold text-gray-900 block">Door Verification</span>
          <span class="text-gray-500 text-[11px]">Present your digital RSVP or ticket pass at the entry gate.</span>
        </div>
      </div>

      <div class="flex items-start gap-2.5 p-3 rounded-xl bg-gray-50/70 border border-gray-100">
        <i data-lucide="clock" class="w-4 h-4 text-gray-500 mt-0.5 shrink-0"></i>
        <div>
          <span class="font-bold text-gray-900 block">Doors Open</span>
          <span class="text-gray-500 text-[11px]">Prompt arrival recommended 30 minutes before schedule.</span>
        </div>
      </div>
    </div>
  </div>
</section>

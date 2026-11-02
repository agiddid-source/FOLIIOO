<?php
/**
 * Foliioo Design System V1.1 — Venue & Location Section
 */
$venue = $event['venue'] ?? [];
if (empty($venue['name'])) {
    return;
}
?>

<section id="venue" class="scroll-mt-24 space-y-4 font-sans">
  <div class="border-b border-[#E9E4DD] pb-3">
    <h2 class="font-display text-2xl sm:text-3xl font-bold text-[#252321] tracking-tight">Venue & Location</h2>
    <p class="text-xs text-[#6B675F] mt-0.5">Directions, parking, and arrival information</p>
  </div>

  <div class="card p-5 sm:p-7 space-y-5">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div class="space-y-1.5">
        <span class="badge badge-sage text-xs">
          <i class="fa-solid fa-location-dot"></i>
          <span>Official Event Venue</span>
        </span>
        <h3 class="text-base sm:text-lg font-bold text-[#252321]"><?= htmlspecialchars($venue['name']) ?></h3>
        <p class="text-xs text-[#6B675F] leading-relaxed"><?= htmlspecialchars($venue['address'] ?? '') ?></p>
      </div>

      <?php if (!empty($venue['map_link'])): ?>
        <a href="<?= htmlspecialchars($venue['map_link']) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-primary btn-sm shrink-0">
          <i class="fa-solid fa-diamond-turn-right"></i>
          <span>Open in Google Maps</span>
        </a>
      <?php endif; ?>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-4 border-t border-[#E9E4DD] text-xs">
      <div class="flex items-start gap-2.5 p-3 rounded-lg bg-[#FAF8F5] border border-[#E9E4DD]">
        <i class="fa-solid fa-square-parking text-[#87927B] mt-0.5 shrink-0 text-sm"></i>
        <div>
          <span class="font-bold text-[#252321] block">Valet & Parking</span>
          <span class="text-[#6B675F] text-[12px]">Designated parking bays on site for all invited guests.</span>
        </div>
      </div>

      <div class="flex items-start gap-2.5 p-3 rounded-lg bg-[#FAF8F5] border border-[#E9E4DD]">
        <i class="fa-solid fa-shield-halved text-[#87927B] mt-0.5 shrink-0 text-sm"></i>
        <div>
          <span class="font-bold text-[#252321] block">Door Verification</span>
          <span class="text-[#6B675F] text-[12px]">Present your digital RSVP or ticket pass at the entry gate.</span>
        </div>
      </div>

      <div class="flex items-start gap-2.5 p-3 rounded-lg bg-[#FAF8F5] border border-[#E9E4DD]">
        <i class="fa-solid fa-door-open text-[#87927B] mt-0.5 shrink-0 text-sm"></i>
        <div>
          <span class="font-bold text-[#252321] block">Doors Open</span>
          <span class="text-[#6B675F] text-[12px]">Prompt arrival recommended 30 minutes before schedule.</span>
        </div>
      </div>
    </div>
  </div>
</section>

<?php
/**
 * Foliioo Design System V1.1 — Live Programme Section
 */
if (empty($programmes)) {
    return;
}
?>

<section id="programme" class="scroll-mt-24 space-y-4 font-sans">
  <div class="flex items-center justify-between border-b border-[#E9E4DD] pb-3">
    <div>
      <h2 class="font-display text-2xl sm:text-3xl font-bold text-[#252321] tracking-tight">Order of Service & Programme</h2>
      <p class="text-xs text-[#6B675F] mt-0.5">Live schedule controlled by the host committee</p>
    </div>
    <?php if ($status === 'in_progress'): ?>
      <span class="badge badge-accent animate-pulse text-xs">
        <span class="w-2 h-2 rounded-full bg-[#9A5F44] animate-ping"></span>
        <span>Live Session Active</span>
      </span>
    <?php endif; ?>
  </div>

  <div class="space-y-3">
    <?php foreach ($programmes as $idx => $item): ?>
      <?php
        $itemStatus = $item['status'] ?? 'upcoming';
        $isLive = ($itemStatus === 'in_progress');
        $isDone = ($itemStatus === 'completed');
      ?>
      <div class="card p-4 sm:p-5 transition-all duration-200 <?= $isLive ? 'border-[#9A5F44] bg-[#FAF8F5]' : ($isDone ? 'opacity-75 bg-[#F3EDE5]/50' : '') ?>">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
          
          <div class="flex items-start gap-3">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-xs shrink-0 mt-0.5 <?= $isLive ? 'bg-[#9A5F44] text-white' : ($isDone ? 'bg-[#3F6B48] text-white' : 'bg-[#EEF0EA] text-[#87927B]') ?>">
              <?php if ($isDone): ?>
                <i class="fa-solid fa-check text-xs"></i>
              <?php elseif ($isLive): ?>
                <i class="fa-solid fa-tower-broadcast text-xs animate-pulse"></i>
              <?php else: ?>
                <span><?= $idx + 1 ?></span>
              <?php endif; ?>
            </div>

            <div>
              <div class="flex items-center gap-2 flex-wrap">
                <span class="text-xs font-semibold text-[#87927B] font-mono"><?= htmlspecialchars($item['time'] ?? '') ?></span>
                <span class="text-[#D8D1C8]">•</span>
                <h3 class="text-sm sm:text-base font-bold text-[#252321] leading-snug">
                  <?= htmlspecialchars($item['title'] ?? '') ?>
                </h3>
              </div>

              <?php if (!empty($item['presenter'])): ?>
                <p class="text-xs text-[#6B675F] mt-1 flex items-center gap-1.5">
                  <i class="fa-solid fa-user text-[11px] text-[#A19B93]"></i>
                  <span><?= htmlspecialchars($item['presenter']) ?></span>
                </p>
              <?php endif; ?>

              <?php if (!empty($item['description'])): ?>
                <p class="text-xs text-[#6B675F] mt-1 leading-relaxed">
                  <?= htmlspecialchars($item['description']) ?>
                </p>
              <?php endif; ?>
            </div>
          </div>

          <div class="sm:self-center shrink-0 pl-11 sm:pl-0">
            <?php if ($isLive): ?>
              <span class="badge badge-accent font-bold uppercase tracking-wider text-[11px]">
                Happening Now
              </span>
            <?php elseif ($isDone): ?>
              <span class="badge badge-success font-semibold text-[11px]">
                Completed
              </span>
            <?php else: ?>
              <span class="badge badge-sage font-semibold text-[11px]">
                Upcoming
              </span>
            <?php endif; ?>
          </div>

        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

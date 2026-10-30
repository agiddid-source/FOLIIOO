<?php
if (empty($programmes)) {
    return;
}
?>

<section id="programme" class="scroll-mt-24 space-y-4">
  <div class="flex items-center justify-between border-b border-gray-200/80 pb-3">
    <div>
      <h2 class="text-xl sm:text-2xl font-extrabold text-gray-900 tracking-tight">Order of Service & Programme</h2>
      <p class="text-xs text-gray-500 mt-0.5">Live schedule controlled by the host committee</p>
    </div>
    <?php if ($status === 'in_progress'): ?>
      <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200 animate-pulse">
        <span class="w-2 h-2 rounded-full bg-rose-600 animate-ping"></span>
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
      <div class="p-4 sm:p-5 rounded-2xl border transition-all duration-200 <?= $isLive ? 'bg-rose-50/40 border-rose-200 shadow-sm' : ($isDone ? 'bg-gray-50/70 border-gray-200/60 opacity-80' : 'bg-white border-gray-200/80 hover:border-gray-300') ?>">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
          
          <div class="flex items-start gap-3">
            <div class="w-8 h-8 rounded-xl flex items-center justify-center font-bold text-xs shrink-0 mt-0.5 <?= $isLive ? 'bg-rose-600 text-white' : ($isDone ? 'bg-emerald-700 text-white' : 'bg-[#320E3B]/10 text-[#320E3B]') ?>">
              <?php if ($isDone): ?>
                <i data-lucide="check" class="w-4 h-4"></i>
              <?php elseif ($isLive): ?>
                <i data-lucide="radio" class="w-4 h-4 animate-spin"></i>
              <?php else: ?>
                <span><?= $idx + 1 ?></span>
              <?php endif; ?>
            </div>

            <div>
              <div class="flex items-center gap-2 flex-wrap">
                <span class="text-xs font-bold text-gray-500 font-mono"><?= htmlspecialchars($item['time'] ?? '') ?></span>
                <span class="text-gray-300">•</span>
                <h3 class="text-sm sm:text-base font-bold text-gray-900 leading-snug">
                  <?= htmlspecialchars($item['title'] ?? '') ?>
                </h3>
              </div>

              <?php if (!empty($item['presenter'])): ?>
                <p class="text-xs text-gray-600 mt-1 flex items-center gap-1.5">
                  <i data-lucide="user" class="w-3.5 h-3.5 text-gray-400"></i>
                  <span><?= htmlspecialchars($item['presenter']) ?></span>
                </p>
              <?php endif; ?>

              <?php if (!empty($item['description'])): ?>
                <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                  <?= htmlspecialchars($item['description']) ?>
                </p>
              <?php endif; ?>
            </div>
          </div>

          <div class="sm:self-center shrink-0 pl-11 sm:pl-0">
            <?php if ($isLive): ?>
              <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-rose-600 text-white">
                Happening Now
              </span>
            <?php elseif ($isDone): ?>
              <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                Completed
              </span>
            <?php else: ?>
              <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-gray-100 text-gray-600">
                Upcoming
              </span>
            <?php endif; ?>
          </div>

        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

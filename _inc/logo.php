<?php
/**
 * Foliioo Brand Logo Helper
 * Renders the official Foliioo logo wordmark with Coral (#FF502B) accent dots
 */
$logoClass = $logoClass ?? 'text-2xl';
$showTagline = $showTagline ?? false;
?>
<div class="inline-flex items-center gap-2 select-none group">
  <span class="inline-flex items-center font-black tracking-tight <?= htmlspecialchars($logoClass) ?> font-sans lowercase leading-none text-[#0F0F0F]">
    <span>fol</span><span class="relative inline-block"><span class="absolute -top-1 left-1/2 -translate-x-1/2 w-1.5 h-1.5 rounded-full bg-[#FF502B]"></span>ı</span><span class="relative inline-block"><span class="absolute -top-1 left-1/2 -translate-x-1/2 w-1.5 h-1.5 rounded-full bg-[#0F0F0F]"></span>ı</span><span>oo</span><span class="w-2.5 h-2.5 rounded-full bg-[#FF502B] ml-1 inline-block self-center shadow-xs"></span>
  </span>
</div>

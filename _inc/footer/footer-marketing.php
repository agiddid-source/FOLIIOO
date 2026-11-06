<?php
/**
 * Foliioo Design System V1.2 — Marketing SaaS Footer (Logo Palette Edition)
 */
?>
<footer class="bg-[#0D0D0D] text-[#A6A39D] mt-auto font-sans">
  <div class="container-main pt-16 pb-8">

    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-12 pb-12 border-b border-white/10">

      <!-- Brand Column -->
      <div class="col-span-2 sm:col-span-2 lg:col-span-1 space-y-4">
        <a href="/" class="flex items-center gap-2 group">
          <span class="inline-flex items-center font-black tracking-tight text-2xl font-sans lowercase leading-none text-white">
            <span>fol</span><span class="relative inline-block"><span class="absolute -top-1 left-1/2 -translate-x-1/2 w-1.5 h-1.5 rounded-full bg-[#FF502B]"></span>ı</span><span class="relative inline-block"><span class="absolute -top-1 left-1/2 -translate-x-1/2 w-1.5 h-1.5 rounded-full bg-white"></span>ı</span><span>oo</span><span class="w-2.5 h-2.5 rounded-full bg-[#FF502B] ml-1 inline-block self-center shadow-xs"></span>
          </span>
        </a>
        <p class="text-xs leading-relaxed text-[#A6A39D] max-w-xs">
          The modern event lifecycle platform. Create, manage, and share beautiful event pages with RSVP, live programmes, and guest check-in — built for Nigerian celebrations.
        </p>
        <div class="flex items-center gap-2 text-[12px] font-medium text-[#4C7C59]">
          <span class="w-2 h-2 rounded-full bg-[#4C7C59]"></span>
          <span>All systems operational</span>
        </div>
      </div>

      <!-- Event Testing Pages -->
      <div class="space-y-4">
        <h4 class="text-xs font-bold uppercase tracking-wider text-white">Event Templates</h4>
        <ul class="space-y-2.5 text-xs">
          <li><a href="/e/chief-adeleke-celebration-of-life" class="hover:text-white transition">Elegant: Chief Adeleke Memorial</a></li>
          <li><a href="/e/ghost-ghostess-wedding" class="hover:text-white transition">Floral: Ghost & Ghostess Wedding</a></li>
          <li><a href="/e/lagos-innovators-gala" class="hover:text-white transition">Modern: Lagos Tech Gala</a></li>
          <li><a href="/e/african-fintech-summit-2026" class="hover:text-white transition">Modern: African Fintech Summit</a></li>
        </ul>
      </div>

      <!-- Lifecycle Test States -->
      <div class="space-y-4">
        <h4 class="text-xs font-bold uppercase tracking-wider text-white">Lifecycle States</h4>
        <ul class="space-y-2.5 text-xs">
          <li><a href="/e/chief-adeleke-celebration-of-life?preview=1&status=published_pre_event" class="hover:text-white transition">Upcoming / RSVP Open</a></li>
          <li><a href="/e/chief-adeleke-celebration-of-life?preview=1&status=in_progress" class="hover:text-white transition">Live Now / Session Active</a></li>
          <li><a href="/e/african-fintech-summit-2026?preview=1&status=completed" class="hover:text-white transition">Completed / Photo Highlights</a></li>
          <li><a href="/e/chief-adeleke-celebration-of-life?preview=1&status=postponed" class="hover:text-white transition">Postponed / Calendar Notice</a></li>
          <li><a href="/e/lagos-charity-polo-match?preview=1&status=cancelled" class="hover:text-white transition">Cancelled / Refund Notice</a></li>
        </ul>
      </div>

      <!-- Dedicated Modules -->
      <div class="space-y-4">
        <h4 class="text-xs font-bold uppercase tracking-wider text-white">Guest Modules</h4>
        <ul class="space-y-2.5 text-xs">
          <li><a href="/gallery/african-fintech-summit-2026" class="hover:text-white transition">Curated Photo Album</a></li>
          <li><a href="/checkout/lagos-innovators-gala" class="hover:text-white transition">Ticket Checkout Simulator</a></li>
          <li><a href="/checkout/lagos-innovators-gala?confirmed=1" class="hover:text-white transition">Digital Admission QR Pass</a></li>
        </ul>
      </div>

    </div>

    <!-- Bottom Bar -->
    <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-[12px] text-[#A6A39D]">
      <div class="flex flex-wrap items-center justify-center gap-x-3 gap-y-1">
        <span>© <?= date('Y') ?> Foliioo Technologies Ltd.</span>
        <span class="hidden sm:inline">•</span>
        <span>Design System V1.2 (Logo Palette Edition)</span>
      </div>
      <div class="flex items-center gap-2 font-semibold text-[#A6A39D]">
        <i class="fa-solid fa-naira-sign text-xs text-[#4C7C59]"></i>
        <span>NGN (₦) Settlement Ready</span>
      </div>
    </div>

  </div>
</footer>

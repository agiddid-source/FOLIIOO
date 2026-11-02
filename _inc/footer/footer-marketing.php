<?php
/**
 * Foliioo Design System V1.1 — Marketing SaaS Footer
 */
?>
<footer class="bg-[#252321] text-[#A19B93] mt-auto font-sans">
  <div class="container-main pt-16 pb-8">

    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-12 pb-12 border-b border-white/10">

      <!-- Brand Column -->
      <div class="col-span-2 sm:col-span-2 lg:col-span-1 space-y-4">
        <a href="/" class="flex items-center gap-2.5 group">
          <div class="w-9 h-9 rounded-lg bg-[#9A5F44] flex items-center justify-center text-white font-display text-lg font-bold shadow-xs group-hover:bg-[#7E4A33] transition-colors">
            F
          </div>
          <span class="text-xl font-display font-bold tracking-tight text-white group-hover:text-[#9A5F44] transition-colors">FOLIIOO</span>
        </a>
        <p class="text-xs leading-relaxed text-[#A19B93] max-w-xs">
          The modern event lifecycle platform. Create, manage, and share beautiful event pages with RSVP, live programmes, and guest check-in — built for Nigerian celebrations.
        </p>
        <div class="flex items-center gap-2 text-[12px] font-medium text-[#87927B]">
          <span class="w-2 h-2 rounded-full bg-[#87927B]"></span>
          <span>All systems operational</span>
        </div>
      </div>

      <!-- Event Types -->
      <div class="space-y-4">
        <h4 class="text-xs font-bold uppercase tracking-wider text-white">Event Types</h4>
        <ul class="space-y-2.5 text-xs">
          <li><a href="#" class="hover:text-white transition">Weddings</a></li>
          <li><a href="#" class="hover:text-white transition">Burials & Celebrations of Life</a></li>
          <li><a href="#" class="hover:text-white transition">Birthday Parties</a></li>
          <li><a href="#" class="hover:text-white transition">Galas & Award Nights</a></li>
          <li><a href="#" class="hover:text-white transition">Conferences & Summits</a></li>
          <li><a href="#" class="hover:text-white transition">Custom Events</a></li>
        </ul>
      </div>

      <!-- Platform Features -->
      <div class="space-y-4">
        <h4 class="text-xs font-bold uppercase tracking-wider text-white">Features</h4>
        <ul class="space-y-2.5 text-xs">
          <li><a href="#" class="hover:text-white transition">Live Programme</a></li>
          <li><a href="#" class="hover:text-white transition">WhatsApp RSVPs & Reminders</a></li>
          <li><a href="#" class="hover:text-white transition">QR Door Check-In</a></li>
          <li><a href="#" class="hover:text-white transition">Tribute & Memorial Funds</a></li>
          <li><a href="#" class="hover:text-white transition">Media Gallery</a></li>
          <li><a href="#" class="hover:text-white transition">Postponement Notices</a></li>
        </ul>
      </div>

      <!-- Pricing & Resources -->
      <div class="space-y-4">
        <h4 class="text-xs font-bold uppercase tracking-wider text-white">Plans</h4>
        <ul class="space-y-2.5 text-xs">
          <li class="flex items-center gap-2">
            <a href="#pricing" class="hover:text-white transition">Starter</a>
            <span class="badge badge-sage text-[10px] py-0 px-1.5">Free</span>
          </li>
          <li class="flex items-center gap-2">
            <a href="#pricing" class="hover:text-white transition">Celebration Pro</a>
            <span class="text-[11px] text-[#A19B93]">₦15,000</span>
          </li>
          <li class="flex items-center gap-2">
            <a href="#pricing" class="hover:text-white transition">Grand Gala</a>
            <span class="text-[11px] text-[#A19B93]">₦45,000</span>
          </li>
        </ul>
        <h4 class="text-xs font-bold uppercase tracking-wider text-white pt-2">Resources</h4>
        <ul class="space-y-2.5 text-xs">
          <li><a href="#" class="hover:text-white transition">Help Center</a></li>
          <li><a href="#" class="hover:text-white transition">Contact Support</a></li>
          <li><a href="#" class="hover:text-white transition">API Documentation</a></li>
        </ul>
      </div>

    </div>

    <!-- Bottom Bar -->
    <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-[12px] text-[#A19B93]">
      <div class="flex flex-wrap items-center justify-center gap-x-3 gap-y-1">
        <span>© <?= date('Y') ?> Foliioo Technologies Ltd.</span>
        <span class="hidden sm:inline">•</span>
        <a href="#" class="hover:text-white transition">Privacy Policy</a>
        <span>•</span>
        <a href="#" class="hover:text-white transition">Terms of Service</a>
      </div>
      <div class="flex items-center gap-2 font-semibold text-[#A19B93]">
        <i class="fa-solid fa-naira-sign text-xs text-[#87927B]"></i>
        <span>NGN (₦)</span>
      </div>
    </div>

  </div>
</footer>

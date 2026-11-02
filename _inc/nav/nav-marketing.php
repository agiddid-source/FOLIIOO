<?php
/**
 * Foliioo Design System V1.1 — Marketing Public Header
 */
$activeNav = $activeNav ?? 'home';
?>
<header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-[#E9E4DD] shadow-[0_8px_30px_rgba(37,35,33,0.06)] font-sans">
  <div class="container-main">
    <div class="flex items-center justify-between h-20">
      
      <div class="flex items-center gap-8">
        <a href="/" class="flex items-center gap-2.5 group">
          <div class="w-10 h-10 rounded-lg bg-[#9A5F44] flex items-center justify-center text-white font-display text-xl font-bold shadow-xs group-hover:bg-[#7E4A33] transition-colors">
            F
          </div>
          <div class="flex flex-col">
            <span class="text-2xl font-display font-bold tracking-tight text-[#252321] group-hover:text-[#9A5F44] transition-colors">FOLIIOO</span>
            <span class="text-[10px] tracking-widest uppercase font-semibold text-[#87927B] -mt-1">Event Lifecycle</span>
          </div>
        </a>

        <nav class="hidden md:flex items-center space-x-1 lg:space-x-2 text-sm font-medium text-[#6B675F]">
          <a href="#event-types" class="px-3 py-2 rounded-lg hover:text-[#9A5F44] hover:bg-[#FAF8F5] transition <?= $activeNav === 'types' ? 'text-[#9A5F44] font-semibold' : '' ?>">
            Event Types
          </a>
          <a href="#templates" class="px-3 py-2 rounded-lg hover:text-[#9A5F44] hover:bg-[#FAF8F5] transition <?= $activeNav === 'templates' ? 'text-[#9A5F44] font-semibold' : '' ?>">
            Templates
          </a>
          <a href="#how-it-works" class="px-3 py-2 rounded-lg hover:text-[#9A5F44] hover:bg-[#FAF8F5] transition <?= $activeNav === 'how-it-works' ? 'text-[#9A5F44] font-semibold' : '' ?>">
            How It Works
          </a>
          <a href="#pricing" class="px-3 py-2 rounded-lg hover:text-[#9A5F44] hover:bg-[#FAF8F5] transition <?= $activeNav === 'pricing' ? 'text-[#9A5F44] font-semibold' : '' ?>">
            Pricing
          </a>
        </nav>
      </div>

      <div class="hidden md:flex items-center gap-3">
        <a href="/create-event.php" id="foliioo-resume-draft-badge" class="hidden badge badge-warning hover:bg-[#F3E1B9] transition animate-pulse">
          Resume Draft
        </a>

        <a href="/login.php" class="btn btn-ghost btn-sm">
          Sign In
        </a>

        <a href="/create-event.php" class="btn btn-primary btn-sm">
          <span>Create an Event</span>
          <i class="fa-solid fa-arrow-right text-xs"></i>
        </a>
      </div>

      <div class="flex items-center gap-2 md:hidden">
        <a href="/create-event.php" class="btn btn-primary btn-sm">
          Create
        </a>
        <button id="foliioo-marketing-mobile-btn" type="button" aria-expanded="false" class="p-2 rounded-lg text-[#6B675F] hover:text-[#252321] hover:bg-[#FAF8F5] focus:outline-none transition" aria-label="Toggle Navigation">
          <i class="fa-solid fa-bars text-lg"></i>
        </button>
      </div>

    </div>
  </div>

  <div id="foliioo-marketing-mobile-menu" class="hidden md:hidden border-t border-[#E9E4DD] bg-white px-4 pt-3 pb-6 space-y-2 shadow-lg font-sans">
    <a href="#event-types" class="block px-3 py-2 rounded-lg text-sm font-medium text-[#252321] hover:bg-[#FAF8F5] hover:text-[#9A5F44]">Event Types</a>
    <a href="#templates" class="block px-3 py-2 rounded-lg text-sm font-medium text-[#252321] hover:bg-[#FAF8F5] hover:text-[#9A5F44]">Templates (Modern, Elegant, Floral)</a>
    <a href="#how-it-works" class="block px-3 py-2 rounded-lg text-sm font-medium text-[#252321] hover:bg-[#FAF8F5] hover:text-[#9A5F44]">How It Works</a>
    <a href="#pricing" class="block px-3 py-2 rounded-lg text-sm font-medium text-[#252321] hover:bg-[#FAF8F5] hover:text-[#9A5F44]">Pricing & Plans</a>
    <div class="pt-4 border-t border-[#E9E4DD] flex flex-col gap-2">
      <a href="/login.php" class="btn btn-secondary btn-block">Sign In</a>
      <a href="/create-event.php" class="btn btn-primary btn-block">Start an Event</a>
    </div>
  </div>
</header>

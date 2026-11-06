<?php
/**
 * Foliioo Design System V1.2 — Marketing Public Header (Logo Palette Edition)
 */
$activeNav = $activeNav ?? 'home';
?>
<header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-[#EAE8E2] shadow-[0_8px_30px_rgba(15,15,15,0.05)] font-sans">
  <div class="container-main">
    <div class="flex items-center justify-between h-20">
      
      <div class="flex items-center gap-8">
        <a href="/" class="flex items-center gap-3 group">
          <?php 
            $logoClass = 'text-2xl'; 
            $showTagline = true;
            require __DIR__ . '/../logo.php'; 
          ?>
        </a>

        <nav class="hidden md:flex items-center space-x-1 lg:space-x-2 text-sm font-medium text-[#63615E]">
          <a href="/#events" class="px-3 py-2 rounded-lg hover:text-[#FF502B] hover:bg-[#FAFBF6] transition <?= $activeNav === 'events' ? 'text-[#FF502B] font-semibold' : '' ?>">
            Events & Templates
          </a>
          <a href="/#states" class="px-3 py-2 rounded-lg hover:text-[#FF502B] hover:bg-[#FAFBF6] transition <?= $activeNav === 'states' ? 'text-[#FF502B] font-semibold' : '' ?>">
            Lifecycle States
          </a>
          <a href="/#galleries" class="px-3 py-2 rounded-lg hover:text-[#FF502B] hover:bg-[#FAFBF6] transition <?= $activeNav === 'galleries' ? 'text-[#FF502B] font-semibold' : '' ?>">
            Galleries
          </a>
          <a href="/#checkout" class="px-3 py-2 rounded-lg hover:text-[#FF502B] hover:bg-[#FAFBF6] transition <?= $activeNav === 'checkout' ? 'text-[#FF502B] font-semibold' : '' ?>">
            Ticketing
          </a>
        </nav>
      </div>

      <div class="hidden md:flex items-center gap-3">
        <a href="/event.php" class="btn btn-secondary btn-sm">
          <i class="fa-solid fa-eye mr-1.5 text-xs text-[#FF502B]"></i>
          <span>Sample Event</span>
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
        <button id="foliioo-marketing-mobile-btn" type="button" aria-expanded="false" class="p-2 rounded-lg text-[#63615E] hover:text-[#0F0F0F] hover:bg-[#FAFBF6] focus:outline-none transition" aria-label="Toggle Navigation">
          <i class="fa-solid fa-bars text-lg"></i>
        </button>
      </div>

    </div>
  </div>

  <div id="foliioo-marketing-mobile-menu" class="hidden md:hidden border-t border-[#EAE8E2] bg-white px-4 pt-3 pb-6 space-y-2 shadow-lg font-sans">
    <a href="/#events" class="block px-3 py-2 rounded-lg text-sm font-medium text-[#0F0F0F] hover:bg-[#FAFBF6] hover:text-[#FF502B]">Events & Templates</a>
    <a href="/#states" class="block px-3 py-2 rounded-lg text-sm font-medium text-[#0F0F0F] hover:bg-[#FAFBF6] hover:text-[#FF502B]">Lifecycle States</a>
    <a href="/#galleries" class="block px-3 py-2 rounded-lg text-sm font-medium text-[#0F0F0F] hover:bg-[#FAFBF6] hover:text-[#FF502B]">Curated Galleries</a>
    <a href="/#checkout" class="block px-3 py-2 rounded-lg text-sm font-medium text-[#0F0F0F] hover:bg-[#FAFBF6] hover:text-[#FF502B]">Ticketing Flow</a>
    <div class="pt-4 border-t border-[#EAE8E2] flex flex-col gap-2">
      <a href="/event.php" class="btn btn-secondary btn-block">Sample Event Website</a>
      <a href="/create-event.php" class="btn btn-primary btn-block">Start an Event</a>
    </div>
  </div>
</header>

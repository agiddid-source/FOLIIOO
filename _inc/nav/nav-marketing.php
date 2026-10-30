<?php
$activeNav = $activeNav ?? 'home';
?>
<header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-gray-100 shadow-xs">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between h-20">
      
      <div class="flex items-center gap-8">
        <a href="/" class="flex items-center gap-2.5 group">
          <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#250A2C] via-[#320E3B] to-indigo-500 flex items-center justify-center text-white font-black text-xl shadow-md group-hover:scale-105 transition-transform">
            F
          </div>
          <div class="flex flex-col">
            <span class="text-2xl font-black tracking-tight text-gray-900 group-hover:text-[#320E3B] transition-colors">FOLIIOO</span>
            <span class="text-[10px] tracking-widest uppercase font-semibold text-gray-600 -mt-1">Event Lifecycle</span>
          </div>
        </a>

        <nav class="hidden md:flex items-center space-x-1 lg:space-x-2 text-sm font-medium text-gray-600">
          <a href="#event-types" class="px-3 py-2 rounded-lg hover:text-[#320E3B] hover:bg-gray-50 transition <?= $activeNav === 'types' ? 'text-[#320E3B] font-semibold' : '' ?>">
            Event Types
          </a>
          <a href="#templates" class="px-3 py-2 rounded-lg hover:text-[#320E3B] hover:bg-gray-50 transition <?= $activeNav === 'templates' ? 'text-[#320E3B] font-semibold' : '' ?>">
            Templates
          </a>
          <a href="#how-it-works" class="px-3 py-2 rounded-lg hover:text-[#320E3B] hover:bg-gray-50 transition <?= $activeNav === 'how-it-works' ? 'text-[#320E3B] font-semibold' : '' ?>">
            How It Works
          </a>
          <a href="#pricing" class="px-3 py-2 rounded-lg hover:text-[#320E3B] hover:bg-gray-50 transition <?= $activeNav === 'pricing' ? 'text-[#320E3B] font-semibold' : '' ?>">
            Pricing
          </a>
        </nav>
      </div>

      <div class="hidden md:flex items-center gap-4">
        <a href="/create-event.php" id="foliioo-resume-draft-badge" class="hidden text-xs font-semibold px-3 py-1.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200 hover:bg-amber-100 transition animate-pulse">
          Resume Draft
        </a>

        <a href="/login.php" class="text-sm font-semibold text-gray-700 hover:text-[#320E3B] px-3 py-2 transition">
          Sign In
        </a>

        <a href="/create-event.php" class="inline-flex items-center gap-2 bg-[#320E3B] hover:bg-[#4A1556] text-white text-sm font-bold px-5 py-2.5 rounded-xl shadow-sm hover:shadow transition transform active:scale-95">
          <span>Create an Event</span>
          <i data-lucide="arrow-right" class="w-4 h-4"></i>
        </a>
      </div>

      <div class="flex items-center gap-2 md:hidden">
        <a href="/create-event.php" class="text-xs font-bold bg-[#320E3B] text-white px-3 py-2 rounded-lg shadow-xs">
          Create
        </a>
        <button id="foliioo-marketing-mobile-btn" type="button" aria-expanded="false" class="p-2 rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none" aria-label="Toggle Navigation">
          <i data-lucide="menu" class="w-6 h-6"></i>
        </button>
      </div>

    </div>
  </div>

  <div id="foliioo-marketing-mobile-menu" class="hidden md:hidden border-t border-gray-100 bg-white px-4 pt-3 pb-6 space-y-2 shadow-lg">
    <a href="#event-types" class="block px-3 py-2 rounded-lg text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-[#320E3B]">Event Types</a>
    <a href="#templates" class="block px-3 py-2 rounded-lg text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-[#320E3B]">Templates (Elegant, Modern, Floral)</a>
    <a href="#how-it-works" class="block px-3 py-2 rounded-lg text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-[#320E3B]">How It Works</a>
    <a href="#pricing" class="block px-3 py-2 rounded-lg text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-[#320E3B]">Pricing & Plans</a>
    <div class="pt-4 border-t border-gray-100 flex flex-col gap-2">
      <a href="/login.php" class="w-full text-center px-4 py-2.5 rounded-xl border border-gray-200 text-sm font-semibold text-gray-700 hover:bg-gray-50">Sign In</a>
      <a href="/create-event.php" class="w-full text-center px-4 py-2.5 rounded-xl bg-[#320E3B] text-white text-sm font-bold shadow-sm">Start an Event</a>
    </div>
  </div>
</header>

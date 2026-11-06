<?php
/**
 * Foliioo — Platform Home & Testing Directory
 * Central launchpad for all event website templates, lifecycle states, and guest modules.
 */
require_once __DIR__ . '/_inc/MockData.php';

$events = MockData::getEvents();
$burialEvent = MockData::getEventById("evt_burial_01") ?? $events[0] ?? [];
$weddingEvent = MockData::getEventById("evt_wedding_02") ?? $events[1] ?? [];
$galaEvent = MockData::getEventById("evt_gala_03") ?? $events[2] ?? [];
$summitEvent = MockData::getEventById("evt_summit_05") ?? $events[4] ?? [];

$pageTitle = 'Foliioo — Event Platform Testing Directory';
require_once __DIR__ . '/_inc/header.php';
require_once __DIR__ . '/_inc/nav/nav-marketing.php';
?>

<main class="min-h-screen bg-[#FAFBF6] pb-24">

  <!-- Hero Section -->
  <section class="border-b border-[#EAE8E2] bg-white py-14 sm:py-20">
    <div class="container-main space-y-6">
      <div class="flex flex-wrap items-center gap-2">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#FFF0ED] text-[#FF502B] text-xs font-bold uppercase tracking-wider">
          <span class="w-2 h-2 rounded-full bg-[#FF502B] animate-pulse"></span>
          <span>Testing Hub & Showcase</span>
        </span>
        <span class="badge badge-sage">Logo Palette V1.2</span>
        <span class="text-xs text-[#63615E] hidden sm:inline">• Active Branch: <code class="bg-[#F3F4EE] px-1.5 py-0.5 rounded text-[#0F0F0F] font-mono text-xs">guestAndEvent</code></span>
      </div>

      <div class="max-w-3xl space-y-4">
        <h1 class="font-display text-3xl sm:text-5xl font-bold tracking-tight text-[#0F0F0F] leading-[1.15]">
          Every Event, Every State, One Visual Language.
        </h1>
        <p class="text-base sm:text-lg text-[#63615E] leading-relaxed">
          Quick-launch directory to test every public event website template, simulate real-time event lifecycle states, and inspect guest ticketing checkout and curated photo galleries.
        </p>
      </div>

      <!-- Quick Metrics Strip -->
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-4 border-t border-[#EAE8E2]">
        <div class="p-3.5 rounded-xl bg-[#FAFBF6] border border-[#EAE8E2]">
          <span class="text-xs text-[#63615E] block font-medium">Event Templates</span>
          <span class="text-2xl font-bold text-[#0F0F0F] mt-0.5 block">4 Variations</span>
        </div>
        <div class="p-3.5 rounded-xl bg-[#FAFBF6] border border-[#EAE8E2]">
          <span class="text-xs text-[#63615E] block font-medium">Lifecycle States</span>
          <span class="text-2xl font-bold text-[#0F0F0F] mt-0.5 block">5 States</span>
        </div>
        <div class="p-3.5 rounded-xl bg-[#FAFBF6] border border-[#EAE8E2]">
          <span class="text-xs text-[#63615E] block font-medium">Per-Event Galleries</span>
          <span class="text-2xl font-bold text-[#0F0F0F] mt-0.5 block">Photo-First</span>
        </div>
        <div class="p-3.5 rounded-xl bg-[#FAFBF6] border border-[#EAE8E2]">
          <span class="text-xs text-[#63615E] block font-medium">Checkout Flow</span>
          <span class="text-2xl font-bold text-[#FF502B] mt-0.5 block">Simulated QR</span>
        </div>
      </div>
    </div>
  </section>

  <div class="container-main space-y-16 pt-12">

    <!-- Section 1: Event Websites by Design Template -->
    <section id="events" class="scroll-mt-24 space-y-6">
      <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-3 border-b border-[#EAE8E2] pb-4">
        <div>
          <span class="eyebrow">Module 6: Public Event Websites</span>
          <h2 class="font-display text-2xl sm:text-3xl font-bold text-[#0F0F0F]">
            Event Websites & Design Templates
          </h2>
          <p class="text-xs sm:text-sm text-[#63615E] mt-1">
            Test each public event website rendered under its assigned template font and features.
          </p>
        </div>
        <span class="text-xs text-[#63615E] font-medium shrink-0">Click any card to open public page</span>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- 1. Chief Adeleke: Elegant (Burial) -->
        <div class="card card-hover p-6 sm:p-7 flex flex-col justify-between space-y-6 group">
          <div class="space-y-3">
            <div class="flex items-center justify-between gap-2">
              <span class="badge badge-accent">Template: Elegant</span>
              <span class="text-xs font-semibold text-[#63615E]">Burial & Memorial</span>
            </div>
            <div>
              <h3 class="font-display text-xl font-bold text-[#0F0F0F] group-hover:text-[#FF502B] transition-colors">
                Chief Emmanuel Adeleke Celebration of Life
              </h3>
              <p class="text-xs text-[#63615E] mt-1 line-clamp-2">
                A life of honor, grace, and enduring legacy (1942 - 2026). Broad Street, Lagos Island.
              </p>
            </div>
            <div class="flex flex-wrap gap-2 pt-2 text-[11px] text-[#63615E]">
              <span class="px-2.5 py-1 rounded-md bg-[#F3F4EE] border border-[#EAE8E2]">Font: <strong>Cormorant Garamond</strong></span>
              <span class="px-2.5 py-1 rounded-md bg-[#F3F4EE] border border-[#EAE8E2]">Tribute Fund (NGN)</span>
              <span class="px-2.5 py-1 rounded-md bg-[#F3F4EE] border border-[#EAE8E2]">Controlled Programme</span>
              <span class="px-2.5 py-1 rounded-md bg-[#F3F4EE] border border-[#EAE8E2]">Photo Album</span>
            </div>
          </div>
          <div class="pt-4 border-t border-[#EAE8E2] flex items-center justify-between">
            <span class="text-xs font-mono text-[#A6A39D]">slug: chief-adeleke-celebration-of-life</span>
            <a href="/event.php?slug=chief-adeleke-celebration-of-life" class="btn btn-primary btn-sm">
              <span>View Event</span>
              <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
          </div>
        </div>

        <!-- 2. Chidi & Amara: Floral (Wedding) -->
        <div class="card card-hover p-6 sm:p-7 flex flex-col justify-between space-y-6 group">
          <div class="space-y-3">
            <div class="flex items-center justify-between gap-2">
              <span class="badge badge-accent">Template: Floral</span>
              <span class="text-xs font-semibold text-[#63615E]">Traditional Wedding</span>
            </div>
            <div>
              <h3 class="font-display text-xl font-bold text-[#0F0F0F] group-hover:text-[#FF502B] transition-colors">
                Chidi & Amara's Wedding & Reception
              </h3>
              <p class="text-xs text-[#63615E] mt-1 line-clamp-2">
                Two families, one beautiful union. Landmark Event Centre, Water Corporation Drive, Victoria Island.
              </p>
            </div>
            <div class="flex flex-wrap gap-2 pt-2 text-[11px] text-[#63615E]">
              <span class="px-2.5 py-1 rounded-md bg-[#F3F4EE] border border-[#EAE8E2]">Font: <strong>DM Serif Display</strong></span>
              <span class="px-2.5 py-1 rounded-md bg-[#F3F4EE] border border-[#EAE8E2]">Party RSVP & Reminders</span>
              <span class="px-2.5 py-1 rounded-md bg-[#F3F4EE] border border-[#EAE8E2]">Wedding Order of Events</span>
              <span class="px-2.5 py-1 rounded-md bg-[#F3F4EE] border border-[#EAE8E2]">Couple Photo Album</span>
            </div>
          </div>
          <div class="pt-4 border-t border-[#EAE8E2] flex items-center justify-between">
            <span class="text-xs font-mono text-[#A6A39D]">slug: chidi-amara-wedding</span>
            <a href="/event.php?slug=chidi-amara-wedding" class="btn btn-primary btn-sm">
              <span>View Event</span>
              <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
          </div>
        </div>

        <!-- 3. Lagos Innovators: Modern (Gala / Ticketing) -->
        <div class="card card-hover p-6 sm:p-7 flex flex-col justify-between space-y-6 group">
          <div class="space-y-3">
            <div class="flex items-center justify-between gap-2">
              <span class="badge badge-accent">Template: Modern</span>
              <span class="text-xs font-semibold text-[#63615E]">Fundraiser & Tech Gala</span>
            </div>
            <div>
              <h3 class="font-display text-xl font-bold text-[#0F0F0F] group-hover:text-[#FF502B] transition-colors">
                Lagos Tech & Founders Annual Gala 2026
              </h3>
              <p class="text-xs text-[#63615E] mt-1 line-clamp-2">
                Celebrating Nigeria's top tech creators, venture leaders, and startup champions. Eko Hotel Grand Ballroom.
              </p>
            </div>
            <div class="flex flex-wrap gap-2 pt-2 text-[11px] text-[#63615E]">
              <span class="px-2.5 py-1 rounded-md bg-[#F3F4EE] border border-[#EAE8E2]">Font: <strong>DM Sans</strong></span>
              <span class="px-2.5 py-1 rounded-md bg-[#F3F4EE] border border-[#EAE8E2]">Tiered Ticket Checkout</span>
              <span class="px-2.5 py-1 rounded-md bg-[#F3F4EE] border border-[#EAE8E2]">Keynote Schedule</span>
              <span class="px-2.5 py-1 rounded-md bg-[#F3F4EE] border border-[#EAE8E2]">VIP Sponsorships</span>
            </div>
          </div>
          <div class="pt-4 border-t border-[#EAE8E2] flex items-center justify-between">
            <span class="text-xs font-mono text-[#A6A39D]">slug: lagos-innovators-gala</span>
            <a href="/event.php?slug=lagos-innovators-gala" class="btn btn-primary btn-sm">
              <span>View Event</span>
              <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
          </div>
        </div>

        <!-- 4. African Fintech Summit: Modern (Conference) -->
        <div class="card card-hover p-6 sm:p-7 flex flex-col justify-between space-y-6 group">
          <div class="space-y-3">
            <div class="flex items-center justify-between gap-2">
              <span class="badge badge-accent">Template: Modern</span>
              <span class="text-xs font-semibold text-[#63615E]">Conference & Summit</span>
            </div>
            <div>
              <h3 class="font-display text-xl font-bold text-[#0F0F0F] group-hover:text-[#FF502B] transition-colors">
                African Fintech Frontier Summit 2026
              </h3>
              <p class="text-xs text-[#63615E] mt-1 line-clamp-2">
                Unifying payment infrastructure, open banking, and borderless finance across Africa. Landmark Centre.
              </p>
            </div>
            <div class="flex flex-wrap gap-2 pt-2 text-[11px] text-[#63615E]">
              <span class="px-2.5 py-1 rounded-md bg-[#F3F4EE] border border-[#EAE8E2]">Font: <strong>DM Sans</strong></span>
              <span class="px-2.5 py-1 rounded-md bg-[#F3F4EE] border border-[#EAE8E2]">Full Photo Album</span>
              <span class="px-2.5 py-1 rounded-md bg-[#F3F4EE] border border-[#EAE8E2]">Multi-Track Programme</span>
              <span class="px-2.5 py-1 rounded-md bg-[#F3F4EE] border border-[#EAE8E2]">Host Gratitude Note</span>
            </div>
          </div>
          <div class="pt-4 border-t border-[#EAE8E2] flex items-center justify-between">
            <span class="text-xs font-mono text-[#A6A39D]">slug: african-fintech-summit-2026</span>
            <a href="/event.php?slug=african-fintech-summit-2026" class="btn btn-primary btn-sm">
              <span>View Event</span>
              <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
          </div>
        </div>

      </div>
    </section>

    <!-- Section 2: Event Lifecycle State Simulator -->
    <section id="states" class="scroll-mt-24 space-y-6">
      <div class="border-b border-[#EAE8E2] pb-4">
        <span class="eyebrow">Interactive Simulator</span>
        <h2 class="font-display text-2xl sm:text-3xl font-bold text-[#0F0F0F]">
          Event Lifecycle States
        </h2>
        <p class="text-xs sm:text-sm text-[#63615E] mt-1">
          Simulate how an event page dynamically adapts across every phase of its real-world lifecycle.
        </p>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">

        <!-- State 1: Upcoming -->
        <a href="/event.php?slug=chief-adeleke-celebration-of-life&preview=1&status=published_pre_event" class="card card-hover p-5 space-y-3 block">
          <div class="flex items-center justify-between">
            <span class="badge badge-sage">Upcoming</span>
            <span class="text-[11px] text-[#63615E]">Pre-Event</span>
          </div>
          <h4 class="font-display text-base font-bold text-[#0F0F0F]">Published Pre-Event</h4>
          <p class="text-xs text-[#63615E] leading-relaxed">
            Standard live state before event date. RSVPs and tickets are actively accepting guest submissions.
          </p>
          <span class="text-xs font-semibold text-[#FF502B] inline-flex items-center gap-1 pt-1">
            <span>Test Pre-Event State</span> &rarr;
          </span>
        </a>

        <!-- State 2: Live Now -->
        <a href="/event.php?slug=chief-adeleke-celebration-of-life&preview=1&status=in_progress" class="card card-hover p-5 space-y-3 block">
          <div class="flex items-center justify-between">
            <span class="badge badge-accent animate-pulse">● Live Now</span>
            <span class="text-[11px] text-[#63615E]">In Session</span>
          </div>
          <h4 class="font-display text-base font-bold text-[#0F0F0F]">Event In-Progress</h4>
          <p class="text-xs text-[#63615E] leading-relaxed">
            Event day live tracking. Active session badge pulses in navbar, live programme tracker highlights ongoing segments.
          </p>
          <span class="text-xs font-semibold text-[#FF502B] inline-flex items-center gap-1 pt-1">
            <span>Test Live State</span> &rarr;
          </span>
        </a>

        <!-- State 3: Completed -->
        <a href="/event.php?slug=african-fintech-summit-2026&preview=1&status=completed" class="card card-hover p-5 space-y-3 block">
          <div class="flex items-center justify-between">
            <span class="badge badge-neutral">Concluded</span>
            <span class="text-[11px] text-[#63615E]">Post-Event</span>
          </div>
          <h4 class="font-display text-base font-bold text-[#0F0F0F]">Completed & Retrospective</h4>
          <p class="text-xs text-[#63615E] leading-relaxed">
            Celebration concluded. Features official Host Gratitude tribute banner and curated photo album preview.
          </p>
          <span class="text-xs font-semibold text-[#FF502B] inline-flex items-center gap-1 pt-1">
            <span>Test Completed State</span> &rarr;
          </span>
        </a>

        <!-- State 4: Postponed -->
        <a href="/event.php?slug=chief-adeleke-celebration-of-life&preview=1&status=postponed" class="card card-hover p-5 space-y-3 block border-l-4 border-l-[#B45309]">
          <div class="flex items-center justify-between">
            <span class="badge badge-warning">Postponed</span>
            <span class="text-[11px] text-[#B45309] font-bold">Rescheduled</span>
          </div>
          <h4 class="font-display text-base font-bold text-[#0F0F0F]">Event Postponed Notice</h4>
          <p class="text-xs text-[#63615E] leading-relaxed">
            Rescheduled date banner with 1-click <strong>Add New Date to Calendar</strong> (Google Cal sync) and <strong>.ICS download</strong>.
          </p>
          <span class="text-xs font-semibold text-[#B45309] inline-flex items-center gap-1 pt-1">
            <span>Test Postponed State</span> &rarr;
          </span>
        </a>

        <!-- State 5: Cancelled -->
        <a href="/event.php?slug=lagos-charity-polo-match&preview=1&status=cancelled" class="card card-hover p-5 space-y-3 block border-l-4 border-l-[#DC2626]">
          <div class="flex items-center justify-between">
            <span class="badge badge-error">Cancelled</span>
            <span class="text-[11px] text-[#DC2626] font-bold">Discontinued</span>
          </div>
          <h4 class="font-display text-base font-bold text-[#0F0F0F]">Event Cancelled Notice</h4>
          <p class="text-xs text-[#63615E] leading-relaxed">
            Official cancellation banner with automatic refund policy guarantee for all ticket purchases and contributions.
          </p>
          <span class="text-xs font-semibold text-[#DC2626] inline-flex items-center gap-1 pt-1">
            <span>Test Cancelled State</span> &rarr;
          </span>
        </a>

        <!-- Preview Mode Toolbar explanation -->
        <div class="p-5 rounded-xl bg-[#F3F4EE] border border-[#EAE8E2] space-y-3 flex flex-col justify-between">
          <div>
            <span class="text-xs font-bold uppercase tracking-wider text-[#63615E] block">Creator Mode</span>
            <h4 class="font-display text-base font-bold text-[#0F0F0F] mt-1">Creator Preview Bar</h4>
            <p class="text-xs text-[#63615E] mt-1 leading-relaxed">
              Any event URL with <code class="bg-white px-1 rounded text-xs">&preview=1</code> renders the top creator toolbar to swap between all 5 states in real-time.
            </p>
          </div>
          <a href="/event.php?slug=chief-adeleke-celebration-of-life&preview=1" class="text-xs font-bold text-[#FF502B] hover:underline">
            Launch Preview Toolbar &rarr;
          </a>
        </div>

      </div>
    </section>

    <!-- Section 3: Curated Galleries & Ticketing Modules -->
    <section id="galleries" class="scroll-mt-24 space-y-8">
      <div class="border-b border-[#EAE8E2] pb-4">
        <span class="eyebrow">Dedicated Modules</span>
        <h2 class="font-display text-2xl sm:text-3xl font-bold text-[#0F0F0F]">
          Curated Photo Galleries (Per Event)
        </h2>
        <p class="text-xs sm:text-sm text-[#63615E] mt-1">
          Simplified, viewer-only photo albums attached to individual events with full-screen lightbox navigation.
        </p>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

        <a href="/gallery.php?event=african-fintech-summit-2026" class="card card-hover p-5 space-y-3 block group">
          <div class="w-10 h-10 rounded-xl bg-[#FFF0ED] text-[#FF502B] flex items-center justify-center text-lg">
            <i class="fa-solid fa-images"></i>
          </div>
          <div>
            <h4 class="font-display text-sm font-bold text-[#0F0F0F] group-hover:text-[#FF502B] transition-colors">African Fintech Summit</h4>
            <p class="text-xs text-[#63615E] mt-0.5">6 Curated Photos • Keynotes & Plenaries</p>
          </div>
          <span class="text-xs font-semibold text-[#FF502B] inline-flex items-center gap-1 pt-1">
            <span>Open Gallery</span> &rarr;
          </span>
        </a>

        <a href="/gallery.php?event=chief-adeleke-celebration-of-life" class="card card-hover p-5 space-y-3 block group">
          <div class="w-10 h-10 rounded-xl bg-[#FFF0ED] text-[#FF502B] flex items-center justify-center text-lg">
            <i class="fa-solid fa-images"></i>
          </div>
          <div>
            <h4 class="font-display text-sm font-bold text-[#0F0F0F] group-hover:text-[#FF502B] transition-colors">Chief Adeleke Memorial</h4>
            <p class="text-xs text-[#63615E] mt-0.5">5 Curated Photos • Family & Honors</p>
          </div>
          <span class="text-xs font-semibold text-[#FF502B] inline-flex items-center gap-1 pt-1">
            <span>Open Gallery</span> &rarr;
          </span>
        </a>

        <a href="/gallery.php?event=chidi-amara-wedding" class="card card-hover p-5 space-y-3 block group">
          <div class="w-10 h-10 rounded-xl bg-[#FFF0ED] text-[#FF502B] flex items-center justify-center text-lg">
            <i class="fa-solid fa-images"></i>
          </div>
          <div>
            <h4 class="font-display text-sm font-bold text-[#0F0F0F] group-hover:text-[#FF502B] transition-colors">Chidi & Amara Wedding</h4>
            <p class="text-xs text-[#63615E] mt-0.5">5 Curated Photos • Couple & Reception</p>
          </div>
          <span class="text-xs font-semibold text-[#FF502B] inline-flex items-center gap-1 pt-1">
            <span>Open Gallery</span> &rarr;
          </span>
        </a>

        <a href="/gallery.php?event=lagos-innovators-gala" class="card card-hover p-5 space-y-3 block group">
          <div class="w-10 h-10 rounded-xl bg-[#FFF0ED] text-[#FF502B] flex items-center justify-center text-lg">
            <i class="fa-solid fa-images"></i>
          </div>
          <div>
            <h4 class="font-display text-sm font-bold text-[#0F0F0F] group-hover:text-[#FF502B] transition-colors">Lagos Innovators Gala</h4>
            <p class="text-xs text-[#63615E] mt-0.5">4 Curated Photos • Awards & Networking</p>
          </div>
          <span class="text-xs font-semibold text-[#FF502B] inline-flex items-center gap-1 pt-1">
            <span>Open Gallery</span> &rarr;
          </span>
        </a>

      </div>
    </section>

    <!-- Section 4: Ticketing & Checkout Flows -->
    <section id="checkout" class="scroll-mt-24 space-y-6">
      <div class="border-b border-[#EAE8E2] pb-4">
        <span class="eyebrow">Guest Checkout</span>
        <h2 class="font-display text-2xl sm:text-3xl font-bold text-[#0F0F0F]">
          Ticketing & Admission Pass Simulation
        </h2>
        <p class="text-xs sm:text-sm text-[#63615E] mt-1">
          End-to-end checkout experience with order summaries, payment simulation, and verified digital admission passes.
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <div class="card p-6 sm:p-7 space-y-4">
          <div class="flex items-center justify-between">
            <span class="badge badge-accent">Checkout Form</span>
            <span class="text-xs text-[#63615E] font-medium">Payment Simulator</span>
          </div>
          <h4 class="font-display text-lg font-bold text-[#0F0F0F]">Secure Ticket Checkout</h4>
          <p class="text-xs text-[#63615E] leading-relaxed">
            Guest attendee information capture, simulated Debit/Credit Card & Bank Transfer selection, sandbox test card inputs, and automated price calculation with zero fees.
          </p>
          <div class="pt-2">
            <a href="/checkout.php?event=lagos-innovators-gala" class="btn btn-primary btn-sm">
              <i class="fa-solid fa-lock mr-1 text-xs"></i>
              <span>Launch Checkout Page</span>
            </a>
          </div>
        </div>

        <div class="card p-6 sm:p-7 space-y-4">
          <div class="flex items-center justify-between">
            <span class="badge badge-success">Success Screen</span>
            <span class="text-xs text-[#63615E] font-medium">Digital QR Pass</span>
          </div>
          <h4 class="font-display text-lg font-bold text-[#0F0F0F]">Order Confirmation & Ticket Pass</h4>
          <p class="text-xs text-[#63615E] leading-relaxed">
            Official admission pass with digital QR code, unique order reference (<code class="bg-[#F3F4EE] px-1 py-0.5 rounded text-xs">FOL-ORD-XXXX</code>), print action, and door check-in validity status.
          </p>
          <div class="pt-2">
            <a href="/checkout.php?event=lagos-innovators-gala&confirmed=1" class="btn btn-secondary btn-sm">
              <i class="fa-solid fa-qrcode mr-1 text-xs text-[#FF502B]"></i>
              <span>View Confirmation & QR Pass</span>
            </a>
          </div>
        </div>

      </div>
    </section>

    <!-- Section 5: Palette V1.2 Reference -->
    <section class="card p-6 sm:p-8 space-y-6">
      <div class="border-b border-[#EAE8E2] pb-4">
        <span class="eyebrow">Visual Spec</span>
        <h3 class="font-display text-xl font-bold text-[#0F0F0F]">
          Foliioo Brand Palette (Extracted From Official Logo)
        </h3>
        <p class="text-xs text-[#63615E] mt-1">
          Color tokens extracted from <code class="bg-[#F3F4EE] px-1 rounded text-xs">photo_6012674028373479164_y.jpg</code> and deployed across all platform templates.
        </p>
      </div>

      <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
        <div class="p-3 rounded-xl border border-[#EAE8E2] bg-white space-y-2">
          <div class="h-12 w-full rounded-lg bg-[#FF502B] flex items-center justify-center text-white font-bold text-xs shadow-xs">
            #FF502B
          </div>
          <div class="text-xs">
            <span class="font-bold text-[#0F0F0F] block">Electric Coral</span>
            <span class="text-[#63615E] text-[11px]">Primary Brand Accent</span>
          </div>
        </div>

        <div class="p-3 rounded-xl border border-[#EAE8E2] bg-white space-y-2">
          <div class="h-12 w-full rounded-lg bg-[#0D0D0D] flex items-center justify-center text-white font-bold text-xs shadow-xs">
            #0D0D0D
          </div>
          <div class="text-xs">
            <span class="font-bold text-[#0F0F0F] block">Pitch Black</span>
            <span class="text-[#63615E] text-[11px]">Dark Sections & Text</span>
          </div>
        </div>

        <div class="p-3 rounded-xl border border-[#EAE8E2] bg-white space-y-2">
          <div class="h-12 w-full rounded-lg bg-[#FAFBF6] border border-[#EAE8E2] flex items-center justify-center text-[#0F0F0F] font-bold text-xs shadow-xs">
            #FAFBF6
          </div>
          <div class="text-xs">
            <span class="font-bold text-[#0F0F0F] block">Alabaster Canvas</span>
            <span class="text-[#63615E] text-[11px]">Page Background</span>
          </div>
        </div>

        <div class="p-3 rounded-xl border border-[#EAE8E2] bg-white space-y-2">
          <div class="h-12 w-full rounded-lg bg-[#EAE8E2] flex items-center justify-center text-[#0F0F0F] font-bold text-xs shadow-xs">
            #EAE8E2
          </div>
          <div class="text-xs">
            <span class="font-bold text-[#0F0F0F] block">Warm Border</span>
            <span class="text-[#63615E] text-[11px]">Cards & Inputs</span>
          </div>
        </div>

        <div class="p-3 rounded-xl border border-[#EAE8E2] bg-white space-y-2">
          <div class="h-12 w-full rounded-lg bg-[#FFF0ED] border border-[#FF502B]/20 flex items-center justify-center text-[#FF502B] font-bold text-xs shadow-xs">
            #FFF0ED
          </div>
          <div class="text-xs">
            <span class="font-bold text-[#0F0F0F] block">Soft Coral Tint</span>
            <span class="text-[#63615E] text-[11px]">Badges & Highlights</span>
          </div>
        </div>
      </div>
    </section>

  </div>

</main>

<?php require_once __DIR__ . '/_inc/footer/footer-marketing.php'; ?>
<?php require_once __DIR__ . '/_inc/footer.php'; ?>

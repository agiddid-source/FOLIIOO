<?php
require_once __DIR__ . '/_inc/MockData.php';

$slug = $_GET['slug'] ?? $_GET['event'] ?? 'chief-adeleke-celebration-of-life';
$event = MockData::getEventBySlug($slug) ?? MockData::getEventById($slug) ?? MockData::getEvents()[0];

$isPreview = !empty($_GET['preview']);
if ($isPreview && !empty($_GET['status'])) {
    $event['status'] = $_GET['status'];
}

$status = $event['status'] ?? 'published_pre_event';
$creator = MockData::getCreatorById($event['creator_id'] ?? '');
$programmes = MockData::getProgrammesForEvent($event['id']);
$tickets = MockData::getTicketsForEvent($event['id']);
$donations = MockData::getDonationsForEvent($event['id']);
$donationSummary = MockData::getDonationSummary($event['id']);
$memories = MockData::getMemoriesForEvent($event['id']);
$guestLimit = MockData::getGuestLimitForEvent($event['id']);
$stats = MockData::getAttendanceStats($event['id']);
$allEvents = MockData::getEvents();

$pageTitle = $event['title'] . ' — Foliioo Event';
require_once __DIR__ . '/_inc/header.php';
?>

<div class="min-h-screen flex flex-col bg-[#F8FAFC]">

  <!-- Creator Preview Mode Banner -->
  <?php if ($isPreview): ?>
    <aside class="sticky top-0 z-50 bg-[#0D160B] text-white border-b border-white/10 px-4 py-2.5 text-xs shadow-lg">
      <div class="max-w-6xl mx-auto flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-2">
          <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
          <span class="font-extrabold uppercase tracking-wider text-amber-300">Creator Preview Mode</span>
          <span class="text-gray-400 hidden sm:inline">•</span>
          <span class="text-gray-300 hidden sm:inline">Viewing public page through guest eyes</span>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
          <span class="text-gray-400 font-semibold hidden md:inline">Test State:</span>
          <div class="inline-flex rounded-lg bg-white/10 p-0.5 border border-white/10 text-[11px] font-bold">
            <a href="?slug=<?= urlencode($event['slug']) ?>&preview=1&status=published_pre_event" class="px-2 py-0.5 rounded-md <?= $status === 'published_pre_event' ? 'bg-[#320E3B] text-white' : 'text-gray-300 hover:text-white' ?>">Upcoming</a>
            <a href="?slug=<?= urlencode($event['slug']) ?>&preview=1&status=in_progress" class="px-2 py-0.5 rounded-md <?= $status === 'in_progress' ? 'bg-rose-600 text-white' : 'text-gray-300 hover:text-white' ?>">Live Now</a>
            <a href="?slug=<?= urlencode($event['slug']) ?>&preview=1&status=completed" class="px-2 py-0.5 rounded-md <?= $status === 'completed' ? 'bg-gray-800 text-white' : 'text-gray-300 hover:text-white' ?>">Completed</a>
            <a href="?slug=<?= urlencode($event['slug']) ?>&preview=1&status=postponed" class="px-2 py-0.5 rounded-md <?= $status === 'postponed' ? 'bg-amber-500 text-white' : 'text-gray-300 hover:text-white' ?>">Postponed</a>
            <a href="?slug=<?= urlencode($event['slug']) ?>&preview=1&status=cancelled" class="px-2 py-0.5 rounded-md <?= $status === 'cancelled' ? 'bg-rose-700 text-white' : 'text-gray-300 hover:text-white' ?>">Cancelled</a>
          </div>

          <a href="?slug=<?= urlencode($event['slug']) ?>" class="px-2.5 py-1 rounded-lg bg-white/10 hover:bg-white/20 text-white font-bold transition ml-2">
            Exit Preview
          </a>
        </div>
      </div>
    </aside>
  <?php endif; ?>

  <!-- Postponed State Notice Banner -->
  <?php if ($status === 'postponed'): ?>
    <?php
      $calDateClean = str_replace('-', '', $event['date'] ?? '20261128');
      $calTimeClean = str_replace(':', '', $event['time'] ?? '1600') . '00';
      $calStartIso = $calDateClean . 'T' . $calTimeClean;
      $calEndIso = date('Ymd\THis', strtotime(($event['date'] ?? '2026-11-28') . ' ' . ($event['time'] ?? '16:00') . ' +4 hours'));
      $gcalUrl = 'https://calendar.google.com/calendar/render?action=TEMPLATE&text=' . urlencode($event['title'] ?? '') . '&dates=' . $calStartIso . '/' . $calEndIso . '&details=' . urlencode($event['postponement_reason'] ?? '') . '&location=' . urlencode(($event['venue']['name'] ?? '') . ', ' . ($event['venue']['address'] ?? ''));
      $icsContent = "BEGIN:VCALENDAR\r\nVERSION:2.0\r\nPRODID:-//Foliioo//Event Lifecycle//EN\r\nBEGIN:VEVENT\r\nSUMMARY:" . addcslashes($event['title'] ?? 'Event', ",;") . "\r\nDESCRIPTION:" . addcslashes($event['postponement_reason'] ?? '', ",;") . "\r\nLOCATION:" . addcslashes(($event['venue']['name'] ?? '') . ', ' . ($event['venue']['address'] ?? ''), ",;") . "\r\nDTSTART:{$calStartIso}\r\nDTEND:{$calEndIso}\r\nSTATUS:CONFIRMED\r\nEND:VEVENT\r\nEND:VCALENDAR";
      $icsHref = "data:text/calendar;charset=utf8," . rawurlencode($icsContent);
    ?>
    <section id="notice" class="bg-amber-50 border-b border-amber-200 text-amber-950 px-4 py-4 sm:py-5">
      <div class="max-w-5xl mx-auto flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-start gap-3.5">
          <div class="w-10 h-10 rounded-2xl bg-amber-200 text-amber-900 flex items-center justify-center shrink-0 mt-0.5">
            <i data-lucide="calendar-clock" class="w-5 h-5"></i>
          </div>
          <div class="space-y-1">
            <div class="flex items-center gap-2">
              <span class="text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded-full bg-amber-200 text-amber-900">Important Update</span>
              <h2 class="text-sm sm:text-base font-extrabold text-amber-950">Event Postponement Notice</h2>
            </div>
            <p class="text-xs text-amber-900/90 leading-relaxed max-w-2xl">
              <?= htmlspecialchars($event['postponement_reason'] ?? 'This event has been rescheduled. All confirmed reservations and passes remain fully valid for the new date.') ?>
            </p>
          </div>
        </div>

        <div class="flex items-center gap-2 pl-13 md:pl-0 shrink-0 flex-wrap">
          <a href="<?= $gcalUrl ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold transition shadow-xs active:scale-95">
            <i data-lucide="calendar-plus" class="w-4 h-4"></i>
            <span>Add New Date to Calendar</span>
          </a>
          <a href="<?= $icsHref ?>" download="event-<?= htmlspecialchars($event['slug']) ?>.ics" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl border border-amber-300 hover:bg-amber-100 text-amber-900 text-xs font-bold transition">
            <i data-lucide="download" class="w-3.5 h-3.5"></i>
            <span>.ICS</span>
          </a>
        </div>
      </div>
    </section>
  <?php endif; ?>

  <!-- Cancelled State Notice Banner -->
  <?php if ($status === 'cancelled'): ?>
    <section id="notice" class="bg-rose-50 border-b border-rose-200 text-rose-950 px-4 py-4 sm:py-5">
      <div class="max-w-5xl mx-auto flex items-start sm:items-center gap-3.5">
        <div class="w-10 h-10 rounded-2xl bg-rose-200 text-rose-900 flex items-center justify-center shrink-0">
          <i data-lucide="alert-octagon" class="w-5 h-5"></i>
        </div>
        <div class="space-y-1">
          <div class="flex items-center gap-2">
            <span class="text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded-full bg-rose-200 text-rose-900">Event Notice</span>
            <h2 class="text-sm sm:text-base font-extrabold text-rose-950">This Event Has Been Cancelled</h2>
          </div>
          <p class="text-xs text-rose-900/90 leading-relaxed max-w-3xl">
            <?= htmlspecialchars($event['cancellation_reason'] ?? 'This event has been cancelled by the host. All ticket purchases and donations will be refunded automatically to your original payment method.') ?>
          </p>
        </div>
      </div>
    </section>
  <?php endif; ?>

  <!-- Main Themed Layout -->
  <div class="flex-1 w-full max-w-7xl mx-auto px-2 sm:px-4 pt-4 sm:pt-6">
    <?php
      $template = $event['template'] ?? 'modern';
      $templateFile = __DIR__ . '/_inc/templates/' . $template . '.php';
      if (!is_file($templateFile)) {
          $templateFile = __DIR__ . '/_inc/templates/modern.php';
      }
      require $templateFile;
    ?>
  </div>

  <!-- Guest Footer -->
  <?php require __DIR__ . '/_inc/footer/footer-event.php'; ?>

</div>

<?php require_once __DIR__ . '/_inc/footer.php'; ?>

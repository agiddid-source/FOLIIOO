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

<div class="min-h-screen flex flex-col bg-[#FAFBF6]">

  <!-- Creator Preview Mode Banner -->
  <?php if ($isPreview): ?>
    <aside class="sticky top-0 z-50 bg-[#0F0F0F] text-white border-b border-white/10 px-4 py-2.5 text-xs shadow-lg">
      <div class="container-main flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-2">
          <span class="w-2 h-2 rounded-full bg-[#FF502B] animate-pulse"></span>
          <span class="eyebrow text-[#FAFBF6]">Creator Preview Mode</span>
          <span class="text-white/40 hidden sm:inline">•</span>
          <span class="text-white/70 hidden sm:inline">Viewing public page through guest eyes</span>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
          <span class="text-white/60 font-medium hidden md:inline">Test State:</span>
          <div class="inline-flex rounded-lg bg-white/10 p-0.5 border border-white/10 text-[11px] font-semibold">
            <a href="?slug=<?= urlencode($event['slug']) ?>&preview=1&status=published_pre_event" class="px-2.5 py-1 rounded-md <?= $status === 'published_pre_event' ? 'bg-[#87927B] text-white font-bold' : 'text-white/70 hover:text-white' ?>">Upcoming</a>
            <a href="?slug=<?= urlencode($event['slug']) ?>&preview=1&status=in_progress" class="px-2.5 py-1 rounded-md <?= $status === 'in_progress' ? 'bg-[#FF502B] text-white font-bold' : 'text-white/70 hover:text-white' ?>">Live Now</a>
            <a href="?slug=<?= urlencode($event['slug']) ?>&preview=1&status=completed" class="px-2.5 py-1 rounded-md <?= $status === 'completed' ? 'bg-[#63615E] text-white font-bold' : 'text-white/70 hover:text-white' ?>">Completed</a>
            <a href="?slug=<?= urlencode($event['slug']) ?>&preview=1&status=postponed" class="px-2.5 py-1 rounded-md <?= $status === 'postponed' ? 'bg-[#8F5F12] text-white font-bold' : 'text-white/70 hover:text-white' ?>">Postponed</a>
            <a href="?slug=<?= urlencode($event['slug']) ?>&preview=1&status=cancelled" class="px-2.5 py-1 rounded-md <?= $status === 'cancelled' ? 'bg-[#B54747] text-white font-bold' : 'text-white/70 hover:text-white' ?>">Cancelled</a>
          </div>

          <a href="?slug=<?= urlencode($event['slug']) ?>" class="btn btn-ghost btn-sm text-white border border-white/20 hover:bg-white/10 ml-2">
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
    <section id="notice" class="bg-[#FBF1DC] border-b border-[#EAE8E2] text-[#8F5F12] px-4 py-4 sm:py-5">
      <div class="container-main flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-start gap-3.5">
          <div class="w-10 h-10 rounded-xl bg-[#8F5F12]/15 text-[#8F5F12] flex items-center justify-center shrink-0 mt-0.5">
            <i class="fa-solid fa-clock-rotate-left text-lg"></i>
          </div>
          <div class="space-y-1">
            <div class="flex items-center gap-2">
              <span class="badge badge-warning">Important Update</span>
              <h2 class="font-display text-sm sm:text-base font-bold text-[#8F5F12]">Event Postponement Notice</h2>
            </div>
            <p class="text-xs text-[#8F5F12]/90 leading-relaxed max-w-2xl">
              <?= htmlspecialchars($event['postponement_reason'] ?? 'This event has been rescheduled. All confirmed reservations and passes remain fully valid for the new date.') ?>
            </p>
          </div>
        </div>

        <div class="flex items-center gap-2 pl-13 md:pl-0 shrink-0 flex-wrap">
          <a href="<?= $gcalUrl ?>" target="_blank" rel="noopener noreferrer" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-calendar-plus mr-1.5"></i>
            <span>Add New Date to Calendar</span>
          </a>
          <a href="<?= $icsHref ?>" download="event-<?= htmlspecialchars($event['slug']) ?>.ics" class="btn btn-secondary btn-sm">
            <i class="fa-solid fa-download mr-1.5"></i>
            <span>.ICS</span>
          </a>
        </div>
      </div>
    </section>
  <?php endif; ?>

  <!-- Cancelled State Notice Banner -->
  <?php if ($status === 'cancelled'): ?>
    <section id="notice" class="bg-[#F8E6E6] border-b border-[#B54747]/20 text-[#B54747] px-4 py-4 sm:py-5">
      <div class="container-main flex items-start sm:items-center gap-3.5">
        <div class="w-10 h-10 rounded-xl bg-[#B54747]/15 text-[#B54747] flex items-center justify-center shrink-0">
          <i class="fa-solid fa-ban text-lg"></i>
        </div>
        <div class="space-y-1">
          <div class="flex items-center gap-2">
            <span class="badge badge-error">Event Notice</span>
            <h2 class="font-display text-sm sm:text-base font-bold text-[#B54747]">This Event Has Been Cancelled</h2>
          </div>
          <p class="text-xs text-[#B54747]/90 leading-relaxed max-w-3xl">
            <?= htmlspecialchars($event['cancellation_reason'] ?? 'This event has been cancelled by the host. All ticket purchases and donations will be refunded automatically to your original payment method.') ?>
          </p>
        </div>
      </div>
    </section>
  <?php endif; ?>

  <!-- Main Themed Layout -->
  <div class="flex-1 w-full container-main px-4 sm:px-6 pt-4 sm:pt-6">
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
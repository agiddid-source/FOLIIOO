<?php
/**
 * Foliioo Design System V1.1 — Public Event Guest Footer
 */
if (empty($event)) {
    return;
}

$creator = $creator ?? (isset($event['creator_id']) ? MockData::getCreatorById($event['creator_id']) : null);
$creatorName = $creator['name'] ?? 'Organizing Committee';
$creatorEmail = $creator['email'] ?? '';
$eventTitle = $event['title'] ?? 'Foliioo Event';
$eventUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost:8000') . '/e/' . urlencode($event['slug'] ?? '');
$shareText = urlencode("You're invited: " . $eventTitle . " — " . $eventUrl);
?>

<footer class="mt-20 border-t border-[#EAE8E2] bg-white">
  <div class="max-w-5xl mx-auto px-4 sm:px-6 py-10">

    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 pb-8 border-b border-[#EAE8E2]">

      <!-- Organizer Details -->
      <div class="flex items-center gap-3 min-w-0">
        <?php if (!empty($creator['avatar'])): ?>
          <img src="<?= htmlspecialchars($creator['avatar']) ?>" class="w-9 h-9 rounded-lg object-cover ring-1 ring-[#EAE8E2] shrink-0" alt="">
        <?php else: ?>
          <div class="w-9 h-9 rounded-lg bg-[#FF502B] text-white flex items-center justify-center font-bold text-xs shrink-0">
            <?= strtoupper(substr($creatorName, 0, 1)) ?>
          </div>
        <?php endif; ?>
        <div class="min-w-0">
          <p class="text-sm font-bold text-[#0F0F0F] truncate"><?= htmlspecialchars($creatorName) ?></p>
          <?php if (!empty($creatorEmail)): ?>
            <a href="mailto:<?= htmlspecialchars($creatorEmail) ?>" class="text-[12px] text-[#63615E] hover:text-[#FF502B] transition"><?= htmlspecialchars($creatorEmail) ?></a>
          <?php endif; ?>
        </div>
      </div>

      <!-- Sharing Actions -->
      <div class="flex items-center gap-2 flex-wrap">
        <a href="https://api.whatsapp.com/send?text=<?= $shareText ?>" target="_blank" rel="noopener noreferrer" class="btn btn-sm bg-[#3F6B48] hover:bg-[#2E5236] text-white border-transparent">
          <i class="fa-brands fa-whatsapp text-sm"></i>
          <span>WhatsApp</span>
        </a>
        <button type="button" onclick="navigator.clipboard.writeText(window.location.href); this.querySelector('span').textContent='Copied!'; setTimeout(() => this.querySelector('span').textContent='Copy Link', 2000);" class="btn btn-secondary btn-sm">
          <i class="fa-solid fa-link text-xs"></i>
          <span>Copy Link</span>
        </button>
      </div>

    </div>

    <!-- Bottom Bar -->
    <div class="pt-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-[12px] text-[#63615E]">
      <span>© <?= date('Y') ?> <?= htmlspecialchars($creatorName) ?></span>
      <a href="/create-event.php" class="btn btn-secondary btn-sm text-[#FF502B] hover:text-[#E03A17]">
        <span>Create your event on Foliioo for free</span>
        <i class="fa-solid fa-arrow-right text-[11px]"></i>
      </a>
    </div>

  </div>
</footer>

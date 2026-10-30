<?php
if (empty($event)) {
    return;
}

$creator = $creator ?? (isset($event['creator_id']) ? MockData::getCreatorById($event['creator_id']) : null);
$creatorName = $creator['name'] ?? 'Organizing Committee';
$creatorEmail = $creator['email'] ?? '';
$eventTitle = $event['title'] ?? 'Foliioo Event';
$eventUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost:8000') . '/event.php?slug=' . urlencode($event['slug'] ?? '');
$shareText = urlencode("You're invited: " . $eventTitle . " — " . $eventUrl);
?>

<footer class="mt-20 border-t border-gray-200/80 bg-white">
  <div class="max-w-5xl mx-auto px-4 sm:px-6 py-10">

    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 pb-8 border-b border-gray-100">

      <div class="flex items-center gap-3 min-w-0">
        <?php if (!empty($creator['avatar'])): ?>
          <img src="<?= htmlspecialchars($creator['avatar']) ?>" class="w-8 h-8 rounded-xl object-cover ring-2 ring-gray-100 shrink-0" alt="">
        <?php else: ?>
          <div class="w-8 h-8 rounded-xl bg-[#320E3B] text-white flex items-center justify-center font-bold text-xs shrink-0">
            <?= strtoupper(substr($creatorName, 0, 1)) ?>
          </div>
        <?php endif; ?>
        <div class="min-w-0">
          <p class="text-sm font-bold text-gray-900 truncate"><?= htmlspecialchars($creatorName) ?></p>
          <?php if (!empty($creatorEmail)): ?>
            <a href="mailto:<?= htmlspecialchars($creatorEmail) ?>" class="text-[11px] text-gray-400 hover:text-[#320E3B] transition"><?= htmlspecialchars($creatorEmail) ?></a>
          <?php endif; ?>
        </div>
      </div>

      <div class="flex items-center gap-2">
        <a href="https://api.whatsapp.com/send?text=<?= $shareText ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition shadow-2xs">
          <i data-lucide="message-circle" class="w-3.5 h-3.5"></i>
          <span>WhatsApp</span>
        </a>
        <button type="button" onclick="navigator.clipboard.writeText(window.location.href); this.querySelector('span').textContent='Copied!'; setTimeout(() => this.querySelector('span').textContent='Copy Link', 2000);" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-gray-200 hover:bg-gray-50 text-gray-700 text-xs font-bold transition">
          <i data-lucide="link" class="w-3.5 h-3.5"></i>
          <span>Copy Link</span>
        </button>
      </div>

    </div>

    <div class="pt-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-[11px] text-gray-400">
      <span>© <?= date('Y') ?> <?= htmlspecialchars($creatorName) ?></span>
      <a href="/create-event.php" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-[#320E3B]/8 hover:bg-[#320E3B] text-[#320E3B] hover:text-white text-xs font-bold transition">
        Create your event on Foliioo — it's free
      </a>
    </div>

  </div>
</footer>

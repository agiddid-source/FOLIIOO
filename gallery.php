<?php
require_once __DIR__ . '/_inc/MockData.php';

$slug = $_GET['event'] ?? $_GET['slug'] ?? 'african-fintech-summit-2026';
$event = MockData::getEventBySlug($slug) ?? MockData::getEventById($slug) ?? MockData::getEvents()[4];

$creator = MockData::getCreatorById($event['creator_id'] ?? '');
$plan = MockData::getPlanById($event['plan_id'] ?? '');
$memories = MockData::getMemoriesForEvent($event['id']);

$isGalleryEnabled = !empty($event['features_enabled']['gallery']) && !empty($plan['features']['post_event_gallery'] ?? true);
$status = $event['status'] ?? 'completed';

$pageTitle = 'Photo Gallery & Memories — ' . $event['title'];
require_once __DIR__ . '/_inc/header.php';
?>

<div class="min-h-screen flex flex-col bg-[#F8FAFC]">

  <!-- Gallery Top Bar -->
  <header class="bg-white border-b border-gray-200/80 sticky top-0 z-30">
    <div class="max-w-6xl mx-auto px-4 h-16 flex items-center justify-between">
      <a href="/event.php?slug=<?= urlencode($event['slug']) ?>" class="inline-flex items-center gap-2 text-xs font-bold text-gray-700 hover:text-[#320E3B] transition">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        <span>Back to Event</span>
      </a>

      <div class="flex items-center gap-2">
        <span class="text-xs font-extrabold text-gray-900 truncate max-w-xs md:max-w-md">
          <?= htmlspecialchars($event['title']) ?>
        </span>
      </div>

      <button type="button" onclick="document.getElementById('upload-memory-modal').classList.remove('hidden');" class="inline-flex items-center gap-1.5 bg-[#320E3B] hover:bg-[#4A1556] text-white text-xs font-bold px-3.5 py-2 rounded-xl transition shadow-xs active:scale-95">
        <i data-lucide="plus" class="w-3.5 h-3.5"></i>
        <span>Add Memory</span>
      </button>
    </div>
  </header>

  <main class="flex-1 max-w-6xl w-full mx-auto px-4 py-8 space-y-8">
    
    <!-- Hero Banner with Host Gratitude -->
    <div class="bg-gradient-to-br from-gray-950 via-stone-900 to-[#320E3B] text-white rounded-3xl p-6 sm:p-10 shadow-xl relative overflow-hidden">
      <div class="space-y-3 max-w-3xl">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-amber-300 text-xs font-bold uppercase tracking-wider backdrop-blur-xs">
          <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
          <span>Official Event Album & Guest Tributes</span>
        </div>

        <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight">
          Memories of <?= htmlspecialchars($event['title']) ?>
        </h1>

        <?php if (!empty($event['post_event_message'])): ?>
          <p class="text-xs sm:text-sm text-gray-300 leading-relaxed max-w-2xl pt-1">
            <?= htmlspecialchars($event['post_event_message']) ?>
          </p>
        <?php else: ?>
          <p class="text-xs sm:text-sm text-gray-300 leading-relaxed max-w-2xl pt-1">
            Relive the memorable highlights, shared photographs, and heartfelt notes contributed by attendees and the organizing committee.
          </p>
        <?php endif; ?>

        <div class="flex flex-wrap items-center gap-4 pt-3 text-xs text-gray-300 font-medium">
          <span class="flex items-center gap-1.5">
            <i data-lucide="calendar" class="w-3.5 h-3.5 text-amber-300"></i>
            <?= date('F j, Y', strtotime($event['date'] ?? 'now')) ?>
          </span>
          <span class="text-gray-600">•</span>
          <span class="flex items-center gap-1.5">
            <i data-lucide="map-pin" class="w-3.5 h-3.5 text-amber-300"></i>
            <?= htmlspecialchars($event['venue']['name'] ?? 'Lagos, Nigeria') ?>
          </span>
          <span class="text-gray-600">•</span>
          <span class="flex items-center gap-1.5">
            <i data-lucide="image" class="w-3.5 h-3.5 text-amber-300"></i>
            <span id="gallery-total-count"><?= count($memories) ?> Total Moments</span>
          </span>
        </div>
      </div>
    </div>

    <!-- Filter Buttons -->
    <div class="flex items-center justify-between border-b border-gray-200/80 pb-4 flex-wrap gap-3">
      <div class="flex items-center gap-2">
        <button type="button" onclick="filterGallery('all', this)" class="gallery-filter-btn px-3.5 py-1.5 rounded-xl text-xs font-bold bg-[#320E3B] text-white transition">
          All Moments
        </button>
        <button type="button" onclick="filterGallery('photo', this)" class="gallery-filter-btn px-3.5 py-1.5 rounded-xl text-xs font-bold bg-white text-gray-700 hover:bg-gray-100 border border-gray-200 transition">
          Photos Only
        </button>
        <button type="button" onclick="filterGallery('tribute', this)" class="gallery-filter-btn px-3.5 py-1.5 rounded-xl text-xs font-bold bg-white text-gray-700 hover:bg-gray-100 border border-gray-200 transition">
          Written Tributes
        </button>
      </div>

      <div class="text-xs text-gray-500">
        Click any photo to enlarge
      </div>
    </div>

    <!-- Gallery Grid -->
    <div id="gallery-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php foreach ($memories as $idx => $item): ?>
        <?php
          $type = $item['type'] ?? 'photo';
          $hasImg = !empty($item['image_url']);
        ?>
        <div class="gallery-item bg-white rounded-3xl border border-gray-200/80 overflow-hidden shadow-2xs hover:shadow-md transition flex flex-col justify-between" data-type="<?= htmlspecialchars($type) ?>">
          <?php if ($hasImg): ?>
            <div class="relative h-60 w-full bg-gray-100 overflow-hidden cursor-pointer group" onclick="openLightbox('<?= htmlspecialchars($item['image_url']) ?>', '<?= htmlspecialchars(addslashes($item['caption'] ?? $item['message'])) ?>')">
              <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="Gallery item" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
              <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white">
                <i data-lucide="maximize-2" class="w-6 h-6 drop-shadow-md"></i>
              </div>
            </div>
          <?php endif; ?>

          <div class="p-5 flex-1 flex flex-col justify-between space-y-3">
            <div>
              <?php if (!empty($item['caption'])): ?>
                <h3 class="text-sm font-extrabold text-gray-900 mb-1"><?= htmlspecialchars($item['caption']) ?></h3>
              <?php endif; ?>
              <p class="text-xs text-gray-700 leading-relaxed italic">
                "<?= htmlspecialchars($item['message']) ?>"
              </p>
            </div>

            <div class="pt-3 border-t border-gray-100 flex items-center gap-3">
              <?php if (!empty($item['avatar'])): ?>
                <img src="<?= htmlspecialchars($item['avatar']) ?>" class="w-8 h-8 rounded-full object-cover ring-2 ring-gray-100" alt="">
              <?php else: ?>
                <div class="w-8 h-8 rounded-full bg-[#320E3B]/10 text-[#320E3B] flex items-center justify-center font-bold text-xs">
                  <?= strtoupper(substr($item['author_name'] ?? 'G', 0, 1)) ?>
                </div>
              <?php endif; ?>
              <div class="min-w-0">
                <span class="text-xs font-bold text-gray-900 block truncate"><?= htmlspecialchars($item['author_name'] ?? 'Guest') ?></span>
                <span class="text-[10px] text-gray-400 block truncate"><?= htmlspecialchars($item['author_role'] ?? 'Attendee') ?></span>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

  </main>

  <!-- Upload Memory Modal -->
  <div id="upload-memory-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 space-y-5 shadow-2xl relative">
      <div class="flex items-center justify-between border-b border-gray-100 pb-3">
        <div>
          <h3 class="text-lg font-extrabold text-gray-900">Add to Event Gallery</h3>
          <p class="text-xs text-gray-500">Share a tribute photo, selfie, or warm reflection</p>
        </div>
        <button type="button" onclick="document.getElementById('upload-memory-modal').classList.add('hidden');" class="p-1 rounded-lg text-gray-400 hover:text-gray-900">
          <i data-lucide="x" class="w-5 h-5"></i>
        </button>
      </div>

      <form id="memory-form" onsubmit="event.preventDefault(); submitMemory();" class="space-y-4 text-xs">
        <div>
          <label class="block font-bold text-gray-700 uppercase tracking-wider mb-1.5">Your Name *</label>
          <input type="text" id="memory-name" required placeholder="e.g. Somto Okafor" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-[#320E3B] focus:outline-none">
        </div>

        <div>
          <label class="block font-bold text-gray-700 uppercase tracking-wider mb-1.5">Your Role or Relation</label>
          <input type="text" id="memory-role" placeholder="e.g. University Classmate, Guest, Colleague" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-[#320E3B] focus:outline-none">
        </div>

        <div>
          <label class="block font-bold text-gray-700 uppercase tracking-wider mb-1.5">Photo URL (Optional)</label>
          <input type="url" id="memory-photo" placeholder="https://images.unsplash.com/..." class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-[#320E3B] focus:outline-none">
        </div>

        <div>
          <label class="block font-bold text-gray-700 uppercase tracking-wider mb-1.5">Caption or Short Title</label>
          <input type="text" id="memory-caption" placeholder="e.g. Cocktail hour with the keynote speakers" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-[#320E3B] focus:outline-none">
        </div>

        <div>
          <label class="block font-bold text-gray-700 uppercase tracking-wider mb-1.5">Memory or Message *</label>
          <textarea id="memory-message" rows="3" required placeholder="Share your experience, tribute, or congratulations..." class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-[#320E3B] focus:outline-none"></textarea>
        </div>

        <div class="pt-2 flex items-center justify-end gap-3">
          <button type="button" onclick="document.getElementById('upload-memory-modal').classList.add('hidden');" class="px-4 py-2.5 rounded-xl border border-gray-200 text-gray-700 font-bold hover:bg-gray-50">
            Cancel
          </button>
          <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#320E3B] hover:bg-[#4A1556] text-white font-bold shadow-md">
            Publish Memory
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Lightbox Modal -->
  <div id="lightbox-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/90 backdrop-blur-md" onclick="closeLightbox()">
    <div class="relative max-w-4xl w-full text-center space-y-3" onclick="event.stopPropagation()">
      <button type="button" onclick="closeLightbox()" class="absolute -top-10 right-0 text-white hover:text-gray-300 p-2">
        <i data-lucide="x" class="w-6 h-6"></i>
      </button>
      <img id="lightbox-img" src="" class="max-h-[75vh] mx-auto rounded-2xl shadow-2xl object-contain border border-white/10" alt="Enlarged photo">
      <p id="lightbox-caption" class="text-xs text-gray-300 font-medium max-w-xl mx-auto"></p>
    </div>
  </div>

  <?php require __DIR__ . '/_inc/footer/footer-event.php'; ?>

</div>

<script>
function filterGallery(type, btn) {
  document.querySelectorAll('.gallery-filter-btn').forEach(b => {
    b.classList.remove('bg-[#320E3B]', 'text-white');
    b.classList.add('bg-white', 'text-gray-700', 'border', 'border-gray-200');
  });
  btn.classList.remove('bg-white', 'text-gray-700', 'border', 'border-gray-200');
  btn.classList.add('bg-[#320E3B]', 'text-white');

  const items = document.querySelectorAll('.gallery-item');
  items.forEach(item => {
    if (type === 'all' || item.getAttribute('data-type') === type) {
      item.classList.remove('hidden');
    } else {
      item.classList.add('hidden');
    }
  });
}

function openLightbox(url, caption) {
  const modal = document.getElementById('lightbox-modal');
  document.getElementById('lightbox-img').src = url;
  document.getElementById('lightbox-caption').textContent = caption;
  modal.classList.remove('hidden');
  if (typeof lucide !== 'undefined') {
    lucide.createIcons();
  }
}

function closeLightbox() {
  document.getElementById('lightbox-modal').classList.add('hidden');
}

function submitMemory() {
  const name = document.getElementById('memory-name').value;
  const role = document.getElementById('memory-role').value || 'Attendee';
  const photo = document.getElementById('memory-photo').value;
  const caption = document.getElementById('memory-caption').value;
  const message = document.getElementById('memory-message').value;

  const card = document.createElement('div');
  card.className = 'gallery-item bg-white rounded-3xl border border-gray-200/80 overflow-hidden shadow-2xs hover:shadow-md transition flex flex-col justify-between';
  card.setAttribute('data-type', photo ? 'photo' : 'tribute');

  let imgHtml = '';
  if (photo) {
    imgHtml = `<div class="relative h-60 w-full bg-gray-100 overflow-hidden cursor-pointer" onclick="openLightbox('${photo}', '${caption || message}')">
      <img src="${photo}" class="w-full h-full object-cover" alt="">
    </div>`;
  }

  card.innerHTML = `
    ${imgHtml}
    <div class="p-5 flex-1 flex flex-col justify-between space-y-3">
      <div>
        ${caption ? `<h3 class="text-sm font-extrabold text-gray-900 mb-1">${caption}</h3>` : ''}
        <p class="text-xs text-gray-700 leading-relaxed italic">"${message}"</p>
      </div>
      <div class="pt-3 border-t border-gray-100 flex items-center gap-3">
        <div class="w-8 h-8 rounded-full bg-[#320E3B]/10 text-[#320E3B] flex items-center justify-center font-bold text-xs">
          ${name.charAt(0).toUpperCase()}
        </div>
        <div class="min-w-0">
          <span class="text-xs font-bold text-gray-900 block truncate">${name}</span>
          <span class="text-[10px] text-gray-400 block truncate">${role}</span>
        </div>
      </div>
    </div>
  `;

  document.getElementById('gallery-container').prepend(card);
  document.getElementById('upload-memory-modal').classList.add('hidden');
  document.getElementById('memory-form').reset();
  
  if (typeof lucide !== 'undefined') {
    lucide.createIcons();
  }
}
</script>

<?php require_once __DIR__ . '/_inc/footer.php'; ?>

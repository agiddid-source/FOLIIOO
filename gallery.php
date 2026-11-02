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

<div class="min-h-screen flex flex-col bg-[#FAF8F5]">

  <!-- Gallery Top Bar -->
  <header class="bg-white border-b border-[#E9E4DD] sticky top-0 z-30">
    <div class="container-main px-4 h-16 flex items-center justify-between">
      <a href="/event.php?slug=<?= urlencode($event['slug']) ?>" class="btn btn-ghost btn-sm text-[#252321] hover:text-[#9A5F44]">
        <i class="fa-solid fa-arrow-left mr-1.5"></i>
        <span>Back to Event</span>
      </a>

      <div class="flex items-center gap-2">
        <span class="text-xs font-bold text-[#252321] truncate max-w-xs md:max-w-md">
          <?= htmlspecialchars($event['title']) ?>
        </span>
      </div>

      <button type="button" onclick="document.getElementById('upload-memory-modal').classList.remove('hidden');" class="btn btn-primary btn-sm">
        <i class="fa-solid fa-plus mr-1.5"></i>
        <span>Add Memory</span>
      </button>
    </div>
  </header>

  <main class="flex-1 container-main px-4 py-8 space-y-8">
    
    <!-- Hero Banner with Host Gratitude -->
    <div class="bg-[#252321] text-white rounded-2xl p-6 sm:p-10 shadow-xl relative overflow-hidden">
      <div class="space-y-3 max-w-3xl">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-[#87927B] text-xs font-semibold uppercase tracking-wider backdrop-blur-xs">
          <i class="fa-solid fa-wand-magic-sparkles mr-1"></i>
          <span>Official Event Album & Guest Tributes</span>
        </div>

        <h1 class="font-display text-2xl sm:text-4xl font-bold tracking-tight text-white">
          Memories of <?= htmlspecialchars($event['title']) ?>
        </h1>

        <?php if (!empty($event['post_event_message'])): ?>
          <p class="text-xs sm:text-sm text-[#FAF8F5]/80 leading-relaxed max-w-2xl pt-1">
            <?= htmlspecialchars($event['post_event_message']) ?>
          </p>
        <?php else: ?>
          <p class="text-xs sm:text-sm text-[#FAF8F5]/80 leading-relaxed max-w-2xl pt-1">
            Relive the memorable highlights, shared photographs, and heartfelt notes contributed by attendees and the organizing committee.
          </p>
        <?php endif; ?>

        <div class="flex flex-wrap items-center gap-4 pt-3 text-xs text-[#FAF8F5]/70 font-medium">
          <span class="flex items-center gap-1.5">
            <i class="fa-solid fa-calendar text-[#87927B]"></i>
            <?= date('F j, Y', strtotime($event['date'] ?? 'now')) ?>
          </span>
          <span class="opacity-40">•</span>
          <span class="flex items-center gap-1.5">
            <i class="fa-solid fa-location-dot text-[#87927B]"></i>
            <?= htmlspecialchars($event['venue']['name'] ?? 'Lagos, Nigeria') ?>
          </span>
          <span class="opacity-40">•</span>
          <span class="flex items-center gap-1.5">
            <i class="fa-solid fa-image text-[#87927B]"></i>
            <span id="gallery-total-count"><?= count($memories) ?> Total Moments</span>
          </span>
        </div>
      </div>
    </div>

    <!-- Filter Buttons -->
    <div class="flex items-center justify-between border-b border-[#E9E4DD] pb-4 flex-wrap gap-3">
      <div class="flex items-center gap-2">
        <button type="button" onclick="filterGallery('all', this)" class="gallery-filter-btn btn btn-primary btn-sm">
          All Moments
        </button>
        <button type="button" onclick="filterGallery('photo', this)" class="gallery-filter-btn btn btn-secondary btn-sm">
          Photos Only
        </button>
        <button type="button" onclick="filterGallery('tribute', this)" class="gallery-filter-btn btn btn-secondary btn-sm">
          Written Tributes
        </button>
      </div>

      <div class="text-xs text-[#6B675F]">
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
        <div class="gallery-item card card-hover overflow-hidden flex flex-col justify-between" data-type="<?= htmlspecialchars($type) ?>">
          <?php if ($hasImg): ?>
            <div class="relative h-60 w-full bg-[#F3EDE5] overflow-hidden cursor-pointer group" onclick="openLightbox('<?= htmlspecialchars($item['image_url']) ?>', '<?= htmlspecialchars(addslashes($item['caption'] ?? $item['message'])) ?>')">
              <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="Gallery item" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
              <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white">
                <i class="fa-solid fa-expand text-2xl drop-shadow-md"></i>
              </div>
            </div>
          <?php endif; ?>

          <div class="p-5 flex-1 flex flex-col justify-between space-y-3">
            <div>
              <?php if (!empty($item['caption'])): ?>
                <h3 class="font-display text-sm font-bold text-[#252321] mb-1"><?= htmlspecialchars($item['caption']) ?></h3>
              <?php endif; ?>
              <p class="text-xs text-[#252321] leading-relaxed italic">
                "<?= htmlspecialchars($item['message']) ?>"
              </p>
            </div>

            <div class="pt-3 border-t border-[#E9E4DD] flex items-center gap-3">
              <?php if (!empty($item['avatar'])): ?>
                <img src="<?= htmlspecialchars($item['avatar']) ?>" class="w-8 h-8 rounded-full object-cover ring-2 ring-[#E9E4DD]" alt="">
              <?php else: ?>
                <div class="w-8 h-8 rounded-full bg-[#EEF0EA] text-[#87927B] flex items-center justify-center font-bold text-xs">
                  <?= strtoupper(substr($item['author_name'] ?? 'G', 0, 1)) ?>
                </div>
              <?php endif; ?>
              <div class="min-w-0">
                <span class="text-xs font-semibold text-[#252321] block truncate"><?= htmlspecialchars($item['author_name'] ?? 'Guest') ?></span>
                <span class="text-[10px] text-[#6B675F] block truncate"><?= htmlspecialchars($item['author_role'] ?? 'Attendee') ?></span>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

  </main>

  <!-- Upload Memory Modal -->
  <div id="upload-memory-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
    <div class="card max-w-lg w-full p-6 sm:p-8 space-y-5 shadow-2xl relative">
      <div class="flex items-center justify-between border-b border-[#E9E4DD] pb-3">
        <div>
          <h3 class="font-display text-lg font-bold text-[#252321]">Add to Event Gallery</h3>
          <p class="text-xs text-[#6B675F]">Share a tribute photo, selfie, or warm reflection</p>
        </div>
        <button type="button" onclick="document.getElementById('upload-memory-modal').classList.add('hidden');" class="p-1 rounded-lg text-[#6B675F] hover:text-[#252321]">
          <i class="fa-solid fa-xmark text-lg"></i>
        </button>
      </div>

      <form id="memory-form" onsubmit="event.preventDefault(); submitMemory();" class="space-y-4">
        <div class="form-group">
          <label for="memory-name" class="form-label">Your Name *</label>
          <input type="text" id="memory-name" required placeholder="e.g. Somto Okafor" class="form-input w-full">
        </div>

        <div class="form-group">
          <label for="memory-role" class="form-label">Your Role or Relation</label>
          <input type="text" id="memory-role" placeholder="e.g. University Classmate, Guest, Colleague" class="form-input w-full">
        </div>

        <div class="form-group">
          <label for="memory-photo" class="form-label">Photo URL (Optional)</label>
          <input type="url" id="memory-photo" placeholder="https://images.unsplash.com/..." class="form-input w-full">
        </div>

        <div class="form-group">
          <label for="memory-caption" class="form-label">Caption or Short Title</label>
          <input type="text" id="memory-caption" placeholder="e.g. Cocktail hour with the keynote speakers" class="form-input w-full">
        </div>

        <div class="form-group">
          <label for="memory-message" class="form-label">Memory or Message *</label>
          <textarea id="memory-message" rows="3" required placeholder="Share your experience, tribute, or congratulations..." class="form-textarea w-full"></textarea>
        </div>

        <div class="pt-2 flex items-center justify-end gap-3">
          <button type="button" onclick="document.getElementById('upload-memory-modal').classList.add('hidden');" class="btn btn-secondary btn-sm">
            Cancel
          </button>
          <button type="submit" class="btn btn-primary btn-sm">
            Publish Memory
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Lightbox Modal -->
  <div id="lightbox-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/90 backdrop-blur-md" onclick="closeLightbox()">
    <div class="relative max-w-4xl w-full text-center space-y-3" onclick="event.stopPropagation()">
      <button type="button" onclick="closeLightbox()" class="absolute -top-10 right-0 text-white hover:text-[#FAF8F5]/80 p-2">
        <i class="fa-solid fa-xmark text-2xl"></i>
      </button>
      <img id="lightbox-img" src="" class="max-h-[75vh] mx-auto rounded-2xl shadow-2xl object-contain border border-white/10" alt="Enlarged photo">
      <p id="lightbox-caption" class="text-xs text-[#FAF8F5]/80 font-medium max-w-xl mx-auto"></p>
    </div>
  </div>

  <?php require __DIR__ . '/_inc/footer/footer-event.php'; ?>

</div>

<script>
function filterGallery(type, btn) {
  document.querySelectorAll('.gallery-filter-btn').forEach(b => {
    b.classList.remove('btn-primary');
    b.classList.add('btn-secondary');
  });
  btn.classList.remove('btn-secondary');
  btn.classList.add('btn-primary');

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
  card.className = 'gallery-item card card-hover overflow-hidden flex flex-col justify-between';
  card.setAttribute('data-type', photo ? 'photo' : 'tribute');

  let imgHtml = '';
  if (photo) {
    imgHtml = `<div class="relative h-60 w-full bg-[#F3EDE5] overflow-hidden cursor-pointer" onclick="openLightbox('${photo}', '${caption || message}')">
      <img src="${photo}" class="w-full h-full object-cover" alt="">
    </div>`;
  }

  card.innerHTML = `
    ${imgHtml}
    <div class="p-5 flex-1 flex flex-col justify-between space-y-3">
      <div>
        ${caption ? `<h3 class="font-display text-sm font-bold text-[#252321] mb-1">${caption}</h3>` : ''}
        <p class="text-xs text-[#252321] leading-relaxed italic">"${message}"</p>
      </div>
      <div class="pt-3 border-t border-[#E9E4DD] flex items-center gap-3">
        <div class="w-8 h-8 rounded-full bg-[#EEF0EA] text-[#87927B] flex items-center justify-center font-bold text-xs">
          ${name.charAt(0).toUpperCase()}
        </div>
        <div class="min-w-0">
          <span class="text-xs font-semibold text-[#252321] block truncate">${name}</span>
          <span class="text-[10px] text-[#6B675F] block truncate">${role}</span>
        </div>
      </div>
    </div>
  `;

  document.getElementById('gallery-container').prepend(card);
  document.getElementById('upload-memory-modal').classList.add('hidden');
  document.getElementById('memory-form').reset();
}
</script>

<?php require_once __DIR__ . '/_inc/footer.php'; ?>
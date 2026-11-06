<?php
require_once __DIR__ . '/_inc/MockData.php';

$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
if (preg_match('#^/gallery/([^/]+)#', $requestPath, $matches)) {
    $slug = $matches[1];
} else {
    $slug = $_GET['event'] ?? $_GET['slug'] ?? null;
}
$event = null;
if ($slug) {
    $event = MockData::getEventBySlug($slug) ?? MockData::getEventById($slug);
}

// Fallback to first event if not specified so guests have a valid demonstration
if (!$event) {
    $events = MockData::getEvents();
    $event = $events[0] ?? null;
}

$memories = $event ? MockData::getMemoriesForEvent($event['id']) : [];
$photos = array_values(array_filter($memories, fn($m) => !empty($m['image_url'])));

$pageTitle = $event ? 'Photo Gallery — ' . $event['title'] : 'Event Photo Gallery';
require_once __DIR__ . '/_inc/header.php';
?>

<div class="min-h-screen flex flex-col bg-[#FAFBF6]">

  <!-- Gallery Top Bar (Sticky, Event-Specific) -->
  <header class="bg-white border-b border-[#EAE8E2] sticky top-0 z-30">
    <div class="container-main px-4 h-16 flex items-center justify-between">
      <?php if ($event): ?>
        <a href="/e/<?= urlencode($event['slug']) ?>" class="btn btn-ghost btn-sm text-[#0F0F0F] hover:text-[#FF502B]">
          <i class="fa-solid fa-arrow-left sm:mr-1.5"></i>
          <span class="hidden sm:inline">Back to Event</span>
          <span class="sm:hidden">Back</span>
        </a>

        <div class="flex items-center gap-2 max-w-[200px] sm:max-w-md truncate">
          <div class="w-6 h-6 rounded-md bg-[#FF502B] text-white flex items-center justify-center font-bold text-xs shrink-0">
            <?= strtoupper(substr($event['title'] ?? 'E', 0, 1)) ?>
          </div>
          <span class="text-xs font-semibold text-[#0F0F0F] truncate">
            <?= htmlspecialchars($event['title']) ?>
          </span>
        </div>

        <span class="badge badge-sage shrink-0">
          <i class="fa-solid fa-images mr-1.5"></i>
          <span><?= count($photos) ?> <?= count($photos) === 1 ? 'Photo' : 'Photos' ?></span>
        </span>
      <?php else: ?>
        <a href="/" class="btn btn-ghost btn-sm text-[#0F0F0F] hover:text-[#FF502B]">
          <i class="fa-solid fa-arrow-left mr-1.5"></i>
          <span>Home</span>
        </a>
      <?php endif; ?>
    </div>
  </header>

  <main class="flex-1 container-main px-4 py-8 space-y-8">

    <?php if (!$event): ?>
      <div class="card p-12 text-center max-w-lg mx-auto space-y-4">
        <div class="w-12 h-12 rounded-full bg-[#EEF0EA] text-[#87927B] flex items-center justify-center mx-auto text-xl">
          <i class="fa-solid fa-images"></i>
        </div>
        <h2 class="font-display text-xl font-bold text-[#0F0F0F]">Event Not Specified</h2>
        <p class="text-xs text-[#63615E]">
          Each gallery on Foliioo is tied directly to a specific event. Please select an event to view its curated photo album.
        </p>
        <a href="/event.php" class="btn btn-primary inline-flex">View Sample Event</a>
      </div>
    <?php else: ?>

      <!-- Event Header -->
      <div class="space-y-3 border-b border-[#EAE8E2] pb-6">
        <div class="flex items-center gap-2">
          <span class="eyebrow text-[#87927B]"><?= htmlspecialchars($event['event_type_label'] ?? 'Event') ?></span>
          <span class="text-[#EAE8E2]">•</span>
          <span class="text-xs text-[#63615E] font-medium">Curated Photo Album</span>
        </div>

        <h1 class="font-display text-2xl sm:text-4xl font-bold tracking-tight text-[#0F0F0F]">
          <?= htmlspecialchars($event['title']) ?>
        </h1>

        <div class="flex flex-wrap items-center gap-4 text-xs text-[#63615E] pt-1">
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
            <span><?= count($photos) ?> <?= count($photos) === 1 ? 'Curated Photo' : 'Curated Photos' ?></span>
          </span>
        </div>

        <?php if (!empty($event['post_event_message'])): ?>
          <div class="mt-4 p-4 rounded-xl bg-[#F3F4EE] border border-[#EAE8E2] text-xs text-[#0F0F0F] leading-relaxed max-w-3xl">
            <div class="flex items-center gap-2 font-semibold text-[#FF502B] mb-1">
              <i class="fa-solid fa-award"></i>
              <span>Host Note</span>
            </div>
            <?= htmlspecialchars($event['post_event_message']) ?>
          </div>
        <?php endif; ?>
      </div>

      <!-- Simplified Photo-First Grid (Pure Photos, No Captions/Names) -->
      <?php if (empty($photos)): ?>
        <div class="card p-12 text-center max-w-md mx-auto space-y-3">
          <div class="w-12 h-12 rounded-full bg-[#EEF0EA] text-[#87927B] flex items-center justify-center mx-auto text-xl">
            <i class="fa-solid fa-image"></i>
          </div>
          <h3 class="font-display text-base font-bold text-[#0F0F0F]">No Photos Uploaded Yet</h3>
          <p class="text-xs text-[#63615E]">The event organizer has not published official photos for this event yet.</p>
        </div>
      <?php else: ?>
        <div id="gallery-grid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-5">
          <?php foreach ($photos as $idx => $item): ?>
            <div 
              class="aspect-square bg-[#F3F4EE] rounded-xl overflow-hidden border border-[#EAE8E2] cursor-pointer group relative shadow-2xs hover:shadow-md transition-all duration-300"
              onclick="openLightbox('<?= htmlspecialchars($item['image_url']) ?>', <?= $idx ?>)"
            >
              <img 
                src="<?= htmlspecialchars($item['image_url']) ?>" 
                alt="Event Photo <?= $idx + 1 ?>" 
                class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                loading="lazy"
              >
              <div class="absolute inset-0 bg-[#0F0F0F]/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white">
                <i class="fa-solid fa-expand text-xl drop-shadow-md"></i>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

    <?php endif; ?>

  </main>

  <!-- Minimalist Lightbox Modal -->
  <div id="lightbox-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-[#0F0F0F]/95 backdrop-blur-md" onclick="closeLightbox()">
    <div class="relative max-w-5xl w-full flex flex-col items-center justify-center" onclick="event.stopPropagation()">
      <button type="button" onclick="closeLightbox()" class="absolute -top-12 right-0 text-white/80 hover:text-white p-2 text-2xl transition">
        <i class="fa-solid fa-xmark"></i>
      </button>

      <img id="lightbox-img" src="" class="max-h-[82vh] max-w-full rounded-xl shadow-2xl object-contain border border-white/10" alt="Full size photo">

      <!-- Navigation Arrows -->
      <button type="button" onclick="prevPhoto()" class="absolute left-2 sm:-left-12 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center text-lg transition">
        <i class="fa-solid fa-chevron-left"></i>
      </button>
      <button type="button" onclick="nextPhoto()" class="absolute right-2 sm:-right-12 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center text-lg transition">
        <i class="fa-solid fa-chevron-right"></i>
      </button>
    </div>
  </div>

  <!-- Guest Footer -->
  <?php require __DIR__ . '/_inc/footer/footer-event.php'; ?>

</div>

<script>
const galleryPhotos = <?= json_encode(array_column($photos, 'image_url')) ?>;
let currentIdx = 0;

function openLightbox(url, idx) {
  currentIdx = typeof idx === 'number' ? idx : galleryPhotos.indexOf(url);
  document.getElementById('lightbox-img').src = url;
  document.getElementById('lightbox-modal').classList.remove('hidden');
  document.body.classList.add('overflow-hidden');
}

function closeLightbox() {
  document.getElementById('lightbox-modal').classList.add('hidden');
  document.body.classList.remove('overflow-hidden');
}

function prevPhoto() {
  if (galleryPhotos.length === 0) return;
  currentIdx = (currentIdx - 1 + galleryPhotos.length) % galleryPhotos.length;
  document.getElementById('lightbox-img').src = galleryPhotos[currentIdx];
}

function nextPhoto() {
  if (galleryPhotos.length === 0) return;
  currentIdx = (currentIdx + 1) % galleryPhotos.length;
  document.getElementById('lightbox-img').src = galleryPhotos[currentIdx];
}

document.addEventListener('keydown', (e) => {
  const modal = document.getElementById('lightbox-modal');
  if (modal && !modal.classList.contains('hidden')) {
    if (e.key === 'Escape') closeLightbox();
    if (e.key === 'ArrowLeft') prevPhoto();
    if (e.key === 'ArrowRight') nextPhoto();
  }
});
</script>

<?php require_once __DIR__ . '/_inc/footer.php'; ?>
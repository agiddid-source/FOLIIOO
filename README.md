# FOLIIOO — Shared Navigation, Mock Data & Reusable Components

Foundation layer for the Foliioo Event Lifecycle Platform.
**Stack:** PHP 8.4 · HTML5 · Tailwind CSS (CDN) · Vanilla JS · Lucide Icons

---

## Quick Start

```bash
php -S localhost:8000
```

---

## Structure

```
_inc/
├── header.php              # <head> with Tailwind CDN, Lucide, fonts, global styles
├── footer.php              # Closing scripts (nav.js + lucide.createIcons)
├── MockData.php            # PHP helper — queries JSON fixtures, no DB needed
├── nav/
│   ├── nav-marketing.php   # Public header: logo, nav links, CTA, mobile drawer
│   ├── nav-event.php       # Guest event navbar: glassmorphic, status badges, RSVP action
│   └── nav-dashboard.php   # Creator workspace: sidebar, topbar, event switcher, user menu
└── footer/
    ├── footer-event.php    # Guest footer: host info, WhatsApp share, copy link, Foliioo CTA
    └── footer-marketing.php # SaaS footer: event types, features, plans/NGN, legal

js/
├── nav.js                  # Mobile drawers, sidebar collapse, scroll-spy, draft persistence
└── data/
    ├── events.json         # Events in all lifecycle states
    ├── event_types.json    # Wedding, Burial, Birthday, Gala, Conference, Custom
    ├── plans.json          # Starter (Free), Celebration Pro (₦15k), Grand Gala (₦45k)
    ├── programmes.json     # Agenda items with order, status, timestamps
    ├── guests.json         # RSVPs, reminders, QR hashes, check-in status
    └── creators.json       # Host profiles
```

---

## Using the Components

### Header & Footer (every page)

```php
<?php
$pageTitle = 'My Page Title';
require_once __DIR__ . '/_inc/header.php';
?>

<!-- page content -->

<?php require_once __DIR__ . '/_inc/footer.php'; ?>
```

### Nav: Public Marketing

For landing, pricing, templates, auth pages.

```php
<?php
$activeNav = 'pricing'; // 'home' | 'templates' | 'pricing' | 'how-it-works'
require __DIR__ . '/_inc/nav/nav-marketing.php';
?>
```

### Nav: Guest Event Page

Requires `$event` array. Renders status-aware navbar with RSVP/Live Now/Postponed actions.

```php
<?php
require_once __DIR__ . '/_inc/MockData.php';
$event = MockData::getEventBySlug($_GET['slug'] ?? '');
require __DIR__ . '/_inc/nav/nav-event.php';
?>
```

**Supported statuses:** `published_pre_event`, `in_progress`, `completed`, `postponed`, `cancelled`

### Nav: Creator Dashboard

Requires `$currentEvent`, `$allEvents`, `$creator`, `$activeTab`.

```php
<?php
require_once __DIR__ . '/_inc/MockData.php';
$currentEvent = MockData::getEventBySlug('chief-adeleke-celebration-of-life');
$allEvents = MockData::getEvents();
$creator = MockData::getCreatorById($currentEvent['creator_id']);
$activeTab = $_GET['tab'] ?? 'overview';
require __DIR__ . '/_inc/nav/nav-dashboard.php';
?>
```

**Tabs:** `overview`, `programme`, `guests`, `checkin`, `ticketing`, `donations`, `gallery`, `settings`

The sidebar collapses on desktop (only the expand button remains). On mobile it slides in as an overlay.

### Footers

```php
<!-- Guest event page -->
<?php require __DIR__ . '/_inc/footer/footer-event.php'; ?>

<!-- Marketing/landing pages -->
<?php require __DIR__ . '/_inc/footer/footer-marketing.php'; ?>
```

`footer-event.php` needs `$event` and optionally `$creator` in scope.

---

## MockData API

```php
require_once __DIR__ . '/_inc/MockData.php';

MockData::getEvents();                          // All events
MockData::getEventBySlug('some-slug');          // Single event by URL slug
MockData::getEventById('evt_001');              // Single event by ID
MockData::getEventTypes();                      // Event categories
MockData::getPlans();                           // Pricing plans
MockData::getPlanById('plan_starter');           // Single plan
MockData::getProgrammesForEvent('evt_001');      // Ordered agenda items
MockData::getGuestsForEvent('evt_001');          // Guest list
MockData::getAttendanceStats('evt_001');         // Counts: total, attending, checked_in, reminders
MockData::getCreatorById('creator_001');         // Host profile
MockData::getTicketsForEvent('evt_001');         // Tickets and table packages
MockData::getDonationsForEvent('evt_001');       // Voluntary tribute gifts
MockData::getDonationSummary('evt_001');         // Aggregate sums and donor counts
MockData::getMemoriesForEvent('evt_001');        // Photos, quotes, and guest tributes
MockData::getGuestLimitForEvent('evt_001');      // Capacity enforcement: max, attending, remaining
```

---

## Clean URL Routing Architecture

Foliioo implements user-friendly clean URL paths with zero developer jargon (no `slug:` or `?slug=`):
- **Events:** `/e/{event-slug}` (e.g. `/e/chief-adeleke-celebration-of-life`)
- **Galleries:** `/gallery/{event-slug}` (e.g. `/gallery/african-fintech-summit-2026`)
- **Checkout:** `/checkout/{event-slug}` (e.g. `/checkout/lagos-innovators-gala`)
- Supported natively in Apache via `.htaccess` and in the PHP built-in server via `router.php`.
- Full backwards-compatibility with legacy query parameters (`?slug=`, `?event=`).

## Guest Experience Pages (Modules 6, 7, 8, 9, 14)

### 1. Public Event Website (`/e/{slug}`)
Rendered dynamically based on the event's selected template (`elegant`, `floral`, `modern`).
- `http://localhost:8000/e/chief-adeleke-celebration-of-life` (Elegant template, Burial)
- `http://localhost:8000/e/ghost-ghostess-wedding` (Floral template, Wedding)
- `http://localhost:8000/e/lagos-innovators-gala` (Modern template, Gala with Tickets)
- `http://localhost:8000/e/kemi-at-30-all-white` (Postponed notice state)
- `http://localhost:8000/e/african-fintech-summit-2026` (Completed state)
- `http://localhost:8000/e/lagos-charity-polo-match` (Cancelled state with refund notice)

**Creator Preview Mode:**
Append `?preview=1` to any event link to view the private organizer bar with instant lifecycle state switching.

### 2. Ticketing & Simulated Checkout (`/checkout/{slug}`)
- `http://localhost:8000/checkout/lagos-innovators-gala?ticket_id=tkt_gala_vip&quantity=2`
Includes order summary, simulated payment options (Card / Bank Transfer), and instant digital pass generation with order reference and QR code.

### 3. Dedicated Event Photo Gallery (`/gallery/{slug}`)
- `http://localhost:8000/gallery/african-fintech-summit-2026`
Curated photo albums attached directly to individual events with full-screen lightbox navigation, keyboard shortcuts (Left/Right/Esc), and direct return link to the event page.

---

## Client-Side Draft Persistence

Pre-registration drafts stored in `localStorage`. The marketing nav shows a "Resume Draft" badge when a draft exists.

```javascript
FoliiooDraft.saveDraft({ title: 'My Event', date: '2026-12-01', template: 'elegant' });
FoliiooDraft.getDraft();    // returns object or null
FoliiooDraft.clearDraft();
```

---

## Foliioo Design System V1.2 (Official Logo Palette Edition)

One visual language for every Foliioo page, derived directly from the official brand identity.

### Typography
- **Primary font (body):** `DM Sans` (400 Regular, 500 Medium, 600 Semi Bold, 700 Bold)
  - Usage: body text, navigation, buttons, forms, tables, dashboard content
- **Secondary font (headings):** `Fraunces` (400, 500, 600, 700, class `.font-display`)
  - Usage: hero headings, major page titles, section headings, event titles
- **Guest-facing template fonts (public event websites only):**
  - Modern: `DM Sans`
  - Elegant: `Cormorant Garamond`
  - Floral: `DM Serif Display`

### Color Palette (Extracted from Logo)

| Token | Hex | Tailwind / CSS | Usage |
|-------|-----|----------------|-------|
| Page background | Alabaster `#FAFBF6` | `bg-[#FAFBF6]` / `--color-page` | Main page and workspace canvas |
| Card background | Paper `#FFFFFF` | `bg-[#FFFFFF]` / `.card` | Cards, forms, modals, dashboard panels |
| Alternate section | Tint `#F3F4EE` | `bg-[#F3F4EE]` / `.surface-tint` | Alternate with Alabaster and Paper on long pages |
| Dark section | Pitch `#0D0D0D` | `bg-[#0D0D0D]` / `.surface-deep` | One dark section per page at most |
| Primary accent | Electric Coral `#FF502B` | `bg-[#FF502B]` / `.btn-primary` | CTAs, brand dots, links, selected states |
| Primary hover | Deep Coral `#E03A17` | `hover:bg-[#E03A17]` | Hover state of primary buttons and links |
| Accent light | Soft Coral `#FFF0ED` | `bg-[#FFF0ED]` | Soft badges and subtle highlighted sections |
| Secondary accent | Soft Emerald `#4C7C59` | `bg-[#4C7C59]` / `.badge-sage` | Highlights, supporting info, upcoming status |
| Primary text | Carbon `#0F0F0F` | `text-[#0F0F0F]` | High contrast body and titles |
| Secondary text | Muted `#63615E` | `text-[#63615E]` | Supporting descriptions and secondary info |
| Tertiary text | Soft `#A6A39D` | `text-[#A6A39D]` | Placeholders and disabled text only |
| Light border | Warm Border `#EAE8E2` | `border-[#EAE8E2]` | Cards, inputs, dividers |
| Dark border | Dark Border `#D8D6CE` | `border-[#D8D6CE]` | Emphasised outlines and selected components |

### Status and Feedback Colors
- **Success:** `#2E7D47` / `#EBF5EE` (`.badge-success`)
- **Warning / Postponed:** `#B45309` / `#FEF3C7` (`.badge-warning`)
- **Error / Cancelled:** `#DC2626` / `#FEE2E2` (`.badge-error`)
- **Upcoming:** Soft Emerald `#4C7C59` / `#EEF5F0` (`.badge-sage`)
- **In-progress:** Electric Coral `#FF502B` / `#FFF0ED` (`.badge-accent`)
- **Completed:** Neutral `#63615E` / `#F3F4EE` (`.badge-neutral`)

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

## Guest Experience Pages (Modules 6, 7, 8, 9, 14)

### 1. Public Event Website (`event.php`)
Rendered dynamically based on the event's selected template (`elegant`, `floral`, `modern`).
- `http://localhost:8000/event.php?slug=chief-adeleke-celebration-of-life` (Elegant template, Burial)
- `http://localhost:8000/event.php?slug=chidi-amara-wedding` (Floral template, Wedding)
- `http://localhost:8000/event.php?slug=lagos-innovators-gala` (Modern template, Gala with Tickets)
- `http://localhost:8000/event.php?slug=kemi-at-30-all-white` (Postponed notice state)
- `http://localhost:8000/event.php?slug=african-fintech-summit-2026` (Completed state)
- `http://localhost:8000/event.php?slug=lagos-charity-polo-match` (Cancelled state with refund notice)

**Creator Preview Mode:**
Append `?preview=1` to any event link to view the private organizer bar with instant lifecycle state switching.

### 2. Ticketing & Simulated Checkout (`checkout.php`)
- `http://localhost:8000/checkout.php?event=lagos-innovators-gala&ticket_id=tkt_gala_vip&quantity=2`
Includes order summary, simulated payment options (Card / Bank Transfer), and instant digital pass generation with order reference and QR code.

### 3. Post-Event Gallery & Memories (`gallery.php`)
- `http://localhost:8000/gallery.php?event=african-fintech-summit-2026`
Includes host gratitude banner, moments filtering (All, Photos, Written Tributes), lightbox photo enlarger, and guest memory contribution drawer.

---

## Client-Side Draft Persistence

Pre-registration drafts stored in `localStorage`. The marketing nav shows a "Resume Draft" badge when a draft exists.

```javascript
FoliiooDraft.saveDraft({ title: 'My Event', date: '2026-12-01', template: 'elegant' });
FoliiooDraft.getDraft();    // returns object or null
FoliiooDraft.clearDraft();
```

---

## Foliioo Design System V1.1

One visual language for every Foliioo page. No new fonts, colours or button styles without team approval.

### Typography
- **Primary font (body):** `DM Sans` (400 Regular, 500 Medium, 600 Semi Bold, 700 Bold)
  - Usage: body text, navigation, buttons, forms, tables, dashboard content
- **Secondary font (headings):** `Fraunces` (400, 500, 600, 700, class `.font-display`)
  - Usage: hero headings, major page titles, section headings, event titles
- **Guest-facing template fonts (public event websites only):**
  - Modern: `DM Sans`
  - Elegant: `Cormorant Garamond`
  - Floral: `DM Serif Display`

### Color Palette

| Token | Hex | Tailwind / CSS | Usage |
|-------|-----|----------------|-------|
| Page background | Cream `#FAF8F5` | `bg-[#FAF8F5]` / `--color-page` | Main page and workspace backgrounds |
| Card background | Paper `#FFFFFF` | `bg-[#FFFFFF]` / `.card` | Cards, forms, modals, dashboard panels |
| Alternate section | Tint `#F3EDE5` | `bg-[#F3EDE5]` / `.surface-tint` | Alternate with Cream and Paper on long pages |
| Dark section | Deep `#252321` | `bg-[#252321]` / `.surface-deep` | One dark section per page at most |
| Primary accent | Terracotta `#9A5F44` | `bg-[#9A5F44]` / `.btn-primary` | CTAs, important actions, links, selected states |
| Primary hover | Deep Terracotta `#7E4A33` | `hover:bg-[#7E4A33]` | Hover state of primary buttons and links |
| Secondary accent | Sage `#87927B` | `bg-[#87927B]` / `.badge-sage` | Highlights, supporting info, upcoming status |
| Secondary light | Sage Light `#EEF0EA` | `bg-[#EEF0EA]` | Soft badges and subtle highlighted sections |
| Primary text | Ink `#252321` | `text-[#252321]` | High contrast body and titles |
| Secondary text | Muted `#6B675F` | `text-[#6B675F]` | Supporting descriptions and secondary info |
| Tertiary text | Soft `#A19B93` | `text-[#A19B93]` | Placeholders and disabled text only |
| Light border | Warm Border `#E9E4DD` | `border-[#E9E4DD]` | Cards, inputs, dividers |
| Dark border | Dark Border `#D8D1C8` | `border-[#D8D1C8]` | Emphasised outlines and selected components |

### Status and Feedback Colors
- **Success:** `#3F6B48` / `#E8F1EA` (`.badge-success`)
- **Warning / Postponed:** `#8F5F12` / `#FBF1DC` (`.badge-warning`)
- **Error / Cancelled:** `#B54747` / `#F8E6E6` (`.badge-error`)
- **Upcoming:** Sage `#87927B` / `#EEF0EA` (`.badge-sage`)
- **In-progress:** Terracotta `#9A5F44` / `#9A5F44` (`.badge-accent`)
- **Completed:** Neutral `#6B675F` / `#F3EDE5` (`.badge-neutral`)

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
```

---

## Client-Side Draft Persistence

Pre-registration drafts stored in `localStorage`. The marketing nav shows a "Resume Draft" badge when a draft exists.

```javascript
FoliiooDraft.saveDraft({ title: 'My Event', date: '2026-12-01', template: 'elegant' });
FoliiooDraft.getDraft();    // returns object or null
FoliiooDraft.clearDraft();
```

---

## Color Palette

| Token | Hex | Usage |
|-------|-----|-------|
| Primary accent | `#320E3B` | Buttons, active states, brand |
| Accent hover | `#4A1556` | Hover states |
| Completed/contrast | `#0D160B` | Dark badges, completed status |
| Light canvas | `#F1F7ED` / `#F8FAFC` | Backgrounds |
| White | `#FFFFFF` | Cards, surfaces |

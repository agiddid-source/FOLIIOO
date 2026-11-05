# Foliioo

Foliioo is a three-member event platform prototype. The application is planned as a plain-PHP multi-page app: each module will have its own PHP entry point and share the Ghost dashboard shell and JSON data helpers.

## Ghost foundations

- `data/` contains the mock event, programme, guest, media, donation, order, notification, and plan records.
- `includes/constants.php` defines shared state values and configuration.
- `includes/data.php` provides allowlisted JSON read/write helpers and output/money formatting.
- `includes/data.php` also provides shared display formatting for event dates.
- `includes/dashboard-shell.php` provides shared desktop navigation, a mobile menu, and optional event context.
- `dashboard.php` is the Module 5 overview with workspace totals, a next-published-event shortcut, and a searchable, filterable event list.
- `manage.php?event=evt_001` opens an event overview with guest totals, a copyable public address, and a confirmed phase-change flow that updates `events.json`.
- `programme.php?event=evt_001` manages ordered programme items, the current item, and pause/resume state.
- `check-in.php?event=evt_001` searches confirmed guests and records pass check-ins through `api/checkin.php`.
- `gallery.php?event=evt_001` edits seeded photo captions, visibility, and order. Image selection is a browser-only preview; uploads are not implemented.
- `settings.php?event=evt_001` edits event details and plan features, and saves simulated postpone/cancel notifications.
- `api/state.php?event=evt_001` returns public-safe phase and programme state for polling.
- Ghost module PHP functions and constants use the `ght` prefix.
- Ghost-owned screens follow the team-lead's supplied Foliioo Design System V1.1: DM Sans body, Fraunces display, and the approved palette.

Money in the sample records is stored as integer naira amounts. Payments and notifications are simulated; no email or SMS is sent.

The checked-in `Design.md` still contains V1.0. It is a shared team file and was left untouched; the team should update it to V1.1 so the repository's shared source of truth matches the latest team-lead guidance.

## Run locally

For XAMPP, run `npm install` and `npm run build:css` from the project folder whenever PHP templates or styles change. Copy the project folder into `C:\xampp\htdocs\Foolio`, start Apache in the XAMPP Control Panel, and visit `http://localhost/Foolio/`. The root redirects to `dashboard.php`; Tailwind is compiled locally into `assets/css/app.css` and does not depend on a browser CDN.

Alternatively, run `php -S localhost:8000` from the project root and visit `http://localhost:8000/`.

## Navigation and prototype limits

The shared shell links to all available Ghost modules. Member-owned routes are provisional: `builder.php` for the event builder and `guests.php` for guest management. The shell disables routes that do not exist yet, and event-specific routes until an event is selected.

The JSON files are a local prototype data store, not a production database. Gallery image uploads, real payment processing, camera-based scanning, and outbound notifications are intentionally not implemented.

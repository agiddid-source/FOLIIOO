# Ghost module assumptions

- The repository had no PHP application, shared includes, stylesheets, data, or Git metadata at inspection time.
- Ghost-owned PHP functions and constants use the `ght` prefix; existing brief filenames are retained for consistency with the proposed structure.
- Plain PHP multi-page entry points remain the architecture; Tailwind is compiled locally into `assets/css/app.css` so pages do not rely on the browser CDN.
- Ghost-owned workspace screens adopt the team-lead's supplied Design System V1.1; the shared Design.md remains unchanged at V1.0 pending team coordination.
- Mock money values are integer naira amounts (not kobo), and all seed records are simulated.
- The seed plan limits, notification fee, and local SVG gallery illustrations are demonstration values and need team confirmation before production use.
- `builder.php` and `guests.php` are provisional navigation destinations for Member 1 and Member 2; their final routes should be confirmed with those owners.
- Navigation items remain visibly disabled until the corresponding page exists and, where required, an event has been selected.
- Event management and phase changes are implemented in `manage.php`; event creation and the public event page remain unavailable until their member-owned pages/routes are added.
- Event phase changes are saved in events.json only after a separate review and confirmation POST; a session CSRF token guards both form submissions.
- The shell uses native HTML disclosure navigation on mobile to provide keyboard-operable behavior without an inline script.
- Programme, check-in, gallery metadata, and settings actions persist to the supplied JSON seed files; these files are prototype storage and are not safe for concurrent production use.
- Gallery photo selection creates temporary browser previews only; no file upload or persistent image storage is implemented.
- Check-in pass entry simulates a scan by matching the seeded pass reference; camera integration is outside this prototype.
- Postponement and cancellation append simulated email notification records, and no outbound email or SMS is sent.

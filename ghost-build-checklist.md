# Foliioo Build Checklist: Dashboard and Event Operations (Ghost)

Filtered from my personal Frontend Build Checklist for the five modules I own (5 Dashboard, 10 Programme, 11 Check-in, 12 Gallery, 13 Settings and notifications) in a plain PHP, multi-page prototype with mock data. Items that belong to other members, or that do not apply to a mock prototype, are listed at the bottom so they are skipped on purpose and not forgotten.

Priority marks: **[P1]** must pass before any PR, **[P2]** should pass, **[P3]** nice to have.

---

## 1. Before I start each module
- [ ] **[P1]** Pull the latest `develop`, then branch as `feature/ghost-<module>` (confirm the team's naming format)
- [ ] **[P1]** Read the module spec and list every screen and state: default, loading, empty, error, success
- [ ] **[P1]** Check the repo for an existing component before creating a new one
- [ ] **[P1]** Confirm where new files belong (pages, includes, assets, data) and follow the repo's structure
- [ ] **[P1]** Confirm the identifier prefix and naming convention with the team (still open; I suggest `fol-`)
- [ ] **[P1]** Confirm the data fields I read and write match the agreed data contract

## 2. Code quality and readability (most important)
The test: a teammate or my supervisor can open any file and understand what it does within a minute, without asking me.

- [ ] **[P1]** Every file starts with a short comment: what it is for, what it includes, which data files it reads and writes
- [ ] **[P1]** Names describe purpose, not appearance (`status-chip`, not `green-pill`) and are consistent with the agreed casing
- [ ] **[P1]** Functions do one thing and are short. If I need to scroll to read one, split it
- [ ] **[P1]** Logic sits at the top of a PHP page, markup below. No data fetching or business logic buried inside HTML
- [ ] **[P1]** No magic strings. Statuses (`pre_event`, `live`, `in_progress` and so on) come from one constants file
- [ ] **[P1]** No duplicated logic. If two pages need it, it becomes a shared helper or component
- [ ] **[P1]** Comments explain why, not what. Code explains what
- [ ] **[P1]** No leftover `var_dump`, `console.log`, commented-out code, or names like `temp`, `test`, `div1`
- [ ] **[P1]** All output is escaped with `htmlspecialchars` and all input is validated server-side
- [ ] **[P2]** Early returns instead of deep nesting
- [ ] **[P2]** Consistent formatting (PSR-12 style for PHP, one style for JS and CSS)
- [ ] **[P2]** No inline styles or inline scripts. Styles in CSS, behaviour in JS modules
- [ ] **[P2]** Small focused commits with meaningful messages, for example `feat(programme): add reorder buttons`

## 3. Structure (multi-page PHP)
- [ ] **[P1]** Each page has its own `.php` entry point with clear links between pages
- [ ] **[P1]** Shared parts (sidebar, top bar, event header) live in one include, not copy-pasted
- [ ] **[P1]** Event context passes deliberately through `?event=ID`. A missing or unknown ID shows a clear error state
- [ ] **[P1]** Shared data moves only through the JSON helpers, never by writing files directly from pages
- [ ] **[P2]** Page `<title>` is set on every page (favicon and base meta come from the shared head, owned by Member 1)
- [ ] **[P2]** Scripts and styles load only on the pages that need them

## 4. Components and reusability
- [ ] **[P1]** One component, one responsibility
- [ ] **[P1]** Configurable through parameters, with no hardcoded text, colours or spacing. Tokens only
- [ ] **[P1]** Works in all its states: default, hover, focus, pressed, disabled, loading, error
- [ ] **[P1]** Does not break with very long, very short or missing content (a long venue name, an empty caption)
- [ ] **[P2]** Documented in a short comment: what it does, its parameters, one usage example

## 5. UI states per page
Tick each state once it is designed and built. N/A is a valid answer, but write it.

| Page | Loading | Empty | Error | Success feedback |
|---|---|---|---|---|
| `dashboard.php` | [ ] | [ ] no events | [ ] | [ ] |
| `manage.php` | [ ] | [ ] | [ ] unknown event | [ ] phase changed |
| `programme.php` | [ ] | [ ] no items | [ ] | [ ] saved |
| `check-in.php` | [ ] | [ ] no guests | [ ] | [ ] valid, already used, invalid |
| `gallery.php` | [ ] | [ ] no photos | [ ] limit reached | [ ] |
| `settings.php` | [ ] | n/a | [ ] validation | [ ] saved, postponed, cancelled |

- [ ] **[P1]** Buttons disable and show loading during a submit
- [ ] **[P1]** Nothing jumps around when content arrives
- [ ] **[P1]** Search and filters in My Events have a no-results state

## 6. Data, validation and errors (adapted for mock data)
There is no real API or environment variables in this phase, so the checklist items change shape.

- [ ] **[P1]** Paths, plan limits and mock fees live in one config file or constants file, not scattered through pages
- [ ] **[P1]** A failed read or write of a data file is handled with a clear message and never loses the user's input
- [ ] **[P1]** Data is accessed safely before rendering (a missing key or `null` never crashes a page)
- [ ] **[P1]** Forms validate on the client with clear inline messages, and again on the server
- [ ] **[P2]** The polling endpoint (`api/state.php`) shows a calm "Reconnecting" state if it fails, and recovers on its own

## 7. System states that concern my pages
- [ ] **[P1]** Unknown event ID (my version of a 404): friendly message and a link back to the dashboard
- [ ] **[P1]** Not authorised (mock 403) for pages that need a host or usher, with a way back
- [ ] **[P2]** Mock session expired: prompts sign in and returns the user to where they were
- [ ] **[P2]** Offline or slow connection state on check-in, since it is used at venues
- [ ] **[P2]** A placeholder page for links into modules that are not built yet (Edit Website, Guests and RSVPs)
- [ ] **[P1]** Error pages never show stack traces or internal details. Turn display of errors off in anything shown to others

## 8. Responsiveness and accessibility
- [ ] **[P1]** Tested at 360px, tablet and desktop. Nothing overflows horizontally
- [ ] **[P1]** Touch targets are at least 44px, and 48px on check-in
- [ ] **[P1]** Semantic HTML (`button`, `nav`, `main`, headings in order). Inputs have labels. Images have `alt`
- [ ] **[P1]** Keyboard navigation and visible focus work, including inside modals
- [ ] **[P1]** Status is never shown by colour alone. Chips and check-in results have icon plus text
- [ ] **[P2]** Colour contrast meets WCAG AA
- [ ] **[P2]** Reduced-motion preference respected

## 9. Before I open a PR
- [ ] **[P1]** Compared side by side against DESIGN.md and the module spec
- [ ] **[P1]** Checked every page and every state, not only the happy path
- [ ] **[P1]** Ran `php -l` on every changed PHP file, and loaded each page with all errors and warnings on. Zero notices
- [ ] **[P1]** Self-reviewed the diff before asking anyone else
- [ ] **[P1]** `docs/assumptions-ghost.md` is up to date
- [ ] **[P1]** PR description says what changed, why, which data files are touched, with screenshots (mobile and desktop)
- [ ] **[P2]** Tested in at least two browsers
- [ ] **[P2]** Did not edit another member's files, except to add a link or include

## Intentionally skipped (not mine, or not applicable yet)
- Sign up, login, password and email verification pages (Member 1, Module 2)
- Legal pages, cookie banner, `robots.txt`, `sitemap.xml`, social share tags (shared site, private dashboard)
- Environment variables and real API error handling (no backend in this phase)
- Production build step (plain PHP has none; `php -l` covers it)
- Payment success and failure pages (Member 1 and Member 2)

## Lessons to add as I learn
- [ ] _Add new internship lessons here_

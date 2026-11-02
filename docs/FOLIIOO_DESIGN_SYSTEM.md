# Foliioo Design System V1.1

**Purpose:** Establish a unified visual language for the entire Foliioo platform. All team members must follow these standards when building new pages, components and modules to prevent inconsistent layouts, typography, colors and interactions.

---

## 1. Typography

### Primary Font (Body)

**DM Sans**

Weights:
- 400 — Regular
- 500 — Medium
- 600 — Semi Bold
- 700 — Bold

Usage:
- Body text
- Navigation links
- Buttons
- Form labels and inputs
- Descriptions
- Dashboard content
- Tables and data displays

### Secondary Font (Headings)

**Fraunces**

Weights:
- 400 — Regular
- 500 — Medium
- 600 — Semi Bold
- 700 — Bold

Usage:
- Hero headings
- Major page titles
- Event titles and headlines
- Section headings
- Editorial and promotional content
- Public event website headings

Example:
"Your Event, Beautifully Told"

Fraunces is a variable font loaded from Google Fonts (weights 400 to 700).

### Typography Guidelines

- Use DM Sans for general interface content.
- Use Fraunces for prominent headings and editorial elements.
- Avoid introducing additional fonts without team approval.
- Maintain consistent heading hierarchy across modules.

---

## 2. Color Palette

### Primary Background

**Cream**

Hex: `#FAF8F5`

Tailwind: `bg-[#FAF8F5]`

Usage:
- Main page backgrounds
- Application workspace backgrounds
- General section backgrounds

### Card / Surface Background

**Paper**

Hex: `#FFFFFF`

Tailwind: `bg-[#FFFFFF]`

Usage:
- Cards
- Forms
- Modals
- Dashboard panels
- Content containers
- Preview surfaces

### Primary Accent

**Terracotta**

Hex: `#9A5F44`

Tailwind:
- `text-[#9A5F44]`
- `bg-[#9A5F44]`
- `border-[#9A5F44]`

Usage:
- Primary CTA buttons
- Important actions
- Active navigation indicators
- Links and highlights
- Selected states

### Primary Accent — Dark Variant

**Deep Terracotta**

Hex: `#7E4A33`

Tailwind:
- `text-[#7E4A33]`
- `bg-[#7E4A33]`
- `border-[#7E4A33]`

Usage:
- Primary button hover states
- Emphasized accent elements
- Darker accent treatments

### Secondary Accent

**Sage**

Hex: `#87927B`

Tailwind:
- `text-[#87927B]`
- `bg-[#87927B]`
- `border-[#87927B]`

Usage:
- Secondary highlights
- Supporting information
- Status indicators
- Decorative elements

### Secondary Accent — Light Variant

**Sage Light**

Hex: `#EEF0EA`

Tailwind:
- `text-[#87927B]`
- `bg-[#EEF0EA]`

Usage:
- Subtle information backgrounds
- Soft status badges
- Secondary highlighted sections

---

## 3. Text Colors

### Primary Text

**Ink**

Hex: `#252321`

Tailwind: `text-[#252321]`

Usage:
- Main headings
- Body text
- Important labels
- Primary interface content

### Secondary Text

**Muted**

Hex: `#6B675F`

Tailwind: `text-[#6B675F]`

Usage:
- Supporting descriptions
- Secondary labels
- Form helper text
- Metadata
- Subtitles

### Tertiary Text

**Soft Gray**

Hex: `#A19B93`

Tailwind: `text-[#A19B93]`

Usage:
- Placeholder text
- Disabled information only (contrast is about 2.6:1, so never use for readable content)

---

## 4. Borders

### Light Border

**Warm Border**

Hex: `#E9E4DD`

Tailwind: `border-[#E9E4DD]`

Usage:
- Card borders
- Input borders
- Section dividers
- Navigation separators
- General component boundaries

### Dark Border

Hex: `#D8D1C8`

Tailwind: `border-[#D8D1C8]`

Usage:
- More prominent input boundaries
- Selected component outlines
- Emphasized separators

---

## 5. Layout & Spacing

### Main Container

Maximum width: `1200px`

CSS class: `.container-main`

Usage:
- Main page content
- Landing page sections
- Pricing and template layouts
- Standard content alignment

### Standard Spacing Scale

Use Tailwind's spacing scale consistently:

| Tailwind | Pixels | Typical Usage |
|---|---:|---|
| `p-2` / `gap-2` | 8px | Small internal spacing |
| `p-3` / `gap-3` | 12px | Compact elements |
| `p-4` / `gap-4` | 16px | Standard component spacing |
| `p-6` / `gap-6` | 24px | Card and section content |
| `p-8` / `gap-8` | 32px | Larger component spacing |
| `py-12` | 48px | Compact section spacing |
| `py-16` | 64px | Standard section spacing |
| `py-20` | 80px | Large section spacing |

### Border Radius

- Small controls: `rounded-lg` (8px)
- Standard cards: `rounded-xl` (12px)
- Large cards and panels: `rounded-2xl` (16px)
- Pills and badges: `rounded-full`

### Shadows

Standard soft shadow:

`0 8px 30px rgba(37, 35, 33, 0.06)`

Tailwind: `shadow-[0_8px_30px_rgba(37,35,33,0.06)]`

Usage:
- Elevated cards
- Dropdowns
- Modals
- Floating interface elements

Avoid excessive shadows.

---

## 6. Buttons & Actions

### Primary Button

Background: `#9A5F44`  
Text: `#FFFFFF`  
Hover: `#7E4A33`

Usage:
- Create an event
- Save changes
- Publish event
- Confirm important actions

### Secondary Button

Background: `#FFFFFF`  
Text: `#252321`  
Border: `#E9E4DD`

Usage:
- Cancel
- Go back
- Edit details
- Secondary actions

### Dark Button

Background: `#252321`  
Text: `#FFFFFF`

Usage:
- Special high-emphasis actions
- Selected dark interface treatments

### Ghost Button

Background: Transparent  
Text: `#6B675F`

Usage:
- Low-priority actions
- Navigation utilities
- Subtle controls

### Button Guidelines

- Use consistent button heights and padding.
- Use Font Awesome icons where appropriate.
- Keep icon and text alignment consistent.
- Provide hover, focus and disabled states.
- Avoid creating new button styles for individual pages.

---

## 7. Forms & Input Fields

Standard form controls should use:

- Background: `#FFFFFF`
- Text: `#252321`
- Border: `#E9E4DD`
- Focus border: `#9A5F44`
- Border radius: `8px`
- Consistent internal padding.

Guidelines:
- Every input must have a visible label.
- Use consistent label typography.
- Provide clear validation messages.
- Required fields should be identified.
- Maintain consistent spacing between form groups.
- Use appropriate input types and validation.

---

## 8. Cards & Components

### Standard Cards

- Background: `#FFFFFF`
- Border: `1px solid #E9E4DD`
- Radius: `12px`
- Shadow: Soft shadow where appropriate.
- Padding: Consistent internal spacing.

### Badges & Status Indicators

Use restrained colors and consistent meanings.

- Upcoming: Sage treatment.
- In Progress: Terracotta treatment.
- Completed: Neutral or Sage treatment.
- Postponed: Amber treatment.
- Cancelled: Muted red treatment.

Status colors must remain consistent across dashboard lists, event pages and notifications.

### Modals

- White background.
- Rounded corners.
- Subtle shadow.
- Clear heading and close action.
- Consistent overlay treatment.
- Responsive width.

### Empty States

Every major listing page should have a meaningful empty state with:
- Appropriate Font Awesome icon.
- Short explanatory message.
- Relevant action where appropriate.

---

## 9. Icons

**Icon Library: Font Awesome 6.7.2**

CDN:

`https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css`

Guidelines:
- Use Font Awesome icons throughout the platform.
- Prefer consistent icon styles within the same interface.
- Avoid mixing emoji, Unicode symbols and unrelated icon libraries.
- Use icons to support meaning, not replace essential labels.

---

## 10. Responsive Design

All pages must support:

- Desktop
- Tablet
- Mobile

Guidelines:
- Use responsive Tailwind breakpoints.
- Avoid horizontal overflow.
- Stack multi-column layouts on smaller screens.
- Make buttons and interactive controls comfortable to use on mobile.
- Ensure navigation remains accessible.
- Resize cards, typography and spacing appropriately.

---

## 11. Transitions & Interactions

Standard transition:

`200ms ease`

Guidelines:
- Use subtle hover effects.
- Avoid excessive animation.
- Provide visible focus states.
- Ensure buttons and links communicate interactivity.
- Maintain consistent feedback for success, warning and error messages.

---

## 12. Shared Project Files & Implementation Rules

All team members must reuse the shared design-system files.

**Shared files:**
- `_inc/head.inc.php`
- `_inc/header.php`
- `_inc/footer.php`
- `css/global.css`
- `css/components.css`
- `js/app.js`

Guidelines:
- Do not redefine global fonts or colors independently on individual pages.
- Do not introduce random hexadecimal colors.
- Reuse existing button, card, form, badge and layout classes.
- Add reusable styles to `components.css`, not scattered inline styles.
- Use the existing Tailwind configuration.
- Keep shared components consistent.
- Coordinate changes to shared files to prevent merge conflicts.

### Existing CSS Classes

Reuse these where applicable:

- `.container-main`
- `.font-display`
- `.section-padding`
- `.btn`
- `.btn-primary`
- `.btn-secondary`
- `.btn-dark`
- `.btn-ghost`
- `.btn-sm`
- `.btn-lg`
- `.btn-block`
- `.eyebrow`
- `.section-heading`
- `.section-description`
- `.card`
- `.card-hover`
- `.badge`
- `.badge-accent`
- `.badge-sage`
- `.divider`

---

## 13. Project-Wide Design Principle

Foliioo should maintain a premium, elegant, modern and event-centric visual identity.

Every module should feel like part of the same product.

**Consistency is mandatory across all team members' contributions.**

Do not introduce a new font, primary color, button style, card treatment or major layout pattern without discussing it with the team first.

This design system is the single visual reference for the entire Foliioo frontend prototype.

---

## V1.1 Additions

### Surfaces (rhythm)
- **Tint** `#F3EDE5` (`--color-tint`, `.surface-tint`): alternate with cream and white sections so long pages do not feel flat.
- **Deep** `#252321` (`--color-deep`, `.surface-deep`): one dark section per page at most (final call to action). Text on it: white, `#CFC8BF` body, Light Terracotta `#E0A98C` for highlights.
- **Sage** is now used on "how it works" icons, success ticks and status badges.

### Semantic colors
| Meaning | Text | Background | Class |
|---|---|---|---|
| Success | `#3F6B48` | `#E8F1EA` | `.badge-success` |
| Warning | `#8F5F12` | `#FBF1DC` | `.badge-warning` |
| Error | `#B54747` | `#F8E6E6` | `.badge-error` |

### Accessibility contrast (checked)
- White on Terracotta `#9A5F44`: 5.1:1
- Muted `#6B675F` on cream: 5.3:1, on Tint: 4.8:1
- Soft Gray `#A19B93` is about 2.6:1: **placeholders and disabled text only**
- Minimum body copy size: 14px. Avoid text below 12px.

### Type scale
| Token | Size | Use |
|---|---|---|
| `--text-hero` | 42px to 64px | Hero heading |
| `--text-h2` | 32px to 40px | Section headings |
| `--text-h3` | 24px | Card and sub-section headings |
| `--text-body` | 16px | Body copy |
| `--text-small` | 14px | Helper text, captions |

- Display headings: line height 1.1 to 1.15, letter spacing -0.02em to -0.03em
- Body: line height 1.6
- Eyebrows: DM Sans 12px, bold, uppercase, 0.12em tracking

### Guest-facing template fonts
Foliioo's own interface uses DM Sans and Fraunces. Each guest-facing template has its own heading font:

| Template | Heading font | Token |
|---|---|---|
| Modern | DM Sans | `--font-template-modern` |
| Elegant / Classic | Cormorant Garamond (large sizes only) | `--font-template-elegant` |
| Floral / Vibrant | DM Serif Display | `--font-template-floral` |

### Motion
- Sections, cards and steps fade up on scroll (`.js-reveal`, added by `js/app.js`).
- All motion is disabled under `prefers-reduced-motion`.
- Keep durations at 200ms to 500ms.

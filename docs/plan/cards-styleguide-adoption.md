# Cards Styleguide Adoption Plan

## Goals

- Adopt the styleguide card system into the live application.
- Give cards a slightly stronger, bolder look than the styleguide demo.
- Fully compose all real cards on top of `.card` + modifier/structural classes (no standalone one-off visual systems).

## 1. Strengthen Shared Card Visuals

Target file: `features/shared/assets/css/cards.css`.

- Align base `.card` with the styleguide, but slightly bolder:
  - Background: `var(--card-bg)`.
  - Border: `1px solid var(--card-border)` (ensure `--card-border` has enough contrast).
  - Border radius: ~12–14px.
  - Shadow: use `var(--shadow-sm)` with a noticeable depth.
  - Padding: `1.25rem`.
  - Transitions: `transform`, `box-shadow`, `border-color` as in the styleguide.
- Add modifier variants (all as modifiers on `.card`):
  - `.card.card--elevated`
    - Stronger, deeper shadow (e.g. `0 20px 50px rgba(2,34,21,0.18)`).
    - 2px slightly darker border.
    - Pure white background.
    - Slightly larger padding.
  - `.card.card--accent`
    - Mint/green-tinted background (brighter than base card).
    - 2px accent border.
    - Left accent stripe (4–5px) using `var(--accent)`.
    - Soft accent-colored shadow.
  - `.card.card--muted`
    - Warm neutral background (non-green).
    - Muted text color.
    - No shadow.
  - `.card.card--outline`
    - Transparent background.
    - 2px dashed green-ish border.
    - No shadow.
- Shared behavior:
  - Hover: `.card`, `.small-card`, `.page-card` all lift slightly with a stronger, unified hover shadow.
  - Typography: `.card h3/h4` bold with consistent margins, `.card p` slightly darker than `--muted` and with consistent spacing.

## 2. Normalize Structural Card Types

Also in `features/shared/assets/css/cards.css`:

- `.small-card`
  - Represents compact cards (forms/dialog-like content).
  - Controls max-width, padding, and slightly smaller radius.
  - Visual look (bg, border, shadow) comes from `.card` + variants.
  - Intended usage: `class="card small-card"` (+ optional variant).
- `.page-card`
  - Represents the main page content container.
  - Controls `max-width: 960px`, margins (centered), larger padding, and larger radius.
  - Visual look also comes from `.card` + variants via `class="card page-card"`.
- `.cards`
  - Remains the grid wrapper for lists of cards (no direct visual styling beyond layout gaps/columns).

## 3. Compose All Feature Cards on `.card` + Variants

### Dashboard (Admin & User)

- Outer content wrapper:
  - Use `class="card page-card"` for the main dashboard content container.
- Dashboard tiles:
  - Change tiles to `class="card dashboard-card card--elevated"`.
  - Update `.dashboard-card` CSS so it assumes base card visuals from `.card` and only handles layout (grid behavior, icon alignment, internal spacing).

### Donations

- Page wrapper:
  - Use `class="card page-card"` instead of only `small-card` for the main donations content.
- Summary / list containers:
  - Use `class="card card--elevated"` for key summary panels.
  - Use `class="card"` for neutral list containers.
- Create/CTA widgets:
  - Use `class="card small-card create-card card--accent"` for primary "create donation" or similar CTAs.
- Individual donation cards:
  - Inside a `.cards` grid, use `class="card donation-card"`.
  - Apply variants based on state:
    - Highlighted/urgent: `class="card donation-card card--accent"`.
    - Archived/low-priority: `class="card donation-card card--muted"`.

### Events

- Page wrapper:
  - Use `class="card page-card"` for the main events content.
- Main "Upcoming Events" panel:
  - Use `class="card card--elevated"`.
- Event creation widget:
  - Use `class="card small-card create-card card--accent"`.
- Individual events:
  - In a `.cards` grid, use `class="card event-card"`.
  - Variants:
    - Featured/upcoming: `card--accent`.
    - Past/secondary: `card--muted`.

### Residents (Admin)

- Main content (tables, lists):
  - Use `class="card page-card"` as the primary container for residents admin pages.
- Filters/side panels:
  - Use `class="card card--outline"` for low-emphasis filter/search sections.

### Users / Waris / Profile / Admin User Management

- Auth pages (login/register/forgot):
  - Wrap forms in something like `<main class="centered"><div class="card small-card login-card">...</div></main>`.
  - `login-card` (and similar) CSS should only adjust layout (max-width, alignment), not base card visuals.
- Profile/admin/waris forms:
  - Use `class="card small-card"` for the main forms.
  - Secondary info panels: add `card--muted` for softer emphasis.
- Alerts/important notices around accounts:
  - Use `class="card small-card card--accent"`.

## 4. Clean Up Domain-Specific Card Classes

- For classes like `create-card`, `donation-card`, `event-card`, `login-card`, etc.:
  - Ensure their CSS does **not** redefine background, border, radius, or shadow.
  - They should be used in combination with the base system:
    - Examples:
      - `class="card small-card create-card card--accent"`
      - `class="card event-card card--muted"`
      - `class="card donation-card card--accent"`
  - Their responsibility: layout tweaks, icons, inner spacing, and module-specific details only.

## 5. Keep Styleguide Separate from Runtime

- Production pages should load shared assets under `features/shared/assets/css/` (including the updated `cards.css`), but **not** `styleguides/styleguide.css`.
- The styleguide remains a reference/demo:
  - It should conceptually mirror the same card system but does not need to be an exact copy.
  - If both CSS files are ever loaded together (for the styleguide page only), ensure selectors do not conflict or accept that the shared system is authoritative.

## 6. Implementation Checklist

1. Update `features/shared/assets/css/cards.css`:
   - Strengthen `.card` and add modifier variants.
   - Normalize `.small-card`, `.page-card`, hover, and typography.
2. Audit templates in dashboard, donations, events, residents, users, and waris features:
   - Replace raw `small-card` / ad-hoc card usages with composed `card` + variants.
3. Adjust feature-specific CSS (`dashboard-card`, `create-card`, `donation-card`, `event-card`, `login-card`, etc.) so they depend on `.card` for visuals.
4. Manually verify key pages visually to ensure the new hierarchy (base + variants) looks correct and consistent.

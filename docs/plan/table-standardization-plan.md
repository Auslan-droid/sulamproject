# Table Standardization Plan

This plan describes how to standardize all tables in SulamProject on the shared `tables.css` styles and a small set of table utilities. It is intended for anyone touching table markup or table-related CSS.

## 1. Canonical Table API

Tables are styled centrally in `features/shared/assets/css/tables.css`, imported via `features/shared/assets/css/base.css`.

**Core classes:**

- `.table` – base table: 100% width, collapsed borders, vertical separators, consistent padding, header background, and text colors.
- `.table-striped` – zebra striping for table body rows.
- `.table-hover` – hover background for table body rows.
- `.table-responsive` – wrapper for horizontal scroll when the table is wider than its container.
- `.table-responsive--wide` – **financial/report variant** wrapper that always allows horizontal scrolling inside cards for very wide tables.

**Standard markup pattern (normal tables):**

```html
<div class="table-responsive">
  <table class="table table-striped table-hover">
    <thead>...</thead>
    <tbody>...</tbody>
  </table>
</div>
```

**Wide financial/report tables:**

```html
<div class="table-responsive table-responsive--wide">
  <table class="table table-hover table--payment-account">
    <thead>...</thead>
    <tbody>...</tbody>
  </table>
</div>
```

`table-responsive--wide` is intended for inherently wide, many-column tables (cash book, payment account, deposit account). It guarantees horizontal scrolling *inside* the card while keeping the card width stable.

## 2. Global Table Utilities

To avoid repeating inline styles and to make table semantics clearer, use these small, shared utilities on top of `.table`:

- `.table__cell--actions`
  - Purpose: keep action buttons in a narrow, non-wrapping column.
  - Typical usage:
    ```html
    <td class="table__cell--actions">
        <button class="btn btn-sm">Edit</button>
    </td>
    ```
  - Behavior:
    - Centers content horizontally.
    - Prevents line wrapping for inline buttons or icons.

- `.table__cell--numeric`
  - Purpose: right-align numeric values (amounts, counts, balances) consistently.
  - Typical usage:
    ```html
    <td class="table__cell--numeric">123.45</td>
    ```
  - Behavior:
    - Right-aligns cell content.

- `.table--compact`
  - Purpose: provide a denser table variant for use in tight spaces (e.g., profile sublists, small widgets).
  - Typical usage:
    ```html
    <table class="table table--compact">
        ...
    </table>
    ```
  - Behavior:
    - Reduces padding on header and body cells.

These utilities should live centrally (either in `tables.css` or a future shared table-utilities file) and be documented in the styleguide with small examples.

## 3. Module-Specific Modifiers

For feature- or page-specific tweaks, use modifier classes instead of overriding `.table` globally:

Examples:

- Residents admin: `.table--families`
- Users admin: `.table--users`
- Financial admin: `.table--cash-book`, `.table--payment-account`, `.table--deposit-account`
- User profile: `.table--profile` or `.table--compact`

**Markup pattern:**

```html
<div class="table-responsive">
    <table class="table table-striped table-hover table--families">
        ...
    </table>
</div>
```

**CSS location and scope:**

- Implement modifier rules in the relevant feature CSS file (e.g., `features/residents/admin/assets/css/...`).
- Do **not** declare bare `.table { ... }` rules in feature CSS; always scope through a modifier like `.table--families`.

## 4. Old → New Mapping Rules

When you touch an existing table, apply these mapping rules:

1. **Replace inline table layout styles**
   - Old:
     ```html
     <table style="width:100%; border-collapse:collapse; ...">
     ```
   - New:
     ```html
     <div class="table-responsive">
         <table class="table table-hover">
             ...
         </table>
     </div>
     ```
   - Remove inline `width`, `border-collapse`, `padding`, `border`, and `overflow-x` when `.table` / `.table-responsive` are in use.

2. **Use utilities for actions and numeric columns**
   - Old (inline):
     ```html
     <td style="white-space: nowrap; text-align:center;">
         ...buttons...
     </td>
     ```
   - New:
     ```html
     <td class="table__cell--actions">
         ...buttons...
     </td>
     ```
   - Old (inline numeric alignment):
     ```html
     <td style="text-align:right">123.45</td>
     ```
   - New:
     ```html
     <td class="table__cell--numeric">123.45</td>
     ```

3. **Apply modifiers for special layouts**
   - Wide financial tables (cash book, payment account, deposit account):
     ```html
     <div class="table-responsive table-responsive--wide">
         <table class="table table-hover table--payment-account">
             ...
         </table>
     </div>
     ```
   - In financial admin CSS:
     ```css
     .table--payment-account {
       min-width: 2000px; /* tune to actual columns */
       font-size: 0.9rem;
     }

     .table--payment-account th,
     .table--payment-account td {
       padding: 0.5rem 0.75rem;
       white-space: nowrap;
     }
     ```

4. **Striping and hover**
   - Use `.table-striped` for zebra striping instead of custom `:nth-child` row colors.
   - Use `.table-hover` for hover behavior; if a feature needs a different hover color, override via a modifier:
     ```css
     .table--cash-book.table-hover tbody tr:hover {
       background-color: /* custom */;
     }
     ```

5. **Status tags / badges**
   - Avoid inline-colored `<span>`s for statuses.
   - Prefer shared or feature-specific badge classes and keep the table purely structural.

## 5. Migration Order

Focus on admin first, then user-facing tables.

1. **Admin residents & users**
   - Standard admin lists that already use `.table` or are close to it.
   - Actions:
     - Ensure wrapper uses `.table-responsive` where needed.
     - Add `.table-hover` / `.table-striped` as appropriate.
     - Introduce `.table__cell--actions` for action columns and `.table__cell--numeric` where numeric.
     - Move any view-level `.table` CSS into feature CSS, renaming to modifiers (e.g. `.table--families`).

2. **Admin tables with heavy inline styling**
   - Admin user list and similar pages that style tables via inline `style` attributes.
   - Actions:
     - Convert markup to `.table` + `.table-responsive`.
     - Replace inline alignment and padding with utilities/modifiers.

3. **Financial admin tables**
   - Cash book, payment account, deposit account, and other financial reports.
   - Actions:
     - Wrap with `.table-responsive`.
     - Use `<table class="table table-hover table--cash-book">` and similar.
     - Move `min-width`, column width hints, and print-related layout to feature CSS via modifiers.
     - Use `.table__cell--numeric` for amount columns and `.table__cell--actions` for action cells.

4. **User-side tables**
   - Waris list, dependents, and other user-side tables.
   - Actions:
     - Convert to `.table` + `.table-responsive`.
     - Use `.table--compact` where a denser layout is desired.

5. **Styleguide alignment**
   - Ensure `styleguides/index.html` demonstrates:
     - Base table usage.
     - `.table-striped` and `.table-hover`.
     - `.table__cell--actions`, `.table__cell--numeric`, `.table--compact` in a small example.

## 6. Per-Table Migration Checklist

For each table you modify:

1. **Identify the pattern**
   - Where is the table? (feature, admin/user).
   - Does it already use `.table` / `.table-responsive`?
   - Does it rely on inline styles or local `<style>` blocks?

2. **Update markup**
   - Wrap in `<div class="table-responsive">` if horizontal scroll may be needed.
   - Set `<table class="table ...">` and add `.table-striped`, `.table-hover`, and any `table--<feature>` modifier.
   - Replace inline `style` attributes related to layout with classes (`table__cell--actions`, `table__cell--numeric`, modifiers).

3. **Move and scope styles**
   - Move in-page `<style>` rules into the appropriate feature CSS file.
   - Rename any generic `.table` selectors to modifier-based selectors (e.g. `.table--families`, `.table--cash-book`).

4. **Verify behavior**
   - Check:
     - Column alignment and spacing.
     - Borders and vertical separators.
     - Hover and striping behavior.
     - Action cells (no awkward wrapping unless desired).
   - Test at desktop and a narrow viewport.
   - Re-test any print-specific behavior for pages that support printing.

5. **Check JavaScript dependencies**
   - Search for usages of the table ID (e.g., `cashBookTable`) before changing structure.
   - Keep table IDs stable unless you also update the JS.

## 7. Risks and Mitigations

- **JS depending on structure/IDs**
  - Mitigation: avoid changing IDs; keep column order and header row spans unless necessary. Update JS if structure must change.

- **CSS specificity conflicts**
  - Mitigation: only customize tables via modifiers and utilities; do not redefine `.table` globally in feature CSS.

- **Wide tables collapsing on smaller screens**
  - Mitigation: preserve `min-width` via modifiers (e.g. `.table--payment-account`) and ensure `.table-responsive` is used.

- **Print layout changes**
  - Mitigation: keep or move `@media print` rules into feature CSS and test printing after changes.

- **Inconsistent look during migration**
  - Mitigation: follow the migration order above so the most visible admin tables are standardized first, and use this doc as a checklist to track which tables are already converted.

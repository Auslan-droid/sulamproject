**Financial Statement - Page Documentation**

This document explains the Financial Statement page (Penyata Terimaan dan Bayaran) and the print view used for printing the statement (Lampiran 9). It's written for a junior developer and covers every file the pages load or use, what each part does, and where to change behavior, styles, and data sources.

Files covered
- `features/financial/admin/pages/financial-statement.php` — page where user selects date range and generates the print view.
- `features/financial/admin/pages/financial-statement-print.php` — printable HTML of the statement (Lampiran 9).
- `features/financial/shared/lib/FinancialStatementController.php` — business logic to assemble statement data.
- `features/financial/shared/lib/DepositAccountRepository.php` — data access for deposit (receipts) table.
- `features/financial/shared/lib/PaymentAccountRepository.php` — data access for payments table.
- `features/shared/lib/auth/session.php` — session and auth helpers used by the pages.
- `features/shared/lib/utilities/functions.php` — general helpers (e.g., `url()`, `e()`).
- `features/shared/lib/database/mysqli-db.php` — legacy mysqli bootstrap used by the controller.
- `database/schema.sql` — database schema (tables referenced by the repositories).

Quick overview (how the feature flows)
- The landing page `financial-statement.php` loads auth/session utilities and renders a small form to choose `start_date` and `end_date`.
- That form points to the print page `financial/statement-print` (resolved by `url('financial/statement-print')`) and opens it in a new tab (`target="_blank"`).
- The print page (`financial-statement-print.php`) initializes the DB and controller, fetches aggregated data for the period, then renders a self-contained printable HTML document (with inline CSS). It auto-runs `window.print()`.
- The heavy lifting — querying and aggregation — is in `FinancialStatementController::getStatementData()` which uses the two repository classes to compute opening balances, receipts, payments, flow, and closing balances.

Where to look when changing things
- Change form fields or labels: `features/financial/admin/pages/financial-statement.php`.
- Change printed layout or styles: `features/financial/admin/pages/financial-statement-print.php` (styles are inline inside `<style>` in the head). For site-wide styles, edit `assets/css/style.css` or add feature-scoped assets under `features/financial/admin/assets/` and include them via layout files.
- Change aggregation logic (how receipts/payments are grouped or calculated): `features/financial/shared/lib/FinancialStatementController.php`.
- Add/remove receipt/payment categories or change labels: `DepositAccountRepository::CATEGORY_COLUMNS` and `::CATEGORY_LABELS` (see `features/financial/shared/lib/DepositAccountRepository.php`) and similarly for `PaymentAccountRepository`.
- Change database structure: `database/schema.sql` and `database/migrations/` contains migrations referencing financial tables.

Detailed file-by-file explanation

1) `features/financial/admin/pages/financial-statement.php`
- Purpose: small admin page where an admin selects `start_date` and `end_date` and clicks Generate to open the print view.
- Top includes and initialization:
  - `$ROOT = dirname(__DIR__, 4);` — calculate project root relative to this file. This pattern appears in many pages for reliable includes.
  - `require_once $ROOT . '/features/shared/lib/auth/session.php';` — session helpers (see below).
  - `require_once $ROOT . '/features/shared/lib/utilities/functions.php';` — general helpers (e.g., `url()`).
  - `initSecureSession(); requireAuth(); requireAdmin();` — start secure session and enforce that the user is authenticated and an admin.
- Default dates: reads `start_date`/`end_date` from `$_GET` or falls back to first/last day of current month using `date('Y-m-01')` and `date('Y-m-t')`.
- `$pageHeader` array: used by the layout to show a contextual header and breadcrumb. The header keys are `title`, `subtitle`, `breadcrumb`, and `actions`.
- The UI: the file captures inner content using `ob_start()`/`ob_get_clean()` and builds a small card containing a form with two `<input type="date">` fields and a submit button. The form action is `url('financial/statement-print')` and the `method` is GET so start/end dates are passed in the query string.
- Important details:
  - `target="_blank"` means the print view opens in a separate tab — this keeps the admin on the selection page.
  - The code uses `url()` to build routes. `url()` is defined in `features/shared/lib/utilities/functions.php`.
- Layout handling: the page captures `$content` and then includes `features/shared/components/layouts/app-layout.php` and `features/shared/components/layouts/base.php` (these contain the global HTML shell). To change where this page is wrapped, update those layout files.

2) `features/financial/admin/pages/financial-statement-print.php`
- Purpose: render a printable statement (Lampiran 9) for the provided date range.
- Top includes and initialization:
  - `$ROOT = dirname(__DIR__, 4);`
  - `require_once` lines load `session.php`, `functions.php`, `mysqli-db.php` and `FinancialStatementController.php`.
  - `initSecureSession(); requireAuth();` — requires only authentication (not requireAdmin); printing may be allowed to other roles depending on project conventions.
- Date input: reads `start_date`/`end_date` from `$_GET` with same defaults as the selection page.
- Data fetch:
  - `$controller = new FinancialStatementController($mysqli);` — the `$mysqli` object is provided by `mysqli-db.php`.
  - `$data = $controller->getStatementData($startDate, $endDate);` — returns an associative array with keys described below.
- Display formatting: `$displayStartDate` and `$displayEndDate` show dates in `d/m/Y` format and `$periodString` is used in the header.
- Inline CSS: the page contains a complete CSS block inside `<style>` to make the print layout independent of global styles. Important rules:
  - `@media print` removes the `.no-print` buttons and removes borders/box-shadows for a clean print.
  - `@media screen` applies a centered page container and shows print/close buttons inside `.no-print`.
  - Change printed fonts, spacing, or brand styles here if you only want the print output to change.
- HTML structure and dynamic data:
  - Opening balance block uses `$data['opening_balance']['cash']` and `['bank']`.
  - Receipts section loops `foreach ($data['receipts'] as $item)` and prints `$item['label']` and `amount`.
  - Payments section loops `foreach ($data['payments'] as $item)`.
  - Totals printed from `$data['total_receipts']`, `$data['total_payments']`, and `$data['surplus_deficit']`.
  - Closing balances from `$data['closing_balance']['cash']` and `['bank']` with a simple breakdown.
- Buttons and behavior:
  - The `.no-print` buttons call `window.print()` and `window.close()`.
  - A script runs `window.print()` on load after 500ms so the print dialog opens automatically.

3) `features/financial/shared/lib/FinancialStatementController.php`
- Purpose: central logic to compute opening balances, grouped receipts/payments, cash flow, and closing balance.
- Constructor: accepts a `mysqli $mysqli` to run queries.
- Public method `getStatementData(string $startDate, string $endDate): array` — returns an array containing:
  - `start_date` / `end_date`
  - `opening_balance` => `['cash' => float, 'bank' => float]` — balances before the start date.
  - `receipts` => array of `['label' => string, 'amount' => float]` grouped by categories defined in `DepositAccountRepository`.
  - `total_receipts` => float (sum of receipt amounts)
  - `payments` => array of `['label' => string, 'amount' => float]` grouped by categories defined in `PaymentAccountRepository`.
  - `total_payments` => float
  - `closing_balance` => `['cash' => float, 'bank' => float]` — calculated using opening + flow
  - `surplus_deficit` => float (total_receipts - total_payments)
- Private helpers:
  - `calculateBalance($date)` — calculates cash & bank positions before a given date by summing category columns grouped by `payment_method`.
  - `calculateCashFlow($startDate, $endDate)` — sums cash/bank in/out between the dates.
  - `getReceiptsByCategory` and `getPaymentsByCategory` — iterate repository category constants and call `getSum()` for each column.
  - `getSum($sql, ...$params)` — prepared statement helper that returns a single float (safe; uses bind_param with `s` types for dates and executes). It returns 0 if null or no rows.
- Important notes and where to change logic:
  - The controller builds SQL statements by joining constants in repository classes (e.g., `DepositAccountRepository::CATEGORY_COLUMNS`). If you add or rename category columns you must update the repository constants, DB schema, and any migration.
  - `getSum()` uses `str_repeat('s', count($params))` — it treats all params as strings. This is fine for dates and numeric values passed as strings, but if you refactor to bind numeric types you can adjust types accordingly.

4) Repositories
- `DepositAccountRepository.php` and `PaymentAccountRepository.php` contain:
  - `CATEGORY_COLUMNS` — an ordered list of database columns containing numeric amounts for categories.
  - `CATEGORY_LABELS` — mapping from column name to human-friendly label used in the printed statement.
  - CRUD helpers (`findAll`, `findById`, `create`, `update`, `delete`) — these use prepared statements and return arrays or boolean success values.
  - `sanitizeAmount()` — converts incoming amounts to positive floats (returns 0.00 for invalid input).
  - `calculateRowTotal()` — utility to compute a row total across category columns.
- When adding a new category:
  - Add the column to the database (`financial_deposit_accounts` or `financial_payment_accounts`) and add a migration in `database/migrations/`.
  - Add the column name to `CATEGORY_COLUMNS` and a label in `CATEGORY_LABELS`.
  - The controller will then automatically pick up the new column when computing sums.

5) Shared helpers and DB
- `features/shared/lib/auth/session.php` — secure session helpers and role checks. Controls access via `requireAuth()` and `requireAdmin()`.
- `features/shared/lib/utilities/functions.php` — helpers like `e()` (escape), `url()` (build path), `formatDate()`, `numberToWords()` (Malay words for amounts). Use `e()` when you echo user input in templates.
- `features/shared/lib/database/mysqli-db.php` — creates a `$mysqli` connection using environment variables `DB_HOST`, `DB_USER`, `DB_PASS`, `DB_NAME` (defaults inside file). If you need a PDO or central DB manager, replace or update this file and the pages that `require_once` it.

Database references
- Tables referenced:
  - `financial_deposit_accounts` — used by `DepositAccountRepository`.
  - `financial_payment_accounts` — used by `PaymentAccountRepository`.
- Schema and migrations stored in `database/schema.sql` and `database/migrations/`. Inspect `database/migrations/` for changes that touch financial tables (e.g., `012_seed_financial_accounts_comprehensive.sql`).

How to test locally
- Start Laragon (Apache + MySQL) and point your browser to the app base (example: `http://localhost/sulamproject/`).
- Login as an admin user (use `register.php` or your seed user) so `requireAuth()` and `requireAdmin()` pass.
- Visit the selection page (URL path depends on your layout/routing). If route patterns follow file names, try: `http://localhost/sulamproject/features/financial/admin/pages/financial-statement.php` (or use the navigation from the dashboard).
- Choose a `start_date` and `end_date`, click Generate — a new tab should open with a printable statement and print dialog should appear.

Common edits and where to make them
- Change printed font and margins (print-only): edit the `<style>` block in `features/financial/admin/pages/financial-statement-print.php`.
- Change site-wide look for the selection form: edit `assets/css/style.css` or the admin layout file `features/shared/components/layouts/app-layout.php`.
- Include feature-specific CSS: create `features/financial/admin/assets/style.css` and include it in the admin layout when the `financial` route is active.
- Modify category labels or add categories: update repository constants and database schema (migrations), then confirm controller aggregation.
- Disable auto-print: remove or comment out the `window.print()` call in the `<script>` at the bottom of `financial-statement-print.php`.

Security and best practices notes
- All database queries in repositories use prepared statements — this is correct and prevents SQL injection.
- `getSum()` prepares SQL strings that are built using column names from constants. Ensure the constants contain only valid column identifiers; don't use user input to build column names.
- Use `e()` from `functions.php` when echoing any dynamic content into attributes or HTML to prevent XSS.
- `session.php` sets `session.cookie_secure` to `0` by default; set it to `1` when serving over HTTPS in production.

Developer tasks checklist (examples)
- Add a new receipt category:
  1. Add column to `financial_deposit_accounts` (create migration + update `database/schema.sql`).
  2. Add the column name to `DepositAccountRepository::CATEGORY_COLUMNS` and a label to `CATEGORY_LABELS`.
  3. Verify the `FinancialStatementController` shows the new category totals in the print view.

- Change printed header address: edit the header lines inside `financial-statement-print.php` (hard-coded lines under the `<div class="header-line">`). Consider moving these strings to a configuration file if you need to reuse them.

Appendix: important paths (copy-paste friendly)
- Selection page: `features/financial/admin/pages/financial-statement.php`
- Print page: `features/financial/admin/pages/financial-statement-print.php`
- Controller: `features/financial/shared/lib/FinancialStatementController.php`
- Repositories: `features/financial/shared/lib/DepositAccountRepository.php`, `features/financial/shared/lib/PaymentAccountRepository.php`
- Session/auth helpers: `features/shared/lib/auth/session.php`
- Utilities: `features/shared/lib/utilities/functions.php`
- DB bootstrap: `features/shared/lib/database/mysqli-db.php`
- Schema + migrations: `database/schema.sql`, `database/migrations/`

If you want, I can:
- Extract the inline print CSS into a feature-scoped CSS file and include it from the layout (so it’s easier to edit and version-control).
- Add a small unit test script that calls `FinancialStatementController::getStatementData()` with sample data (requires a test DB or mocking `$mysqli`).

---
Document created: financial-financial-statement-doc.md

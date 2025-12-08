## Financial — Financial Management Page

Location: `features/financial/admin/views/financial-management.php`

Purpose
- This view is the admin dashboard entry for the Financial module. It shows a high-level summary (total balance, cash vs bank, receipts vs payments), quick action links, and a link to the financial settings page.

Who should read this
- Junior devs who need to: change the dashboard layout, add a quick-action link, change how balances are displayed, or find the server-side code that provides the data.

Quick links (files referenced)
- View (this file): `features/financial/admin/views/financial-management.php`
- Controller (data provider): `features/financial/admin/controllers/FinancialController.php`
- Settings repository (calculates balances): `features/financial/shared/lib/FinancialSettingsRepository.php`
- Deposit repository: `features/financial/shared/lib/DepositAccountRepository.php`
- Payment repository: `features/financial/shared/lib/PaymentAccountRepository.php`
- View styles: `features/financial/admin/assets/css/financial.css`
- Shared layout styles (bento grid): `features/shared/assets/css/bento-grid.css`
- Helper functions (e.g. `url()`): `features/shared/lib/utilities/functions.php`

Variables expected by the view
- `$balances` (array) — calculated by `FinancialSettingsRepository::calculateClosingBalances()` and returned by `FinancialController::index()`.
  - keys used in the view: `total_balance`, `closing_cash`, `closing_bank`, `opening_cash`, `opening_bank`, `total_cash_in`, `total_bank_in`, `total_cash_out`, `total_bank_out`.
- `$settings` (array) — current financial settings from `FinancialSettingsRepository::getCurrentSettings()` (not heavily used in this view but provided for links or future use).
- `$fiscalYear` (int) — the fiscal year integer displayed in the hero card (e.g., `2025`).

How the data flows (end-to-end)
1. A route or page loader calls `FinancialController->__construct($mysqli)` and then `index()`.
2. `FinancialController::index()` calls `$this->settingsRepo->calculateClosingBalances($currentYear)` which in turn:
   - Fetches opening balances from `financial_settings` (via `getByFiscalYear`).
   - Sums deposits and payments for the fiscal year by calling SQL that aggregates the category columns in `financial_deposit_accounts` and `financial_payment_accounts`.
   - Adds/subtracts totals to compute closing balances.
3. `index()` returns an array with keys `'balances'`, `'settings'`, and `'fiscalYear'` which the view uses as `$balances`, `$settings`, and `$fiscalYear`.

Detailed walkthrough of `financial-management.php`
- File header comment: documents variables expected by the view.
- Root container: `<div class="content-container">` — standard wrapper used by app pages.
- `.bento-grid`: top-level layout grid provided by the project's bento-grid CSS. See `features/shared/assets/css/bento-grid.css` for grid sizing and utility classes.

- Total Balance Hero Card (class `bento-card bento-2x2 card-balance`):
  - Shows an icon, the label `JUMLAH BAKI KESELURUHAN`, and the fiscal year using `<?php echo $fiscalYear; ?>`.
  - `number_format($balances['total_balance'] ?? 0, 2)` formats the total balance.
  - The breakdown shows `Tunai` (cash) and `Bank` (bank) closing balances and their opening balances using `$balances['closing_cash']`, `$balances['opening_cash']`, etc.

- Stat cards (`bento-card bento-1x1 card-stat`):
  - `Terimaan` card: sums `total_cash_in` and `total_bank_in` and displays the breakdown.
  - `Bayaran` card: sums `total_cash_out` and `total_bank_out` and displays the breakdown.
  - Both use `number_format(..., 2)`; the view uses inline colors for positive/negative styling.

- Quick Actions (`bento-card bento-2x2`):
  - Four action tiles linking to: deposit account, payment account, cash book, and statement pages.
  - Each link uses the `url()` helper: `<?php echo url('financial/deposit-account'); ?>`. See `features/shared/lib/utilities/functions.php` for `url()` implementation.
  - To add a new quick action: add another `<a href="<?php echo url('path'); ?>" class="bento-btn">...</a>` and style in `financial.css` if needed.

- Settings card (`bento-card bento-2x1`):
  - Shows a brief description and a link to `url('financial/settings')`.

Where to change visual styling
- Module-specific CSS: `features/financial/admin/assets/css/financial.css` — import and modify here for tables and module-specific tweaks.
- Shared layout: `features/shared/assets/css/bento-grid.css` and shared stat cards `features/shared/assets/css/stat-cards.css` — change here for global grid or card styles.

Where to change behavior / calculations
- Dashboard data source: `features/financial/admin/controllers/FinancialController::index()` — change what data is prepared for the view here.
- Balance calculations and SQL aggregation: `features/financial/shared/lib/FinancialSettingsRepository.php` → `calculateClosingBalances()` contains the SQL that aggregates category columns and computes closing balances. If you add new deposit/payment categories, update the SQL aggregation and the corresponding repository `CATEGORY_COLUMNS` constants.
- Category definitions and labels: `DepositAccountRepository::CATEGORY_COLUMNS` and `::CATEGORY_LABELS` and `PaymentAccountRepository::CATEGORY_COLUMNS` / `::CATEGORY_LABELS`. Update these to add/remove categories; the controller and repository code uses these lists to build SQL and forms.

Related views (also worth reading)
- `features/financial/admin/views/financial-settings.php` — the settings form where opening balances are set and effective date is chosen.
- `features/financial/admin/views/cash-book.php` — cash book listing and running balances.
- `features/financial/admin/views/payment-account.php` and `deposit-account.php` — lists for payments and deposits; their forms are in `payment-add.php` / `deposit-add.php`.

Server-side helpers and utilities used by the view
- `url($path)` — builds an app-relative URL. See `features/shared/lib/utilities/functions.php`.
- `number_format()` — PHP builtin used to format currency values. Values are shown in `RM` currency prefix inside the view.
- `htmlspecialchars()` — used in other financial views to escape output; use when printing user data.

Adding/Changing a field (example)
1. Add the column in database migration (SQL in `database/migrations/`), e.g. add `new_category` to `financial_deposit_accounts`.
2. Add the column name to `DepositAccountRepository::CATEGORY_COLUMNS` and a label in `::CATEGORY_LABELS`.
3. Update repository create/update methods (they append columns dynamically already), and update any views/forms that present category inputs (e.g. `deposit-add.php`).
4. Update `FinancialSettingsRepository::calculateClosingBalances()` SQL if it uses a hard-coded list.

Security and best-practices notes
- Repositories use prepared statements (`$mysqli->prepare`) for SQL where user input is bound — this prevents SQL injection. When adding new SQL, prefer prepared statements.
- Escape any user-entered strings when rendering (`htmlspecialchars()` or helper `e()` if available).
- Controller methods assume a `$mysqli` instance and use session for created_by. Ensure session is started and user is authorized before allowing settings changes.

Local testing (Laragon)
1. Start Laragon (Apache + MySQL). Place project in `C:\laragon\www\sulamprojectex`.
2. Visit `http://localhost/sulamprojectex/` (adjust path if your Laragon base differs).
3. To exercise the dashboard: ensure you are logged in as an admin, then visit the financial route (typically `url('financial')` or via navigation in the app).

Pointers for junior devs
- If you need to change the display text, edit the view file directly — it's simple PHP/HTML.
- If you need to change the numbers, change the controller or repository, not the view.
- To add styles, prefer `features/financial/admin/assets/css/financial.css` for module-scoped styles; add shared utilities to `features/shared/assets/css/`.
- When editing SQL, test queries in `phpMyAdmin` first and write a migration file under `database/migrations/` to keep schema changes reproducible.

Contact points in code (map of important symbols)
- `financial-management.php` — view for admin dashboard
- `FinancialController::index()` — prepares `$balances`, `$settings`, `$fiscalYear`
- `FinancialSettingsRepository::calculateClosingBalances($fiscalYear)` — returns the structure used by the view
- `DepositAccountRepository::CATEGORY_COLUMNS` / `PaymentAccountRepository::CATEGORY_COLUMNS` — update these when you add/remove categories
- `features/shared/lib/utilities/functions.php::url()` — helper used to build links

Done — next steps
- If you want, I can:
  - Generate similar per-page docs for `cash-book.php`, `payment-account.php`, and `deposit-account.php`.
  - Create a small checklist for testing financial calculations after schema changes.

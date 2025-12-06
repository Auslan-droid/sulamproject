**Financial Settings — Page Documentation**

This document explains every part of the Financial Settings page so a junior developer can safely read, modify, and extend it. It covers the page rendering flow, the view, the server-side controller, the data repository, related layout files, the little client-side JS, and where to change styles or logic.

**Files:**
- `features/financial/admin/pages/financial-settings.php`: Page entry point. Handles authentication, form submission, controller calls, and layout wrapping.
- `features/financial/admin/views/financial-settings.php`: The HTML/PHP view template for the settings form and the history table.
- `features/financial/admin/controllers/FinancialController.php`: Controller that coordinates validation and repository calls for saving and retrieving settings.
- `features/financial/shared/lib/FinancialSettingsRepository.php`: Data access for `financial_settings` table (get, save, list, calculate closing balances).
- `features/shared/components/layouts/app-layout.php`: Inner app layout used to wrap page content in the dashboard.
- `features/shared/components/layouts/base.php`: Base HTML layout (loads CSS, scripts, FontAwesome).
- `features/shared/assets/css/base.css` and `features/shared/assets/css/variables.css`: Global styles used by pages (where to add global style changes).

**Quick Overview / Purpose**
- Purpose: let an admin configure "opening balances" for a fiscal year (cash and bank balances) and view past years' opening balances.
- Typical flow: Admin opens `financial/settings` → the page shows the current year's defaults (or existing record) and a list of previous settings → Admin edits fields and saves → controller validates and persists via repository → user is redirected to avoid resubmit.

**How the page is rendered (request lifecycle)**
1. Browser visits the URL mapped to `features/financial/admin/pages/financial-settings.php` (usually `financial/settings`).
2. `financial-settings.php` includes shared auth/session and initializes a secure session:
   - `features/shared/lib/auth/session.php` is required and `initSecureSession()` called.
   - `requireAuth()` and `requireAdmin()` enforce RBAC.
3. The page instantiates `FinancialController` passing `$mysqli` (from `features/shared/lib/database/mysqli-db.php`).
4. If the request is `POST`, the page calls `$controller->saveFinancialSettings($_POST)` to validate and save.
   - If successful, the page redirects to `financial/settings?saved=1` to show success message and avoid double POST.
5. For GET (or after redirect), the page calls `$controller->financialSettings()` to get view data (`$settings`, `$allSettings`, `$availableYears`, `$currentYear`, etc.).
6. The page captures the view output by including `views/financial-settings.php` into `$content`, then wraps it with `app-layout.php` and finally `base.php`.

**Variables passed to the view (what to expect in `views/financial-settings.php`)**
- `$settings`: associative array with keys like `fiscal_year`, `opening_cash_balance`, `opening_bank_balance`, `effective_date`, `notes`, and `exists` (boolean). Provided by `FinancialController::financialSettings()` via `FinancialSettingsRepository::getCurrentSettings()`.
- `$allSettings`: array of all settings records (each record mirrors `financial_settings` table columns). Used to render the history table.
- `$availableYears`: list of years (integers) to populate the fiscal year `<select>`.
- `$currentYear`: integer (e.g., 2025).
- `$errors`: array of validation errors to display.
- `$success`: boolean flag to show success message.

**View file details — `features/financial/admin/views/financial-settings.php`**
- Top comment indicates expected variables. The view contains three main UI areas:
  1. Left column: Settings Form (fiscal year, opening cash, opening bank, effective date, notes).
     - Each monetary input is a `type="number"` with `step="0.01"` and `min="0"`.
     - Monetary values are pre-filled using `number_format($settings[...] ?? 0, 2, '.', '')`.
     - The fiscal year `<select>` loops `$availableYears` and marks the selected option where `$settings['fiscal_year'] == $year`.
     - Submit button is a standard POST back to the same page.
  2. Right column (top): Settings History Table — lists `$allSettings` with columns: Year, Cash Balance, Bank Balance, Total.
  3. Right column (bottom): Help/Guidance card with plain language descriptions of fields.

- Alerts:
  - If `$success` is true, a success alert is shown.
  - If `$errors` is non-empty, a red alert shows each error line in a list (escaped via `htmlspecialchars`).

- Small inline script:
  - Listens to `fiscal_year` change and auto-sets the `effective_date` to `YYYY-01-01` for convenience.
  - This is plain JS at bottom of the view (`document.getElementById('fiscal_year')...`). If you move this to a shared JS file, ensure ID selectors remain unique.

**Server-side handling — `features/financial/admin/pages/financial-settings.php`**
- Responsibilities of this file:
  - Bootstrapping session and auth.
  - Instantiating controller: `new FinancialController($mysqli)`.
  - Handling POST: calls `$controller->saveFinancialSettings($_POST)`, receives `['success'=>bool, 'errors'=>[]]`.
  - Redirecting on success to avoid resubmission: `header('Location: ' . url('financial/settings?saved=1'))`.
  - Getting view data: `$controller->financialSettings()` and `extract($data)` for view variables.
  - Wrapping the view contents with `app-layout.php` then `base.php` (so the page appears in the admin dashboard layout).

**Controller responsibilities — `FinancialController::saveFinancialSettings()`**
- Validates `$_POST`:
  - `fiscal_year` must be present.
  - `opening_cash_balance` and `opening_bank_balance` must be set and numeric.
  - `effective_date` required.
- If validation passes, adds `created_by` from `$_SESSION['user_id']` if available, and calls `$this->settingsRepo->save($postData)`.
- Returns `['success' => bool, 'errors' => []]`.

Other relevant controller methods:
- `financialSettings()` — prepares data for the view: current settings via `getCurrentSettings()`, `findAll()` for history, `$availableYears` generated from current year ±5.
- `getBalanceSummary()` and `calculateClosingBalances()` — used elsewhere (dashboard) to compute balances using the repository.

**Repository — `FinancialSettingsRepository`**
- Methods:
  - `getByFiscalYear(int $fiscalYear): ?array` — fetch by `fiscal_year`.
  - `getCurrentSettings(): array` — returns defaults if no record exists for current year (also sets `exists` flag).
  - `findAll(): array` — list all records ordered by `fiscal_year DESC`.
  - `save(array $data): bool` — insert or update depending on whether record exists.
  - `calculateClosingBalances(int $fiscalYear): array` — computes closing balances by summing related deposit/payment tables for the provided year and applying opening balances. Used by controller to show balances across the module.

- SQL safety: repository uses prepared statements for `getByFiscalYear`, `save` (binds params), and for the `calculate...` queries.

**Database table(s) involved**
- Primary table for this page: `financial_settings` (columns referenced in code):
  - `fiscal_year` (INT)
  - `opening_cash_balance` (DECIMAL/DOUBLE)
  - `opening_bank_balance` (DECIMAL/DOUBLE)
  - `effective_date` (DATE)
  - `notes` (TEXT or VARCHAR)
  - `created_by` (INT) — optional.

- Other tables used by repository/controller for calculations: `financial_deposit_accounts`, `financial_payment_accounts` — used when calculating totals and closing balances.

**Where to change styles**
- Global styles are in `features/shared/assets/css/base.css` and `features/shared/assets/css/variables.css`. To change typography, spacing, and general look, edit those files.
- If you need a page-specific stylesheet: add it to the `$additionalStyles` array in `pages/financial-settings.php` before including `base.php` OR set `$additionalStyles` variable in the page scope to an array of URLs. Example:

```php
$additionalStyles = [ url('features/financial/admin/assets/css/financial-settings.css') ];
```

- Create `features/financial/admin/assets/css/financial-settings.css` and include your rules there. `base.php` will print the links automatically if `$additionalStyles` exists.

**Where to change JS behavior**
- The view contains a small inline script that sets `effective_date` when `fiscal_year` changes. To centralize JS, create `features/financial/admin/assets/js/financial-settings.js` and add its path to `$additionalScripts` array before `base.php` inclusion, similar to styles.

**Security & validation notes**
- Server-side validation is in `FinancialController::saveFinancialSettings()`. Do not rely on client-side checks only. Any change to inputs must also update server-side validation.
- User authentication and role checks are enforced in the page via `requireAuth()` and `requireAdmin()`.
- The repository uses prepared statements for DB writes and reads — keep this pattern.

**Common modifications — examples**
- Change default effective date: Modify `FinancialSettingsRepository::getCurrentSettings()` to alter the fallback `date('Y-01-01')`.
- Add a new field (e.g., `initial_petty_cash`):
  1. Add column to `financial_settings` table (migration).
  2. Update `FinancialSettingsRepository::save()` to bind and persist the new column.
  3. Expose the field in `getCurrentSettings()` and `findAll()` as appropriate.
  4. Add input in `views/financial-settings.php` with name matching the DB key and update controller validation to allow numeric value.

**Where to change validation rules**
- Edit `FinancialController::saveFinancialSettings()` — this is the canonical place for validation for this page. Keep the errors returned as strings (the page displays them in a `<ul>`).

**How to add logging or audit for settings changes**
- The repository `save()` method accepts `created_by` from controller. To add audit logs, either:
  - Add an `updated_by` & `updated_at` columns and set values on update/insert in `save()`; or
  - Insert a separate row into an `audit_logs` table inside `save()` whenever a change occurs. Ensure you have transactional safety and error handling.

**Where this page integrates with the rest of the app**
- The opening balances are read by cash book and financial statement methods (see `FinancialController::cashBook()` and `financialStatement()`), and by `FinancialSettingsRepository::calculateClosingBalances()` which is used by the dashboard to show the current balances.
- The page is wrapped by `app-layout.php` and `base.php` which include the sidebar, page header, and global assets.

**Quick edit checklist for a junior dev**
- To add a new style rule: place CSS in `features/financial/admin/assets/css/financial-settings.css`, then set `$additionalStyles` in `pages/financial-settings.php`.
- To change server validation: edit `FinancialController::saveFinancialSettings()`.
- To change DB behaviour: edit `FinancialSettingsRepository` (and write a migration under `database/migrations/`).
- To move JS out of the view: create `features/financial/admin/assets/js/financial-settings.js`, move the script, and add `$additionalScripts` in the page file.

**Related files (quick links)**
- Page: `features/financial/admin/pages/financial-settings.php`
- View: `features/financial/admin/views/financial-settings.php`
- Controller: `features/financial/admin/controllers/FinancialController.php`
- Repository: `features/financial/shared/lib/FinancialSettingsRepository.php`
- Layouts: `features/shared/components/layouts/app-layout.php`, `features/shared/components/layouts/base.php`
- Global CSS: `features/shared/assets/css/base.css`, `features/shared/assets/css/variables.css`
- Database migrations: `database/migrations/` (add a migration file here when changing schema)

If you want, I can also:
- Extract the inline JS to a proper asset file and wire `$additionalScripts` in the page; or
- Add a small page-level CSS file and the corresponding entry in `$additionalStyles`.

End of document.

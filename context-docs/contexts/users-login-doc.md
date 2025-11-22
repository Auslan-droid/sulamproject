# Users - Login Page Documentation

This document explains the login page(s), components, controllers, helpers, and related files in the SulamProject repository so a junior dev can understand and modify the login feature.

A note up front:
- This repository contains both a modern controller/view approach and a legacy/procedural approach for the login page. The route configuration (`features/shared/lib/routes.php`) currently points to the procedural pages (e.g., `login-direct.php`). The `AuthController` and view (`views/login.php`) are present and are used in a controller-based flow, but the router uses procedural pages for now.

---

## Files covered (quick map)
- `features/users/shared/views/login.php` — Login view (controller-based)
- `features/users/shared/pages/login-direct.php` — Procedural login page (legacy/used by current routes)
- `features/users/shared/controllers/AuthController.php` — Controller for login/registration handling (modern)
- `features/shared/lib/auth/AuthService.php` — Authentication service (business logic)
- `features/shared/lib/auth/session.php` — Session helpers (`initSecureSession`, `isAuthenticated`, etc.)
- `features/shared/lib/utilities/csrf.php` — CSRF helpers (`generateCsrfToken`, `csrfField`, `verifyCsrfToken`)
- `features/shared/lib/utilities/functions.php` — Helper functions (`e()`, `redirect()`, etc.)
- `features/shared/components/layouts/base.php` — Base HTML layout used by controller-based views
- `features/shared/components/footer.php` — Footer included in layout and procedural pages
- `features/shared/assets/css/variables.css` and `base.css` — Core CSS for layout & classes
- `features/shared/lib/database/mysqli-db.php` — Mysqli DB bootstrap (used by legacy pages)

---

## 1) `login.php` view (controller-based)
**Path**: `features/users/shared/views/login.php`

Purpose: The file renders the login form UI when the `AuthController` calls its `showLogin()` method and includes the view inside `base.php` layout. The view is minimal and contains only the form and user-facing messages.

Key parts of the view:
- `<main class="centered small-card">` — Container that consumers `base.css` layout styles. If you need to change layout spacing or card appearance, edit `features/shared/assets/css/base.css` or add `additionalStyles` in controller before including `base.php`.

- Notice rendering (lines around the message):
  - Uses `$message` variable passed by the controller via `showLogin()`.
  - It evaluates whether `$message` contains the word 'successful' to show `success` styling; otherwise `error` is used. This is a basic heuristic; you can modify conditions in the controller.
  - `<?php echo e($message); ?>` — prints escaped message (see `e()` helper).

- CSRF Field
  - `<?php echo csrfField(); ?>` — prints a hidden input with CSRF token using `features/shared/lib/utilities/csrf.php`.
  - This relies on session initialization (`initSecureSession`) and `generateCsrfToken` in the controller.

- Inputs
  - Username: `name="username"` (text)
  - Password: `name="password"` (password)
  - Both `required`, password is not auto-validated for complexity here; validation happens server-side in controller `handleLogin()` or in `login-direct.php`.

- Form submission
  - `action="/sulamproject/login" method="post"` — sends POST to the configured `/login` route (see `features/shared/lib/routes.php`).

- Links:
  - Register link: `href="/sulamproject/register"` — navigates to the registration page.

What to change here (common tasks):
- Change button text / labels: Edit the view and change wording inside the markup.
- Add client-side validation (JS): Add a script in `additionalScripts` injected by the controller or add a new script in `features/users/shared/assets/js/login.js` and include it in the controller.
- Change look & feel: Modify `features/shared/assets/css/base.css` or extend with `features/users/shared/assets/css/login.css` and inject via `$additionalStyles` in the controller.

Notes:
- The view does not handle POST, it only renders UI and messages supplied by controller. The controller handles POST and will redirect with messages stored in `$_SESSION`.

---

## 2) `login-direct.php` (procedural page — used by current router)
**Path**: `features/users/shared/pages/login-direct.php`

This is the legacy login handler: a self-contained HTML + PHP page that performs both rendering and form handling (POST). Currently `routes.php` maps `/login` to this file (see router).

Key flow (server-side):
- Calls `session_start()` and sets `$message = ''`.
- If `$_SERVER['REQUEST_METHOD'] === 'POST'`, it does:
  - Includes `features/shared/lib/database/mysqli-db.php` to create `$mysqli` connection.
  - Reads `$_POST['username']` and `$_POST['password']` into `$rawUsername`, `$rawPassword`.
  - Validates presence; if missing, sets `$message` accordingly.
  - Prepares and executes a `SELECT` query to `users` table and expects the row to contain `password` and `roles` fields. Note: The modern AuthService uses `password_hash` column naming — legacy uses `password`. This inconsistency is important when maintaining the app.
  - Uses `password_verify()` to validate password.
  - On success: regenerates session id and sets session variables `user_id`, `username`, `role` (value of `roles` field) and redirects to `/sulamproject/dashboard`.
  - On failure: sets `$message = 'Invalid username or password.'`

Key differences with the controller approach:
- Uses `$mysqli` and raw `prepare` statements.
- No CSRF protection (no `csrfField` hidden token). If you rely on `login-direct.php`, CSRF token is not enforced here.
- `roles` column is used instead of `role`, `password` column is used instead of `password_hash` — naming mismatch between legacy DB and modern AuthService.

What to change here:
- If you want to add CSRF protection, include `features/shared/lib/utilities/csrf.php` and call `requireCsrfToken()` on POST.
- Standardize column names: prefer `password_hash` and `role` since the modern `AuthService` uses those; if you standardize DB, update this file accordingly.
- Improve error messages and add `messages` via `$_SESSION` if refactoring to controller flow.
- Refactor: convert page to use `AuthController` by replacing procedural logic with `header()` calls to controller-based flow and removing inline DB code.

---

## 3) Controller: `AuthController.php` (modern preferred approach)
**Path**: `features/users/shared/controllers/AuthController.php`

Purpose: Provides a cleaner, testable, and maintainable approach for authentication-related routes. The controller's `showLogin()` renders the view and `handleLogin()` processes the POST.

Key functions in controller:
- `showLogin()`
  - Calls `initSecureSession()` to ensure secure session configuration.
  - If already authenticated, `redirect('/dashboard')` to prevent logged-in users from seeing login.
  - Pulls `$message` from `$_SESSION['message']` and unsets it so it doesn't persist after render.
  - Generates a CSRF token via `generateCsrfToken()` and includes the `login.php` view. The view uses `csrfField()` helper to print the token input.
  - Wraps view content with the `base.php` layout (sets `$pageTitle = 'Login'`, `$content` is the view buffer.)

- `handleLogin()`
  - Ensures `initSecureSession()` was called and request is `POST`.
  - Verifies the CSRF token `verifyCsrfToken()` for the submitted `csrf_token`.
  - Validates `username` and `password` presence; if not valid sets session message and redirects to `/login`.
  - Calls `AuthService->login($username, $password)` which returns an array with `success` bool and user info on success.
  - On success: adds a login event to audit log, `redirect('/dashboard')`.
  - On failure: logs event, sets session message and redirects back to login.

Why use the controller:
- Centralized logic (CSRF, validation, audit logging) is easier to unit test and maintain.
- Layout injection (`base.php`) ensures consistent header/footer, theme CSS, and scripts.
- Cleaner separation of view (HTML) and logic.

---

## 4) `AuthService.php` (auth business logic)
**Path**: `features/shared/lib/auth/AuthService.php`

Purpose: Handles low-level authentication and user data operations (login, register, logout, current user retrieval). Uses `Database::getInstance()` (a PDO-ish wrapper or DB abstraction, see `features/shared/lib/database/Database.php`) and `session` helpers.

Key functions:
- `login($username, $password)`
  - Fetches user by username or email with `password_hash` column
  - `password_verify` the provided password against `password_hash` column
  - On success: sets session variables `user_id`, `username`, `email`, `role`, and returns `['success' => true, 'user' => ...]`.
  - Note: sets `$_SESSION['last_regeneration']` and regenerates session id.

- `register($username, $email, $password, $role = 'user')`
  - Checks if username/email exists.
  - Hashes password with `password_hash` and `PASSWORD_DEFAULT`.
  - Writes to DB using prepared statements and returns success/failure message.

- `logout()`
  - Calls `destroySession()` in `session.php` then returns success.

Important observation: In the modern approach this service expects `password_hash` (instead of `password`) and `role` (instead of `roles`) column names.

---

## 5) Session helpers and CSRF helpers
- `features/shared/lib/auth/session.php`
  - `initSecureSession()` sets secure session cookie flags and regenerates session ids periodically.
  - `isAuthenticated()`, `requireAuth()`, `getUserId()`, `getUserRole()`, `isAdmin()` are utility helpers.
  - `destroySession()` cleans up session data, cookie and destroys session.

- `features/shared/lib/utilities/csrf.php`
  - `generateCsrfToken()` holds token in session and returns it.
  - `verifyCsrfToken($token)` and `csrfField()` helper to print input hidden field for token, and `requireCsrfToken()` to enforce validation.

Security note: The controller and view-based approach consistently uses CSRF helpers; the legacy `login-direct.php` does not. To secure the legacy route add `csrf.php` checks.

---

## 6) Styling & CSS
- `features/shared/assets/css/variables.css` sets CSS variables for colors and theme tokens (e.g., `--accent`, `--muted`, `--card-bg` etc.). Ideal place to change colors / visual theme globally.
- `features/shared/assets/css/base.css` contains layout helpers and classes used by the login view:
  - `.centered`, `.small-card`, `.notice`, `.btn` etc.
- To add login-specific styles, create `features/users/shared/assets/css/login.css` and set `$additionalStyles = ['/sulamproject/features/users/shared/assets/css/login.css'];` in the controller before including `base.php`.

---

## 7) Database & Field differences (important)
- The modern auth service expects `users` table to have fields `password_hash` and `role`.
- The legacy DB bootstrap and procedural page uses `password` and `roles` fields (see `features/shared/lib/database/mysqli-db.php`). This is a mismatch.
  - If you refactor to AuthService approach, update `mysqli-db.php` to create `password_hash` and `role` fields and update legacy queries.
  - Or update `AuthService` to support both field names (do a fallback lookup) — but prefer standardization.

---

## 8) Where to change logic / validations / UI (What a junior dev often needs to do)
- Change UI texts
  - `features/users/shared/views/login.php` (controller view) or `features/users/shared/pages/login-direct.php` (legacy page) — change labels, placeholders, and messages.

- Change CSS/styling
  - `features/shared/assets/css/base.css` for layout changes, or add `features/users/shared/assets/css/login.css` for page-specific overrides.
  - If using the controller view, include the new CSS in `$additionalStyles` before including layout.

- Add client-side validation
  - Add an `assets/js` file for the view and include via `$additionalScripts` in controller or `<script>` tag in procedural page.

- Change server-side login logic
  - Modern approach: `features/users/shared/controllers/AuthController.php` => `handleLogin()`; `features/shared/lib/auth/AuthService.php` => `login()`.
  - Legacy approach: `features/users/shared/pages/login-direct.php` — edit the raw PHP logic or convert to controller.

- Add CSRF protection to legacy page
  - Include `features/shared/lib/utilities/csrf.php` and check `requireCsrfToken()` before processing POST.

- Adjust DB schema
  - `features/shared/lib/database/mysqli-db.php` defines schema; update column names and default admin row if you standardize to the new names.

---

## 9) Session & Security Notes
- Session hardening is done in `initSecureSession()` — ensure production uses `session.cookie_secure = 1` when using HTTPS.
- All forms should include `csrfField()` and validate on server with `verifyCsrfToken()` or `requireCsrfToken()`.
- Use `password_hash` & `password_verify` to store and compare hashed passwords. Avoid plain text.
- Escape all user-supplied output using `e()` before printing.
- Avoid including raw SQL with interpolated values; use prepared statements to prevent SQL injection.

---

## 10) Testing the login flow (manual steps)
1. Start Laragon and ensure Apache/MySQL are running.
2. Open http://localhost/sulamproject/ — it should redirect to `/login`.
3. Test login with seeded admin credentials (default in `mysqli-db.php`) or register a new account via `/register`.
4. For controller-based view (if router updated): ensure `AuthController` `handleLogin` correctly validates CSRF and uses `AuthService` to set session.
5. For procedural login: try login with seeded admin user, ensure `role` in session is set correctly.

---

## 11) Suggested improvements / refactor checklist (nice to have)
- Standardize column names across DB and code (`password_hash`, `role`). This reduces bugs and confusion.
- Remove duplicates: migrate `login-direct.php` logic to `AuthController` and update `routes.php` to use the controller instead of procedural page.
- Add CSRF token usage to all legacy pages and enforce it on POST.
- Add unit tests for `AuthService` to test registration and login flows.
- Add form CSRF & server-side validators for edge cases like SQL injections or attacker attempts.

---

## 12) Quick Links
- Login view (controller): `features/users/shared/views/login.php`
- Login procedural page: `features/users/shared/pages/login-direct.php`
- Controller: `features/users/shared/controllers/AuthController.php`
- Auth service: `features/shared/lib/auth/AuthService.php`
- Session helpers: `features/shared/lib/auth/session.php`
- CSRF helper: `features/shared/lib/utilities/csrf.php`
- Utilities: `features/shared/lib/utilities/functions.php`
- Layout: `features/shared/components/layouts/base.php`
- CSS: `features/shared/assets/css/variables.css`, `features/shared/assets/css/base.css`
- DB: `features/shared/lib/database/mysqli-db.php`

---

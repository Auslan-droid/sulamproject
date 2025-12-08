# GEMINI.md

## Project Context
**SulamProject** is a community management system for a mosque (Desa Ilmu) replacing Excel/manual processes. It manages residents, zakat (financial assistance), donations, death/funeral records, and events.
- **Type**: Web Application (PHP/MySQL).
- **State**: Active development, feature-based migration in progress.
- **Goal**: Secure, role-based system with audit logs and reporting.

## Technical Stack
- **Language**: PHP (Vanilla, no framework).
- **Database**: MySQL/MariaDB (`masjidkamek` DB).
- **Frontend**: HTML5, CSS3, Vanilla JS.
- **Server**: Apache (via Laragon in dev).
- **Environment**: Windows (`win32`) / Laragon.

## Architecture & Structure
The project follows a **Feature-Based Architecture**. Code is organized by business domain, not by technical layer (e.g., no global `controllers/` folder).

### Directory Structure
```
sulamproject/
├── index.php                      # Front controller / Entry point
├── db.php                         # DB Connection & Auto-provisioning
├── features/                      # CORE LOGIC HERE
│   ├── [feature-name]/            # e.g., residents, financial, donations
│   │   ├── shared/                # Logic/Assets used by Admin & User
│   │   │   ├── lib/               # Models & Business Logic (e.g., Resident.php)
│   │   │   └── assets/            # Shared CSS/JS
│   │   ├── admin/                 # Admin-specific Interface
│   │   │   ├── controllers/       # Admin Controllers
│   │   │   ├── views/             # Admin Views (HTML/PHP)
│   │   │   └── pages/             # Admin Route Files (Direct access)
│   │   └── user/                  # User-specific Interface
│   │       ├── controllers/
│   │       ├── views/
│   │       └── pages/
├── assets/                        # Compiled output from Vite
├── context-docs/                  # Documentation (Architecture, PRD, etc.)
├── database/                      # Migrations & Seeds
└── storage/                       # Logs, uploads
```

### Key Features
1.  **Residents**: Registry, household management.
2.  **Financial**: Zakat applications, assessments, disbursements.
3.  **Donations**: Donor tracking, receipts.
4.  **Death/Funeral**: Notification and assistance.
5.  **Events**: Announcements and scheduling.

## Coding Conventions

### Naming
- **Controllers**: `PascalCaseController.php` (e.g., `AdminResidentsController.php`)
- **Models/Classes**: `PascalCase.php` (e.g., `Resident.php`)
- **Views/Files**: `kebab-case.php` (e.g., `manage-residents.php`)
- **CSS/JS**: `kebab-case` (e.g., `admin-residents.css`)

### Routing
- **Direct File Access**: The URL maps directly to files in `pages/` directories (e.g., `/features/residents/admin/pages/create.php`).
- **Controller Delegation**: Route files instantiate a Controller and call a method.
  ```php
  // Example Route File
  require_once '.../AdminResidentsController.php';
  $controller = new AdminResidentsController();
  $controller->showCreateResident();
  ```

### Database & Security
- **Connection**: Uses `PDO` (via `db.php`).
- **Queries**: **MUST** use prepared statements for ALL variable input.
- **Auth**: Native PHP Sessions.
- **Passwords**: `password_hash()` / `password_verify()`.
- **Sanitization**: `htmlspecialchars()` for output.
- **CSRF**: Required for all forms.

## Development Workflow

### Database
- **Auto-provisioning**: `db.php` automatically creates the `masjidkamek` database and tables if they don't exist.
- **Migrations**: Located in `database/migrations/`.

### Build/Run
1.  **Start Server**: Ensure Laragon (Apache/MySQL) is running.
2.  **Assets**:
    -   `npm install` (if new dependencies).
    -   `npm run dev` (for hot reloading during dev).
    -   `npm run build` (for production assets).

### Testing
- Currently manual verification via browser.
- Check `storage/logs` for PHP errors.

## Documentation Reference
- `context-docs/Architecture.md`: System design.
- `context-docs/Feature-Based-Structure.md`: Detailed folder rules.
- `AGENTS.md`: Quick summary for AI.

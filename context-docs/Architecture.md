# Architecture

Status: Draft 0.1  
Owner: [Your Name]  
Last updated: [YYYY-MM-DD]

## System Context
- Users: Admin, Staff, Regular User.
- External: Email/SMS gateway (optional later), Payment channels (future), Backup storage.

## Suggested Stack (to confirm)
- Backend: Laravel (PHP) with REST/JSON APIs.  
- DB: MySQL/MariaDB (Laragon for dev).  
- Frontend: Blade or lightweight SPA (Vue/React) depending on complexity.  
- Auth: Laravel auth with RBAC middleware/policies.

## Key Components
- Auth & RBAC  
- Resident Registry  
- Assistance (applications, approvals, disbursements)  
- Death & Funeral  
- Donations & Receipts  
- Events & Announcements  
- Audit Logging  
- Reporting

## Cross-cutting Concerns
- Security: hashing (bcrypt/argon2), input validation, CSRF/XSS defenses, HTTPS in prod.  
- Observability: request logs, audit logs, error tracking.  
- Data: migrations, seeders, retention/archival jobs, backups.

## Deployment
- Dev: Laragon/Xampp (Windows).  
- Staging/Prod: Linux server or managed hosting with HTTPS (Let’s Encrypt), nightly backups, restricted DB access.  
- CI/CD: Lint, tests, migrations; artifact-based deploys or zero-downtime if possible.

## Availability & Performance
- Targets: page ≤ 3s; queries ≤ 2s; 50 concurrent users.  
- Tactics: DB indexing, pagination, caching (config/query when safe), N+1 avoidance.  
- Backups & Recovery: nightly full + hourly binlog (or equivalent); recovery drills.

## Data Retention & Audit
- Immutable audit log for CRUD and approvals.  
- Configurable retention jobs for data subject to policy.

## Open Decisions
- Frontend approach (server-rendered vs SPA).  
- Approval workflow complexity (single vs multi-step).  
- Notification channels (email/SMS/WhatsApp).
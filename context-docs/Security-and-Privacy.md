# Security & Privacy

Status: Draft 0.1  
Owner: [Your Name]  
Last updated: [YYYY-MM-DD]

## Identity & Access
- Passwords hashed and salted (bcrypt/argon2).  
- RBAC enforcement for every route/action (deny-by-default).  
- Session security, CSRF protection, secure cookies.  
- Account lockout/attempt throttling.

## Data Protection
- Input validation and output encoding to prevent SQL injection and XSS.  
- HTTPS in production (HSTS, modern TLS).  
- Least-privilege DB user; parameterized queries/ORM.  
- Document uploads validated and scanned; limit types/sizes; store outside web root or in object storage with signed URLs.

## Privacy & PDPA Considerations
- Deceased data outside PDPA, but related living persons’ data must be protected.  
- Consent records for data processing and retention; revocation handling.  
- Data subject rights (export/redaction) – document process (post-MVP if needed).  
- Configurable data retention per category; documented archival/deletion.

## Audit & Monitoring
- Immutable audit log (who, what, when, before/after, IP).  
- Admin access logs and periodic review.  
- Alerting for repeated failed logins and privilege changes.

## Backup & Recovery
- Encrypted backups; stored offsite or separate storage.  
- Regular recovery drills; RPO/RTO targets agreed with stakeholders.

## Secure Development Checklist (MVP)
- [ ] Password hashing configured and verified.  
- [ ] RBAC middleware/policies on sensitive routes.  
- [ ] Input validation + output encoding in all forms/pages.  
- [ ] CSRF tokens on write endpoints; CSP headers where applicable.  
- [ ] File upload hardening; antivirus scan if available.  
- [ ] HTTPS enforced in staging/prod; HSTS.  
- [ ] Audit log append-only; tamper-evident.  
- [ ] Backups encrypted; restore test performed.
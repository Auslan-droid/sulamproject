# Vision & Scope

Status: Draft 0.1  
Owner: [Your Name]  
Last updated: [YYYY-MM-DD]

## Vision
A unified, secure, and auditable system that ensures no resident in need is overlooked, replacing spreadsheets and fragmented tools with reliable, searchable records and clear assistance workflows.

## Objectives
- Maintain accurate resident registry (households and individuals).  
- Streamline assistance workflows from application to disbursement.  
- Provide oversight through audit logs and standard reports.  
- Enable events/announcements to engage the community.  
- Meet performance and security targets.

## In Scope (MVP)
- Resident registry, assistance (zakat), donations, events, audit logging, summary reports.  
- Role-based access control and secure authentication.

## Out of Scope (MVP)
- Full GL accounting, advanced payments, multi-tenant support, complex analytics dashboards.

## Success Metrics
- Adoption: ≥ 80% of target workflows moved off Excel within 3 months.  
- Performance: page ≤ 3s; queries ≤ 2s; ≥ 50 concurrent users.  
- Compliance: password hashing, RBAC, input validation, HTTPS in production.

## Assumptions
- Initial deployment on existing infrastructure (e.g., Laragon/MySQL for dev).  
- Staff are available for UAT and data migration.

## Dependencies
- Stakeholder sign-off on RBAC and approval flows.  
- Availability of initial resident and donation data for import.
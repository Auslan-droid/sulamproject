# Roles & Permissions (RBAC)

Status: Draft 0.1  
Owner: [Your Name]  
Last updated: [YYYY-MM-DD]

## Roles
- Admin (can include Treasurer/Finance responsibilities)
- Staff
- Regular User

## Permission Areas
- Users & Roles
- Resident Registry
- Assistance (Applications, Approvals, Disbursements)
- Donations & Receipts
- Death & Funeral
- Events & Announcements
- Reports
- Audit Log
- System Config (retention, categories, etc.)

## Starter Matrix (MVP)

| Area | Regular | Staff | Admin |
|------|---------|-------|-------|
| Users & Roles | - | View own | Full CRUD |
| Resident Registry | View | View + Create/Update | Full CRUD + Merge |
| Assistance | View own status | Create/Update apps; view decisions | Approve/Reject; Disburse; Configure |
| Donations & Receipts | View own receipts | Record donations; issue receipts | Configure; export |
| Death & Funeral | View | Record notifications; update logistics | Verify; approve assistance |
| Events & Announcements | View | Create/Publish | Approve/Unpublish |
| Reports | View public | View operational | View all; export |
| Audit Log | - | View own actions | View all |
| System Config | - | - | Full |

Notes:
- Start with a single Admin role; introduce Treasurer as a scoped Admin later if needed.  
- "View own" means limited to the user’s own account or actions.  
- Use deny-by-default; grant minimal privileges per role.
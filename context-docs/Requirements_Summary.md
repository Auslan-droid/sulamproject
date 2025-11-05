# Requirements Summary

## Main Goals (What Problems the System Solves)

- Using Excel is too tedious  
- myMasjid system is too inconvenient  
- No existent data on residents in Desa Ilmu  
- To make sure there’s no residents who are in need that got overlooked  

---

## Main Features / Modules

- **Resident registry:** Households and individuals with demographics, contact info, consent records, documents, and relationship links for family and next‑of‑kin management.  
- **Accounting management:** Payer profiles, applicant/recipient profiles, eligibility data (asnaf categories), applications, assessments, approvals, disbursements, and receipts.  
- **Death and funeral:** Death notifications, verification details, next‑of‑kin, burial logistics, funeral assistance disbursements, and related correspondence, treating deceased data as outside PDPA but protecting living persons’ data.  
- **Donations and finance:** General donations, pledges, receipts, and basic accounting reports consistent with mosque systems that integrate fundraising and member portals.  
- **Events and announcements:** Schedules, notices, and public information to support congregation engagement alongside administrative records.  
- **Audit, reporting, and retention:** Immutable audit logs, disclosure registers, configurable retention periods, and standard reports for committee oversight.  

---

## User Roles

- **Admin:** Might have multiple admins with different access (e.g., treasurer can access financial modules).  
- **Regular User**  

---

## Functional Requirements (Actions Users Can Perform)

1. The system shall allow admins to create, edit, and delete user accounts with role-based access (Admin, Staff, Regular User).  
2. The system shall allow users or staff to register, update, and manage household and individual resident records.  
3. The system shall record zakat payers and applicants, process applications, and allow admins to approve or reject them.  
4. The system shall record death notifications, next-of-kin details, and funeral assistance information.  
5. The system shall allow recording and tracking of donations, pledges, and disbursements.  
6. The system shall allow staff to create, publish, and update events or announcements visible to users.  
7. The system shall allow users to search and filter records (e.g., residents, donations, events) efficiently.  
8. The system shall generate summary reports for modules such as zakat, donations, and resident records.  
9. The system shall record user activities (e.g., data edits, approvals) for accountability and transparency.  
10. The system shall provide secure login and logout functions, ensuring that only authorized users can access sensitive data.  

---

## Non-Functional Requirements (Performance, Security, Scalability, etc.)

- The system should load any page within **3 seconds** under normal network conditions.  
- The system should handle at least **50 concurrent users** without noticeable performance degradation.  
- Database queries should execute within **2 seconds** for standard operations.  
- All user credentials must be stored using **hashed and salted passwords**.  
- Only authorized users can access restricted modules based on their role (e.g., admin, staff).  
- The system must implement **input validation** to prevent SQL injection and XSS attacks.  
- All communication between client and server should use **HTTPS** when deployed online.  
- The system should be available **99% of the time** during operational hours.  
- Data should not be lost in case of unexpected shutdowns (use of database transactions or backups).  
- The system should automatically **recover from minor failures** without manual intervention.  

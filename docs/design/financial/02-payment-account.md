# Payment Account (Akaun Bayaran)

## Overview
The **Payment Account** (Akaun Bayaran) is the database record of all money leaving the organization. In accounting terms, this represents the **Credit** side of the Cash Book (when viewing from the perspective of the Cash account decreasing).

For a junior developer, think of this as the "Expense Log". Every time the Masjid pays a bill, buys food for an event, or pays an allowance, it gets recorded here.

## Database Structure
We currently use the table `financial_payment_accounts`.

### Key Columns
- **`id`**: Unique identifier.
- **`tx_date`**: The date the money was paid out.
- **`description`**: A brief explanation (e.g., "Bil Elektrik TNB").
- **`amount columns`**: Similar to deposits, we use specific columns for categories (e.g., `utiliti`, `penyelenggaraan_masjid`, `saguhati`).

## Relationship to Other Documents
- **1-to-1 Relationship with Payment Voucher (Baucar Bayaran)**:
    - Every row in this table represents **one** Payment Voucher.
    - The database record *is* the voucher.

## Future Improvements (To Be Implemented)
To fully support the "Payment Voucher" generation, we need to add:
1.  **`ref_number`**: A string to store the voucher number (e.g., `PV/2025/001`).
2.  **`paid_to`**: A string to store the name of the person/company receiving the money (e.g., "Tenaga Nasional Berhad").

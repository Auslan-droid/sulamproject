# Deposit Account (Akaun Terimaan)

## Overview
The **Deposit Account** (Akaun Terimaan) is the database record of all money entering the organization (Masjid). In accounting terms, this represents the **Debit** side of the Cash Book (when viewing from the perspective of the Cash account increasing).

For a junior developer, think of this as the "Income Log". Every time someone donates money, pays rental fees, or the government gives a grant, it gets recorded here.

## Database Structure
We currently use the table `financial_deposit_accounts`.

### Key Columns
- **`id`**: Unique identifier for the record.
- **`tx_date`**: The date the money was received.
- **`description`**: A brief explanation of the transaction (e.g., "Sumbangan Jumaat").
- **`amount columns`**: We use a "columnar" approach where specific columns represent specific categories (e.g., `geran_kerajaan`, `sumbangan_derma`, `tabung_masjid`).
    - *Why?* This makes it very easy to sum up totals for a specific category later without complex joins.

## Relationship to Other Documents
- **1-to-1 Relationship with Official Receipt (Resit Rasmi)**:
    - Every single row in this `financial_deposit_accounts` table represents **one** Official Receipt.
    - If you create a row here, you effectively create a receipt.
    - If you delete a row here, you void that receipt.

## Future Improvements (To Be Implemented)
To fully support the "Official Receipt" generation, we need to add:
1.  **`ref_number`**: A string to store the receipt number (e.g., `RR/2025/001`). This ensures we have a human-readable, sequential ID for physical filing.
2.  **`received_from`**: A string to store the name of the person/entity giving the money. Currently, we only have `description`.

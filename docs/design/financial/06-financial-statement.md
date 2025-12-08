# Financial Statement (Penyata Terimaan dan Bayaran)

## Overview
The **Financial Statement** is a summary report generated for a specific period (e.g., Monthly or Annually). It follows the standard "Receipts and Payments" format used by non-profits (Lampiran 54).

## The Concept
This is a **Report Generator**. It takes a date range (e.g., "1 Jan 2025" to "31 Dec 2025") and calculates totals based on the Cash Book data.

## Visual Layout & Fields
Based on the provided template, the statement includes:

### 1. Header Section
- **Title**: PENYATA TERIMAAN DAN BAYARAN
- **Organization**: Name & Address of Masjid.
- **Period**: "BAGI [tempoh / tahun berakhir]"
    - *Explanation*: This defines the scope. Example: "BAGI TAHUN BERAKHIR 31 DISEMBER 2025".

### 2. Opening Balance Section (Baki Awal)
- **BAKI PADA [Start Date]**:
    - Wang Tunai di tangan (Cash in Hand)
    - Wang Tunai di bank (Cash at Bank)
    - Pelaburan (Investments) - *Optional/Advanced feature*

### 3. Section A: Terimaan (Receipts)
- List of all income categories (e.g., Sumbangan, Sewa, Geran).
- **JUMLAH TERIMAAN**: Total sum of all receipts during the period.

### 4. Section B: Bayaran (Payments)
- List of all expense categories (e.g., Utiliti, Saguhati, Pembaikan).
- **JUMLAH BAYARAN**: Total sum of all payments during the period.

### 5. Surplus/Deficit
- **Lebihan / (Kurangan) (A-B)**: The net change in funds (Receipts - Payments).

### 6. Closing Balance Section (Baki Akhir)
- **BAKI PADA [End Date]**:
- **DIWAKILI OLEH** (Represented By):
    - Wang Tunai di tangan (Calculated from Opening Cash + Cash In - Cash Out)
    - Wang Tunai di bank (Calculated from Opening Bank + Bank In - Bank Out)
    - Pelaburan

### 7. Signatures
- **Disediakan oleh**: (Prepared By)
- **Disahkan oleh**: (Certified By)
- **Disemak oleh**: (Checked By)

## Implementation Logic
To generate this report accurately, the system must:
1.  **Calculate Opening Balances**: Sum all transactions *before* the start date, split by `payment_method` (Cash vs Bank).
2.  **Sum Current Period**: Sum transactions *within* the date range, grouped by category columns.
3.  **Calculate Closing Balances**: Apply the formula: `Opening + (In - Out) = Closing` separately for Cash and Bank.

## Required Data Fields
- Relies on the `payment_method` field (added in previous step) to distinguish between "Tunai di tangan" and "Tunai di bank".

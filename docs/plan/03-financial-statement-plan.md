# Financial Implementation Plan - Phase 3: Financial Statement (Penyata)

The **Financial Statement** (Penyata Terimaan dan Bayaran) is a summary report showing totals for a specific period, usually generated monthly or annually.

## 1. Controller Logic
**Action:**
- Add `public function financialStatement()` to `FinancialController`.
- **Inputs:** `start_date`, `end_date` (e.g., 2025-01-01 to 2025-12-31).
- **Logic:**

  ### A. Calculate Opening Balance (Baki Awal)
  - Sum all Deposits where `date < start_date`.
  - Sum all Payments where `date < start_date`.
  - **Split by Method:**
    - `opening_cash` = (Total Cash In) - (Total Cash Out)
    - `opening_bank` = (Total Bank In) - (Total Bank Out)

  ### B. Sum Current Period (Terimaan & Bayaran)
  - **Deposits:** Query `financial_deposit_accounts` where `date BETWEEN start_date AND end_date`.
    - Group by Category Column (sum each column).
    - Also sum by Payment Method (to calculate closing balance).
  - **Payments:** Query `financial_payment_accounts` where `date BETWEEN start_date AND end_date`.
    - Group by Category Column.
    - Also sum by Payment Method.

  ### C. Calculate Closing Balance (Baki Akhir)
  - `closing_cash` = `opening_cash` + `current_cash_in` - `current_cash_out`
  - `closing_bank` = `opening_bank` + `current_bank_in` - `current_bank_out`

## 2. The View (`financial-statement.php`)
**Action:**
- Create `features/financial/admin/pages/financial-statement.php`.
- **UI Components:**
  - **Top Bar:** Date Range Picker (Start Date, End Date) & "Generate Report" button.
  - **Report Area:** The main content area that displays the report.

- **Report Layout (Lampiran 54):**
  - **Header:** "PENYATA TERIMAAN DAN BAYARAN BAGI..."
  - **Opening Balance Section:**
    - Tunai di tangan
    - Tunai di bank
  - **Section A (Terimaan):** List of income categories and total.
  - **Section B (Bayaran):** List of expense categories and total.
  - **Surplus/Deficit:** (Total A - Total B).
  - **Closing Balance Section:**
    - Tunai di tangan
    - Tunai di bank
  - **Signatures:** Prepared By, Certified By, Checked By.

# Financial Implementation Plan - Phase 1: Cash Book (Buku Tunai)

The **Cash Book** is the central ledger that combines both Deposits (In) and Payments (Out) into a single chronological list. This is the foundation of the financial module.

## 1. Backend Logic (The UNION Query)
We need a way to fetch data from both `financial_deposit_accounts` and `financial_payment_accounts` in one go.

**Action:**
- Modify `FinancialController.php`.
- Add a private method `getCashBookData()` that executes a raw SQL query using `UNION ALL`.
- **Query Logic:**
  ```sql
  SELECT id, tx_date, receipt_number as ref_no, description, amount, payment_method, 'IN' as type 
  FROM financial_deposit_accounts
  UNION ALL
  SELECT id, tx_date, voucher_number as ref_no, description, amount, payment_method, 'OUT' as type 
  FROM financial_payment_accounts
  ORDER BY tx_date ASC
  ```
  *Note: We need to calculate `amount` by summing the category columns for each row in the query, or fetch all columns and calculate in PHP. Fetching pre-calculated totals in SQL is more efficient for sorting.*

## 2. Controller Method
**Action:**
- Add `public function cashBook()` to `FinancialController`.
- Call `getCashBookData()`.
- Iterate through the results to calculate **Running Balances**:
  - Initialize `$tunaiBalance = 0` and `$bankBalance = 0`.
  - Loop through transactions:
    - If Type is **IN**:
      - If Method is **Cash**: `$tunaiBalance += $amount`
      - If Method is **Bank**: `$bankBalance += $amount`
    - If Type is **OUT**:
      - If Method is **Cash**: `$tunaiBalance -= $amount`
      - If Method is **Bank**: `$bankBalance -= $amount`
    - Store these running balances in the row data for display.
- Pass this processed data to the view.

## 3. The View (`cash-book.php`)
**Action:**
- Create `features/financial/admin/pages/cash-book.php` (Controller/Page wrapper).
- Create `features/financial/admin/views/cash-book.php` (The Table).
- **Table Columns:**
  1.  **Tarikh** (Date)
  2.  **No. Rujukan** (Resit/Baucar) - *Link to Print later*
  3.  **Butiran** (Description)
  4.  **Tunai (RM)**
      - Masuk
      - Keluar
  5.  **Bank (RM)**
      - Masuk
      - Keluar
  6.  **Baki (RM)**
      - Tunai
      - Bank

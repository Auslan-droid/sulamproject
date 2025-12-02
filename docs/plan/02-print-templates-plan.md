# Financial Implementation Plan - Phase 2: Print Templates

This phase focuses on generating professional, printable documents for "Official Receipts" (Resit Rasmi) and "Payment Vouchers" (Baucar Bayaran) that match the physical paper templates.

## 1. Helper Function
We need to convert numeric amounts into words for the "Ringgit Malaysia" line.

**Action:**
- Update `features/shared/lib/utilities/functions.php`.
- Add `numberToWords($number)` function.
  - Input: `150.50`
  - Output: "Satu Ratus Lima Puluh Ringgit Dan Lima Puluh Sen Sahaja".

## 2. Official Receipt (`receipt-print.php`)
**Action:**
- Create `features/financial/admin/pages/receipt-print.php`.
- **Logic:**
  - Get `id` from URL (`?id=123`).
  - Fetch deposit record using `DepositAccountRepository`.
  - Render HTML that mimics "Lampiran 6" (The Receipt Template).
  - **Styling:**
    - Use CSS Grid/Flexbox for the box layout.
    - Use `@media print` to hide headers/sidebars/buttons.
    - Set page size to A5 or half-A4 if required, or standard A4.
  - **Auto-Print:** Add `<script>window.print();</script>` to the footer.

## 3. Payment Voucher (`voucher-print.php`)
**Action:**
- Create `features/financial/admin/pages/voucher-print.php`.
- **Logic:**
  - Get `id` from URL.
  - Fetch payment record using `PaymentAccountRepository`.
  - Render HTML that mimics "Lampiran 1" (The Voucher Template).
  - **Layout:**
    - Header (Masjid details).
    - Payee Details (Left) vs Voucher Details (Right).
    - Table for items.
    - Signature section with 3 columns (Prepared, Verified, Approved).

## 4. Integration
**Action:**
- Add a "Print" button (Icon) to every row in the **Cash Book** table.
- Add a "Print" button to the main **Deposit List** table.
- Add a "Print" button to the main **Payment List** table.

# Official Receipt (Resit Rasmi)

## Overview
The **Official Receipt** is the legal document generated to prove that money was received. It follows a strict template provided by the client (Lampiran 6).

## The Concept
- We do **not** store "receipts" separately.
- We store "transactions" in `financial_deposit_accounts`.
- When a user clicks "Print Receipt" for Transaction ID #50, the system generates this document.

## Visual Layout & Fields
Based on the provided template, the receipt must include:

### 1. Header Section
- **Nama Jawatankuasa**: (e.g., "JAWATANKUASA PENGURUSAN MASJID KAMEK")
- **Alamat**: The full address of the Masjid.

### 2. Transaction Details
- **No. Resit**: The unique serial number (e.g., `RR/2025/001`).
- **Tarikh**: The date of the transaction.
- **Diterima Dari**: Name of the person/entity giving the money.
- **Jumlah (Words)**: The amount written in words (e.g., "Satu Ratus Ringgit Sahaja").
- **Perkara**: The description or category of the deposit (e.g., "Sumbangan Jumaat").

### 3. Financial Footer
- **RM Box**: A specific box displaying the numeric amount (e.g., "100.00").
- **Tunai / No. Transaksi Bank**: 
    - Originally "Tunai / Cek No.".
    - **Client Request**: Change "Cek No." to "Bank" or "No. Transaksi".
    - Logic: If Cash, show "Tunai". If Bank, show the Bank Transaction Reference Number.

### 4. Authorization
- **Disediakan Oleh**: Name of the person preparing the receipt (System user or manual entry).
- **Tandatangan**: A line for the physical signature.

## Required Data Fields (Database Mapping)
To support this, the `financial_deposit_accounts` table needs:
1.  `receipt_number`: For "No. Resit".
2.  `tx_date`: For "Tarikh".
3.  `received_from`: For "Diterima Dari".
4.  `amount`: Sum of columns (Used for both "RM Box" and "Jumlah in Words").
5.  `description`: For "Perkara".
6.  `payment_method`: To determine if it's "Tunai" or "Bank".
7.  `payment_reference`: **(New)** To store the "No. Transaksi Bank" if applicable.

## Implementation Strategy
- **File**: `receipt-print.php?id=XYZ`
- **Styling**: Use CSS Grid/Flexbox to replicate the boxy layout of the image.
- **Helper Function**: Need a PHP function `numberToWords(float $amount)` to convert "100.00" to "Satu Ratus Ringgit".

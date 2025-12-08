# Payment Voucher (Baucar Bayaran)

## Overview
The **Payment Voucher** is the internal document authorizing a cash outflow. It follows a specific template provided by the client (Lampiran 1).

## The Concept
- We store "expenses" in `financial_payment_accounts`.
- When a user clicks "Print Voucher" for Transaction ID #88, the system generates this document.

## Visual Layout & Fields
Based on the provided template, the voucher must include:

### 1. Header Section
- **Nama Jawatankuasa**: (e.g., "JAWATANKUASA PENGURUSAN MASJID KAMEK")
- **Alamat**: The full address of the Masjid.

### 2. Payee Details (Left Side)
- **Bayar Kepada**: Name of the person/company receiving the money.
- **No. Kad Pengenalan**: IC Number of the payee.
- **Nama Bank**: Bank Name (e.g., Maybank, CIMB).
- **No. Akaun**: Bank Account Number.

### 3. Voucher Details (Right Side)
- **No. Baucar**: Unique serial number (e.g., `PV/2025/088`).
- **Tarikh**: Date of the transaction.
- **Kaedah Pembayaran**: Checkboxes for:
    - [ ] Tunai
    - [ ] Cek (or Bank Transfer Ref)
    - [ ] E-Banking

### 4. Transaction Table
- **No**: Item number (usually just '1' for single transaction).
- **Butiran Bayaran**: Description of the payment.
- **Amaun (RM)**: The numeric amount.
- **Jumlah (RM)**: Total amount.

### 5. Amount in Words
- **Amaun (Dalam Perkataan)**: The amount written out (e.g., "Satu Ratus Ringgit Malaysia").

### 6. Signatures / Authorization
The template features a robust approval workflow:
- **Disediakan Oleh** (Prepared By): Name, Position, Date.
- **Disemak dan Diluluskan Oleh** (Checked & Approved By): **Three** separate slots for committee members to sign (Name, Position, Date).
- **Penerima** (Recipient): "Saya Mengesahkan Pembayaran Seperti Di Atas Telah Diterima" (I confirm receipt...), with Name, IC, and Date.

## Required Data Fields (Database Mapping)
To support this, the `financial_payment_accounts` table needs:
1.  `voucher_number`: For "No. Baucar".
2.  `tx_date`: For "Tarikh".
3.  `paid_to`: For "Bayar Kepada".
4.  `payee_ic`: **(New)** For "No. Kad Pengenalan".
5.  `payee_bank_name`: **(New)** For "Nama Bank".
6.  `payee_bank_account`: **(New)** For "No. Akaun".
7.  `payment_method`: To tick the correct checkbox (Tunai/Cek/E-Banking).
8.  `description`: For "Butiran Bayaran".
9.  `amount`: For "Amaun".

## Implementation Strategy
- **File**: `voucher-print.php?id=XYZ`
- **Styling**: Strict CSS grid to match the complex signature boxes.
- **Helper Function**: Reuse `numberToWords()` for the amount in words.

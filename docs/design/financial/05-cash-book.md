# Cash Book (Buku Tunai)

## Overview
The **Cash Book** is the central ledger that combines both Deposits and Payments into a single chronological list. It allows the Treasurer to see the flow of funds, specifically split between **Cash (Tunai)** and **Bank**.

## The "Virtual View" Concept
We do not create a `cash_book` table in the database. Instead, we create a **Virtual View** by querying both the Deposit and Payment tables and merging them.

### The Columns Structure
The Cash Book will display the following specific columns:

1.  **Tarikh** (Date)
2.  **No. Resit Rasmi** (Receipt Number) - *From Deposit Table*
3.  **No. Baucar Bayaran** (Voucher Number) - *From Payment Table*
4.  **Payment Method** (Cash / Bank)
5.  **Perkara** (Description)
6.  **Tunai (Cash on Hand)**
    *   Masuk (In)
    *   Keluar (Out)
7.  **Bank (Cash in Bank)**
    *   Masuk (In)
    *   Keluar (Out)
8.  **Baki (Balance)**
    *   *Calculated running total*

### The Logic (UNION Query)
We use a SQL `UNION` operation to combine the two tables:

1.  **Select from Deposits**:
    - `receipt_number` is present.
    - `voucher_number` is NULL.
    - `type` = 'IN'.
2.  **Select from Payments**:
    - `receipt_number` is NULL.
    - `voucher_number` is present.
    - `type` = 'OUT'.
3.  **Order**: Sort everything by Date (Ascending).

### Handling "Tunai" vs "Bank"
To support the split columns, we rely on the `payment_method` field in the database:

- **If Deposit (IN):**
    - If `payment_method` == 'cash' -> Display in **Tunai (Masuk)**.
    - If `payment_method` == 'bank' -> Display in **Bank (Masuk)**.
- **If Payment (OUT):**
    - If `payment_method` == 'cash' -> Display in **Tunai (Keluar)**.
    - If `payment_method` == 'bank' -> Display in **Bank (Keluar)**.

### Calculating Balance
We will calculate the running balance in PHP as we iterate through the rows. We might track two separate balances (Tunai Balance and Bank Balance) or a total balance depending on the final UI preference, but the data supports both.

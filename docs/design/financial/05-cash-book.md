# Cash Book (Buku Tunai)

## Overview
The **Cash Book** is the central ledger that combines both Deposits and Payments into a single chronological list. It allows the Treasurer to see the flow of funds, specifically split between **Cash (Tunai)** and **Bank**.

## Opening Balances (Baki Awal)

Before calculating the running balance, we must account for **opening balances**:

- **Baki di Tangan** (Cash on Hand) - Opening cash balance
- **Baki di Bank** (Cash in Bank) - Opening bank balance

These are stored in the `financial_settings` table, keyed by fiscal year:

```sql
-- Table structure
financial_settings (
    id,
    fiscal_year,              -- e.g., 2025
    opening_cash_balance,     -- Baki Awal di Tangan
    opening_bank_balance,     -- Baki Awal di Bank
    effective_date,           -- When this balance was set
    notes,
    created_by,
    created_at,
    updated_at
)
```

### Year-End Process
At the end of each fiscal year:
1. Calculate closing balances (Baki Akhir) for cash and bank
2. Create a new record in `financial_settings` for the next fiscal year
3. Set `opening_cash_balance` and `opening_bank_balance` to the previous year's closing values

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
    *   Baki (Running Balance)
7.  **Bank (Cash in Bank)**
    *   Masuk (In)
    *   Keluar (Out)
    *   Baki (Running Balance)
8.  **Jumlah Baki (Total Balance)**
    *   *Sum of Tunai Baki + Bank Baki*

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

The running balance calculation must include the opening balances from `financial_settings`:

```php
/**
 * Get Cash Book with running balances
 * @param int $fiscalYear The fiscal year to query
 * @return array Transactions with running balances
 */
function getCashBookWithBalances(int $fiscalYear): array {
    global $conn;
    
    // 1. Get opening balances for the fiscal year
    $stmt = $conn->prepare("
        SELECT opening_cash_balance, opening_bank_balance 
        FROM financial_settings 
        WHERE fiscal_year = ?
    ");
    $stmt->bind_param("i", $fiscalYear);
    $stmt->execute();
    $result = $stmt->get_result();
    $settings = $result->fetch_assoc();
    
    $cashBalance = (float)($settings['opening_cash_balance'] ?? 0);
    $bankBalance = (float)($settings['opening_bank_balance'] ?? 0);
    
    // 2. Get all transactions for the fiscal year (UNION query)
    $transactions = getTransactionsForYear($fiscalYear);
    
    // 3. Calculate running balances
    foreach ($transactions as &$tx) {
        $amount = (float)$tx['total_amount'];
        
        if ($tx['payment_method'] === 'cash') {
            if ($tx['type'] === 'IN') {
                $cashBalance += $amount;
            } else {
                $cashBalance -= $amount;
            }
        } else { // bank or cheque
            if ($tx['type'] === 'IN') {
                $bankBalance += $amount;
            } else {
                $bankBalance -= $amount;
            }
        }
        
        $tx['running_cash_balance'] = $cashBalance;
        $tx['running_bank_balance'] = $bankBalance;
        $tx['running_total_balance'] = $cashBalance + $bankBalance;
    }
    
    return [
        'opening_cash' => $settings['opening_cash_balance'] ?? 0,
        'opening_bank' => $settings['opening_bank_balance'] ?? 0,
        'transactions' => $transactions,
        'closing_cash' => $cashBalance,
        'closing_bank' => $bankBalance,
    ];
}
```

### Display Example

| Tarikh | No. Resit | No. Baucar | Perkara | Tunai Masuk | Tunai Keluar | Tunai Baki | Bank Masuk | Bank Keluar | Bank Baki |
|--------|-----------|------------|---------|-------------|--------------|------------|------------|-------------|-----------|
| - | - | - | **Baki Awal** | - | - | **5,000.00** | - | - | **25,000.00** |
| 01/01/2025 | RR-001 | - | Tabung Jumaat | 1,500.00 | - | 6,500.00 | - | - | 25,000.00 |
| 02/01/2025 | - | BB-001 | Bayar Elektrik | - | - | 6,500.00 | - | 500.00 | 24,500.00 |
| 03/01/2025 | RR-002 | - | Derma Orang Ramai | - | - | 6,500.00 | 2,000.00 | - | 26,500.00 |

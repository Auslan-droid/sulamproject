<?php
/**
 * DepositAccountRepository - Data access layer for financial_deposit_accounts
 * 
 * Handles CRUD operations for deposit records using mysqli prepared statements.
 */

class DepositAccountRepository
{
    private mysqli $mysqli;

    /**
     * Category columns in the deposits table
     */
    public const CATEGORY_COLUMNS = [
        'geran_kerajaan',
        'sumbangan_derma',
        'tabung_masjid',
        'kutipan_jumaat_sadak',
        'kutipan_aidilfitri_aidiladha',
        'sewa_peralatan_masjid',
        'hibah_faedah_bank',
        'faedah_simpanan_tetap',
        'sewa_rumah_kedai_tadika_menara',
        'lain_lain_terimaan',
    ];

    /**
     * Category labels (display names)
     */
    public const CATEGORY_LABELS = [
        'geran_kerajaan' => 'Geran Kerajaan',
        'sumbangan_derma' => 'Sumbangan/Derma',
        'tabung_masjid' => 'Tabung Masjid',
        'kutipan_jumaat_sadak' => 'Kutipan Jumaat (Sadak)',
        'kutipan_aidilfitri_aidiladha' => 'Kutipan Aidilfitri/Aidiladha',
        'sewa_peralatan_masjid' => 'Sewa Peralatan Masjid',
        'hibah_faedah_bank' => 'Hibah/Faedah Bank',
        'faedah_simpanan_tetap' => 'Faedah Simpanan Tetap',
        'sewa_rumah_kedai_tadika_menara' => 'Sewa (Rumah/Kedai/Tadika/Menara)',
        'lain_lain_terimaan' => 'Lain-lain Terimaan',
    ];

    public function __construct(mysqli $mysqli)
    {
        $this->mysqli = $mysqli;
    }

    /**
     * Get all deposit records, ordered by tx_date descending
     *
     * @return array
     */
    public function findAll(): array
    {
        $sql = "SELECT * FROM financial_deposit_accounts ORDER BY tx_date DESC, id DESC";
        $result = $this->mysqli->query($sql);

        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    /**
     * Find a single deposit record by ID
     *
     * @param int $id
     * @return array|null
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->mysqli->prepare("SELECT * FROM financial_deposit_accounts WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        return $row ?: null;
    }

    /**
     * Create a new deposit record
     *
     * @param array $data Associative array with tx_date, description, and category amounts
     * @return int The inserted ID
     */
    public function create(array $data): int
    {
        $columns = [
            'tx_date', 
            'description', 
            'receipt_number', 
            'received_from', 
            'payment_method', 
            'payment_reference'
        ];
        $placeholders = ['?', '?', '?', '?', '?', '?'];
        $types = 'ssssss';
        $values = [
            $data['tx_date'],
            $data['description'],
            $data['receipt_number'] ?? null,
            $data['received_from'] ?? null,
            $data['payment_method'] ?? 'cash',
            $data['payment_reference'] ?? null,
        ];

        foreach (self::CATEGORY_COLUMNS as $col) {
            $columns[] = $col;
            $placeholders[] = '?';
            $types .= 'd';
            $values[] = $this->sanitizeAmount($data[$col] ?? 0);
        }

        $sql = sprintf(
            "INSERT INTO financial_deposit_accounts (%s) VALUES (%s)",
            implode(', ', $columns),
            implode(', ', $placeholders)
        );

        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param($types, ...$values);
        $stmt->execute();
        $insertedId = $stmt->insert_id;
        $stmt->close();

        return $insertedId;
    }

    /**
     * Update an existing deposit record
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        $setClauses = [
            'tx_date = ?', 
            'description = ?',
            'receipt_number = ?',
            'received_from = ?',
            'payment_method = ?',
            'payment_reference = ?'
        ];
        $types = 'ssssss';
        $values = [
            $data['tx_date'],
            $data['description'],
            $data['receipt_number'] ?? null,
            $data['received_from'] ?? null,
            $data['payment_method'] ?? 'cash',
            $data['payment_reference'] ?? null,
        ];

        foreach (self::CATEGORY_COLUMNS as $col) {
            $setClauses[] = "$col = ?";
            $types .= 'd';
            $values[] = $this->sanitizeAmount($data[$col] ?? 0);
        }

        $types .= 'i';
        $values[] = $id;

        $sql = sprintf(
            "UPDATE financial_deposit_accounts SET %s WHERE id = ?",
            implode(', ', $setClauses)
        );

        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param($types, ...$values);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    /**
     * Delete a deposit record by ID
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $stmt = $this->mysqli->prepare("DELETE FROM financial_deposit_accounts WHERE id = ?");
        $stmt->bind_param('i', $id);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    /**
     * Sanitize amount to a float, defaulting to 0.00 for invalid values
     *
     * @param mixed $value
     * @return float
     */
    private function sanitizeAmount($value): float
    {
        if (is_numeric($value) && $value > 0) {
            return (float) $value;
        }
        return 0.00;
    }

    /**
     * Calculate row total for a deposit record
     *
     * @param array $row
     * @return float
     */
    public function calculateRowTotal(array $row): float
    {
        $total = 0.0;
        foreach (self::CATEGORY_COLUMNS as $col) {
            $total += (float) ($row[$col] ?? 0);
        }
        return $total;
    }
}

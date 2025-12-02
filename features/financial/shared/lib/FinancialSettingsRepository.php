<?php
/**
 * FinancialSettingsRepository - Handles financial settings data access
 * Manages opening balances (baki di tangan & baki di bank) per fiscal year
 */

class FinancialSettingsRepository {
    private mysqli $mysqli;

    public function __construct(mysqli $mysqli) {
        $this->mysqli = $mysqli;
    }

    /**
     * Get settings for a specific fiscal year
     * @param int $fiscalYear The fiscal year (e.g., 2025)
     * @return array|null Settings record or null if not found
     */
    public function getByFiscalYear(int $fiscalYear): ?array {
        $stmt = $this->mysqli->prepare("
            SELECT * FROM financial_settings WHERE fiscal_year = ?
        ");
        $stmt->bind_param("i", $fiscalYear);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        
        return $row ?: null;
    }

    /**
     * Get the current fiscal year's settings (defaults to current year)
     * @return array Opening balances with defaults if not set
     */
    public function getCurrentSettings(): array {
        $currentYear = (int)date('Y');
        $settings = $this->getByFiscalYear($currentYear);
        
        if (!$settings) {
            // Return defaults if no settings exist
            return [
                'fiscal_year' => $currentYear,
                'opening_cash_balance' => 0.00,
                'opening_bank_balance' => 0.00,
                'effective_date' => date('Y-01-01'),
                'notes' => null,
                'exists' => false
            ];
        }
        
        $settings['exists'] = true;
        return $settings;
    }

    /**
     * Get all fiscal year settings
     * @return array List of all settings records
     */
    public function findAll(): array {
        $result = $this->mysqli->query("
            SELECT * FROM financial_settings ORDER BY fiscal_year DESC
        ");
        
        $rows = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
        }
        return $rows;
    }

    /**
     * Create or update settings for a fiscal year
     * @param array $data Settings data
     * @return bool Success status
     */
    public function save(array $data): bool {
        $fiscalYear = (int)$data['fiscal_year'];
        $openingCash = (float)($data['opening_cash_balance'] ?? 0);
        $openingBank = (float)($data['opening_bank_balance'] ?? 0);
        $effectiveDate = $data['effective_date'] ?? date('Y-01-01');
        $notes = $data['notes'] ?? null;
        $createdBy = $data['created_by'] ?? null;

        // Check if record exists
        $existing = $this->getByFiscalYear($fiscalYear);
        
        if ($existing) {
            // Update
            $stmt = $this->mysqli->prepare("
                UPDATE financial_settings 
                SET opening_cash_balance = ?,
                    opening_bank_balance = ?,
                    effective_date = ?,
                    notes = ?
                WHERE fiscal_year = ?
            ");
            $stmt->bind_param("ddssi", $openingCash, $openingBank, $effectiveDate, $notes, $fiscalYear);
        } else {
            // Insert
            $stmt = $this->mysqli->prepare("
                INSERT INTO financial_settings 
                (fiscal_year, opening_cash_balance, opening_bank_balance, effective_date, notes, created_by)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->bind_param("iddssi", $fiscalYear, $openingCash, $openingBank, $effectiveDate, $notes, $createdBy);
        }
        
        $success = $stmt->execute();
        $stmt->close();
        
        return $success;
    }

    /**
     * Calculate closing balances for a fiscal year
     * @param int $fiscalYear The fiscal year
     * @param mysqli $mysqli Database connection
     * @return array Closing balances
     */
    public function calculateClosingBalances(int $fiscalYear): array {
        $settings = $this->getByFiscalYear($fiscalYear);
        
        $cashBalance = (float)($settings['opening_cash_balance'] ?? 0);
        $bankBalance = (float)($settings['opening_bank_balance'] ?? 0);
        
        // Get totals from deposits (IN)
        $depositSql = "
            SELECT 
                SUM(CASE WHEN payment_method = 'cash' THEN total ELSE 0 END) as cash_in,
                SUM(CASE WHEN payment_method IN ('bank', 'cheque') THEN total ELSE 0 END) as bank_in
            FROM (
                SELECT payment_method,
                    COALESCE(geran_kerajaan, 0) + 
                    COALESCE(sumbangan_derma, 0) + 
                    COALESCE(tabung_masjid, 0) + 
                    COALESCE(kutipan_jumaat_sadak, 0) + 
                    COALESCE(kutipan_aidilfitri_aidiladha, 0) + 
                    COALESCE(sewa_peralatan_masjid, 0) + 
                    COALESCE(hibah_faedah_bank, 0) + 
                    COALESCE(faedah_simpanan_tetap, 0) + 
                    COALESCE(sewa_rumah_kedai_tadika_menara, 0) + 
                    COALESCE(lain_lain_terimaan, 0) as total
                FROM financial_deposit_accounts
                WHERE YEAR(tx_date) = ?
            ) as deposits
        ";
        
        $stmt = $this->mysqli->prepare($depositSql);
        $stmt->bind_param("i", $fiscalYear);
        $stmt->execute();
        $result = $stmt->get_result();
        $deposits = $result->fetch_assoc();
        $stmt->close();
        
        // Get totals from payments (OUT)
        $paymentSql = "
            SELECT 
                SUM(CASE WHEN payment_method = 'cash' THEN total ELSE 0 END) as cash_out,
                SUM(CASE WHEN payment_method IN ('bank', 'cheque') THEN total ELSE 0 END) as bank_out
            FROM (
                SELECT payment_method,
                    COALESCE(perayaan_islam, 0) + 
                    COALESCE(pengimarahan_aktiviti_masjid, 0) + 
                    COALESCE(penyelenggaraan_masjid, 0) + 
                    COALESCE(keperluan_kelengkapan_masjid, 0) + 
                    COALESCE(gaji_upah_saguhati_elaun, 0) + 
                    COALESCE(sumbangan_derma, 0) + 
                    COALESCE(mesyuarat_jamuan, 0) + 
                    COALESCE(utiliti, 0) + 
                    COALESCE(alat_tulis_percetakan, 0) + 
                    COALESCE(pengangkutan_perjalanan, 0) + 
                    COALESCE(caj_bank, 0) + 
                    COALESCE(lain_lain_perbelanjaan, 0) as total
                FROM financial_payment_accounts
                WHERE YEAR(tx_date) = ?
            ) as payments
        ";
        
        $stmt = $this->mysqli->prepare($paymentSql);
        $stmt->bind_param("i", $fiscalYear);
        $stmt->execute();
        $result = $stmt->get_result();
        $payments = $result->fetch_assoc();
        $stmt->close();
        
        $cashBalance += (float)($deposits['cash_in'] ?? 0);
        $cashBalance -= (float)($payments['cash_out'] ?? 0);
        
        $bankBalance += (float)($deposits['bank_in'] ?? 0);
        $bankBalance -= (float)($payments['bank_out'] ?? 0);
        
        return [
            'opening_cash' => (float)($settings['opening_cash_balance'] ?? 0),
            'opening_bank' => (float)($settings['opening_bank_balance'] ?? 0),
            'total_cash_in' => (float)($deposits['cash_in'] ?? 0),
            'total_bank_in' => (float)($deposits['bank_in'] ?? 0),
            'total_cash_out' => (float)($payments['cash_out'] ?? 0),
            'total_bank_out' => (float)($payments['bank_out'] ?? 0),
            'closing_cash' => $cashBalance,
            'closing_bank' => $bankBalance,
            'total_balance' => $cashBalance + $bankBalance,
        ];
    }
}

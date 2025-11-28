<?php
/**
 * FinancialController - Handles all financial module admin operations
 */

// Include repositories
$ROOT = dirname(__DIR__, 4);
require_once $ROOT . '/features/financial/shared/lib/PaymentAccountRepository.php';
require_once $ROOT . '/features/financial/shared/lib/DepositAccountRepository.php';

class FinancialController {
    private mysqli $mysqli;
    private PaymentAccountRepository $paymentRepo;
    private DepositAccountRepository $depositRepo;

    public function __construct(mysqli $mysqli) {
        $this->mysqli = $mysqli;
        $this->paymentRepo = new PaymentAccountRepository($mysqli);
        $this->depositRepo = new DepositAccountRepository($mysqli);
    }

    public function index(): array {
        return [
            'title' => 'Financial Management',
            'data' => []
        ];
    }

    // ==================== PAYMENT ACCOUNT METHODS ====================

    /**
     * List all payment records
     */
    public function paymentAccount(): array {
        $payments = $this->paymentRepo->findAll();
        return [
            'title' => 'Akaun Bayaran',
            'payments' => $payments,
            'categoryColumns' => PaymentAccountRepository::CATEGORY_COLUMNS,
            'categoryLabels' => PaymentAccountRepository::CATEGORY_LABELS,
        ];
    }

    /**
     * Show add payment form
     */
    public function addPayment(): array {
        return [
            'title' => 'Add Payment',
            'record' => null,
            'categoryColumns' => PaymentAccountRepository::CATEGORY_COLUMNS,
            'categoryLabels' => PaymentAccountRepository::CATEGORY_LABELS,
            'errors' => [],
            'old' => [],
        ];
    }

    /**
     * Store a new payment record
     */
    public function storePayment(array $postData): array {
        $errors = $this->validatePaymentData($postData);

        if (!empty($errors)) {
            return [
                'success' => false,
                'errors' => $errors,
                'old' => $postData,
            ];
        }

        $this->paymentRepo->create($postData);
        return ['success' => true];
    }

    /**
     * Show edit payment form
     */
    public function editPayment(int $id): array {
        $record = $this->paymentRepo->findById($id);
        if (!$record) {
            return [
                'title' => 'Edit Payment',
                'record' => null,
                'errors' => ['Record not found.'],
                'categoryColumns' => PaymentAccountRepository::CATEGORY_COLUMNS,
                'categoryLabels' => PaymentAccountRepository::CATEGORY_LABELS,
                'old' => [],
            ];
        }

        return [
            'title' => 'Edit Payment',
            'record' => $record,
            'categoryColumns' => PaymentAccountRepository::CATEGORY_COLUMNS,
            'categoryLabels' => PaymentAccountRepository::CATEGORY_LABELS,
            'errors' => [],
            'old' => [],
        ];
    }

    /**
     * Update an existing payment record
     */
    public function updatePayment(int $id, array $postData): array {
        $errors = $this->validatePaymentData($postData);

        if (!empty($errors)) {
            return [
                'success' => false,
                'errors' => $errors,
                'old' => $postData,
            ];
        }

        $this->paymentRepo->update($id, $postData);
        return ['success' => true];
    }

    /**
     * Delete a payment record
     */
    public function deletePayment(int $id): array {
        $record = $this->paymentRepo->findById($id);
        if (!$record) {
            return ['success' => false, 'error' => 'Record not found.'];
        }

        $this->paymentRepo->delete($id);
        return ['success' => true];
    }

    /**
     * Validate payment form data
     */
    private function validatePaymentData(array $data): array {
        $errors = [];

        if (empty($data['tx_date'])) {
            $errors[] = 'Date (Tarikh) is required.';
        }

        if (empty($data['description'])) {
            $errors[] = 'Description (Butiran) is required.';
        }

        // Check that at least one category has a positive amount
        $hasAmount = false;
        foreach (PaymentAccountRepository::CATEGORY_COLUMNS as $col) {
            if (!empty($data[$col]) && is_numeric($data[$col]) && $data[$col] > 0) {
                $hasAmount = true;
                break;
            }
        }

        if (!$hasAmount) {
            $errors[] = 'At least one category must have an amount greater than 0.';
        }

        return $errors;
    }

    // ==================== DEPOSIT ACCOUNT METHODS ====================

    /**
     * List all deposit records
     */
    public function depositAccount(): array {
        $deposits = $this->depositRepo->findAll();
        return [
            'title' => 'Akaun Terimaan',
            'deposits' => $deposits,
            'categoryColumns' => DepositAccountRepository::CATEGORY_COLUMNS,
            'categoryLabels' => DepositAccountRepository::CATEGORY_LABELS,
        ];
    }

    /**
     * Show add deposit form
     */
    public function addDeposit(): array {
        return [
            'title' => 'Add Deposit',
            'record' => null,
            'categoryColumns' => DepositAccountRepository::CATEGORY_COLUMNS,
            'categoryLabels' => DepositAccountRepository::CATEGORY_LABELS,
            'errors' => [],
            'old' => [],
        ];
    }

    /**
     * Store a new deposit record
     */
    public function storeDeposit(array $postData): array {
        $errors = $this->validateDepositData($postData);

        if (!empty($errors)) {
            return [
                'success' => false,
                'errors' => $errors,
                'old' => $postData,
            ];
        }

        $this->depositRepo->create($postData);
        return ['success' => true];
    }

    /**
     * Show edit deposit form
     */
    public function editDeposit(int $id): array {
        $record = $this->depositRepo->findById($id);
        if (!$record) {
            return [
                'title' => 'Edit Deposit',
                'record' => null,
                'errors' => ['Record not found.'],
                'categoryColumns' => DepositAccountRepository::CATEGORY_COLUMNS,
                'categoryLabels' => DepositAccountRepository::CATEGORY_LABELS,
                'old' => [],
            ];
        }

        return [
            'title' => 'Edit Deposit',
            'record' => $record,
            'categoryColumns' => DepositAccountRepository::CATEGORY_COLUMNS,
            'categoryLabels' => DepositAccountRepository::CATEGORY_LABELS,
            'errors' => [],
            'old' => [],
        ];
    }

    /**
     * Update an existing deposit record
     */
    public function updateDeposit(int $id, array $postData): array {
        $errors = $this->validateDepositData($postData);

        if (!empty($errors)) {
            return [
                'success' => false,
                'errors' => $errors,
                'old' => $postData,
            ];
        }

        $this->depositRepo->update($id, $postData);
        return ['success' => true];
    }

    /**
     * Delete a deposit record
     */
    public function deleteDeposit(int $id): array {
        $record = $this->depositRepo->findById($id);
        if (!$record) {
            return ['success' => false, 'error' => 'Record not found.'];
        }

        $this->depositRepo->delete($id);
        return ['success' => true];
    }

    /**
     * Validate deposit form data
     */
    private function validateDepositData(array $data): array {
        $errors = [];

        if (empty($data['tx_date'])) {
            $errors[] = 'Date (Tarikh) is required.';
        }

        if (empty($data['description'])) {
            $errors[] = 'Description (Butiran) is required.';
        }

        // Check that at least one category has a positive amount
        $hasAmount = false;
        foreach (DepositAccountRepository::CATEGORY_COLUMNS as $col) {
            if (!empty($data[$col]) && is_numeric($data[$col]) && $data[$col] > 0) {
                $hasAmount = true;
                break;
            }
        }

        if (!$hasAmount) {
            $errors[] = 'At least one category must have an amount greater than 0.';
        }

        return $errors;
    }

    /**
     * Get the payment repository (for external access if needed)
     */
    public function getPaymentRepository(): PaymentAccountRepository {
        return $this->paymentRepo;
    }

    /**
     * Get the deposit repository (for external access if needed)
     */
    public function getDepositRepository(): DepositAccountRepository {
        return $this->depositRepo;
    }
}

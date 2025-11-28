<?php

class FinancialController {
    private $mysqli;

    public function __construct($mysqli) {
        $this->mysqli = $mysqli;
    }

    public function index() {
        return [
            'title' => 'Financial Management',
            'data' => []
        ];
    }

    public function paymentAccount() {
        return [
            'title' => 'Akaun Bayaran',
            'data' => []
        ];
    }

    public function depositAccount() {
        return [
            'title' => 'Akaun Terimaan',
            'data' => []
        ];
    }

    public function addPayment() {
        return [
            'title' => 'Add Payment',
            'data' => []
        ];
    }

    public function addDeposit() {
        return [
            'title' => 'Add Deposit',
            'data' => []
        ];
    }
}

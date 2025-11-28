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
}

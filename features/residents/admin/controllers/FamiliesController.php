<?php

require_once __DIR__ . '/../../shared/lib/UsersModel.php';

class FamiliesController {
    private $model;

    public function __construct($mysqli) {
        $this->model = new UsersModel($mysqli);
    }

    public function index() {
        $families = $this->model->getFamilies();
        return ['families' => $families];
    }
}

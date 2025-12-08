<?php

require_once __DIR__ . '/../../../../shared/controllers/BaseController.php';

class DependentController extends BaseController {
    private $mysqli;

    public function __construct($mysqli) {
        parent::__construct();
        $this->mysqli = $mysqli;
    }

    public function form() {
        $this->requireAuth();
        $userId = $this->currentUser['id'];
        $id = $_GET['id'] ?? null;
        
        $dependent = [];
        
        if ($id) {
            $stmt = $this->mysqli->prepare('SELECT * FROM dependent WHERE id=? AND user_id=? LIMIT 1');
            $stmt->bind_param('ii', $id, $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            $dependent = $result->fetch_assoc();
            $stmt->close();
            
            if (!$dependent) {
                $this->notFound();
            }
        }

        return ['dependent' => $dependent];
    }

    public function save() {
        $this->requireAuth();
        $userId = $this->currentUser['id'];
        
        $id = $_POST['id'] ?? null;
        $name = trim($_POST['name'] ?? '');
        $relationship = trim($_POST['relationship'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone_number = trim($_POST['phone_number'] ?? '');
        $address = trim($_POST['address'] ?? '');

        if (empty($name)) {
            return ['error' => 'Name is required.', 'dependent' => $_POST];
        }

        if ($id) {
            // Update
            // Verify ownership first
            $check = $this->mysqli->prepare('SELECT id FROM dependent WHERE id=? AND user_id=?');
            $check->bind_param('ii', $id, $userId);
            $check->execute();
            if (!$check->get_result()->fetch_assoc()) {
                $this->forbidden();
            }
            $check->close();

            $stmt = $this->mysqli->prepare('UPDATE dependent SET name=?, relationship=?, email=?, phone_number=?, address=? WHERE id=?');
            $stmt->bind_param('sssssi', $name, $relationship, $email, $phone_number, $address, $id);
        } else {
            // Insert
            $stmt = $this->mysqli->prepare('INSERT INTO dependent (user_id, name, relationship, email, phone_number, address) VALUES (?, ?, ?, ?, ?, ?)');
            $stmt->bind_param('isssss', $userId, $name, $relationship, $email, $phone_number, $address);
        }

        if ($stmt->execute()) {
            $stmt->close();
            return ['success' => true];
        } else {
            $error = $stmt->error;
            $stmt->close();
            return ['error' => 'Save failed: ' . $error, 'dependent' => $_POST];
        }
    }

    public function delete() {
        $this->requireAuth();
        $userId = $this->currentUser['id'];
        $id = $_POST['id'] ?? null;

        if ($id) {
            $stmt = $this->mysqli->prepare('DELETE FROM dependent WHERE id=? AND user_id=?');
            $stmt->bind_param('ii', $id, $userId);
            $stmt->execute();
            $stmt->close();
        }
        
        return ['success' => true];
    }
}

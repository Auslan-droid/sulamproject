<?php

require_once __DIR__ . '/../../../shared/controllers/BaseController.php';

class ProfileController extends BaseController {
    private $mysqli;

    public function __construct($mysqli) {
        parent::__construct();
        $this->mysqli = $mysqli;
    }

    public function edit() {
        $this->requireAuth();
        $userId = $this->currentUser['id'];

        $stmt = $this->mysqli->prepare('SELECT id, name, username, email, phone_number, address, marital_status, income FROM users WHERE id=? LIMIT 1');
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        if (!$user) {
            $this->notFound();
        }

        return ['user' => $user];
    }

    public function update() {
        $this->requireAuth();
        $userId = $this->currentUser['id'];

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone_number = trim($_POST['phone_number'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $marital_status = trim($_POST['marital_status'] ?? '');
        $income = isset($_POST['income']) && $_POST['income'] !== '' ? $_POST['income'] : null;

        // Basic validation
        if (empty($name) || empty($email)) {
            // Return current POST data as user data to repopulate form
            return ['error' => 'Name and Email are required.', 'user' => $_POST];
        }

        // Update
        $stmt = $this->mysqli->prepare('UPDATE users SET name=?, email=?, phone_number=?, address=?, marital_status=?, income=? WHERE id=?');
        $stmt->bind_param('sssssdi', $name, $email, $phone_number, $address, $marital_status, $income, $userId);
        
        if ($stmt->execute()) {
            $stmt->close();
            // Refresh user data from DB to ensure we show what's saved
            $data = $this->edit();
            $data['success'] = 'Profile updated successfully.';
            return $data;
        } else {
            $error = $stmt->error;
            $stmt->close();
            return ['error' => 'Update failed: ' . $error, 'user' => $_POST];
        }
    }
}

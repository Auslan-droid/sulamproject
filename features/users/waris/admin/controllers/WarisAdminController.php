<?php

class WarisAdminController {
    private $mysqli;

    public function __construct($mysqli) {
        $this->mysqli = $mysqli;
    }

    public function showUserWaris($userId) {
        // 1. Fetch User Details
        $stmt = $this->mysqli->prepare("SELECT id, name, username, email, phone_number FROM users WHERE id = ?");
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $userResult = $stmt->get_result();
        $user = $userResult->fetch_assoc();

        if (!$user) {
            return ['error' => 'User not found'];
        }

        // 2. Fetch Dependents List for this User
        $stmtDependents = $this->mysqli->prepare("SELECT * FROM dependent WHERE user_id = ? ORDER BY created_at DESC");
        $stmtDependents->bind_param('i', $userId);
        $stmtDependents->execute();
        $dependentsResult = $stmtDependents->get_result();
        $dependentsList = $dependentsResult->fetch_all(MYSQLI_ASSOC);

        // 3. Fetch Next of Kin List for this User
        $stmtNextOfKin = $this->mysqli->prepare("SELECT * FROM next_of_kin WHERE user_id = ? ORDER BY created_at DESC");
        $stmtNextOfKin->bind_param('i', $userId);
        $stmtNextOfKin->execute();
        $nextOfKinResult = $stmtNextOfKin->get_result();
        $nextOfKinList = $nextOfKinResult->fetch_all(MYSQLI_ASSOC);

        return [
            'targetUser' => $user,
            'dependentsList' => $dependentsList,
            'nextOfKinList' => $nextOfKinList
        ];
    }
}

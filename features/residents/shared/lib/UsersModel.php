<?php

class UsersModel {
    private $db;

    public function __construct($mysqli) {
        $this->db = $mysqli;
    }

    public function getUsers($role = null): array {
        $sql = "SELECT users.*, COUNT(dependent.id) as dependent_count 
                FROM users 
                LEFT JOIN dependent ON users.id = dependent.user_id";
        
        if ($role) {
            $sql .= " WHERE users.roles = ?";
        }
        
        $sql .= " GROUP BY users.id ORDER BY users.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        
        if ($role) {
            $stmt->bind_param('s', $role);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getFamilies(): array {
        // 1. Get all users (potential heads of families)
        $allUsers = $this->getUsers(); 

        if (!is_array($allUsers)) {
            return [];
        }

        // Filter out admins
        $users = array_filter($allUsers, function($user) {
            return isset($user['roles']) && $user['roles'] !== 'admin';
        });

        // 2. Get all dependents
        $sql = "SELECT * FROM dependent ORDER BY user_id, created_at";
        $result = $this->db->query($sql);
        $allDependents = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

        // 3. Group dependents by user_id
        $dependentsByUserId = [];
        foreach ($allDependents as $dep) {
            $dependentsByUserId[$dep['user_id']][] = $dep;
        }

        // 4. Attach dependents to users
        foreach ($users as &$user) {
            $user['dependents'] = $dependentsByUserId[$user['id']] ?? [];
        }
        unset($user); // Break the reference

        return array_values($users);
    }
}

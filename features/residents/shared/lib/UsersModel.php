<?php

class UsersModel {
    private $db;

    public function __construct($mysqli) {
        $this->db = $mysqli;
    }

    public function getUsers($role = null) {
        $sql = "SELECT users.*, COUNT(next_of_kin.id) as dependent_count 
                FROM users 
                LEFT JOIN next_of_kin ON users.id = next_of_kin.user_id";
        
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
}

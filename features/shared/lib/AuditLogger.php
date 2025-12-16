<?php
/**
 * AuditLogger - Comprehensive audit trail logging system
 * 
 * Tracks all create, update, delete, and restore actions on financial records
 * Stores full change history with user information and metadata
 */

class AuditLogger
{
    private $mysqli;
    
    public function __construct($mysqli)
    {
        $this->mysqli = $mysqli;
    }
    
    /**
     * Log a create action
     * 
     * @param string $tableName Name of the table (e.g., 'financial_payment_accounts')
     * @param int $recordId ID of the created record
     * @param int|null $userId User who created the record
     * @param array $newValues All values of the new record
     */
    public function logCreate($tableName, $recordId, $userId, $newValues)
    {
        $this->log($tableName, $recordId, 'create', $userId, null, null, $newValues);
    }
    
    /**
     * Log an update action
     * 
     * @param string $tableName Name of the table
     * @param int $recordId ID of the updated record
     * @param int|null $userId User who updated the record
     * @param array $oldValues Previous values before update
     * @param array $newValues New values after update
     */
    public function logUpdate($tableName, $recordId, $userId, $oldValues, $newValues)
    {
        // Calculate which fields changed
        $changedFields = [];
        foreach ($newValues as $field => $newValue) {
            $oldValue = $oldValues[$field] ?? null;
            if ($oldValue != $newValue) {
                $changedFields[] = $field;
            }
        }
        
        // Only log if there were actual changes
        if (empty($changedFields)) {
            return;
        }
        
        $this->log($tableName, $recordId, 'update', $userId, $changedFields, $oldValues, $newValues);
    }
    
    /**
     * Log a delete action (soft delete)
     * 
     * @param string $tableName Name of the table
     * @param int $recordId ID of the deleted record
     * @param int|null $userId User who deleted the record
     * @param array $oldValues Values before deletion
     */
    public function logDelete($tableName, $recordId, $userId, $oldValues)
    {
        $this->log($tableName, $recordId, 'delete', $userId, null, $oldValues, null);
    }
    
    /**
     * Log a restore action (undo soft delete)
     * 
     * @param string $tableName Name of the table
     * @param int $recordId ID of the restored record
     * @param int|null $userId User who restored the record
     */
    public function logRestore($tableName, $recordId, $userId)
    {
        $this->log($tableName, $recordId, 'restore', $userId, null, null, null);
    }
    
    /**
     * Core logging method
     * 
     * @param string $tableName
     * @param int $recordId
     * @param string $action 'create', 'update', 'delete', or 'restore'
     * @param int|null $userId
     * @param array|null $changedFields
     * @param array|null $oldValues
     * @param array|null $newValues
     */
    private function log($tableName, $recordId, $action, $userId, $changedFields, $oldValues, $newValues)
    {
        // Get user information (snapshot in case user is deleted later)
        $username = null;
        $userFullname = null;
        
        if ($userId) {
            $stmt = $this->mysqli->prepare("SELECT username, name FROM users WHERE id = ?");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $username = $row['username'];
                $userFullname = $row['name'];
            }
            $stmt->close();
        }
        
        // Get client metadata
        $ipAddress = $this->getClientIp();
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;
        
        // Prepare JSON data
        $changedFieldsJson = $changedFields ? json_encode($changedFields) : null;
        $oldValuesJson = $oldValues ? json_encode($oldValues, JSON_UNESCAPED_UNICODE) : null;
        $newValuesJson = $newValues ? json_encode($newValues, JSON_UNESCAPED_UNICODE) : null;
        
        // Insert audit log
        $stmt = $this->mysqli->prepare("
            INSERT INTO audit_logs (
                table_name, record_id, action, user_id, username, user_fullname,
                changed_fields, old_values, new_values, ip_address, user_agent
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->bind_param(
            "sisssssssss",
            $tableName,
            $recordId,
            $action,
            $userId,
            $username,
            $userFullname,
            $changedFieldsJson,
            $oldValuesJson,
            $newValuesJson,
            $ipAddress,
            $userAgent
        );
        
        $stmt->execute();
        $stmt->close();
    }
    
    /**
     * Get audit trail for a specific record
     * 
     * @param string $tableName
     * @param int $recordId
     * @return array Array of audit log entries
     */
    public function getAuditTrail($tableName, $recordId)
    {
        $stmt = $this->mysqli->prepare("
            SELECT 
                id, action, user_id, username, user_fullname,
                changed_fields, old_values, new_values,
                ip_address, created_at
            FROM audit_logs
            WHERE table_name = ? AND record_id = ?
            ORDER BY created_at DESC
        ");
        
        $stmt->bind_param("si", $tableName, $recordId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $logs = [];
        while ($row = $result->fetch_assoc()) {
            // Decode JSON fields
            $row['changed_fields'] = $row['changed_fields'] ? json_decode($row['changed_fields'], true) : null;
            $row['old_values'] = $row['old_values'] ? json_decode($row['old_values'], true) : null;
            $row['new_values'] = $row['new_values'] ? json_decode($row['new_values'], true) : null;
            $logs[] = $row;
        }
        
        $stmt->close();
        return $logs;
    }
    
    /**
     * Get the most recent action for a record (for tooltip display)
     * 
     * @param string $tableName
     * @param int $recordId
     * @param string $action 'create' or 'update'
     * @return array|null
     */
    public function getLastAction($tableName, $recordId, $action = 'create')
    {
        $stmt = $this->mysqli->prepare("
            SELECT username, user_fullname, created_at
            FROM audit_logs
            WHERE table_name = ? AND record_id = ? AND action = ?
            ORDER BY created_at DESC
            LIMIT 1
        ");
        
        $stmt->bind_param("sis", $tableName, $recordId, $action);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        
        return $row;
    }
    
    /**
     * Get client IP address (handles proxies)
     */
    private function getClientIp()
    {
        $ipKeys = [
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'
        ];
        
        foreach ($ipKeys as $key) {
            if (array_key_exists($key, $_SERVER)) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? null;
    }
}

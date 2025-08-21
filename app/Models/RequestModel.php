<?php
namespace App\Models;

use Database;

class RequestModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Gets all requests with associated user info.
     * @return array
     */
    public function getAllRequestsWithUsers() {
        try {
            $sql = "SELECT r.*, u.email as user_email 
                    FROM requests r 
                    JOIN users u ON r.user_id = u.id 
                    ORDER BY r.status ASC, r.created_at DESC";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            return [];
        }
    }

    /**
     * Updates the status of a request.
     * @param int $id The ID of the request.
     * @param string $status The new status (e.g., 'pending', 'completed', 'rejected').
     * @return bool
     */
    public function updateStatus($id, $status) {
        try {
            $stmt = $this->db->prepare("UPDATE requests SET status = ? WHERE id = ?");
            return $stmt->execute([$status, $id]);
        } catch (\PDOException $e) {
            return false;
        }
    }

    /**
     * Deletes a request by its ID.
     * @param int $id The ID of the request to delete.
     * @return bool
     */
    public function deleteById($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM requests WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (\PDOException $e) {
            return false;
        }
    }
}
<?php
namespace App\Models;

use Database;

class HomepageModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Gets all homepage sections, ordered by their display order.
     * @return array
     */
    public function getAllSections() {
        try {
            $stmt = $this->db->query("SELECT * FROM homepage_sections ORDER BY display_order ASC");
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            return [];
        }
    }
    
    /**
     * Updates the display order and active status of multiple sections.
     * @param array $sectionsData An array of section data, each containing id, is_active, and display_order.
     * @return bool
     */
    public function updateSections($sectionsData) {
        try {
            $this->db->beginTransaction();
            
            $stmt = $this->db->prepare("UPDATE homepage_sections SET is_active = ?, display_order = ? WHERE id = ?");
            
            foreach ($sectionsData as $id => $data) {
                $stmt->execute([
                    (int)($data['is_active'] ?? 0),
                    (int)$data['display_order'],
                    (int)$id
                ]);
            }
            
            $this->db->commit();
            return true;
        } catch (\PDOException $e) {
            $this->db->rollBack();
            return false;
        }
    }

    /**
     * Gets all ACTIVE homepage sections for the frontend, ordered by display order.
     * @return array
     */
    public function getActiveSections() {
        try {
            $stmt = $this->db->query("SELECT * FROM homepage_sections WHERE is_active = 1 ORDER BY display_order ASC");
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            return [];
        }
    }
}
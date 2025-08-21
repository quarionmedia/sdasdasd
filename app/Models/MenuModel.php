<?php
namespace App\Models;
use Database;
class MenuModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Gets all active menu items for the public site header.
     * @return array
     */
    public function getAllActive() {
        try {
            $stmt = $this->db->query("SELECT * FROM menu_items WHERE is_active = 1 ORDER BY menu_order ASC");
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            return [];
        }
    }

    /**
     * Gets ALL menu items for the admin panel manager.
     * @return array
     */
    public function getAllForAdmin() {
        try {
            $stmt = $this->db->query("SELECT * FROM menu_items ORDER BY menu_order ASC");
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            return [];
        }
    }

    /**
     * Creates a new menu item in the database.
     * @param array $data The data for the new menu item.
     * @return bool True on success, false on failure.
     */
    public function create($data) {
        try {
            $sql = "INSERT INTO menu_items (title, url, menu_order, is_active) VALUES (:title, :url, :menu_order, :is_active)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':title' => $data['title'],
                ':url' => $data['url'],
                ':menu_order' => $data['menu_order'] ?? 0,
                ':is_active' => $data['is_active'] ?? 1
            ]);
        } catch (\PDOException $e) {
            // Return false on error
            return false;
        }
    }

    /**
     * Deletes a menu item by its ID.
     * @param int $id The ID of the menu item to delete.
     * @return bool True on success, false on failure.
     */
    public function deleteById($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM menu_items WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function findById($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM menu_items WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function update($id, $data) {
        try {
            $sql = "UPDATE menu_items SET title = :title, url = :url, menu_order = :menu_order, is_active = :is_active WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':id' => $id,
                ':title' => $data['title'],
                ':url' => $data['url'],
                ':menu_order' => $data['menu_order'],
                ':is_active' => $data['is_active']
            ]);
        } catch (\PDOException $e) {
            return false;
        }
    }
}
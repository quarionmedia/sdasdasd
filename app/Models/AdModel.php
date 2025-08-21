<?php
// File: app/Models/AdModel.php

namespace App\Models;
use Database;

class AdModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Gets all video ads from the database.
     * @return array
     */
    public function getAllAds() {
        try {
            $stmt = $this->db->query("SELECT * FROM video_ads ORDER BY type, name ASC");
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            return [];
        }
    }

    /**
     * Creates a new ad record in the database.
     * @param array $data The ad data.
     * @return bool
     */
    public function createAd($data) {
        try {
            $sql = "INSERT INTO video_ads (name, type, vast_url, offset_time, is_active) 
                    VALUES (:name, :type, :vast_url, :offset_time, 1)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($data);
        } catch (\PDOException $e) {
            error_log("AdModel Create Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Deletes an ad by its ID.
     * @param int $id
     * @return bool
     */
    public function deleteAd($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM video_ads WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (\PDOException $e) {
            error_log("AdModel Delete Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Toggles the active status of an ad.
     * @param int $id
     * @return bool
     */
    public function toggleStatus($id) {
        try {
            // This query flips the is_active value from 0 to 1, or 1 to 0.
            $stmt = $this->db->prepare("UPDATE video_ads SET is_active = 1 - is_active WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (\PDOException $e) {
            error_log("AdModel Toggle Error: " . $e->getMessage());
            return false;
        }
    }
}
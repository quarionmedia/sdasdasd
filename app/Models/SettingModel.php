<?php
namespace App\Models;
use Database;
class SettingModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Fetches all settings from the database and returns them as an associative array.
     * @return array
     */
    public function getAllSettings() {
        try {
            $stmt = $this->db->query("SELECT * FROM settings");
            $settings = [];
            foreach ($stmt->fetchAll() as $setting) {
                $settings[$setting['setting_name']] = $setting['setting_value'];
            }
            return $settings;
        } catch (\PDOException $e) {
            // In case of an error, return an empty array
            return [];
        }
    }

    /**
     * Updates multiple settings in the database.
     * @param array $data Associative array of setting_name => setting_value
     * @return bool True on success, false on failure.
     */
    public function updateSettings($data) {
        try {
            $stmt = $this->db->prepare("UPDATE settings SET setting_value = ? WHERE setting_name = ?");
            
            // Loop through each setting provided and update it
            foreach ($data as $name => $value) {
                $stmt->execute([$value, $name]);
            }
            return true;
        } catch (\PDOException $e) {
            die("Database Error: " . $e->getMessage());
            return false;
        }
    }

// Add this new function inside your SettingModel.php class

/**
 * Gets all ad-related settings from the database.
 * @return array
 */
public function getAdsSettings() {
    $ads = [];
    $settings = $this->getAllSettings();
    // These keys match the 'name' attributes in your ads-settings.php form
    $adKeys = ['preroll_ad_vast', 'midroll_ad_vast', 'postroll_ad_vast', 'pauseroll_ad_vast', 'midroll_offset'];
    foreach ($adKeys as $key) {
        if (isset($settings[$key]) && !empty($settings[$key])) {
            $ads[$key] = $settings[$key];
        }
    }
    return $ads;
}
}
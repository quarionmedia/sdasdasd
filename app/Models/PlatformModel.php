<?php
namespace App\Models;

use Database;

class PlatformModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Fetches all platforms from the database.
     * @return array
     */
    public function getAll() {
        try {
            $stmt = $this->db->query("SELECT * FROM platforms ORDER BY name ASC");
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            return [];
        }
    }

    /**
     * Finds a single platform by its ID.
     * @param int $id The ID of the platform.
     * @return mixed The platform data or false if not found.
     */
    public function findById($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM platforms WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (\PDOException $e) {
            return false;
        }
    }

    /**
     * Creates a new platform.
     * @param array $data The data for the new platform.
     * @return bool True on success, false on failure.
     */
    public function create($data) {
        try {
            $stmt = $this->db->prepare("INSERT INTO platforms (name, slug, logo_path, background_path) VALUES (?, ?, ?, ?)");
            return $stmt->execute([
                $data['name'],
                $data['slug'],
                $data['logo_path'] ?? null,
                $data['background_path'] ?? null
            ]);
        } catch (\PDOException $e) {
            return false;
        }
    }

    /**
     * Updates an existing platform.
     * @param int $id The ID of the platform to update.
     * @param array $data The new data for the platform.
     * @return bool True on success, false on failure.
     */
    public function update($id, $data) {
        try {
            $stmt = $this->db->prepare("UPDATE platforms SET name = ?, slug = ?, logo_path = ?, background_path = ? WHERE id = ?");
            return $stmt->execute([
                $data['name'],
                $data['slug'],
                $data['logo_path'],
                $data['background_path'],
                $id
            ]);
        } catch (\PDOException $e) {
            return false;
        }
    }

    /**
     * Deletes a platform by its ID.
     * @param int $id The ID of the platform to delete.
     * @return bool True on success, false on failure.
     */
    public function deleteById($id) {
        try {
            // Also delete associations
            $stmt = $this->db->prepare("DELETE FROM movie_platforms WHERE platform_id = ?");
            $stmt->execute([$id]);
            $stmt = $this->db->prepare("DELETE FROM tv_show_platforms WHERE platform_id = ?");
            $stmt->execute([$id]);

            // Delete the platform itself
            $stmt = $this->db->prepare("DELETE FROM platforms WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (\PDOException $e) {
            return false;
        }
    }

    /**
     * Fetches all active platforms for the frontend.
     * @return array
     */
    public function getAllActive() {
        try {
            $stmt = $this->db->query("SELECT * FROM platforms WHERE status = 1 ORDER BY name ASC");
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            return [];
        }
    }

    /**
     * Finds a single platform by its unique slug.
     * @param string $slug The slug of the platform.
     * @return mixed The platform data or false if not found.
     */
    public function findBySlug($slug) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM platforms WHERE slug = ?");
            $stmt->execute([$slug]);
            return $stmt->fetch();
        } catch (\PDOException $e) {
            return false;
        }
    }
}
<?php
namespace App\Models;

use Database;

class ContentNetworkModel {
    private $db;

    /**
     * Constructor to get the database instance.
     */
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Fetches all content networks from the database.
     * @return array
     */
    public function getAll() {
        try {
            $stmt = $this->db->query("SELECT * FROM content_networks ORDER BY name ASC");
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            return [];
        }
    }

    /**
     * Finds a single content network by its ID.
     * @param int $id The ID of the network.
     * @return mixed The network data or false if not found.
     */
    public function findById($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM content_networks WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (\PDOException $e) {
            return false;
        }
    }

    /**
     * Creates a new content network.
     * @param array $data The data for the new network (name, logo_path).
     * @return bool True on success, false on failure.
     */
    public function create($data) {
        try {
            $stmt = $this->db->prepare("INSERT INTO content_networks (name, logo_path) VALUES (?, ?)");
            return $stmt->execute([
                $data['name'],
                $data['logo_path'] ?? null
            ]);
        } catch (\PDOException $e) {
            return false;
        }
    }

    /**
     * Updates an existing content network.
     * @param int $id The ID of the network to update.
     * @param array $data The new data for the network.
     * @return bool True on success, false on failure.
     */
    public function update($id, $data) {
        try {
            $stmt = $this->db->prepare("UPDATE content_networks SET name = ?, logo_path = ? WHERE id = ?");
            return $stmt->execute([
                $data['name'],
                $data['logo_path'] ?? null,
                $id
            ]);
        } catch (\PDOException $e) {
            return false;
        }
    }

    /**
     * Deletes a content network by its ID, but only if it's not in use.
     * @param int $id The ID of the network to delete.
     * @return bool True on success, false if in use or on failure.
     */
    public function deleteById($id) {
        try {
            // Check if the network is being used by any movies.
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM movie_networks WHERE network_id = ?");
            $stmt->execute([$id]);
            if ($stmt->fetchColumn() > 0) {
                return false; // Network is in use, cannot delete.
            }

            // Check if the network is being used by any tv shows.
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM tv_show_networks WHERE network_id = ?");
            $stmt->execute([$id]);
            if ($stmt->fetchColumn() > 0) {
                return false; // Network is in use, cannot delete.
            }

            // If not in use, proceed with deletion.
            $stmt = $this->db->prepare("DELETE FROM content_networks WHERE id = ?");
            return $stmt->execute([$id]);

        } catch (\PDOException $e) {
            return false;
        }
    }
}
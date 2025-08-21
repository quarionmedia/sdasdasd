<?php
namespace App\Models;

use Database;

class ReportModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Gets all reports with associated user and content (movie/tv) info.
     * @return array
     */
    public function getAllReportsWithDetails() {
        try {
            $sql = "SELECT 
                        r.id, r.reason, r.status, r.created_at,
                        u.email as user_email,
                        COALESCE(m.title, tv.title) as content_title,
                        COALESCE(m.poster_path, tv.poster_path) as content_poster,
                        CASE 
                            WHEN r.movie_id IS NOT NULL THEN 'Movie'
                            WHEN r.episode_id IS NOT NULL THEN 'TV Show'
                            ELSE 'N/A'
                        END as content_type
                    FROM reports r
                    JOIN users u ON r.user_id = u.id
                    LEFT JOIN movies m ON r.movie_id = m.id
                    LEFT JOIN episodes e ON r.episode_id = e.id
                    LEFT JOIN seasons s ON e.season_id = s.id
                    LEFT JOIN tv_shows tv ON s.tv_show_id = tv.id
                    ORDER BY r.status ASC, r.created_at DESC";
            
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            return [];
        }
    }

    /**
     * Updates the status of a report.
     * @param int $id The ID of the report.
     * @param string $status The new status (e.g., 'pending', 'resolved').
     * @return bool
     */
    public function updateStatus($id, $status) {
        try {
            $stmt = $this->db->prepare("UPDATE reports SET status = ? WHERE id = ?");
            return $stmt->execute([$status, $id]);
        } catch (\PDOException $e) {
            return false;
        }
    }

    /**
     * Deletes a report by its ID.
     * @param int $id The ID of the report to delete.
     * @return bool
     */
    public function deleteById($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM reports WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (\PDOException $e) {
            return false;
        }
    }
}
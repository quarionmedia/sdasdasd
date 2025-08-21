<?php
namespace App\Models;
use Database;
class SeasonModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // Finds a season by TMDb ID, or creates it if it doesn't exist
    public function findOrCreate($tvShowId, $seasonData) {
        $stmt = $this->db->prepare("SELECT id FROM seasons WHERE tmdb_season_id = ?");
        $stmt->execute([$seasonData['id']]);
        $season = $stmt->fetch();

        if ($season) {
            return $season['id'];
        } else {
            $stmt = $this->db->prepare(
                "INSERT INTO seasons (tv_show_id, tmdb_season_id, season_number, name, overview, poster_path, air_date, episode_count) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
            );
            $stmt->execute([
                $tvShowId,
                $seasonData['id'],
                $seasonData['season_number'],
                $seasonData['name'],
                $seasonData['overview'],
                $seasonData['poster_path'],
                !empty($seasonData['air_date']) ? $seasonData['air_date'] : null,
                $seasonData['episode_count'] ?? 0
            ]);
            return $this->db->lastInsertId();
        }
    }

    // Method to find all seasons for a given TV Show ID
    public function findAllByTvShowId($tvShowId) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM seasons WHERE tv_show_id = ? ORDER BY season_number ASC");
            $stmt->execute([$tvShowId]);
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            return [];
        }
    }

    // Method to find a single season by its primary ID
    public function findById($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM seasons WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (\PDOException $e) {
            return false;
        }
    }

    // NEW METHOD: Manually creates a new season in the database
    public function createManual($data) {
        try {
            $sql = "INSERT INTO seasons (tv_show_id, season_number, name) 
                    VALUES (:tv_show_id, :season_number, :name)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':tv_show_id' => $data['tv_show_id'],
                ':season_number' => $data['season_number'],
                ':name' => $data['name']
            ]);
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function deleteById($id) {
        try {
            $this->db->beginTransaction();

            // Önce bu sezona ait tüm bölümleri sil
            $stmt = $this->db->prepare("DELETE FROM episodes WHERE season_id = ?");
            $stmt->execute([$id]);

            // Sonra sezonun kendisini sil
            $stmt = $this->db->prepare("DELETE FROM seasons WHERE id = ?");
            $stmt->execute([$id]);

            $this->db->commit();
            return true;
        } catch (\PDOException $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function update($id, $data) {
        try {
            $sql = "UPDATE seasons SET season_number = :season_number, name = :name WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':id' => $id,
                ':season_number' => $data['season_number'],
                ':name' => $data['name']
            ]);
        } catch (\PDOException $e) {
            return false;
        }
    }

    /**
 * Finds all episodes for a given Season ID.
 * @param int $seasonId The ID of the season.
 * @return array
 */
public function findEpisodesBySeasonId($seasonId) {
    try {
        $stmt = $this->db->prepare("SELECT * FROM episodes WHERE season_id = ? ORDER BY episode_number ASC");
        $stmt->execute([$seasonId]);
        return $stmt->fetchAll();
    } catch (\PDOException $e) {
        // Hata durumunda boş bir dizi döndür
        return [];
    }
}

// Add this new function inside your SeasonModel.php class

/**
 * Finds the first season of a TV show, ordered by season number.
 * @param int $tvShowId The ID of the TV show.
 * @return mixed The season data or false if not found.
 */
public function findFirstByTvShowId($tvShowId) {
    try {
        $stmt = $this->db->prepare("SELECT * FROM seasons WHERE tv_show_id = ? ORDER BY season_number ASC LIMIT 1");
        $stmt->execute([$tvShowId]);
        return $stmt->fetch();
    } catch (\PDOException $e) {
        return false;
    }
}
}
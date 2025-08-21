<?php
namespace App\Models;
use Database;
class EpisodeModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // Veritabanına tek bir bölüm ekler
    public function create($seasonId, $episodeData) {
        // Tekrar eklemeyi önlemek için bölümün zaten var olup olmadığını kontrol et
        $stmt = $this->db->prepare("SELECT id FROM episodes WHERE tmdb_episode_id = ?");
        $stmt->execute([$episodeData['id']]);
        if ($stmt->fetch()) {
            return; // Bölüm zaten var, bir şey yapma
        }
        
        $stmt = $this->db->prepare(
            "INSERT INTO episodes (season_id, tmdb_episode_id, episode_number, name, overview, still_path, air_date, runtime) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $seasonId,
            $episodeData['id'],
            $episodeData['episode_number'],
            $episodeData['name'],
            $episodeData['overview'],
            $episodeData['still_path'],
            !empty($episodeData['air_date']) ? $episodeData['air_date'] : null,
            $episodeData['runtime'] ?? null
        ]);
    }

    // Belirli bir sezona ait tüm bölümleri bulan metod
    public function findAllBySeasonId($seasonId) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM episodes WHERE season_id = ? ORDER BY episode_number ASC");
            $stmt->execute([$seasonId]);
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            return [];
        }
    }

    // YENİ EKLENEN METODLAR

    // ID'ye göre tek bir bölümü bulan metod
    public function findById($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM episodes WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (\PDOException $e) {
            return false;
        }
    }

    // Mevcut bir bölümü güncelleyen metod
    public function update($id, $data) {
        try {
            // Not: video_url artık bu tablodan yönetilmiyor.
            $sql = "UPDATE episodes SET 
                        name = :name, 
                        overview = :overview, 
                        episode_number = :episode_number,
                        air_date = :air_date
                    WHERE id = :id";

            $stmt = $this->db->prepare($sql);

            return $stmt->execute([
                ':id'             => $id,
                ':name'           => $data['name'],
                ':overview'       => $data['overview'],
                ':episode_number' => $data['episode_number'],
                ':air_date'       => !empty($data['air_date']) ? $data['air_date'] : null
            ]);
        } catch (\PDOException $e) {
            // Log error in a real app
            return false;
        }
    }

    public function createManual($data) {
        try {
            $sql = "INSERT INTO episodes (season_id, episode_number, name, overview) 
                    VALUES (:season_id, :episode_number, :name, :overview)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':season_id' => $data['season_id'],
                ':episode_number' => $data['episode_number'],
                ':name' => $data['name'],
                ':overview' => $data['overview'] ?? ''
            ]);
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function deleteById($id) {
        try {
            $this->db->beginTransaction();

            // Önce bölüme ait video linklerini bul
            $linkModel = new \App\Models\VideoLinkModel();
            $links = $linkModel->findAllByEpisodeId($id);

            if ($links) {
                // Her linke ait altyazıları sil
                $subtitleModel = new \App\Models\SubtitleModel();
                foreach ($links as $link) {
                    $stmtSubtitles = $this->db->prepare("DELETE FROM subtitles WHERE video_link_id = ?");
                    $stmtSubtitles->execute([$link['id']]);
                }
            }

            // Bölüme ait tüm video linklerini sil
            $stmtLinks = $this->db->prepare("DELETE FROM video_links WHERE episode_id = ?");
            $stmtLinks->execute([$id]);

            // Son olarak bölümün kendisini sil
            $stmtEpisode = $this->db->prepare("DELETE FROM episodes WHERE id = ?");
            $stmtEpisode->execute([$id]);

            $this->db->commit();
            return true;
        } catch (\PDOException $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function countAll() {
        try {
            return $this->db->query("SELECT COUNT(*) FROM episodes")->fetchColumn();
        } catch (\PDOException $e) { return 0; }
    }

    // Add this new function inside your EpisodeModel.php class

/**
 * Finds a specific episode using the TV show's slug, season number, and episode number.
 * This is a more complex query that joins multiple tables.
 * @return mixed The episode data or false if not found.
 */
public function findByShowSlugAndNumbers($slug, $seasonNumber, $episodeNumber) {
    try {
        $sql = "SELECT e.* FROM episodes e
                JOIN seasons s ON e.season_id = s.id
                JOIN tv_shows tv ON s.tv_show_id = tv.id
                WHERE tv.slug = :slug 
                  AND s.season_number = :season_number 
                  AND e.episode_number = :episode_number
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':slug' => $slug,
            ':season_number' => $seasonNumber,
            ':episode_number' => $episodeNumber
        ]);
        return $stmt->fetch();
    } catch (\PDOException $e) {
        return false;
    }
}

// Add this new function inside your EpisodeModel.php class

/**
 * Finds the first episode of a season, ordered by episode number.
 * @param int $seasonId The ID of the season.
 * @return mixed The episode data or false if not found.
 */
public function findFirstBySeasonId($seasonId) {
    try {
        $stmt = $this->db->prepare("SELECT * FROM episodes WHERE season_id = ? ORDER BY episode_number ASC LIMIT 1");
        $stmt->execute([$seasonId]);
        return $stmt->fetch();
    } catch (\PDOException $e) {
        return false;
    }
}

// Add this new function inside your EpisodeModel.php class

/**
 * Finds the next episode in the same season.
 * @param int $seasonId The ID of the current season.
 * @param int $currentEpisodeNumber The number of the current episode.
 * @return mixed The next episode's data or false if not found.
 */
public function findNextEpisode($seasonId, $currentEpisodeNumber) {
    try {
        $sql = "SELECT * FROM episodes 
                WHERE season_id = :season_id AND episode_number = :next_episode_number 
                LIMIT 1";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':season_id' => $seasonId,
            ':next_episode_number' => $currentEpisodeNumber + 1
        ]);
        return $stmt->fetch();
    } catch (\PDOException $e) {
        return false;
    }
}
}
<?php
namespace App\Models;
use Database;
class TvShowModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // Method to get all TV shows
    public function getAll() {
        try {
            $stmt = $this->db->query("SELECT * FROM tv_shows ORDER BY id DESC");
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            return [];
        }
    }

    // Method to find a single TV show by its ID
    public function findById($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM tv_shows WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (\PDOException $e) {
            return false;
        }
    }

    // Method to create a new TV show and return its ID
    // TvShowModel.php içinde
public function createAndGetId($data) {
    try {
        // SQL sorgusuna ve VALUES kısmına "slug" eklendi
        $sql = "INSERT INTO tv_shows (tmdb_id, title, slug, overview, first_air_date, poster_path, backdrop_path, logo_backdrop_path, logo_path, status, trailer_key) 
                VALUES (:tmdb_id, :title, :slug, :overview, :first_air_date, :poster_path, :backdrop_path, :logo_backdrop_path, :logo_path, :status, :trailer_key)";
        
        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':tmdb_id'         => $data['id'],
            ':title'           => $data['name'],
            ':slug'            => $data['slug'], // <-- YENİ EKLENDİ
            ':overview'        => $data['overview'],
            ':first_air_date'  => !empty($data['first_air_date']) ? $data['first_air_date'] : null,
            ':poster_path'     => $data['poster_path'] ?? null,
            ':backdrop_path'   => $data['backdrop_path'] ?? null,
            ':logo_backdrop_path' => $data['logo_backdrop_path'] ?? null,
            ':logo_path'       => $data['logo_path'] ?? null,
            ':status'          => $data['status'] ?? null,
            ':trailer_key'     => $data['trailer_key'] ?? null
        ]);
        return $this->db->lastInsertId();
    } catch (\PDOException $e) {
        die("Database Error: ". $e->getMessage());
    }
}

    // Method to update an existing TV show
    // TvShowModel.php içinde
public function update($id, $data) {
    try {
        // SQL sorgusuna "slug = :slug" eklendi
        $sql = "UPDATE tv_shows SET 
                    title = :title, 
                    slug = :slug,
                    overview = :overview, 
                    first_air_date = :first_air_date,
                    status = :status,
                    logo_backdrop_path = :logo_backdrop_path,
                    logo_path = :logo_path,
                    trailer_key = :trailer_key
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id'             => $id,
            ':title'          => $data['title'],
            ':slug'           => $data['slug'], // <-- YENİ EKLENDİ
            ':overview'       => $data['overview'],
            ':first_air_date' => !empty($data['first_air_date']) ? $data['first_air_date'] : null,
            ':status'         => $data['status'] ?? null,
            ':logo_backdrop_path' => $data['logo_backdrop_path'] ?? null,
            ':logo_path'      => $data['logo_path'] ?? null,
            ':trailer_key'    => $data['trailer_key'] ?? null
        ]);
    } catch (\PDOException $e) {
        die("Database Error: ". $e->getMessage());
    }
}

    // Method to delete a TV show by its ID
    public function deleteById($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM tv_shows WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (\PDOException $e) {
            die("Database Error: " . $e->getMessage());
        }
    }
    
    // Method to sync genres for a specific TV show
    public function syncGenres($tvShowId, $genreIds) {
        $stmt = $this->db->prepare("DELETE FROM tv_show_genres WHERE tv_show_id = ?");
        $stmt->execute([$tvShowId]);

        $stmt = $this->db->prepare("INSERT INTO tv_show_genres (tv_show_id, genre_id) VALUES (?, ?)");
        foreach ($genreIds as $genreId) {
            $stmt->execute([$tvShowId, $genreId]);
        }
    }

    // Method to sync cast for a specific TV show
    public function syncCast($tvShowId, $castData) {
        $stmtDelete = $this->db->prepare("DELETE FROM tv_show_cast WHERE tv_show_id = ?");
        $stmtDelete->execute([$tvShowId]);

        $stmtInsert = $this->db->prepare("INSERT INTO tv_show_cast (tv_show_id, person_id, character_name, cast_order) VALUES (?, ?, ?, ?)");
        foreach ($castData as $castMember) {
            $stmtInsert->execute([$tvShowId, $castMember['person_id'], $castMember['character_name'], $castMember['order']]);
        }
    }

    public function countAll() {
        try {
            return $this->db->query("SELECT COUNT(*) FROM tv_shows")->fetchColumn();
        } catch (\PDOException $e) { return 0; }
    }

 // TvShowModel.php içerisindeki getLatest fonksiyonunu hem sezon hem de türleri alacak şekilde güncelleyin
public function getLatest($limit) {
    try {
        // Hem sezon sayısını hem de türleri getiren güncel sorgu
        $sql = "SELECT tv.*, 
                       (SELECT COUNT(*) FROM seasons WHERE seasons.tv_show_id = tv.id) as season_count,
                       GROUP_CONCAT(g.name SEPARATOR ', ') as genres
                FROM tv_shows tv
                LEFT JOIN tv_show_genres tsg ON tv.id = tsg.tv_show_id
                LEFT JOIN genres g ON tsg.genre_id = g.id
                GROUP BY tv.id
                ORDER BY tv.first_air_date DESC 
                LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (\PDOException $e) {
        return [];
    }
}

    /**
     * Finds all platforms for a given TV Show ID.
     * @param int $tvShowId The ID of the TV show.
     * @return array
     */
    public function findPlatformsByTvShowId($tvShowId) {
        $sql = "SELECT p.* FROM platforms p
                JOIN tv_show_platforms tsp ON p.id = tsp.platform_id
                WHERE tsp.tv_show_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$tvShowId]);
        return $stmt->fetchAll();
    }

    /**
     * Syncs (updates) the platforms for a specific TV show.
     * @param int $tvShowId The ID of the TV show.
     * @param array $platformIds An array of platform IDs to associate with the TV show.
     */
    public function syncPlatforms($tvShowId, $platformIds) {
        // First, delete existing associations
        $stmt = $this->db->prepare("DELETE FROM tv_show_platforms WHERE tv_show_id = ?");
        $stmt->execute([$tvShowId]);

        // Then, insert new associations
        if (!empty($platformIds)) {
            $stmt = $this->db->prepare("INSERT INTO tv_show_platforms (tv_show_id, platform_id) VALUES (?, ?)");
            foreach ($platformIds as $platformId) {
                $stmt->execute([$tvShowId, $platformId]);
            }
        }
    }

    public function findAllByPlatformId($platformId) {
        $sql = "SELECT tv.* FROM tv_shows tv
                JOIN tv_show_platforms tsp ON tv.id = tsp.tv_show_id
                WHERE tsp.platform_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$platformId]);
        return $stmt->fetchAll();
    }

    public function findAllByGenreId($genreId, $limit = 10) {
    // Bu sorgu, belirli bir türe ait olan dizileri bulur
    // ve bu dizilerin hem sezon sayısını hem de sahip olduğu TÜM türleri getirir.
    $sql = "SELECT tv.*,
                   (SELECT COUNT(*) FROM seasons WHERE seasons.tv_show_id = tv.id) as season_count,
                   GROUP_CONCAT(g.name SEPARATOR ', ') as genres
            FROM tv_shows tv
            LEFT JOIN tv_show_genres tsg ON tv.id = tsg.tv_show_id
            LEFT JOIN genres g ON tsg.genre_id = g.id
            WHERE tv.id IN (
                SELECT tv_show_id FROM tv_show_genres WHERE genre_id = ?
            )
            GROUP BY tv.id
            ORDER BY tv.first_air_date DESC
            LIMIT ?";
            
    // Hatanın düzeltildiği doğru satır
    $stmt = $this->db->prepare($sql); 
    
    // execute içinde limit integer olmalı
    $stmt->bindValue(1, $genreId, \PDO::PARAM_INT);
    $stmt->bindValue(2, $limit, \PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

    /**
     * Increments the view count for a specific TV show.
     * @param int $id The ID of the TV show.
     */
    public function incrementViewCount($id) {
        try {
            $stmt = $this->db->prepare("UPDATE tv_shows SET view_count = view_count + 1 WHERE id = ?");
            $stmt->execute([$id]);
        } catch (\PDOException $e) {
            // Hata olursa sessizce devam et
        }
    }

    /**
     * Gets the most viewed TV shows.
     * @param int $limit The number of TV shows to retrieve.
     * @return array
     */
    public function getMostViewed($limit) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM tv_shows ORDER BY view_count DESC LIMIT :limit");
            $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            return [];
        }
    }

    public function getRandom($limit) {
    try {
        // "movies" yerine "tv_shows" olmalı
        $stmt = $this->db->prepare("SELECT * FROM tv_shows ORDER BY RAND() LIMIT :limit");
        $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (\PDOException $e) {
        return [];
    }
     }
    
    public function findByTitle($title) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM tv_shows WHERE title LIKE ? LIMIT 1");
            $stmt->execute(["%$title%"]);
            return $stmt->fetch();
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function getSeasonCount($tvShowId) {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM seasons WHERE tv_show_id = ?");
            $stmt->execute([$tvShowId]);
            return $stmt->fetchColumn();
        } catch (\PDOException $e) {
            return 0;
        }
    }

    /**
     * Finds a single TV show by its unique slug.
     * @param string $slug The slug of the TV show.
     * @return mixed The TV show data or false if not found.
     */
    public function findBySlug($slug) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM tv_shows WHERE slug = ?");
            $stmt->execute([$slug]);
            return $stmt->fetch();
        } catch (\PDOException $e) {
            return false;
        }
    }
}
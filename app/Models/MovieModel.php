<?php
namespace App\Models;
use Database;

class MovieModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // Method to get all movies
    public function getAll() {
        try {
            $stmt = $this->db->query("SELECT * FROM movies ORDER BY id DESC");
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            return [];
        }
    }

    // Method to find a single movie by its ID
    public function findById($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM movies WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (\PDOException $e) {
            return false;
        }
    }

    // Method to create a new movie and return its ID
    public function createAndGetId($data) {
        try {
            $sql = "INSERT INTO movies (tmdb_id, title, slug, overview, release_date, poster_path, backdrop_path, logo_backdrop_path, logo_path, runtime, trailer_key) 
                    VALUES (:tmdb_id, :title, :slug, :overview, :release_date, :poster_path, :backdrop_path, :logo_backdrop_path, :logo_path, :runtime, :trailer_key)";
            
            $stmt = $this->db->prepare($sql);

            $stmt->execute([
                ':tmdb_id'       => $data['tmdb_id'] ?? null,
                ':title'         => $data['title'],
                ':slug'          => $data['slug'],
                ':overview'      => $data['overview'],
                ':release_date'  => !empty($data['release_date']) ? $data['release_date'] : null,
                ':poster_path'   => $data['poster_path'] ?? null,
                ':backdrop_path' => $data['backdrop_path'] ?? null,
                ':logo_backdrop_path' => $data['logo_backdrop_path'] ?? null,
                ':logo_path'     => $data['logo_path'] ?? null,
                ':runtime'       => $data['runtime'] ?? null,
                ':trailer_key'   => $data['trailer_key'] ?? null
            ]);
            return $this->db->lastInsertId();
        } catch (\PDOException $e) {
            die("Database Error: " . $e->getMessage());
        }
    }
    
    // Method to update an existing movie
    public function update($id, $data) {
        try {
            $sql = "UPDATE movies SET 
                        title = :title, 
                        slug = :slug,
                        overview = :overview, 
                        release_date = :release_date, 
                        poster_path = :poster_path, 
                        backdrop_path = :backdrop_path, 
                        logo_backdrop_path = :logo_backdrop_path,
                        logo_path = :logo_path,
                        runtime = :runtime,
                        trailer_key = :trailer_key
                    WHERE id = :id";

            $stmt = $this->db->prepare($sql);

            return $stmt->execute([
                ':id'                 => $id,
                ':title'              => $data['title'],
                ':slug'               => $data['slug'],
                ':overview'           => $data['overview'],
                ':release_date'       => !empty($data['release_date']) ? $data['release_date'] : null,
                ':poster_path'        => $data['poster_path'] ?? null,
                ':backdrop_path'      => $data['backdrop_path'] ?? null,
                ':logo_backdrop_path' => $data['logo_backdrop_path'] ?? null,
                ':logo_path'          => $data['logo_path'] ?? null,
                ':runtime'            => $data['runtime'] ?? null,
                ':trailer_key'        => $data['trailer_key'] ?? null
            ]);
        } catch (\PDOException $e) {
            die("Database Error: " . $e->getMessage());
        }
    }

    // Method to sync genres for a specific movie
    public function syncGenres($movieId, $genreIds) {
        $stmt = $this->db->prepare("DELETE FROM movie_genres WHERE movie_id = ?");
        $stmt->execute([$movieId]);

        $stmt = $this->db->prepare("INSERT INTO movie_genres (movie_id, genre_id) VALUES (?, ?)");
        foreach ($genreIds as $genreId) {
            $stmt->execute([$movieId, $genreId]);
        }
    }

    // Method to sync cast for a specific movie
    public function syncCast($movieId, $castData) {
        $stmtDelete = $this->db->prepare("DELETE FROM movie_cast WHERE movie_id = ?");
        $stmtDelete->execute([$movieId]);

        $stmtInsert = $this->db->prepare("INSERT INTO movie_cast (movie_id, person_id, character_name, cast_order) VALUES (?, ?, ?, ?)");
        foreach ($castData as $castMember) {
            $stmtInsert->execute([$movieId, $castMember['person_id'], $castMember['character_name'], $castMember['order']]);
        }
    }

    // Method to delete a movie by its ID
    public function deleteById($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM movies WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (\PDOException $e) {
            die("Database Error: " . $e->getMessage());
        }
    }

    public function countAll() {
        try {
            return $this->db->query("SELECT COUNT(*) FROM movies")->fetchColumn();
        } catch (\PDOException $e) { return 0; }
    }

    // =================================================================
    // DÜZELTİLEN FONKSİYON 1: getLatest
    // =================================================================
    public function getLatest($limit) {
        try {
            $sql = "SELECT m.*, GROUP_CONCAT(g.name SEPARATOR ', ') as genres
                    FROM movies m
                    LEFT JOIN movie_genres mg ON m.id = mg.movie_id
                    LEFT JOIN genres g ON mg.genre_id = g.id
                    GROUP BY m.id
                    ORDER BY m.release_date DESC 
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
     * Finds all platforms for a given Movie ID.
     * @param int $movieId The ID of the movie.
     * @return array
     */
    public function findPlatformsByMovieId($movieId) {
        $sql = "SELECT p.* FROM platforms p
                JOIN movie_platforms mp ON p.id = mp.platform_id
                WHERE mp.movie_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$movieId]);
        return $stmt->fetchAll();
    }

    /**
     * Syncs (updates) the platforms for a specific movie.
     * @param int $movieId The ID of the movie.
     * @param array $platformIds An array of platform IDs to associate with the movie.
     */
    public function syncPlatforms($movieId, $platformIds) {
        // First, delete existing associations
        $stmt = $this->db->prepare("DELETE FROM movie_platforms WHERE movie_id = ?");
        $stmt->execute([$movieId]);

        // Then, insert new associations
        if (!empty($platformIds)) {
            $stmt = $this->db->prepare("INSERT INTO movie_platforms (movie_id, platform_id) VALUES (?, ?)");
            foreach ($platformIds as $platformId) {
                $stmt->execute([$movieId, $platformId]);
            }
        }
    }

    public function findAllByPlatformId($platformId) {
        $sql = "SELECT m.* FROM movies m
                JOIN movie_platforms mp ON m.id = mp.movie_id
                WHERE mp.platform_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$platformId]);
        return $stmt->fetchAll();
    }

    // =================================================================
    // DÜZELTİLEN FONKSİYON 2: findAllByGenreId
    // =================================================================
    public function findAllByGenreId($genreId, $limit = 10) {
        $sql = "SELECT m.*, GROUP_CONCAT(g.name SEPARATOR ', ') as genres
                FROM movies m
                LEFT JOIN movie_genres mg ON m.id = mg.movie_id
                LEFT JOIN genres g ON mg.genre_id = g.id
                WHERE m.id IN (
                    SELECT movie_id FROM movie_genres WHERE genre_id = ?
                )
                GROUP BY m.id
                ORDER BY m.release_date DESC
                LIMIT ?";
        $stmt = $this->db->prepare($sql);
        
        $stmt->bindValue(1, $genreId, \PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Increments the view count for a specific movie.
     * @param int $id The ID of the movie.
     */
    public function incrementViewCount($id) {
        try {
            $stmt = $this->db->prepare("UPDATE movies SET view_count = view_count + 1 WHERE id = ?");
            $stmt->execute([$id]);
        } catch (\PDOException $e) {
            // Hata olursa sessizce devam et
        }
    }

    /**
     * Gets the most viewed movies.
     * @param int $limit The number of movies to retrieve.
     * @return array
     */
    public function getMostViewed($limit) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM movies ORDER BY view_count DESC LIMIT :limit");
            $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            return [];
        }
    }

    public function getRandom($limit) {
    try {
        // "tv_shows" yerine "movies" olmalı
        $stmt = $this->db->prepare("SELECT * FROM movies ORDER BY RAND() LIMIT :limit");
        $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (\PDOException $e) {
        return [];
    }
     }

    public function findByTitle($title) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM movies WHERE title LIKE ? LIMIT 1");
            $stmt->execute(["%$title%"]);
            return $stmt->fetch();
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function findBySlug($slug) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM movies WHERE slug = ?");
            $stmt->execute([$slug]);
            return $stmt->fetch();
        } catch (\PDOException $e) {
            return false;
        }
    }
}
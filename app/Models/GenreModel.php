<?php
namespace App\Models;
use Database;
class GenreModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // Finds a genre by TMDb ID, or creates it if it doesn't exist
    public function findOrCreate($tmdbGenre) {
        $stmt = $this->db->prepare("SELECT id FROM genres WHERE tmdb_genre_id = ?");
        $stmt->execute([$tmdbGenre['id']]);
        $genre = $stmt->fetch();

        if ($genre) {
            return $genre['id'];
        } else {
            $stmt = $this->db->prepare("INSERT INTO genres (tmdb_genre_id, name) VALUES (?, ?)");
            $stmt->execute([$tmdbGenre['id'], $tmdbGenre['name']]);
            return $this->db->lastInsertId();
        }
    }

    // Method to find all genres for a given Movie ID
    public function findAllByMovieId($movieId) {
        $sql = "SELECT g.* FROM genres g
                JOIN movie_genres mg ON g.id = mg.genre_id
                WHERE mg.movie_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$movieId]);
        return $stmt->fetchAll();
    }

    // Method to find all genres for a given TV Show ID
    public function findAllByTvShowId($tvShowId) {
        $sql = "SELECT g.* FROM genres g
                JOIN tv_show_genres tsg ON g.id = tsg.genre_id
                WHERE tsg.tv_show_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$tvShowId]);
        return $stmt->fetchAll();
    }

    public function getAll() {
        try {
            $stmt = $this->db->query("SELECT * FROM genres ORDER BY name ASC");
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            return [];
        }
    }

    public function findById($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM genres WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function create($name) {
        try {
            $stmt = $this->db->prepare("INSERT INTO genres (name) VALUES (?)");
            return $stmt->execute([$name]);
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function update($id, $name) {
        try {
            $stmt = $this->db->prepare("UPDATE genres SET name = ? WHERE id = ?");
            return $stmt->execute([$name, $id]);
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function deleteById($id) {
        // Bu türü kullanan film veya dizi var mı diye kontrol et
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM movie_genres WHERE genre_id = ?");
        $stmt->execute([$id]);
        if ($stmt->fetchColumn() > 0) {
            return false; // Kullanımda, silinemez
        }

        $stmt = $this->db->prepare("SELECT COUNT(*) FROM tv_show_genres WHERE genre_id = ?");
        $stmt->execute([$id]);
        if ($stmt->fetchColumn() > 0) {
            return false; // Kullanımda, silinemez
        }

        // Kullanımda değilse sil
        $stmt = $this->db->prepare("DELETE FROM genres WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
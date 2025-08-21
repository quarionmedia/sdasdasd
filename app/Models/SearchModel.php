<?php
namespace App\Models;

use Database;

class SearchModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function recordSearch($searchTerm) {
        try {
            $stmt = $this->db->prepare("SELECT id FROM searches WHERE search_term = ?");
            $stmt->execute([$searchTerm]);
            $existing = $stmt->fetch();

            if ($existing) {
                $stmt = $this->db->prepare("UPDATE searches SET search_count = search_count + 1 WHERE id = ?");
                $stmt->execute([$existing['id']]);
            } else {
                $stmt = $this->db->prepare("INSERT INTO searches (search_term) VALUES (?)");
                $stmt->execute([$searchTerm]);
            }
        } catch (\PDOException $e) {
            // Hata olursa sessizce devam et
        }
    }

    public function findContentByQuery($query, $limit = 20) {
        try {
            // Adım 1: Filmleri ara
            $sqlMovies = "SELECT *, 'movie' as content_type FROM movies WHERE title LIKE :query";
            $stmtMovies = $this->db->prepare($sqlMovies);
            $stmtMovies->bindValue(':query', '%' . $query . '%', \PDO::PARAM_STR);
            $stmtMovies->execute();
            $movies = $stmtMovies->fetchAll();

            // Adım 2: Dizileri ara
            $sqlTvShows = "SELECT *, 'tv_show' as content_type FROM tv_shows WHERE title LIKE :query";
            $stmtTvShows = $this->db->prepare($sqlTvShows);
            $stmtTvShows->bindValue(':query', '%' . $query . '%', \PDO::PARAM_STR);
            $stmtTvShows->execute();
            $tvShows = $stmtTvShows->fetchAll();

            // Adım 3: Sonuçları PHP içinde birleştir
            $results = array_merge($movies, $tvShows);
            
            // Adım 4: Sonuçları limitle
            return array_slice($results, 0, $limit);

        } catch (\PDOException $e) {
            // Hata olursa boş dizi döndür
            return [];
        }
    }

    /**
     * Gets the most popular search terms.
     * @param int $limit The number of terms to retrieve.
     * @return array
     */
    public function getMostSearched($limit) {
        try {
            $stmt = $this->db->prepare("SELECT search_term FROM searches ORDER BY search_count DESC LIMIT :limit");
            $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_COLUMN);
        } catch (\PDOException $e) {
            return [];
        }
    }
}
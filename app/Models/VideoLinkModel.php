<?php
namespace App\Models;

use Database;

class VideoLinkModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // Find all links for a specific movie
    public function findAllByMovieId($movieId) {
        $stmt = $this->db->prepare("SELECT * FROM video_links WHERE movie_id = ? ORDER BY id ASC");
        $stmt->execute([$movieId]);
        return $stmt->fetchAll();
    }

    // Find all links for a specific episode
    public function findAllByEpisodeId($episodeId) {
        $stmt = $this->db->prepare("SELECT * FROM video_links WHERE episode_id = ? ORDER BY id ASC");
        $stmt->execute([$episodeId]);
        return $stmt->fetchAll();
    }
    
    // Find a single link by its ID
    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM video_links WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Create a new video link
    public function create($data) {
        $sql = "INSERT INTO video_links (movie_id, episode_id, label, quality, size, source, url, link_type, status) 
                VALUES (:movie_id, :episode_id, :label, :quality, :size, :source, :url, :link_type, :status)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':movie_id' => $data['movie_id'] ?? null,
            ':episode_id' => $data['episode_id'] ?? null,
            ':label' => $data['label'] ?? null,
            ':quality' => $data['quality'] ?? null,
            ':size' => $data['size'] ?? null,
            ':source' => $data['source'],
            ':url' => $data['url'],
            ':link_type' => $data['link_type'] ?? 'stream',
            ':status' => $data['status'] ?? 'publish'
        ]);
    }
    
    // Update an existing video link
    public function update($id, $data) {
        $sql = "UPDATE video_links SET label = :label, quality = :quality, size = :size, source = :source, url = :url, link_type = :link_type, status = :status WHERE id = :id";
        $stmt = $this->db->prepare($sql);
         return $stmt->execute([
            ':id' => $id,
            ':label' => $data['label'] ?? null,
            ':quality' => $data['quality'] ?? null,
            ':size' => $data['size'] ?? null,
            ':source' => $data['source'],
            ':url' => $data['url'],
            ':link_type' => $data['link_type'] ?? 'stream',
            ':status' => $data['status'] ?? 'publish'
        ]);
    }

    // Delete a video link by its ID
    public function deleteById($id) {
        $stmt = $this->db->prepare("DELETE FROM video_links WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
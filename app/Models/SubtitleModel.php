<?php
namespace App\Models;

use Database;

class SubtitleModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // MEVCUT METOD - BİR FİLME AİT TÜM ALTYAZILARI BULUR (İLERİDE LAZIM OLABİLİR)
    public function findAllByMovieId($movieId) {
        $stmt = $this->db->prepare("SELECT * FROM subtitles WHERE movie_id = ? ORDER BY id ASC");
        $stmt->execute([$movieId]);
        return $stmt->fetchAll();
    }

    // MEVCUT METOD - BİR BÖLÜME AİT TÜM ALTYAZILARI BULUR (İLERİDE LAZIM OLABİLİR)
    public function findAllByEpisodeId($episodeId) {
        $stmt = $this->db->prepare("SELECT * FROM subtitles WHERE episode_id = ? ORDER BY id ASC");
        $stmt->execute([$episodeId]);
        return $stmt->fetchAll();
    }

    // YENİ METOD - BİR VİDEO LİNKİNE AİT TÜM ALTYAZILARI BULUR
    public function findAllByVideoLinkId($videoLinkId) {
        $stmt = $this->db->prepare("SELECT * FROM subtitles WHERE video_link_id = ? ORDER BY id ASC");
        $stmt->execute([$videoLinkId]);
        return $stmt->fetchAll();
    }
    
    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM subtitles WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // GÜNCELLENDİ - ARTIK movie_id/episode_id YERİNE video_link_id KULLANIYOR
    public function create($data) {
    $sql = "INSERT INTO subtitles (video_link_id, language, url, type, status)
            VALUES (:video_link_id, :language, :url, :type, :status)";
    $stmt = $this->db->prepare($sql);
    return $stmt->execute([
        ':video_link_id' => $data['video_link_id'],
        ':language' => $data['language'],
        ':url' => $data['url'],
        ':type' => $data['type'],
        ':status' => $data['status'] ?? 'publish'
    ]);
    }
    
    // GÜNCELLENDİ - ARTIK GÜNCELLEME İÇİN GEREKLİ TÜM ALANLARI İÇERİYOR
    public function update($id, $data) {
        $sql = "UPDATE subtitles SET language = :language, url = :url, status = :status WHERE id = :id";
        $stmt = $this->db->prepare($sql);
         return $stmt->execute([
            ':id' => $id,
            ':language' => $data['language'],
            ':url' => $data['url'],
            ':status' => $data['status'] ?? 'publish'
        ]);
    }

    public function deleteById($id) {
        $stmt = $this->db->prepare("DELETE FROM subtitles WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
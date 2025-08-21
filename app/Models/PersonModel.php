<?php
namespace App\Models;
use Database;
class PersonModel {
    private $db;
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // Finds a person by TMDb ID, or creates them if they don't exist
    public function findOrCreate($personData) {
        $stmt = $this->db->prepare("SELECT id FROM people WHERE tmdb_person_id = ?");
        $stmt->execute([$personData['id']]);
        $person = $stmt->fetch();

        if ($person) {
            return $person['id'];
        } else {
            $stmt = $this->db->prepare("INSERT INTO people (tmdb_person_id, name, profile_path) VALUES (?, ?, ?)");
            $stmt->execute([
                $personData['id'], 
                $personData['name'], 
                $personData['profile_path'] ?? null
            ]);
            return $this->db->lastInsertId();
        }
    }

    // Method to find all cast members for a given Movie ID
    public function findCastByMovieId($movieId) {
        $sql = "SELECT p.name, p.profile_path, mc.character_name FROM people p
                JOIN movie_cast mc ON p.id = mc.person_id
                WHERE mc.movie_id = ?
                ORDER BY mc.cast_order ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$movieId]);
        return $stmt->fetchAll();
    }

    // Method to find all cast members for a given TV Show ID
    public function findCastByTvShowId($tvShowId) {
        $sql = "SELECT p.name, p.profile_path, tsc.character_name FROM people p
                JOIN tv_show_cast tsc ON p.id = tsc.person_id
                WHERE tsc.tv_show_id = ?
                ORDER BY tsc.cast_order ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$tvShowId]);
        return $stmt->fetchAll();
    }
}
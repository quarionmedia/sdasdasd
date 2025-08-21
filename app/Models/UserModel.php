<?php
namespace App\Models;
use Database;

class UserModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // --- Mevcut Metodlar ---

    public function countAllUsers() {
        try {
            $stmt = $this->db->query("SELECT COUNT(*) FROM users");
            return $stmt->fetchColumn();
        } catch (\PDOException $e) {
            return 0;
        }
    }

    // Replace the existing create() function in UserModel.php with this version
public function create($data) {
    $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);
    try {
        // Added 'username' to the query
        $stmt = $this->db->prepare("INSERT INTO users (email, username, password_hash) VALUES (?, ?, ?)");
        $success = $stmt->execute([$data['email'], $data['username'], $passwordHash]);
        return $success ? $this->db->lastInsertId() : false;
    } catch (\PDOException $e) {
        return false;
    }
}

    public function findByEmail($email) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            return $stmt->fetch();
        } catch (\PDOException $e) {
            return false;
        }
    }

    // --- YENİ YÖNETİM METODLARI ---

    /**
     * Tüm kullanıcıları admin paneli için listeler.
     * @return array
     */
    public function getAllUsers() {
        try {
            $stmt = $this->db->query("SELECT id, email, is_admin, created_at FROM users ORDER BY id DESC");
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            return [];
        }
    }

    /**
     * ID'ye göre tek bir kullanıcıyı bulur.
     * @param int $id
     * @return mixed
     */
    public function findById($id) {
        try {
            $stmt = $this->db->prepare("SELECT id, email, is_admin, created_at FROM users WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (\PDOException $e) {
            return false;
        }
    }

    /**
     * Bir kullanıcının bilgilerini günceller.
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data) {
        try {
            $sql = "UPDATE users SET email = :email, is_admin = :is_admin";
            $params = [
                ':id' => $id,
                ':email' => $data['email'],
                ':is_admin' => $data['is_admin']
            ];

            // Eğer yeni bir şifre girildiyse, onu da güncelleme sorgusuna ekle
            if (!empty($data['password'])) {
                $sql .= ", password_hash = :password_hash";
                $params[':password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
            }

            $sql .= " WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($params);
        } catch (\PDOException $e) {
            return false;
        }
    }

    /**
     * Bir kullanıcıyı ID'ye göre siler.
     * @param int $id
     * @return bool
     */
    public function deleteById($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (\PDOException $e) {
            return false;
        }
    }

    // Add this new function to UserModel.php
public function findByUsername($username) {
    try {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch();
    } catch (\PDOException $e) {
        return false;
    }
}
}
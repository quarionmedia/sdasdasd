<?php
namespace App\Models;

use Database;

class CommentModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Gets all comments with user information for the admin panel.
     * @return array
     */
    public function getAllCommentsWithUsers() {
        try {
            $sql = "SELECT c.*, u.email 
                    FROM comments c 
                    JOIN users u ON c.user_id = u.id 
                    ORDER BY c.created_at DESC";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            return [];
        }
    }

    /**
     * Updates the status of a comment (e.g., 'pending' to 'approved').
     * @param int $id The ID of the comment.
     * @param string $status The new status.
     * @return bool
     */
    public function updateStatus($id, $status) {
        try {
            $stmt = $this->db->prepare("UPDATE comments SET status = ? WHERE id = ?");
            return $stmt->execute([$status, $id]);
        } catch (\PDOException $e) {
            return false;
        }
    }

    /**
     * Deletes a comment by its ID.
     * @param int $id The ID of the comment to delete.
     * @return bool
     */
    public function deleteById($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM comments WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (\PDOException $e) {
            return false;
        }
    }

// Add this new function inside your CommentModel.php class
public function toggleLike($userId, $commentId) {
    $this->db->beginTransaction();
    try {
        $stmt = $this->db->prepare("SELECT id FROM comment_likes WHERE user_id = ? AND comment_id = ?");
        $stmt->execute([$userId, $commentId]);
        $existingLike = $stmt->fetch();

        if ($existingLike) {
            $stmt = $this->db->prepare("DELETE FROM comment_likes WHERE id = ?");
            $stmt->execute([$existingLike['id']]);
            $stmt = $this->db->prepare("UPDATE comments SET like_count = GREATEST(0, like_count - 1) WHERE id = ?");
            $stmt->execute([$commentId]);
            $userLikes = false;
        } else {
            $stmt = $this->db->prepare("INSERT INTO comment_likes (user_id, comment_id) VALUES (?, ?)");
            $stmt->execute([$userId, $commentId]);
            $stmt = $this->db->prepare("UPDATE comments SET like_count = like_count + 1 WHERE id = ?");
            $stmt->execute([$commentId]);
            $userLikes = true;
        }

        $stmt = $this->db->prepare("SELECT like_count FROM comments WHERE id = ?");
        $stmt->execute([$commentId]);
        $newLikeCount = $stmt->fetchColumn();

        $this->db->commit();
        return ['success' => true, 'newLikeCount' => $newLikeCount, 'userLikes' => $userLikes];
    } catch (\PDOException $e) {
        $this->db->rollBack();
        error_log($e->getMessage());
        return ['success' => false];
    }
}

public function findAllByContentId($contentId, $contentType, $userId = null) {
    if ($contentType === 'movie') { 
        $idColumn = 'movie_id'; 
    } elseif ($contentType === 'tv_show') { 
        $idColumn = 'tv_show_id'; 
    } else { 
        return []; 
    }

    try {
        // We order by ASC (oldest first) to build the reply tree correctly
        $sql = "SELECT c.*, u.username as author_username 
                FROM comments c
                LEFT JOIN users u ON c.user_id = u.id
                WHERE c.{$idColumn} = ? AND c.status = 'approved'
                ORDER BY c.created_at ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$contentId]);
        $flatComments = $stmt->fetchAll();

        // If no comments, return early
        if (empty($flatComments)) {
            return [];
        }

        // If a user is logged in, check which comments they have liked
        if ($userId) {
            $commentIds = array_column($flatComments, 'id');
            $placeholders = implode(',', array_fill(0, count($commentIds), '?'));

            $sqlLikes = "SELECT comment_id FROM comment_likes WHERE user_id = ? AND comment_id IN ($placeholders)";
            $params = array_merge([$userId], $commentIds);
            
            $stmtLikes = $this->db->prepare($sqlLikes);
            $stmtLikes->execute($params);
            $likedCommentIds = $stmtLikes->fetchAll(\PDO::FETCH_COLUMN);

            foreach ($flatComments as &$comment) {
                $comment['is_liked_by_user'] = in_array($comment['id'], $likedCommentIds);
            }
            unset($comment); // Break the reference
        }

        // --- NEW LOGIC TO BUILD THE REPLY TREE ---
        $commentTree = [];
        $commentsById = [];

        // First, map all comments by their ID for easy access
        foreach ($flatComments as $comment) {
            $commentsById[$comment['id']] = $comment;
            $commentsById[$comment['id']]['replies'] = []; // Initialize replies array for each comment
        }

        // Now, structure them into a tree
        foreach ($commentsById as $id => &$comment) { // Use reference to modify the array directly
            if ($comment['parent_id'] && isset($commentsById[$comment['parent_id']])) {
                // If it's a reply, add it to its parent's 'replies' array
                $commentsById[$comment['parent_id']]['replies'][] = &$comment;
            } else {
                // If it's a top-level comment, add it to the root of the tree
                $commentTree[] = &$comment;
            }
        }
        unset($comment); // Break the reference

        return $commentTree;

    } catch (\PDOException $e) {
        // On database error, return an empty array
        return [];
    }
}

// Add this new function inside your CommentModel class
/**
 * Creates a new comment in the database.
 * @param array $data The comment data.
 * @return bool True on success, false on failure.
 */
// Replace the create() function in CommentModel.php with this updated version
// Replace the create() function in CommentModel.php with this temporary debug version

// Replace the create() function in CommentModel.php with this updated version
public function create($data) {
    try {
        // Added parent_id to the SQL query
        $sql = "INSERT INTO comments (user_id, parent_id, movie_id, tv_show_id, comment, rating, status, is_spoiler)
                VALUES (:user_id, :parent_id, :movie_id, :tv_show_id, :comment, :rating, :status, :is_spoiler)";
        
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            ':user_id'    => $data['user_id'],
            ':parent_id'  => $data['parent_id'] ?? null, // Add parent_id
            ':movie_id'   => $data['content_type'] === 'movie' ? $data['content_id'] : null,
            ':tv_show_id' => $data['content_type'] === 'tv_show' ? $data['content_id'] : null,
            ':comment'    => $data['comment'],
            ':rating'     => $data['rating'],
            ':status'     => 'pending',
            ':is_spoiler' => $data['is_spoiler'] ?? 0
        ]);
    } catch (\PDOException $e) {
        error_log("Error creating comment: " . $e->getMessage());
        return false;
    }
}
}
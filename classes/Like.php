<?php
class Like {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function toggleLike($articleId, $userId) {
        $query = "SELECT * FROM likes WHERE id_article = ? AND id_utilisateur = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$articleId, $userId]);
        
        if ($stmt->rowCount() > 0) {
            $query = "DELETE FROM likes WHERE id_article = ? AND id_utilisateur = ?";
        } else {
            $query = "INSERT INTO likes (id_article, id_utilisateur) VALUES (?, ?)";
        }
        
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$articleId, $userId]);
    }

    public function getLikeCount($articleId) {
        $query = "SELECT COUNT(*) as count FROM likes WHERE id_article = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$articleId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }

    public function isLikedByUser($articleId, $userId) {
        $query = "SELECT * FROM likes WHERE id_article = ? AND id_utilisateur = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$articleId, $userId]);
        return $stmt->rowCount() > 0;
    }
}


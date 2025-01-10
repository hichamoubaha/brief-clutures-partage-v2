<?php
class Comment {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function addComment($articleId, $userId, $content) {
        $query = "INSERT INTO commentaires (id_article, id_utilisateur, contenu) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$articleId, $userId, $content]);
    }

    public function getCommentsByArticle($articleId) {
        $query = "SELECT c.*, u.nom_utilisateur FROM commentaires c 
                  JOIN utilisateurs u ON c.id_utilisateur = u.id_utilisateur 
                  WHERE c.id_article = ? 
                  ORDER BY c.date_creation DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$articleId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteComment($id, $userId, $userRole) {
        if ($userRole === 'admin') {
            $query = "DELETE FROM commentaires WHERE id_commentaire = ?";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([$id]);
        } else {
            $query = "DELETE FROM commentaires WHERE id_commentaire = ? AND id_utilisateur = ?";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([$id, $userId]);
        }
    }
}


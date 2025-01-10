<?php
class Favorite {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function toggleFavorite($articleId, $userId) {
        $query = "SELECT * FROM favoris WHERE id_article = ? AND id_utilisateur = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$articleId, $userId]);
        
        if ($stmt->rowCount() > 0) {
            $query = "DELETE FROM favoris WHERE id_article = ? AND id_utilisateur = ?";
        } else {
            $query = "INSERT INTO favoris (id_article, id_utilisateur) VALUES (?, ?)";
        }
        
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$articleId, $userId]);
    }

    public function getFavoriteArticles($userId) {
        $query = "SELECT a.*, c.nom_categorie, u.nom_utilisateur FROM articles a 
                  JOIN categories c ON a.id_categorie = c.id_categorie 
                  JOIN utilisateurs u ON a.id_auteur = u.id_utilisateur 
                  JOIN favoris f ON a.id_article = f.id_article 
                  WHERE f.id_utilisateur = ? 
                  ORDER BY f.date_creation DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function isFavoritedByUser($articleId, $userId) {
        $query = "SELECT * FROM favoris WHERE id_article = ? AND id_utilisateur = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$articleId, $userId]);
        return $stmt->rowCount() > 0;
    }
}


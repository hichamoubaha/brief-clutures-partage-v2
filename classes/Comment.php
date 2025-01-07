<?php
class Comment {
    private $conn;
    private $table_name = "commentaires";

    public function __construct($db) {
        $this->conn = $db->getConnection();
    }

    public function addComment($id_article, $id_utilisateur, $contenu) {
        $query = "INSERT INTO " . $this->table_name . " (id_article, id_utilisateur, contenu) VALUES (:id_article, :id_utilisateur, :contenu)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_article', $id_article);
        $stmt->bindParam(':id_utilisateur', $id_utilisateur);
        $stmt->bindParam(':contenu', $contenu);
        return $stmt->execute();
    }

    public function getCommentsByArticle($id_article) {
        $query = "SELECT c.*, u.nom_utilisateur FROM " . $this->table_name . " c 
                  JOIN utilisateurs u ON c.id_utilisateur = u.id_utilisateur 
                  WHERE c.id_article = :id_article 
                  ORDER BY c.date_creation DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_article', $id_article);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteComment($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_commentaire = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}


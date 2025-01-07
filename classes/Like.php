<?php
class Like {
    private $conn;
    private $table_name = "likes";

    public function __construct($db) {
        $this->conn = $db->getConnection();
    }

    public function addLike($id_article, $id_utilisateur) {
        $query = "INSERT INTO " . $this->table_name . " (id_article, id_utilisateur) VALUES (:id_article, :id_utilisateur)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_article', $id_article);$stmt->bindParam(':id_utilisateur', $id_utilisateur);
        if ($stmt->execute()) {
            $this->addToFavorites($id_article, $id_utilisateur);
            return true;
        }
        return false;
    }

    public function removeLike($id_article, $id_utilisateur) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_article = :id_article AND id_utilisateur = :id_utilisateur";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_article', $id_article);
        $stmt->bindParam(':id_utilisateur', $id_utilisateur);
        return $stmt->execute();
    }

    public function getLikeCount($id_article) {
        $query = "SELECT COUNT(*) as count FROM " . $this->table_name . " WHERE id_article = :id_article";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_article', $id_article);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }

    private function addToFavorites($id_article, $id_utilisateur) {
        $query = "INSERT INTO favoris (id_article, id_utilisateur) VALUES (:id_article, :id_utilisateur)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_article', $id_article);
        $stmt->bindParam(':id_utilisateur', $id_utilisateur);
        return $stmt->execute();
    }
}


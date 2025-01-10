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
        $stmt->bindParam(':id_article', $id_article);
        $stmt->bindParam(':id_utilisateur', $id_utilisateur);
        return $stmt->execute();
    }

    public function getLikesByArticle($id_article) {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE id_article = :id_article";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_article', $id_article);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
}
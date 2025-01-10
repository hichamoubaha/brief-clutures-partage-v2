<?php
class Favorite {
    private $conn;
    private $table_name = "favoris";

    public function __construct($db) {
        $this->conn = $db->getConnection();
    }

    public function addFavorite($id_article, $id_utilisateur) {
        $query = "INSERT INTO " . $this->table_name . " (id_article, id_utilisateur) VALUES (:id_article, :id_utilisateur)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_article', $id_article);
        $stmt->bindParam(':id_utilisateur', $id_utilisateur);
        return $stmt->execute();
    }
}
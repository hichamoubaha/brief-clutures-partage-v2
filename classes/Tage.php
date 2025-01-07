<?php
class Tag {
    private $conn;
    private $table_name = "tags";

    public function __construct($db) {
        $this->conn = $db->getConnection();
    }

    public function createTag($nom_tag) {
        $query = "INSERT INTO " . $this->table_name . " (nom_tag) VALUES (:nom_tag)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nom_tag', $nom_tag);
        return $stmt->execute();
    }

    public function getAllTags() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY nom_tag";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTagsByArticle($id_article) {
        $query = "SELECT t.* FROM " . $this->table_name . " t 
                  JOIN article_tags at ON t.id_tag = at.id_tag 
                  WHERE at.id_article = :id_article";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_article', $id_article);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}


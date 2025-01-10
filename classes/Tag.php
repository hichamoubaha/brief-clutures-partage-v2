<?php
class Tag {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllTags() {
        $query = "SELECT * FROM tags ORDER BY nom_tag";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTagsByArticle($articleId) {
        $query = "SELECT t.* FROM tags t 
                  JOIN article_tags at ON t.id_tag = at.id_tag 
                  WHERE at.id_article = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$articleId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addTag($name) {
        $query = "INSERT INTO tags (nom_tag) VALUES (?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$name]);
    }

    public function updateTag($id, $name) {
        $query = "UPDATE tags SET nom_tag = ? WHERE id_tag = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$name, $id]);
    }

    public function deleteTag($id) {
        $query = "DELETE FROM tags WHERE id_tag = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$id]);
    }
}


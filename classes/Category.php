<?php
class Category {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllCategories() {
        $query = "SELECT * FROM categories ORDER BY nom_categorie";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCategoryById($id) {
        $query = "SELECT * FROM categories WHERE id_categorie = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addCategory($name) {
        $query = "INSERT INTO categories (nom_categorie) VALUES (?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$name]);
    }

    public function updateCategory($id, $name) {
        $query = "UPDATE categories SET nom_categorie = ? WHERE id_categorie = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$name, $id]);
    }

    public function deleteCategory($id) {
        $query = "DELETE FROM categories WHERE id_categorie = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$id]);
    }
}


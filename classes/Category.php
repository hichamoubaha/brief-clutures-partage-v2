<?php
class Category {
    private $conn;
    private $table_name = "categories";

    public function __construct($db) {
        $this->conn = $db->getConnection();
    }

    public function getAllCategories() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY nom_categorie";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createCategory($nom_categorie) {
        $query = "INSERT INTO " . $this->table_name . " (nom_categorie) VALUES (:nom_categorie)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nom_categorie', $nom_categorie);
        return $stmt->execute();
    }

    public function updateCategory($id_categorie, $nom_categorie) {
        $query = "UPDATE " . $this->table_name . " SET nom_categorie = :nom_categorie WHERE id_categorie = :id_categorie";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_categorie', $id_categorie);
        $stmt->bindParam(':nom_categorie', $nom_categorie);
        return $stmt->execute();
    }

    public function deleteCategory($id_categorie) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_categorie = :id_categorie";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_categorie', $id_categorie);
        return $stmt->execute();
    }

    public function getCategoryById($id_categorie) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_categorie = :id_categorie";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_categorie', $id_categorie);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}


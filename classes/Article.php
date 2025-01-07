<?php
class Article {
    private $conn;
    private $table_name = "articles";

    public function __construct($db) {
        $this->conn = $db->getConnection();
    }

    public function createArticle($titre, $contenu, $id_categorie, $id_auteur, $photo, $tags) {
        $query = "INSERT INTO " . $this->table_name . " (titre, contenu, id_categorie, id_auteur, photo, statut) VALUES (:titre, :contenu, :id_categorie, :id_auteur, :photo, 'en attente')";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':titre', $titre);
        $stmt->bindParam(':contenu', $contenu);
        $stmt->bindParam(':id_categorie', $id_categorie);
        $stmt->bindParam(':id_auteur', $id_auteur);
        $stmt->bindParam(':photo', $photo);

        if ($stmt->execute()) {
            $article_id = $this->conn->lastInsertId();
            $this->addTags($article_id, $tags);
            return true;
        } else {
            return false;
        }
    }

    public function updateArticle($id, $titre, $contenu, $id_categorie, $photo, $tags) {
        $query = "UPDATE " . $this->table_name . " SET titre = :titre, contenu = :contenu, id_categorie = :id_categorie, photo = :photo WHERE id_article = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':titre', $titre);
        $stmt->bindParam(':contenu', $contenu);
        $stmt->bindParam(':id_categorie', $id_categorie);
        $stmt->bindParam(':photo', $photo);

        if ($stmt->execute()) {
            $this->updateTags($id, $tags);
            return true;
        } else {
            return false;
        }
    }

    public function deleteArticle($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_article = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function getArticleById($id) {
        $query = "SELECT a.*, c.nom_categorie, u.nom_utilisateur FROM " . $this->table_name . " a 
                  JOIN categories c ON a.id_categorie = c.id_categorie 
                  JOIN utilisateurs u ON a.id_auteur = u.id_utilisateur 
                  WHERE a.id_article = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getLatestArticles($page = 1, $per_page = 10) {
        $start = ($page - 1) * $per_page;
        $query = "SELECT a.*, c.nom_categorie, u.nom_utilisateur FROM " . $this->table_name . " a 
                  JOIN categories c ON a.id_categorie = c.id_categorie 
                  JOIN utilisateurs u ON a.id_auteur = u.id_utilisateur 
                  WHERE a.statut = 'approuve' 
                  ORDER BY a.date_creation DESC LIMIT :start, :per_page";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':start', $start, PDO::PARAM_INT);
        $stmt->bindParam(':per_page', $per_page, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getArticlesByCategory($category_id, $page = 1, $per_page = 10) {
        $start = ($page - 1) * $per_page;
        $query = "SELECT a.*, c.nom_categorie, u.nom_utilisateur FROM " . $this->table_name . " a 
                  JOIN categories c ON a.id_categorie = c.id_categorie 
                  JOIN utilisateurs u ON a.id_auteur = u.id_utilisateur 
                  WHERE a.id_categorie = :category_id AND a.statut = 'approuve' 
                  ORDER BY a.date_creation DESC LIMIT :start, :per_page";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':start', $start, PDO::PARAM_INT);
        $stmt->bindParam(':per_page', $per_page, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchArticles($keyword) {
        $query = "SELECT a.*, c.nom_categorie, u.nom_utilisateur FROM " . $this->table_name . " a 
                  JOIN categories c ON a.id_categorie = c.id_categorie 
                  JOIN utilisateurs u ON a.id_auteur = u.id_utilisateur 
                  WHERE (a.titre LIKE :keyword OR a.contenu LIKE :keyword OR u.nom_utilisateur LIKE :keyword) 
                  AND a.statut = 'approuve' 
                  ORDER BY a.date_creation DESC";
        $stmt = $this->conn->prepare($query);
        $keyword = "%{$keyword}%";
        $stmt->bindParam(':keyword', $keyword);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function approveArticle($id) {
        $query = "UPDATE " . $this->table_name . " SET statut = 'approuve' WHERE id_article = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function rejectArticle($id) {
        $query = "UPDATE " . $this->table_name . " SET statut = 'refuse' WHERE id_article = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    private function addTags($article_id, $tags) {
        $query = "INSERT INTO article_tags (id_article, id_tag) VALUES (:article_id, :tag_id)";
        $stmt = $this->conn->prepare($query);
        foreach ($tags as $tag_id) {
            $stmt->bindParam(':article_id', $article_id);
            $stmt->bindParam(':tag_id', $tag_id);
            $stmt->execute();
        }
    }

    private function updateTags($article_id, $tags) {
        $query = "DELETE FROM article_tags WHERE id_article = :article_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':article_id', $article_id);
        $stmt->execute();

        $this->addTags($article_id, $tags);
    }
}


<?php
class Article {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function createArticle($title, $content, $categoryId, $authorId, $photo, $tags) {
        $this->db->beginTransaction();

        try {
            $query = "INSERT INTO articles (titre, contenu, id_categorie, id_auteur, photo) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$title, $content, $categoryId, $authorId, $photo]);

            $articleId = $this->db->lastInsertId();

            foreach ($tags as $tagId) {
                $query = "INSERT INTO article_tags (id_article, id_tag) VALUES (?, ?)";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$articleId, $tagId]);
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function updateArticle($id, $title, $content, $categoryId, $photo, $tags) {
        $this->db->beginTransaction();

        try {
            $query = "UPDATE articles SET titre = ?, contenu = ?, id_categorie = ?, photo = ? WHERE id_article = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$title, $content, $categoryId, $photo, $id]);

            $query = "DELETE FROM article_tags WHERE id_article = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id]);

            foreach ($tags as $tagId) {
                $query = "INSERT INTO article_tags (id_article, id_tag) VALUES (?, ?)";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$id, $tagId]);
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function deleteArticle($id) {
        $query = "DELETE FROM articles WHERE id_article = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$id]);
    }

    public function getArticleById($id) {
        $query = "SELECT a.*, c.nom_categorie, u.nom_utilisateur FROM articles a 
                  JOIN categories c ON a.id_categorie = c.id_categorie 
                  JOIN utilisateurs u ON a.id_auteur = u.id_utilisateur 
                  WHERE a.id_article = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getLatestArticles($limit = 10) {
        $query = "SELECT a.*, c.nom_categorie, u.nom_utilisateur FROM articles a 
                  JOIN categories c ON a.id_categorie = c.id_categorie 
                  JOIN utilisateurs u ON a.id_auteur = u.id_utilisateur 
                  WHERE a.statut = 'approuve' 
                  ORDER BY a.date_creation DESC LIMIT ?";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getArticlesByCategory($categoryId, $limit = 10) {
        $query = "SELECT a.*, c.nom_categorie, u.nom_utilisateur FROM articles a 
                  JOIN categories c ON a.id_categorie = c.id_categorie 
                  JOIN utilisateurs u ON a.id_auteur = u.id_utilisateur 
                  WHERE a.id_categorie = ? AND a.statut = 'approuve' 
                  ORDER BY a.date_creation DESC LIMIT ?";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(1, $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getArticlesByAuthor($authorId) {
        $query = "SELECT a.*, c.nom_categorie FROM articles a 
                  JOIN categories c ON a.id_categorie = c.id_categorie 
                  WHERE a.id_auteur = ? 
                  ORDER BY a.date_creation DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$authorId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPendingArticles() {
        $query = "SELECT a.*, c.nom_categorie, u.nom_utilisateur FROM articles a 
                  JOIN categories c ON a.id_categorie = c.id_categorie 
                  JOIN utilisateurs u ON a.id_auteur = u.id_utilisateur 
                  WHERE a.statut = 'en attente' 
                  ORDER BY a.date_creation DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function approveArticle($id) {
        $query = "UPDATE articles SET statut = 'approuve' WHERE id_article = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$id]);
    }

    public function rejectArticle($id) {
        $query = "UPDATE articles SET statut = 'refuse' WHERE id_article = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$id]);
    }

    public function searchArticles($keyword) {
        $query = "SELECT a.*, c.nom_categorie, u.nom_utilisateur FROM articles a 
                  JOIN categories c ON a.id_categorie = c.id_categorie 
                  JOIN utilisateurs u ON a.id_auteur = u.id_utilisateur 
                  WHERE (a.titre LIKE ? OR a.contenu LIKE ?) AND a.statut = 'approuve' 
                  ORDER BY a.date_creation DESC";
        $stmt = $this->db->prepare($query);
        $keyword = "%$keyword%";
        $stmt->execute([$keyword, $keyword]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}


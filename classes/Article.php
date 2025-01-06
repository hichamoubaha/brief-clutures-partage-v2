<?php
class Article {
    private $conn;
    private $table_name = "articles";

    public function __construct($db) {
        $this->conn = $db->getConnection();
    }

    public function getLatestArticles($page = 1, $per_page = 6) {
        $offset = ($page - 1) * $per_page;
        $query = "SELECT a.*, c.nom_categorie, u.nom_utilisateur 
              FROM " . $this->table_name . " a 
              JOIN categories c ON a.id_categorie = c.id_categorie 
              JOIN utilisateurs u ON a.id_auteur = u.id_utilisateur 
              WHERE a.statut = 'approuve' 
              ORDER BY a.date_creation DESC 
              LIMIT :offset, :per_page";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindParam(':per_page', $per_page, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createArticle($titre, $contenu, $id_categorie, $id_auteur, $photo) {
        $query = "INSERT INTO " . $this->table_name . " (titre, contenu, id_categorie, id_auteur, statut, photo) VALUES (:titre, :contenu, :id_categorie, :id_auteur, 'en attente', :photo)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':titre', $titre);
        $stmt->bindParam(':contenu', $contenu);
        $stmt->bindParam(':id_categorie', $id_categorie);
        $stmt->bindParam(':id_auteur', $id_auteur);
        $stmt->bindParam(':photo', $photo);

        if ($stmt->execute()) {
            header('Location: index.php?page=dashboard');
        } else {
            echo "Error creating article.";
        }
    }

    public function getPendingArticles() {
        $query = "SELECT a.*, c.nom_categorie, u.nom_utilisateur 
                  FROM " . $this->table_name . " a 
                  JOIN categories c ON a.id_categorie = c.id_categorie 
                  JOIN utilisateurs u ON a.id_auteur = u.id_utilisateur 
                  WHERE a.statut = 'en attente' 
                  ORDER BY a.date_creation DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserArticles($user_id) {
        $query = "SELECT a.*, c.nom_categorie 
                  FROM " . $this->table_name . " a 
                  JOIN categories c ON a.id_categorie = c.id_categorie 
                  WHERE a.id_auteur = :user_id 
                  ORDER BY a.date_creation DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getArticleById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_article = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateArticle($id, $titre, $contenu, $id_categorie, $photo) {
        $query = "UPDATE " . $this->table_name . " SET titre = :titre, contenu = :contenu, id_categorie = :id_categorie, photo = :photo WHERE id_article = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':titre', $titre);
        $stmt->bindParam(':contenu', $contenu);
        $stmt->bindParam(':id_categorie', $id_categorie);
        $stmt->bindParam(':photo', $photo);

        if ($stmt->execute()) {
            header('Location: index.php?page=dashboard');
        } else {
            echo "Error updating article.";
        }
    }

    public function deleteArticle($id, $user_id, $user_role) {
        $article = $this->getArticleById($id);
        if ($article && ($article['id_auteur'] == $user_id || $user_role === 'admin')) {
            $query = "DELETE FROM " . $this->table_name . " WHERE id_article = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
        }
    }

    public function approveArticle($id) {
        $query = "UPDATE " . $this->table_name . " SET statut = 'approuve' WHERE id_article = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    public function rejectArticle($id) {
        $query = "UPDATE " . $this->table_name . " SET statut = 'refuse' WHERE id_article = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    public function getArticlesByCategory($category_id, $page = 1, $per_page = 6) {
        $offset = ($page - 1) * $per_page;
        $query = "SELECT a.*, c.nom_categorie, u.nom_utilisateur 
                  FROM " . $this->table_name . " a 
                  JOIN categories c ON a.id_categorie = c.id_categorie 
                  JOIN utilisateurs u ON a.id_auteur = u.id_utilisateur 
                  WHERE a.id_categorie = :category_id AND a.statut = 'approuve' 
                  ORDER BY a.date_creation DESC 
                  LIMIT :offset, :per_page";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindParam(':per_page', $per_page, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalArticles() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE statut = 'approuve'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    public function getTotalArticlesByCategory($category_id) {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE id_categorie = :category_id AND statut = 'approuve'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    public function getArticlesByStatus($status) {
        $query = "SELECT a.*, c.nom_categorie, u.nom_utilisateur 
                  FROM " . $this->table_name . " a 
                  JOIN categories c ON a.id_categorie = c.id_categorie 
                  JOIN utilisateurs u ON a.id_auteur = u.id_utilisateur 
                  WHERE a.statut = :status 
                  ORDER BY a.date_creation DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getArticleWithDetails($id, $is_authenticated = false) {
        $query = "SELECT a.*, c.nom_categorie, u.nom_utilisateur 
                  FROM " . $this->table_name . " a 
                  JOIN categories c ON a.id_categorie = c.id_categorie 
                  JOIN utilisateurs u ON a.id_auteur = u.id_utilisateur 
                  WHERE a.id_article = :id AND a.statut = 'approuve'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $article = $stmt->fetch(PDO::FETCH_OBJ);

        if ($article && !$is_authenticated) {
            // If user is not authenticated, return only a preview of the content
            $article->contenu = substr($article->contenu, 0, 200) . '...';
            $article->is_preview = true;
        } else if ($article) {
            $article->is_preview = false;
        }

        return $article;
    }
}


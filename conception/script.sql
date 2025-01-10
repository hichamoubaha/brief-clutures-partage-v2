-- Création de la base de données
CREATE DATABASE culture_platform_v2;

-- Sélectionner la base de données
USE culture_platform_v2;

-- Table utilisateurs
CREATE TABLE utilisateurs (
    id_utilisateur INT AUTO_INCREMENT PRIMARY KEY,
    nom_utilisateur VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    role ENUM('admin', 'auteur', 'utilisateur', 'banned') NOT NULL,
    photo VARCHAR(255),
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table categories
CREATE TABLE categories (
    id_categorie INT AUTO_INCREMENT PRIMARY KEY,
    nom_categorie VARCHAR(50) NOT NULL UNIQUE,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table articles
CREATE TABLE articles (
    id_article INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    contenu TEXT NOT NULL,
    id_categorie INT,
    id_auteur INT,
    statut ENUM('en attente', 'approuve', 'refuse') DEFAULT 'en attente',
    photo VARCHAR(255) NOT NULL,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_categorie) REFERENCES categories(id_categorie),
    FOREIGN KEY (id_auteur) REFERENCES utilisateurs(id_utilisateur)
);

-- Table commentaires
CREATE TABLE commentaires (
    id_commentaire INT AUTO_INCREMENT PRIMARY KEY,
    id_article INT,
    id_utilisateur INT,
    contenu TEXT NOT NULL,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_article) REFERENCES articles(id_article),
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id_utilisateur)
);

-- Table likes
CREATE TABLE likes (
    id_like INT AUTO_INCREMENT PRIMARY KEY,
    id_article INT,
    id_utilisateur INT,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_article) REFERENCES articles(id_article),
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id_utilisateur),
    UNIQUE KEY (id_article, id_utilisateur)
);

-- Table favoris
CREATE TABLE favoris (
    id_favori INT AUTO_INCREMENT PRIMARY KEY,
    id_article INT,
    id_utilisateur INT,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_article) REFERENCES articles(id_article),
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id_utilisateur),
    UNIQUE KEY (id_article, id_utilisateur)
);

-- Table tags
CREATE TABLE tags (
    id_tag INT AUTO_INCREMENT PRIMARY KEY,
    nom_tag VARCHAR(50) NOT NULL UNIQUE,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table article_tags (relation many-to-many entre articles et tags)
CREATE TABLE article_tags (
    id_article INT,
    id_tag INT,
    PRIMARY KEY (id_article, id_tag),
    FOREIGN KEY (id_article) REFERENCES articles(id_article),
    FOREIGN KEY (id_tag) REFERENCES tags(id_tag)
);

-- Insérer des catégories initiales
INSERT INTO categories (nom_categorie) VALUES
('Peinture'),
('Musique'),
('Littérature'),
('Cinéma'),
('Sculpture'),
('Photographie');

-- Insérer des utilisateurs initiaux
INSERT INTO utilisateurs (nom_utilisateur, email, mot_de_passe, role, photo) VALUES
('Admin1', 'admin1@example.com', 'admin1passwordhash', 'admin', 'admin1.jpg'),
('Auteur1', 'auteur1@example.com', 'auteur1passwordhash', 'auteur', 'auteur1.jpg'),
('Utilisateur1', 'utilisateur1@example.com', 'utilisateur1passwordhash', 'utilisateur', 'utilisateur1.jpg');

-- Insérer des articles initiaux
INSERT INTO articles (titre, contenu, id_categorie, id_auteur, statut, photo) VALUES
('Titre Article 1', 'Contenu de l\'Article 1', 1, 2, 'approuve', 'article1.jpg'),
('Titre Article 2', 'Contenu de l\'Article 2', 2, 2, 'en attente', 'article2.jpg'),
('Titre Article 3', 'Contenu de l\'Article 3', 3, 2, 'refuse', 'article3.jpg');

-- Insérer des tags initiaux
INSERT INTO tags (nom_tag) VALUES
('Art moderne'),
('Classique'),
('Contemporain'),
('Abstrait'),
('Réalisme');

-- Associer des tags aux articles
INSERT INTO article_tags (id_article, id_tag) VALUES
(1, 1), (1, 3), (1, 4),
(2, 2), (2, 5),
(3, 1), (3, 3);



-- 1 Création d'une vue

CREATE VIEW articles_les_plus_likes AS
SELECT a.titre, COUNT(l.id_like) AS nombre_likes, c.nom_categorie
FROM articles a
JOIN likes l ON a.id_article = l.id_article
JOIN categories c ON a.id_categorie = c.id_categorie
WHERE a.statut = 'approuve'
GROUP BY a.id_article
ORDER BY nombre_likes DESC;


-- 2 Création d'une procédure stockée 

DELIMITER $$

CREATE PROCEDURE bannir_utilisateur(IN p_id_utilisateur INT)
BEGIN
    UPDATE utilisateurs
    SET role = 'banned'
    WHERE id_utilisateur = p_id_utilisateur;
END $$

DELIMITER ;

-- 3 Requête SQL

SELECT t.nom_tag, COUNT(at.id_article) AS nombre_associations
FROM tags t
JOIN article_tags at ON t.id_tag = at.id_tag
JOIN articles a ON at.id_article = a.id_article
WHERE a.date_creation >= NOW() - INTERVAL 30 DAY
GROUP BY t.id_tag
ORDER BY nombre_associations DESC;
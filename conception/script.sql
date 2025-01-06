-- Création de la base de données
CREATE DATABASE art_culture_platform;

-- Sélectionner la base de données
USE art_culture_platform;

-- Table utilisateurs
CREATE TABLE utilisateurs (
    id_utilisateur INT AUTO_INCREMENT PRIMARY KEY,
    nom_utilisateur VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    role ENUM('admin', 'auteur', 'utilisateur') NOT NULL,
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
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_categorie) REFERENCES categories(id_categorie),
    FOREIGN KEY (id_auteur) REFERENCES utilisateurs(id_utilisateur)
);

-- Modifier la table utilisateurs pour ajouter une colonne photo
ALTER TABLE utilisateurs ADD COLUMN photo VARCHAR(255);

-- Modifier la table articles pour ajouter une colonne photo
ALTER TABLE articles ADD COLUMN photo VARCHAR(255);

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

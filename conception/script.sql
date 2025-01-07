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
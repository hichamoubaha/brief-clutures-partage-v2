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
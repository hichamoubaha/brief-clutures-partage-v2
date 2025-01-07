<?php
require_once 'EmailSender.php';

class User {
    private $conn;
    private $table_name = "utilisateurs";

    public function __construct($db) {
        $this->conn = $db->getConnection();
    }

    public function register($nom_utilisateur, $email, $password, $role, $photo) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $query = "INSERT INTO " . $this->table_name . " (nom_utilisateur, email, mot_de_passe, role, photo) VALUES (:nom_utilisateur, :email, :password, :role, :photo)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nom_utilisateur', $nom_utilisateur);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':photo', $photo);

        if ($stmt->execute()) {
            $emailSender = new EmailSender();
            $emailSender->sendWelcomeEmail($email, $role);
            return true;
        } else {
            return false;
        }
    }

    public function login($email, $password) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['mot_de_passe']) && $user['role'] !== 'banned') {
            $_SESSION['user_id'] = $user['id_utilisateur'];
            $_SESSION['role'] = $user['role'];
            return true;
        } else {
            return false;
        }
    }

    public function updateProfile($id, $nom_utilisateur, $email, $password, $photo) {
        $query = "UPDATE " . $this->table_name . " SET nom_utilisateur = :nom_utilisateur, email = :email";
        
        if (!empty($password)) {
            $query .= ", mot_de_passe = :password";
        }
        
        if (!empty($photo)) {
            $query .= ", photo = :photo";
        }
        
        $query .= " WHERE id_utilisateur = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nom_utilisateur', $nom_utilisateur);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id', $id);
        
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt->bindParam(':password', $hashed_password);
        }
        
        if (!empty($photo)) {
            $stmt->bindParam(':photo', $photo);
        }

        return $stmt->execute();
    }

    public function getAllUsers() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id_utilisateur DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_utilisateur = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function banUser($id) {
        $query = "UPDATE " . $this->table_name . " SET role = 'banned' WHERE id_utilisateur = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function unbanUser($id) {
        $query = "UPDATE " . $this->table_name . " SET role = 'utilisateur' WHERE id_utilisateur = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function changeUserRole($id, $newRole) {
        $query = "UPDATE " . $this->table_name . " SET role = :role WHERE id_utilisateur = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':role', $newRole);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}


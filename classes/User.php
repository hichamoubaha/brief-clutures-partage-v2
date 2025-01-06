<?php
class User {
    private $conn;
    private $table_name = "utilisateurs";

    public function __construct($db) {
        $this->conn = $db->getConnection();
    }

    public function login($email, $password) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['mot_de_passe'])) {
            $_SESSION['user_id'] = $user['id_utilisateur'];
            $_SESSION['role'] = $user['role'];
            header('Location: index.php');
        } else {
            echo "Invalid email or password.";
        }
    }

    public function register($nom_utilisateur, $email, $password, $role) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Ensure only valid roles can be registered
        $allowed_roles = ['auteur', 'utilisateur'];
        $role = in_array($role, $allowed_roles) ? $role : 'utilisateur';

        $query = "INSERT INTO " . $this->table_name . " (nom_utilisateur, email, mot_de_passe, role) VALUES (:nom_utilisateur, :email, :password, :role)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nom_utilisateur', $nom_utilisateur);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':role', $role);

        if ($stmt->execute()) {
            header('Location: index.php?page=login');
        } else {
            echo "Error registering user.";
        }
    }

    public function logout() {
        session_unset();
        session_destroy();
        header('Location: index.php');
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
        $query = "UPDATE " . $this->table_name . " SET statut = 'banni' WHERE id_utilisateur = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function unbanUser($id) {
        $query = "UPDATE " . $this->table_name . " SET statut = 'actif' WHERE id_utilisateur = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function deleteUser($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_utilisateur = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}


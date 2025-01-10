<?php
class User {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function register($username, $email, $password, $role, $photo) {
        if ($username === null || $email === null || $password === null || $role === null) {
            return false;
        }
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO utilisateurs (nom_utilisateur, email, mot_de_passe, role, photo) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$username, $email, $hashedPassword, $role, $photo]);
    }

    public function login($email, $password) {
        $query = "SELECT * FROM utilisateurs WHERE email = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['mot_de_passe'])) {
            $_SESSION['user_id'] = $user['id_utilisateur'];
            $_SESSION['username'] = $user['nom_utilisateur'];
            $_SESSION['role'] = $user['role'];
            return true;
        }
        return false;
    }

    public function logout() {
        session_unset();
        session_destroy();
    }

    public function getUserById($id) {
        $query = "SELECT * FROM utilisateurs WHERE id_utilisateur = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllUsers() {
        $query = "SELECT * FROM utilisateurs ORDER BY nom_utilisateur";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateProfile($id, $username, $email, $password, $photo) {
        $query = "UPDATE utilisateurs SET nom_utilisateur = ?, email = ?";
        $params = [$username, $email];

        if ($password) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $query .= ", mot_de_passe = ?";
            $params[] = $hashedPassword;
        }

        if ($photo) {
            $query .= ", photo = ?";
            $params[] = $photo;
        }

        $query .= " WHERE id_utilisateur = ?";
        $params[] = $id;

        $stmt = $this->db->prepare($query);
        return $stmt->execute($params);
    }

    public function changeUserRole($id, $role) {
        $query = "UPDATE utilisateurs SET role = ? WHERE id_utilisateur = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$role, $id]);
    }

    public function banUser($id) {
        $query = "UPDATE utilisateurs SET role = 'banned' WHERE id_utilisateur = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$id]);
    }

    public function unbanUser($id) {
        $query = "UPDATE utilisateurs SET role = 'utilisateur' WHERE id_utilisateur = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$id]);
    }
}


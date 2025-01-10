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
    
        if ($user) {
            if ($user['role'] === 'banned') {
                echo "Votre compte a été banni.";
                return;
            }
            if (password_verify($password, $user['mot_de_passe'])) {
                session_start();
                $_SESSION['user_id'] = $user['id_utilisateur'];
                $_SESSION['role'] = $user['role'];
                header('Location: index.php');
                exit();
            }
        }
        echo "Email ou mot de passe invalide.";
    }

    public function register($nom_utilisateur, $email, $password, $role, $photo) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Ensure only valid roles can be registered
        $allowed_roles = ['admin', 'auteur', 'utilisateur'];
        $role = in_array($role, $allowed_roles) ? $role : 'utilisateur';

        // Handle photo upload
        $photo_path = '';
        if ($photo['error'] === UPLOAD_ERR_OK) {
            $upload_dir = 'uploads/';
            $photo_name = uniqid() . '_' . basename($photo['name']);
            $target_path = $upload_dir . $photo_name;
            
            if (move_uploaded_file($photo['tmp_name'], $target_path)) {
                $photo_path = $target_path;
            }
        }

        $query = "INSERT INTO " . $this->table_name . " (nom_utilisateur, email, mot_de_passe, role, photo) VALUES (:nom_utilisateur, :email, :password, :role, :photo)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nom_utilisateur', $nom_utilisateur);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':photo', $photo_path);

        if ($stmt->execute()) {
            header('Location: index.php?page=login');
            exit(); // Stop further script execution after a redirect
        } else {
            echo "Error registering user.";
        }
    }

    public function logout() {
        session_start(); // Ensure the session is started before destroying it
        session_unset();
        session_destroy();
        header('Location: index.php');
        exit(); // Stop further script execution after a redirect
    }

    public function getAllUsers() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id_utilisateur DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserById($id) { // Fixed method name (removed space in "getUser ById")
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_utilisateur = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); // Specify parameter type
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function banUser ($id) {
        $query = "UPDATE " . $this->table_name . " SET role = 'banned' WHERE id_utilisateur = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function unbanUser ($id) {
        $query = "UPDATE " . $this->table_name . " SET role = 'utilisateur' WHERE id_utilisateur = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function deleteUser($id) { // Fixed the incorrect PHP comment block
        $query = "DELETE FROM " . $this->table_name . " WHERE id_utilisateur = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); // Specify parameter type
        return $stmt->execute();
    }

    public function updateProfile($id, $nom_utilisateur, $email, $new_password = null, $photo = null) {
        $query = "UPDATE " . $this->table_name . " SET nom_utilisateur = :nom_utilisateur, email = :email";
        $params = [
            ':id' => $id,
            ':nom_utilisateur' => $nom_utilisateur,
            ':email' => $email
        ];

        if ($new_password) {
            $query .= ", mot_de_passe = :password";
            $params[':password'] = password_hash($new_password, PASSWORD_DEFAULT);
        }

        if ($photo && $photo['error'] === UPLOAD_ERR_OK) {
            $upload_dir = 'uploads/';
            $photo_name = uniqid() . '_' . basename($photo['name']);
            $target_path = $upload_dir . $photo_name;
            
            if (move_uploaded_file($photo['tmp_name'], $target_path)) {
                $query .= ", photo = :photo";
                $params[':photo'] = $target_path;

                // Delete old photo if exists
                $old_photo = $this->getUserById($id)['photo'];
                if ($old_photo && file_exists($old_photo)) {
                    unlink($old_photo);
                }
            }
        }

        $query .= " WHERE id_utilisateur = :id";

        $stmt = $this->conn->prepare($query);
        return $stmt->execute($params);
    }
}


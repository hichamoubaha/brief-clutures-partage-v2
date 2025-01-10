<?php
session_start();
define('ROOT_PATH', __DIR__);
require_once ROOT_PATH . '/config/Database.php';
require_once ROOT_PATH . '/classes/User.php';
require_once ROOT_PATH . '/classes/Article.php';
require_once ROOT_PATH . '/classes/Category.php';
require_once ROOT_PATH . '/classes/Comment.php';
require_once ROOT_PATH . '/classes/Like.php';
require_once ROOT_PATH . '/classes/Favorite.php';
require_once ROOT_PATH . '/classes/Tag.php';

$db = new Database();
$dbConnection = $db->getConnection();
$user = new User($dbConnection);
$article = new Article($dbConnection);
$category = new Category($dbConnection);
$comment = new Comment($dbConnection);
$like = new Like($dbConnection);
$favorite = new Favorite($dbConnection);
$tag = new Tag($dbConnection);

$page = isset($_GET['page']) ? $_GET['page'] : 'home';

include 'views/header.php';

switch ($page) {
    case 'home':
        $categories = $category->getAllCategories();
        $latestArticles = $article->getLatestArticles(6);
        include 'views/home.php';
        break;
    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];
            if ($user->login($email, $password)) {
                header('Location: index.php');
            } else {
                $error = "Invalid email or password";
            }
        }
        include 'views/login.php';
        break;
    case 'register':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['nom_utilisateur'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $role = $_POST['role'];
            $photo = '';
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'uploads/profiles/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $photoName = uniqid() . '_' . basename($_FILES['photo']['name']);
                $uploadFile = $uploadDir . $photoName;
                if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadFile)) {
                    $photo = $uploadFile;
                }
            }
            if ($user->register($username, $email, $password, $role, $photo)) {
                header('Location: index.php?page=login');
            } else {
                $error = "Registration failed";
            }
        }
        include 'views/register.php';
        break;
    case 'logout':
        $user->logout();
        header('Location: index.php');
        break;
    case 'article':
        if (isset($_GET['id'])) {
            $articleData = $article->getArticleById($_GET['id']);
            $comments = $comment->getCommentsByArticle($_GET['id']);
            $tags = $tag->getTagsByArticle($_GET['id']);
            include 'views/article.php';
        } else {
            header('Location: index.php');
        }
        break;
    case 'create_article':
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'auteur') {
            header('Location: index.php');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'];
            $content = $_POST['content'];
            $categoryId = $_POST['category'];
            $tags = isset($_POST['tags']) ? $_POST['tags'] : [];
            $photo = '';
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'uploads/articles/';
                $photoName = uniqid() . '_' . basename($_FILES['photo']['name']);
                $uploadFile = $uploadDir . $photoName;
                if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadFile)) {
                    $photo = $uploadFile;
                }
            }
            if ($article->createArticle($title, $content, $categoryId, $_SESSION['user_id'], $photo, $tags)) {
                header('Location: index.php?page=dashboard');
            } else {
                $error = "Failed to create article";
            }
        }
        $categories = $category->getAllCategories();
        $allTags = $tag->getAllTags();
        include 'views/create_article.php';
        break;
    case 'edit_article':
        if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'auteur' && $_SESSION['role'] !== 'admin')) {
            header('Location: index.php');
            exit;
        }
        if (isset($_GET['id'])) {
            $articleData = $article->getArticleById($_GET['id']);
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $title = $_POST['title'];
                $content = $_POST['content'];
                $categoryId = $_POST['category'];
                $tags = isset($_POST['tags']) ? $_POST['tags'] : [];
                $photo = $articleData['photo'];
                if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = 'uploads/articles/';
                    $photoName = uniqid() . '_' . basename($_FILES['photo']['name']);
                    $uploadFile = $uploadDir . $photoName;
                    if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadFile)) {
                        $photo = $uploadFile;
                    }
                }
                if ($article->updateArticle($_GET['id'], $title, $content, $categoryId, $photo, $tags)) {
                    header('Location: index.php?page=dashboard');
                } else {
                    $error = "Failed to update article";
                }
            }
            $categories = $category->getAllCategories();
            $allTags = $tag->getAllTags();
            $articleTags = $tag->getTagsByArticle($_GET['id']);
            include 'views/edit_article.php';
        } else {
            header('Location: index.php');
        }
        break;
    case 'delete_article':
        if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'auteur' && $_SESSION['role'] !== 'admin')) {
            header('Location: index.php');
            exit;
        }
        if (isset($_GET['id'])) {
            if ($article->deleteArticle($_GET['id'])) {
                header('Location: index.php?page=dashboard');
            } else {
                $error = "Failed to delete article";
                include 'views/error.php';
            }
        } else {
            header('Location: index.php');
        }
        break;
    case 'dashboard':
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }
        if ($_SESSION['role'] === 'admin') {
            $pendingArticles = $article->getPendingArticles();
            $allUsers = $user->getAllUsers();
        } elseif ($_SESSION['role'] === 'auteur') {
            $userArticles = $article->getArticlesByAuthor($_SESSION['user_id']);
        }
        include 'views/dashboard.php';
        break;
    case 'approve_article':
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php');
            exit;
        }
        if (isset($_GET['id'])) {
            if ($article->approveArticle($_GET['id'])) {
                header('Location: index.php?page=dashboard');
            } else {
                $error = "Failed to approve article";
                include 'views/error.php';
            }
        } else {
            header('Location: index.php');
        }
        break;
    case 'reject_article':
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php');
            exit;
        }
        if (isset($_GET['id'])) {
            if ($article->rejectArticle($_GET['id'])) {
                header('Location: index.php?page=dashboard');
            } else {
                $error = "Failed to reject article";
                include 'views/error.php';
            }
        } else {
            header('Location: index.php');
        }
        break;
    case 'manage_categories':
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['action'])) {
                switch ($_POST['action']) {
                    case 'add':
                        $category->addCategory($_POST['name']);
                        break;
                    case 'edit':
                        $category->updateCategory($_POST['id'], $_POST['name']);
                        break;
                    case 'delete':
                        $category->deleteCategory($_POST['id']);
                        break;
                }
            }
        }
        $categories = $category->getAllCategories();
        include 'views/manage_categories.php';
        break;
    case 'manage_users':
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['action'])) {
                switch ($_POST['action']) {
                    case 'change_role':
                        $user->changeUserRole($_POST['id'], $_POST['role']);
                        break;
                    case 'ban':
                        $user->banUser($_POST['id']);
                        break;
                    case 'unban':
                        $user->unbanUser($_POST['id']);
                        break;
                }
            }
        }
        $users = $user->getAllUsers();
        include 'views/manage_users.php';
        break;
    case 'profile':
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }
        $userData = $user->getUserById($_SESSION['user_id']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = !empty($_POST['password']) ? $_POST['password'] : null;
            $photo = $userData['photo'];
            $uploadDir = 'uploads/profiles/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                $photoName = uniqid() . '_' . basename($_FILES['photo']['name']);
                $uploadFile = $uploadDir . $photoName;
                if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadFile)) {
                    $photo = $uploadFile;
                }
            }
            if ($user->updateProfile($_SESSION['user_id'], $username, $email, $password, $photo)) {
                $success = "Profile updated successfully";
            } else {
                $error = "Failed to update profile";
            }
            $userData = $user->getUserById($_SESSION['user_id']);
        }
        include 'views/profile.php';
        break;
    case 'add_comment':
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['article_id']) && isset($_POST['content'])) {
            if ($comment->addComment($_POST['article_id'], $_SESSION['user_id'], $_POST['content'])) {
                header('Location: index.php?page=article&id=' . $_POST['article_id']);
            } else {
                $error = "Failed to add comment";
                include 'views/error.php';
            }
        } else {
            header('Location: index.php');
        }
        break;
    case 'delete_comment':
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }
        if (isset($_GET['id']) && isset($_GET['article_id'])) {
            if ($comment->deleteComment($_GET['id'], $_SESSION['user_id'], $_SESSION['role'])) {
                header('Location: index.php?page=article&id=' . $_GET['article_id']);
            } else {
                $error = "Failed to delete comment";
                include 'views/error.php';
            }
        } else {
            header('Location: index.php');
        }
        break;
    case 'like_article':
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }
        if (isset($_GET['id'])) {
            $like->toggleLike($_GET['id'], $_SESSION['user_id']);
            header('Location: index.php?page=article&id=' . $_GET['id']);
        } else {
            header('Location: index.php');
        }
        break;
    case 'favorite_article':
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }
        if (isset($_GET['id'])) {
            $favorite->toggleFavorite($_GET['id'], $_SESSION['user_id']);
            header('Location: index.php?page=article&id=' . $_GET['id']);
        } else {
            header('Location: index.php');
        }
        break;
    case 'search':
        $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
        $searchResults = $article->searchArticles($keyword);
        include 'views/search_results.php';
        break;
    case 'category':
        if (isset($_GET['id'])) {
            $categoryInfo = $category->getCategoryById($_GET['id']);
            $categoryArticles = $article->getArticlesByCategory($_GET['id']);
            include 'views/category.php';
        } else {
            header('Location: index.php');
        }
        break;
    default:
        include 'views/404.php';
        break;
}

include 'views/footer.php';


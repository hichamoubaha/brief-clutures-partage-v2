<?php
session_start();
require_once 'classes/Database.php';
require_once 'classes/User.php';
require_once 'classes/Article.php';
require_once 'classes/Category.php';
require_once 'classes/Comment.php';
require_once 'classes/Favorite.php';
require_once 'classes/Like.php';

$db = new Database();
$user = new User($db);
$article = new Article($db);
$category = new Category($db);
$comment = new Comment($db);
$favorite = new Favorite($db);
$like = new Like($db);

$page = isset($_GET['page']) ? $_GET['page'] : 'home';

include 'views/header.php';

switch ($page) {
    case 'home':
        $categories = $category->getAllCategories();
        $latestArticles = $article->getLatestArticles(1, 6);  // Get first page of latest articles
        include 'views/home.php';
        break;
        case 'article':
            if (isset($_GET['id'])) {
                $is_authenticated = isset($_SESSION['user_id']);
                $articleData = $article->getArticleWithDetails($_GET['id'], $is_authenticated);
                $comments = $comment->getCommentsByArticle($_GET['id']);
                $likesCount = $like->getLikesByArticle($_GET['id']);
                if ($articleData) {
                    include 'views/article_detail.php';
                } else {
                    header('Location: index.php');
                }
            } else {
                header('Location: index.php');
            }
            break;
    
        case 'add_comment':
            if (isset($_POST['id_article']) && isset($_SESSION['user_id'])) {
                $comment->addComment($_POST['id_article'], $_SESSION['user_id'], $_POST['contenu']);
                header('Location: index.php?page=article&id=' . $_POST['id_article']);
            }
            break;
    
        case 'like_article':
            if (isset($_POST['id_article']) && isset($_SESSION['user_id'])) {
                $like->addLike($_POST['id_article'], $_SESSION['user_id']);
                header('Location: index.php?page=article&id=' . $_POST['id_article']);
            }
            break;
    
        case 'favorite_article':
            if (isset($_POST['id_article']) && isset($_SESSION['user_id'])) {
                $favorite->addFavorite($_POST['id_article'], $_SESSION['user_id']);
                header('Location: index.php?page=article&id=' . $_POST['id_article']);
            }
            break;
    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user->login($_POST['email'], $_POST['password']);
        } else {
            include 'views/login.php';
        }
        break;
    case 'register':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $photo = isset($_FILES['photo']) ? $_FILES['photo'] : null;
            $user->register($_POST['nom_utilisateur'], $_POST['email'], $_POST['password'], $_POST['role'], $photo);
        } else {
            include 'views/register.php';
        }
        break;
    case 'dashboard':
        if (isset($_SESSION['user_id'])) {
            $userRole = $_SESSION['role'];
            if ($userRole === 'admin') {
                $allUsers = $user->getAllUsers();
                $allCategories = $category->getAllCategories();
                $pendingArticles = $article->getPendingArticles();
            } elseif ($userRole === 'auteur') {
                $userArticles = $article->getUserArticles($_SESSION['user_id']); // Corrected method name
            }
            include 'views/dashboard.php';
        } else {
            header('Location: index.php');
        }
        break;
    case 'create_article':
        if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'auteur') {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $photo = '';
                if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = 'uploads/';
                    $photoName = uniqid() . '_' . basename($_FILES['photo']['name']);
                    $uploadFile = $uploadDir . $photoName;
                    if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadFile)) {
                        $photo = $uploadFile;
                    }
                }
                $article->createArticle($_POST['titre'], $_POST['contenu'], $_POST['id_categorie'], $_SESSION['user_id'], $photo);
            } else {
                $allCategories = $category->getAllCategories();
                include 'views/create_article.php';
            }
        } else {
            header('Location: index.php');
        }
        break;
    case 'edit_article':
        if (isset($_SESSION['user_id']) && isset($_GET['id'])) {
            $articleData = $article->getArticleById($_GET['id']);
            if ($articleData && ($articleData['id_auteur'] == $_SESSION['user_id'] || $_SESSION['role'] === 'admin')) {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $photo = $articleData['photo'];
                    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                        $uploadDir = 'uploads/';
                        $photoName = uniqid() . '_' . basename($_FILES['photo']['name']);
                        $uploadFile = $uploadDir . $photoName;
                        if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadFile)) {
                            $photo = $uploadFile;
                            if (!empty($articleData['photo']) && file_exists($articleData['photo'])) {
                                unlink($articleData['photo']);
                            }
                        }
                    }
                    $article->updateArticle($_GET['id'], $_POST['titre'], $_POST['contenu'], $_POST['id_categorie'], $photo);
                    header('Location: index.php?page=dashboard');
                } else {
                    $allCategories = $category->getAllCategories();
                    include 'views/edit_article.php';
                }
            } else {
                header('Location: index.php');
            }
        } else {
            header('Location: index.php');
        }
        break;
    case 'delete_article':
        if (isset($_SESSION['user_id']) && isset($_GET['id'])) {
            $article->deleteArticle($_GET['id'], $_SESSION['user_id'], $_SESSION['role']);
        }
        header('Location: index.php?page=dashboard');
        break;
    case 'approve_article':
        if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'admin' && isset($_GET['id'])) {
            $article->approveArticle($_GET['id']);
            header('Location: index.php?page=dashboard');
        } else {
            header('Location: index.php');
        }
        break;
    case 'reject_article':
        if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'admin' && isset($_GET['id'])) {
            $article->rejectArticle($_GET['id']);
            header('Location: index.php?page=dashboard');
        } else {
            header('Location: index.php');
        }
        break;
    case 'manage_categories':
        if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'admin') {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_POST['action'])) {
                    switch ($_POST['action']) {
                        case 'create':
                            $category->createCategory($_POST['nom_categorie']);
                            break;
                        case 'update':
                            $category->updateCategory($_POST['id_categorie'], $_POST['nom_categorie']);
                            break;
                        case 'delete':
                            $category->deleteCategory($_POST['id_categorie']);
                            break;
                    }
                }
            }
            $allCategories = $category->getAllCategories();
            include 'views/manage_categories.php';
        } else {
            header('Location: index.php');
        }
        break;
    case 'user_profile':
        if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'admin' && isset($_GET['id'])) {
            $userData = $user->getUserById($_GET['id']);
            include 'views/user_profile.php';
        } else {
            header('Location: index.php');
        }
        break;
        case 'ban_user':
            if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'admin' && isset($_GET['id'])) {
                $user->banUser ($_GET['id']);
                header('Location: index.php?page=dashboard');
            } else {
                header('Location: index.php');
            }
            break;
        
        case 'unban_user':
            if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'admin' && isset($_GET['id'])) {
                $user->unbanUser ($_GET['id']);
                header('Location: index.php?page=dashboard');
            } else {
                header('Location: index.php');
            }
            break;
    case 'delete_user':
        if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'admin' && isset($_GET['id'])) {
            $user->deleteUser($_GET['id']);
            header('Location: index.php?page=dashboard');
        } else {
            header('Location: index.php');
        }
        break;
    case 'logout':
        $user->logout();
        break;
    case 'edit_profile':
        if (isset($_SESSION['user_id'])) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $photo = isset($_FILES['photo']) ? $_FILES['photo'] : null;
                if ($user->updateProfile($_SESSION['user_id'], $_POST['nom_utilisateur'], $_POST['email'], $_POST['new_password'], $photo)) {
                    header('Location: index.php?page=dashboard&message=profile_updated');
                } else {
                    header('Location: index.php?page=edit_profile&error=update_failed');
                }
            } else {
                include 'views/edit_profile.php';
            }
        } else {
            header('Location: index.php');
        }
        break;
    case 'search':
        $searchQuery = isset($_GET['query']) ? trim($_GET['query']) : '';
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $limit = 6;

        if (!empty($searchQuery)) {
            $searchResults = $article->searchArticles($searchQuery, $page, $limit);
            $totalResults = $article->getTotalSearchResults($searchQuery);
            $totalPages = ceil($totalResults / $limit);
        } else {
            $searchResults = [];
            $totalPages = 0;
        }

        include 'views/search_results.php';
        break;
    default:
        header('Location: index.php');
        break;
}

include 'views/footer.php';


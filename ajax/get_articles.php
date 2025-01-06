<?php
require_once '../classes/Database.php';
require_once '../classes/Article.php';

$db = new Database();
$article = new Article($db);

$category = isset($_GET['category']) ? $_GET['category'] : 'all';
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$per_page = 6;

if ($category === 'all') {
    $articles = $article->getLatestArticles($page, $per_page);
    $total_articles = $article->getTotalArticles();
} else {
    $articles = $article->getArticlesByCategory($category, $page, $per_page);
    $total_articles = $article->getTotalArticlesByCategory($category);
}

$total_pages = ceil($total_articles / $per_page);

$articles_html = '';
foreach ($articles as $article) {
    $articles_html .= '
    <div class="bg-white rounded-lg shadow-md overflow-hidden transition-transform hover:scale-105">
        ' . (!empty($article['photo']) ? '<img src="' . htmlspecialchars($article['photo']) . '" alt="' . htmlspecialchars($article['titre']) . '" class="w-full h-48 object-cover">' : '<div class="w-full h-48 bg-gray-200 flex items-center justify-center"><span class="text-gray-500">Pas d\'image</span></div>') . '
        <div class="p-6">
            <h2 class="text-xl font-semibold mb-2">
                <a href="index.php?page=article&id=' . $article['id_article'] . '" class="text-blue-600 hover:text-blue-800 transition-colors">
                    ' . htmlspecialchars($article['titre']) . '
                </a>
            </h2>
            <p class="text-gray-600 mb-4">' . substr(htmlspecialchars($article['contenu']), 0, 150) . '...</p>
            <div class="flex justify-between items-center text-sm text-gray-500">
                <span>Catégorie: ' . htmlspecialchars($article['nom_categorie']) . '</span>
                <span>Auteur: ' . htmlspecialchars($article['nom_utilisateur']) . '</span>
            </div>
        </div>
    </div>';
}

$pagination_html = '';
if ($total_pages > 1) {
    $pagination_html .= '<div class="flex justify-center space-x-2">';
    for ($i = 1; $i <= $total_pages; $i++) {
        $active_class = $i === $page ? 'bg-blue-500 text-white' : 'bg-white text-blue-500 hover:bg-blue-100';
        $pagination_html .= '<a href="#" class="pagination-link ' . $active_class . ' font-bold py-2 px-4 rounded transition-colors" data-page="' . $i . '">' . $i . '</a>';
    }
    $pagination_html .= '</div>';
}

echo json_encode([
    'articles' => $articles_html,
    'pagination' => $pagination_html
]);
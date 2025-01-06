<?php
$is_authenticated = isset($_SESSION['user_id']);
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-4xl font-extrabold text-gray-900 mb-12 text-center">
        <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600">
            Derniers Articles
        </span>
    </h1>

    <div class="mb-12">
        <h2 class="text-2xl font-bold mb-6 text-gray-800 ">Catégories</h2>
        <div class="flex flex-wrap justify-center gap-3" id="category-filters">
            <button class="category-filter bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-2.5 px-5 rounded-full shadow-md transition-all duration-300 transform hover:scale-105" data-category="all">
                Toutes les catégories
            </button>
            <?php foreach ($categories as $category): ?>
                <button class="category-filter bg-white text-blue-600 border-2 border-blue-500 hover:bg-blue-50 font-semibold py-2 px-4 rounded-full shadow-sm transition-all duration-300 transform hover:scale-105" data-category="<?php echo $category['id_categorie']; ?>">
                    <?php echo htmlspecialchars($category['nom_categorie']); ?>
                </button>
            <?php endforeach; ?>
        </div>
    </div>

    <div id="articles-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php foreach ($latestArticles as $article): ?>
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-2xl hover:transform hover:-translate-y-2">
                <?php if (!empty($article['photo'])): ?>
                    <div class="relative h-56 overflow-hidden">
                        <img src="<?php echo htmlspecialchars($article['photo']); ?>" 
                             alt="<?php echo htmlspecialchars($article['titre']); ?>" 
                             class="w-full h-full object-cover transform hover:scale-110 transition-transform duration-500">
                    </div>
                <?php else: ?>
                    <div class="w-full h-56 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                        <span class="text-gray-400 text-lg font-medium">Pas d'image disponible</span>
                    </div>
                <?php endif; ?>
                
                <div class="p-8">
                    <h2 class="text-2xl font-bold mb-4 line-clamp-2">
                        <?php if ($is_authenticated): ?>
                            <a href="index.php?page=article&id=<?php echo $article['id_article']; ?>" 
                               class="text-gray-900 hover:text-blue-600 transition-colors duration-300">
                                <?php echo htmlspecialchars($article['titre']); ?>
                            </a>
                        <?php else: ?>
                            <?php echo htmlspecialchars($article['titre']); ?>
                        <?php endif; ?>
                    </h2>
                    
                    <p class="text-gray-600 mb-6 line-clamp-3">
                        <?php echo substr(htmlspecialchars($article['contenu']), 0, 150) . '...'; ?>
                    </p>
                    
                    <div class="flex flex-col space-y-3">
                        <div class="flex items-center space-x-2 text-sm text-gray-500">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                            </svg>
                            <span class="font-medium"><?php echo htmlspecialchars($article['nom_categorie']); ?></span>
                        </div>
                        
                        <div class="flex items-center space-x-2 text-sm text-gray-500">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                            <span class="font-medium"><?php echo htmlspecialchars($article['nom_utilisateur']); ?></span>
                        </div>
                    </div>
                    
                    <?php if (!$is_authenticated): ?>
                        <div class="mt-6">
                            <a href="index.php?page=login" 
                               class="inline-flex items-center justify-center w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold py-3 px-6 rounded-xl shadow-md transition-all duration-300 transform hover:scale-105">
                                Se connecter pour lire
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div id="pagination" class="mt-12 flex justify-center space-x-3"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentPage = 1;
    let currentCategory = 'all';

    function loadArticles(page, category) {
        // Add loading state
        document.getElementById('articles-container').classList.add('opacity-50');
        
        fetch(`ajax/get_articles.php?page=${page}&category=${category}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('articles-container').innerHTML = data.articles;
                document.getElementById('pagination').innerHTML = data.pagination;
                currentPage = page;
                currentCategory = category;
                
                // Remove loading state
                document.getElementById('articles-container').classList.remove('opacity-50');
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('articles-container').classList.remove('opacity-50');
            });
    }

    // Update category filter handling to show active state
    document.getElementById('category-filters').addEventListener('click', function(e) {
        if (e.target.classList.contains('category-filter')) {
            // Remove active state from all buttons
            document.querySelectorAll('.category-filter').forEach(btn => {
                btn.classList.remove('from-blue-600', 'to-blue-700', 'text-blue');
                btn.classList.add('text-blue-600', 'border-2', 'border-blue-500', 'bg-white');
            });
            
            // Add active state to clicked button
            e.target.classList.remove('text-blue-600', 'border-2', 'border-blue-500', 'bg-white');
            e.target.classList.add('from-blue-600', 'to-blue-700', 'text-blue');
            
            let category = e.target.dataset.category;
            loadArticles(1, category);
        }
    });

    document.getElementById('pagination').addEventListener('click', function(e) {
        if (e.target.classList.contains('pagination-link')) {
            e.preventDefault();
            let page = e.target.dataset.page;
            loadArticles(page, currentCategory);
        }
    });

    // Initial load
    loadArticles(1, 'all');
});
</script>

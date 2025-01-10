<h1 class="text-3xl font-bold mb-6">Bienvenue sur Art & Culture</h1>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    <?php foreach ($categories as $category): ?>
        <a href="index.php?page=category&id=<?php echo $category['id_categorie']; ?>" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
            <h2 class="text-xl font-semibold mb-2"><?php echo htmlspecialchars($category['nom_categorie']); ?></h2>
            <p class="text-gray-600">Explorez les articles de cette catégorie</p>
        </a>
    <?php endforeach; ?>
</div>

<h2 class="text-2xl font-bold mb-4">Derniers articles</h2>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php foreach ($latestArticles as $article): ?>
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <?php if (!empty($article['photo'])): ?>
                <img src="<?php echo htmlspecialchars($article['photo']); ?>" alt="<?php echo htmlspecialchars($article['titre']); ?>" class="w-full h-48 object-cover">
            <?php endif; ?>
            <div class="p-6">
                <h3 class="text-xl font-semibold mb-2">
                    <a href="index.php?page=article&id=<?php echo $article['id_article']; ?>" class="text-blue-600 hover:text-blue-800">
                        <?php echo htmlspecialchars($article['titre']); ?>
                    </a>
                </h3>
                <p class="text-gray-600 mb-4"><?php echo substr(htmlspecialchars($article['contenu']), 0, 100) . '...'; ?></p>
                <div class="flex justify-between text-sm text-gray-500">
                    <span>Par <?php echo htmlspecialchars($article['nom_utilisateur']); ?></span>
                    <span><?php echo htmlspecialchars($article['nom_categorie']); ?></span>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>


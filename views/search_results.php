<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Résultats de recherche pour "<?php echo htmlspecialchars($searchQuery); ?>"</h1>

    <?php if (empty($searchResults)): ?>
        <p class="text-gray-600">Aucun résultat trouvé pour votre recherche.</p>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($searchResults as $article): ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <?php if (!empty($article['photo'])): ?>
                        <img src="<?php echo htmlspecialchars($article['photo']); ?>" alt="<?php echo htmlspecialchars($article['titre']); ?>" class="w-full h-48 object-cover">
                    <?php endif; ?>
                    <div class="p-6">
                        <h2 class="text-xl font-semibold mb-2">
                            <a href="index.php?page=article&id=<?php echo $article['id_article']; ?>" class="text-blue-600 hover:text-blue-800 transition-colors">
                                <?php echo htmlspecialchars($article['titre']); ?>
                            </a>
                        </h2>
                        <p class="text-gray-600 mb-4"><?php echo substr(htmlspecialchars($article['contenu']), 0, 150) . '...'; ?></p>
                        <div class="flex justify-between items-center text-sm text-gray-500">
                            <span>Catégorie: <?php echo htmlspecialchars($article['nom_categorie']); ?></span>
                            <span>Auteur: <?php echo htmlspecialchars($article['nom_utilisateur']); ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <a href="index.php" class="inline-block mt-8 bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition-colors">
        Retour à l'accueil
    </a>
</div>


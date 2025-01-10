<h1 class="text-3xl font-bold mb-6">Tableau de bord</h1>

<?php if ($_SESSION['role'] === 'admin'): ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">Gestion des utilisateurs</h2>
            <a href="index.php?page=manage_users" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition-colors inline-block">
                Gérer les utilisateurs
            </a>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">Gestion des catégories</h2>
            <a href="index.php?page=manage_categories" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md transition-colors inline-block">
                Gérer les catégories
            </a>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">Gestion des tags</h2>
            <a href="index.php?page=manage_tags" class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-md transition-colors inline-block">
                Gérer les tags
            </a>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <h2 class="text-2xl font-semibold mb-4">Articles en attente d'approbation</h2>
        <?php if (!empty($pendingArticles)): ?>
            <div class="overflow-x-auto">
                <table class="w-full table-auto">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="px-4 py-2 text-left">Titre</th>
                            <th class="px-4 py-2 text-left">Auteur</th>
                            <th class="px-4 py-2 text-left">Catégorie</th>
                            <th class="px-4 py-2 text-left">Date de création</th>
                            <th class="px-4 py-2 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pendingArticles as $article): ?>
                            <tr>
                                <td class="border px-4 py-2"><?php echo htmlspecialchars($article['titre']); ?></td>
                                <td class="border px-4 py-2"><?php echo htmlspecialchars($article['nom_utilisateur']); ?></td>
                                <td class="border px-4 py-2"><?php echo htmlspecialchars($article['nom_categorie']); ?></td>
                                <td class="border px-4 py-2"><?php echo date('d/m/Y', strtotime($article['date_creation'])); ?></td>
                                <td class="border px-4 py-2">
                                    <a href="index.php?page=approve_article&id=<?php echo $article['id_article']; ?>" class="text-green-500 hover:text-green-700 mr-2">Approuver</a>
                                    <a href="index.php?page=reject_article&id=<?php echo $article['id_article']; ?>" class="text-red-500 hover:text-red-700">Rejeter</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-gray-600">Aucun article en attente d'approbation.</p>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php if ($_SESSION['role'] === 'auteur'): ?>
    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <h2 class="text-2xl font-semibold mb-4">Mes articles</h2>
        <a href="index.php?page=create_article" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md transition-colors inline-block mb-4">
            Créer un nouvel article
        </a>
        <?php if (!empty($userArticles)): ?>
            <div class="overflow-x-auto">
                <table class="w-full table-auto">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="px-4 py-2 text-left">Titre</th>
                            <th class="px-4 py-2 text-left">Catégorie</th>
                            <th class="px-4 py-2 text-left">Statut</th>
                            <th class="px-4 py-2 text-left">Date de création</th>
                            <th class="px-4 py-2 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($userArticles as $article): ?>
                            <tr>
                                <td class="border px-4 py-2"><?php echo htmlspecialchars($article['titre']); ?></td>
                                <td class="border px-4 py-2"><?php echo htmlspecialchars($article['nom_categorie']); ?></td>
                                <td class="border px-4 py-2"><?php echo htmlspecialchars($article['statut']); ?></td>
                                <td class="border px-4 py-2"><?php echo date('d/m/Y', strtotime($article['date_creation'])); ?></td>
                                <td class="border px-4 py-2">
                                    <a href="index.php?page=edit_article&id=<?php echo $article['id_article']; ?>" class="text-blue-500 hover:text-blue-700 mr-2">Modifier</a>
                                    <a href="index.php?page=delete_article&id=<?php echo $article['id_article']; ?>" class="text-red-500 hover:text-red-700" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?');">Supprimer</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-gray-600">Vous n'avez pas encore créé d'articles.</p>
        <?php endif; ?>
    </div>
<?php endif; ?>




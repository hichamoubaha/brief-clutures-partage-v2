<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <?php if ($_SESSION['role'] === 'admin'): ?>
    <div class="max-w-7xl mx-auto">
        <h1 class="text-4xl font-extrabold text-gray-900 mb-8">
            <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600">
                Tableau de bord administrateur
            </span>
        </h1>
        
        <!-- User Management Section -->
        <div class="mb-12 bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-800">Gestion des utilisateurs</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom d'utilisateur</th>
                            <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rôle</th>
                            <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date de création</th>
                            <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($allUsers as $user): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-4 px-6"><?php echo htmlspecialchars($user['nom_utilisateur']); ?></td>
                                <td class="py-4 px-6"><?php echo htmlspecialchars($user['email']); ?></td>
                                <td class="py-4 px-6">
                                    <span class="px-3 py-1 rounded-full text-sm font-medium
                                        <?php echo $user['role'] === 'admin' ? 'bg-purple-100 text-purple-800' : 
                                        ($user['role'] === 'auteur' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800'); ?>">
                                        <?php echo htmlspecialchars($user['role']); ?>
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-sm text-gray-500"><?php echo htmlspecialchars($user['date_creation']); ?></td>
                                <td class="py-4 px-6">
                                    <a href="index.php?page=user_profile&id=<?php echo $user['id_utilisateur']; ?>" 
                                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 transition-colors">
                                        Voir le profil
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Category Management Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
            <div class="bg-white rounded-2xl shadow-lg p-8 transform transition-all hover:scale-105">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Gestion des catégories</h2>
                <a href="index.php?page=manage_categories" 
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-medium rounded-xl shadow-md transition-all">
                    Gérer les catégories →
                </a>
            </div>
        </div>

        <!-- Pending Articles Section -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-12">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Articles en attente d'approbation</h2>
            <div class="space-y-4">
                <?php foreach ($pendingArticles as $article): ?>
                    <div class="p-6 border border-gray-200 rounded-xl hover:shadow-md transition-shadow">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2"><?php echo htmlspecialchars($article['titre']); ?></h3>
                        <p class="text-gray-600 mb-4">Par <?php echo htmlspecialchars($article['nom_utilisateur']); ?></p>
                        <div class="flex space-x-3">
                            <a href="index.php?page=approve_article&id=<?php echo $article['id_article']; ?>" 
                               class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                                Approuver
                            </a>
                            <a href="index.php?page=reject_article&id=<?php echo $article['id_article']; ?>" 
                               class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
                                Rejeter
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($_SESSION['role'] === 'auteur'): ?>
    <div class="max-w-7xl mx-auto">
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl font-bold text-gray-800">Mes articles</h2>
                <a href="index.php?page=create_article" 
                   class="px-6 py-3 bg-gradient-to-r from-green-600 to-green-500 hover:from-green-700 hover:to-green-600 text-white font-medium rounded-xl shadow-md transition-all">
                    Créer un nouvel article
                </a>
            </div>
            <div class="space-y-4">
                <?php foreach ($userArticles as $article): ?>
                    <div class="p-6 border border-gray-200 rounded-xl hover:shadow-md transition-all">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2"><?php echo htmlspecialchars($article['titre']); ?></h3>
                        <div class="flex flex-wrap gap-4 mb-4">
                            <span class="px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                <?php echo htmlspecialchars($article['nom_categorie']); ?>
                            </span>
                            <span class="px-3 py-1 rounded-full text-sm font-medium 
                                <?php echo $article['statut'] === 'publié' ? 'bg-green-100 text-green-800' : 
                                    ($article['statut'] === 'en_attente' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'); ?>">
                                <?php echo htmlspecialchars($article['statut']); ?>
                            </span>
                        </div>
                        <div class="flex space-x-3">
                            <a href="index.php?page=edit_article&id=<?php echo $article['id_article']; ?>" 
                               class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                                Modifier
                            </a>
                            <a href="index.php?page=delete_article&id=<?php echo $article['id_article']; ?>" 
                               class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors"
                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?');">
                                Supprimer
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
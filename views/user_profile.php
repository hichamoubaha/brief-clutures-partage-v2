<h1 class="text-3xl font-bold mb-6">Profil de l'utilisateur</h1>

<?php if (isset($userData)): ?>
    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="username">
                Nom d'utilisateur
            </label>
            <p class="text-gray-900"><?php echo htmlspecialchars($userData['nom_utilisateur']); ?></p>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                Email
            </label>
            <p class="text-gray-900"><?php echo htmlspecialchars($userData['email']); ?></p>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="role">
                Rôle
            </label>
            <p class="text-gray-900"><?php echo htmlspecialchars($userData['role']); ?></p>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="date_creation">
                Date de création
            </label>
            <p class="text-gray-900"><?php echo htmlspecialchars($userData['date_creation']); ?></p>
        </div>
        <div class="flex items-center justify-between mt-8">
            <?php if ($userData['role'] !== 'admin'): ?>
                <?php if ($userData['role'] === 'auteur'): ?>
                    <a href="index.php?page=user_articles&id=<?php echo $userData['id_utilisateur']; ?>" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Voir les articles
                    </a>
                <?php endif; ?>
                <a href="index.php?page=delete_user&id=<?php echo $userData['id_utilisateur']; ?>" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.');">
                    Supprimer l'utilisateur
                </a>
            <?php endif; ?>
        </div>
    </div>
<?php else: ?>
    <p class="text-red-500">Utilisateur non trouvé.</p>
<?php endif; ?>

<a href="index.php?page=dashboard" class="inline-block mt-4 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
    Retour au tableau de bord
</a>
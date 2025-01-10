<div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
    <div class="p-6">
        <h2 class="text-2xl font-bold mb-6 text-center">Profil de <?php echo htmlspecialchars($userData['nom_utilisateur']); ?></h2>
        
        <div class="mb-6 text-center">
            <?php if (!empty($userData['photo'])): ?>
                <img src="<?php echo htmlspecialchars($userData['photo']); ?>" alt="Photo de profil" class="w-32 h-32 rounded-full mx-auto mb-4 object-cover">
            <?php else: ?>
                <div class="w-32 h-32 rounded-full mx-auto mb-4 bg-gray-300 flex items-center justify-center text-gray-500">
                    <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                </div>
            <?php endif; ?>
        </div>
        
        <form action="index.php?page=profile" method="POST" enctype="multipart/form-data">
            <div class="mb-4">
                <label for="nom_utilisateur" class="block text-gray-700 text-sm font-bold mb-2">Nom d'utilisateur</label>
                <input type="text" id="nom_utilisateur" name="nom_utilisateur" value="<?php echo htmlspecialchars($userData['nom_utilisateur']); ?>" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($userData['email']); ?>" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Nouveau mot de passe (laisser vide pour ne pas changer)</label>
                <input type="password" id="password" name="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-6">
                <label for="photo" class="block text-gray-700 text-sm font-bold mb-2">Nouvelle photo de profil</label>
                <input type="file" id="photo" name="photo" accept="image/*" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Mettre à jour le profil
                </button>
            </div>
        </form>
    </div>
</div>


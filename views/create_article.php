<h1 class="text-3xl font-bold mb-6">Créer un nouvel article</h1>
<form action="index.php?page=create_article" method="POST" enctype="multipart/form-data" class="max-w-md mx-auto">
    <div class="mb-4">
        <label for="titre" class="block text-gray-700 text-sm font-bold mb-2">Titre</label>
        <input type="text" id="titre" name="titre" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
    </div>
    <div class="mb-4">
        <label for="contenu" class="block text-gray-700 text-sm font-bold mb-2">Contenu</label>
        <textarea id="contenu" name="contenu" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="6"></textarea>
    </div>
    <div class="mb-4">
        <label for="id_categorie" class="block text-gray-700 text-sm font-bold mb-2">Catégorie</label>
        <select id="id_categorie" name="id_categorie" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            <?php foreach ($allCategories as $category): ?>
                <option value="<?php echo $category['id_categorie']; ?>"><?php echo htmlspecialchars($category['nom_categorie']); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-6">
        <label for="photo" class="block text-gray-700 text-sm font-bold mb-2">Image de l'article</label>
        <input type="file" id="photo" name="photo" accept="image/*" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
    </div>
    <div class="flex items-center justify-between">
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
            Publier l'article
        </button>
    </div>
</form>


<h1 class="text-3xl font-bold mb-6">Modifier l'article</h1>
<form action="index.php?page=edit_article&id=<?php echo $articleData['id_article']; ?>" method="POST" enctype="multipart/form-data" class="max-w-md mx-auto">
    <div class="mb-4">
        <label for="titre" class="block text-gray-700 text-sm font-bold mb-2">Titre</label>
        <input type="text" id="titre" name="titre" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo htmlspecialchars($articleData['titre']); ?>">
    </div>
    <div class="mb-4">
        <label for="contenu" class="block text-gray-700 text-sm font-bold mb-2">Contenu</label>
        <textarea id="contenu" name="contenu" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="6"><?php echo htmlspecialchars($articleData['contenu']); ?></textarea>
    </div>
    <div class="mb-4">
        <label for="id_categorie" class="block text-gray-700 text-sm font-bold mb-2">Catégorie</label>
        <select id="id_categorie" name="id_categorie" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            <?php foreach ($allCategories as $category): ?>
                <option value="<?php echo $category['id_categorie']; ?>" <?php echo ($category['id_categorie'] == $articleData['id_categorie']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($category['nom_categorie']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-4">
        <label for="photo" class="block text-gray-700 text-sm font-bold mb-2">Image de l'article</label>
        <?php if (!empty($articleData['photo'])): ?>
            <img src="<?php echo htmlspecialchars($articleData['photo']); ?>" alt="Current article image" class="mb-2 max-w-full h-auto">
        <?php endif; ?>
        <input type="file" id="photo" name="photo" accept="image/*" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
    </div>
    <div class="flex items-center justify-between">
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
            Mettre à jour l'article
        </button>
    </div>
</form>


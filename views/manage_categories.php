<h1 class="text-3xl font-bold mb-6">Gestion des catégories</h1>

<div class="bg-white rounded-lg shadow-md p-6 mb-8">
    <h2 class="text-xl font-semibold mb-4">Ajouter une nouvelle catégorie</h2>
    <form action="index.php?page=manage_categories" method="POST" class="mb-4">
        <input type="hidden" name="action" value="create">
        <div class="flex items-center">
            <input type="text" name="nom_categorie" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-2">
            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                Ajouter
            </button>
        </div>
    </form>
</div>

<div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-xl font-semibold mb-4">Liste des catégories</h2>
    <ul>
        <?php foreach ($allCategories as $category): ?>
            <li class="mb-4 p-4 border rounded flex items-center justify-between">
                <span><?php echo htmlspecialchars($category['nom_categorie']); ?></span>
                <div>
                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded mr-2 edit-category" data-id="<?php echo $category['id_categorie']; ?>" data-name="<?php echo htmlspecialchars($category['nom_categorie']); ?>">
                        Modifier
                    </button>
                    <form action="index.php?page=manage_categories" method="POST" class="inline">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id_categorie" value="<?php echo $category['id_categorie']; ?>">
                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?');">
                            Supprimer
                        </button>
                    </form>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<div id="edit-category-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <h3 class="text-lg font-semibold mb-4">Modifier la catégorie</h3>
        <form action="index.php?page=manage_categories" method="POST">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id_categorie" id="edit-category-id">
            <div class="mb-4">
                <label for="edit-category-name" class="block text-gray-700 text-sm font-bold mb-2">Nom de la catégorie</label>
                <input type="text" id="edit-category-name" name="nom_categorie" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="flex justify-end">
                <button type="button" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2" onclick="closeEditModal()">
                    Annuler
                </button>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditModal(id, name) {
    document.getElementById('edit-category-id').value = id;
    document.getElementById('edit-category-name').value = name;
    document.getElementById('edit-category-modal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('edit-category-modal').classList.add('hidden');
}

document.querySelectorAll('.edit-category').forEach(button => {
    button.addEventListener('click', function() {
        openEditModal(this.dataset.id, this.dataset.name);
    });
});
</script>


<?php
$is_authenticated = isset($_SESSION['user_id']);
?>

<div class="max-w-4xl mx-auto mt-8 p-6 bg-white shadow-lg rounded-lg">
    <h1 class="text-4xl font-extrabold mb-6 text-gray-800 border-b pb-2"> <?php echo htmlspecialchars($articleData->titre); ?> </h1>

    <div class="mb-6 text-gray-600 text-sm flex items-center space-x-4">
        <span class="flex items-center space-x-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                <path d="M10 2a6 6 0 100 12 6 6 0 000-12zm0 14c-4.418 0-8 1.79-8 4v1h16v-1c0-2.21-3.582-4-8-4z" />
            </svg>
            <span><?php echo htmlspecialchars($articleData->nom_utilisateur); ?></span>
        </span>
        <span class="mx-2">|</span>
        <span>Catégorie: <strong><?php echo htmlspecialchars($articleData->nom_categorie); ?></strong></span>
        <span class="mx-2">|</span>
        <span class="flex items-center space-x-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                <path d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V4a2 2 0 00-2-2H6z" />
            </svg>
            <span>Publié le <?php echo date('d/m/Y', strtotime($articleData->date_creation)); ?></span>
        </span>
    </div>

    <?php if (!empty($articleData->photo)): ?>
        <img src="<?php echo htmlspecialchars($articleData->photo); ?>" alt="<?php echo htmlspecialchars($articleData->titre); ?>" class="w-full h-64 object-cover rounded-lg mb-6">
    <?php endif; ?>

    <div class="prose max-w-none text-gray-800 leading-relaxed">
        <?php echo nl2br(htmlspecialchars($articleData->contenu)); ?>
    </div>

    <div class="mt-6 space-y-4">
        <form action="index.php?page=like_article" method="POST" class="inline-block">
            <input type="hidden" name="id_article" value="<?php echo $articleData->id_article; ?>">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded shadow-md focus:ring focus:ring-blue-300">
                J'aime (<?php echo $likesCount; ?>)
            </button>
        </form>
        <form action="index.php?page=favorite_article" method="POST" class="inline-block">
            <input type="hidden" name="id_article" value="<?php echo $articleData->id_article; ?>">
            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded shadow-md focus:ring focus:ring-green-300">
                Ajouter aux Favoris
            </button>
        </form>
        <button id="download-pdf" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded shadow-md mt-4">
    Télécharger en PDF
</button>
    </div>

    <h2 class="text-2xl font-bold mt-8 text-gray-800 border-b pb-2">Commentaires</h2>
    <div id="comments-section" class="mt-4 space-y-4">
        <?php foreach ($comments as $comment): ?>
            <div class="border-b pb-4">
                <p class="font-semibold text-gray-700"> <?php echo htmlspecialchars($comment['nom_utilisateur']); ?> </p>
                <p class="text-gray-600"> <?php echo nl2br(htmlspecialchars($comment['contenu'])); ?> </p>
                <span class="text-gray-500 text-sm block mt-1"> <?php echo date('d/m/Y H:i', strtotime($comment['date_creation'])); ?> </span>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if ($is_authenticated): ?>
        <h3 class="text-lg font-bold mt-8">Ajouter un commentaire</h3>
        <form action="index.php?page=add_comment" method="POST" class="mt-4">
            <input type="hidden" name="id_article" value="<?php echo $articleData->id_article; ?>">
            <textarea name="contenu" required class="border rounded w-full p-3 focus:outline-none focus:ring-2 focus:ring-blue-400" rows="4" placeholder="Écrivez votre commentaire ici..."></textarea>
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded shadow-md mt-4 focus:ring focus:ring-blue-300">
                Ajouter Commentaire
            </button>
        </form>
    <?php else: ?>
        <div class="mt-6 p-6 bg-yellow-100 border-l-4 border-yellow-500">
            <p class="font-bold">Contenu restreint</p>
            <p>Connectez-vous pour ajouter un commentaire.</p>
            <a href="index.php?page=login" class="inline-block mt-4 bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded shadow-md focus:ring focus:ring-blue-300">
                Se connecter
            </a>
        </div>
    <?php endif; ?>

    <a href="index.php" class="inline-block mt-8 bg-gray-700 hover:bg-gray-800 text-white font-semibold py-2 px-4 rounded shadow-md focus:ring focus:ring-gray-500">
        Retour à la liste des articles
    </a>
</div>

<script>
   document.getElementById('download-pdf').addEventListener('click', function() {
    // Define the content to be downloaded as PDF
    const content = `
        <h1>${<?php echo json_encode($articleData->titre); ?>}</h1>
        <p><strong>Auteur:</strong> ${<?php echo json_encode($articleData->nom_utilisateur); ?>}</p>
        <p><strong>Catégorie:</strong> ${<?php echo json_encode($articleData->nom_categorie); ?>}</p>
        <p><strong>Publié le:</strong> ${<?php echo json_encode(date('d/m/Y', strtotime($articleData->date_creation))); ?>}</p>
        <p>${<?php echo json_encode(nl2br($articleData->contenu)); ?>}</p>
    `;

    // Create the PDF using html2pdf
    const element = document.createElement('div');
    element.innerHTML = content;

    // Use html2pdf to generate and download the PDF
    html2pdf()
        .from(element)
        .save('<?php echo addslashes(htmlspecialchars($articleData->titre)); ?>.pdf');
});

</script>
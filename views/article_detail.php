<?php
$is_authenticated = isset($_SESSION['user_id']);
?>

<div class="max-w-4xl mx-auto mt-8">
    <h1 class="text-3xl font-bold mb-4"><?php echo htmlspecialchars($article->titre); ?></h1>
    
    <div class="mb-4 text-gray-600">
        <span>Par <?php echo htmlspecialchars($article->nom_utilisateur); ?></span>
        <span class="mx-2">|</span>
        <span>Catégorie: <?php echo htmlspecialchars($article->nom_categorie); ?></span>
        <span class="mx-2">|</span>
        <span>Publié le <?php echo date('d/m/Y', strtotime($article->date_creation)); ?></span>
    </div>

    <?php if (!empty($article->photo)): ?>
        <img src="<?php echo htmlspecialchars($article->photo); ?>" alt="<?php echo htmlspecialchars($article->titre); ?>" class="w-full h-64 object-cover rounded-lg mb-6">
    <?php endif; ?>

    <div class="prose max-w-none">
        <?php echo nl2br(htmlspecialchars($article->contenu)); ?>
        
        <?php if ($article->is_preview): ?>
            <div class="mt-4 p-4 bg-yellow-100 border-l-4 border-yellow-500">
                <p class="font-bold">Contenu restreint</p>
                <p>Connectez-vous pour lire l'article complet.</p>
                <a href="index.php?page=login" class="inline-block mt-2 bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded transition-colors">Se connecter</a>
            </div>
        <?php endif; ?>
    </div>

    <a href="index.php" class="inline-block mt-8 bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded transition-colors">
        Retour à la liste des articles
    </a>
</div>


<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <h1 class="text-3xl font-bold p-6 bg-gray-100"><?php echo htmlspecialchars($articleData['titre']); ?></h1>
    
    <?php if (!empty($articleData['photo'])): ?>
        <img src="<?php echo htmlspecialchars($articleData['photo']); ?>" alt="<?php echo htmlspecialchars($articleData['titre']); ?>" class="w-full h-64 object-cover">
    <?php endif; ?>
    
    <div class="p-6">
        <div class="flex justify-between text-sm text-gray-500 mb-4">
            <span>Par <?php echo htmlspecialchars($articleData['nom_utilisateur']); ?></span>
            <span><?php echo htmlspecialchars($articleData['nom_categorie']); ?></span>
            <span><?php echo date('d/m/Y', strtotime($articleData['date_creation'])); ?></span>
        </div>
        
        <div class="proseCertainly, I'll continue the text stream from the cut-off point:

creation'])); ?></span>
        </div>
        
        <div class="prose max-w-none mb-6">
            <?php echo nl2br(htmlspecialchars($articleData['contenu'])); ?>
        </div>
        
        <div class="flex items-center space-x-4 mb-6">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="index.php?page=like_article&id=<?php echo $articleData['id_article']; ?>" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition-colors">
                    J'aime (<?php echo $like->getLikeCount($articleData['id_article']); ?>)
                </a>
            <?php endif; ?>
        </div>
        
        <div class="mb-6">
            <h3 class="text-xl font-semibold mb-2">Tags</h3>
            <div class="flex flex-wrap gap-2">
                <?php foreach ($tags as $tag): ?>
                    <span class="bg-gray-200 text-gray-700 px-2 py-1 rounded-md text-sm">
                        <?php echo htmlspecialchars($tag['nom_tag']); ?>
                    </span>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div>
            <h3 class="text-xl font-semibold mb-4">Commentaires</h3>
            
            <?php if (isset($_SESSION['user_id'])): ?>
                <form action="index.php?page=add_comment" method="POST" class="mb-6">
                    <input type="hidden" name="id_article" value="<?php echo $articleData['id_article']; ?>">
                    <textarea name="contenu" rows="4" class="w-full p-2 border rounded-md" placeholder="Ajoutez votre commentaire..." required></textarea>
                    <button type="submit" class="mt-2 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition-colors">
                        Ajouter un commentaire
                    </button>
                </form>
            <?php endif; ?>
            
            <?php if (!empty($comments)): ?>
                <div class="space-y-4">
                    <?php foreach ($comments as $comment): ?>
                        <div class="bg-gray-100 p-4 rounded-md">
                            <div class="flex justify-between items-center mb-2">
                                <span class="font-semibold"><?php echo htmlspecialchars($comment['nom_utilisateur']); ?></span>
                                <span class="text-sm text-gray-500"><?php echo date('d/m/Y H:i', strtotime($comment['date_creation'])); ?></span>
                            </div>
                            <p><?php echo nl2br(htmlspecialchars($comment['contenu'])); ?></p>
                            <?php if (isset($_SESSION['user_id']) && ($_SESSION['user_id'] == $comment['id_utilisateur'] || $_SESSION['role'] === 'admin')): ?>
                                <div class="mt-2 text-right">
                                    <a href="index.php?page=delete_comment&id=<?php echo $comment['id_commentaire']; ?>&id_article=<?php echo $articleData['id_article']; ?>" class="text-red-500 hover:text-red-700" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ?');">
                                        Supprimer
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-gray-500">Aucun commentaire pour le moment.</p>
            <?php endif; ?>
        </div>
    </div>
</div>


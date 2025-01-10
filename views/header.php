<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plateforme Art et Culture</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <header class="bg-white shadow-md">
        <nav class="container mx-auto px-6 py-3">
            <div class="flex justify-between items-center">
                <div>
                    <a href="index.php" class="text-xl font-bold text-gray-800">Art & Culture</a>
                </div>
                <div class="flex items-center">
                    <a href="index.php" class="text-gray-800 hover:text-blue-600 px-3 py-2">Accueil</a>
                    <a href="index.php?page=search" class="text-gray-800 hover:text-blue-600 px-3 py-2">Recherche</a>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="index.php?page=dashboard" class="text-gray-800 hover:text-blue-600 px-3 py-2">Tableau de bord</a>
                        <a href="index.php?page=profile" class="text-gray-800 hover:text-blue-600 px-3 py-2">Profil</a>
                        <a href="index.php?page=logout" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md ml-2">Déconnexion</a>
                    <?php else: ?>
                        <a href="index.php?page=login" class="text-gray-800 hover:text-blue-600 px-3 py-2">Connexion</a>
                        <a href="index.php?page=register" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md ml-2">Inscription</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </header>
    <main class="container mx-auto px-6 py-8 flex-grow">


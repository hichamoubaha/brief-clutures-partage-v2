<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plateforme Art et Culture</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <header class="bg-white shadow-lg border-b border-gray-200">
        <nav class="container mx-auto px-6">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-2">
                    <!-- Logo/Brand -->
                    <a href="index.php" class="flex items-center group">
                    <img src="./uploads/logo.png" alt="Art & Culture Logo" class="h-24 w-24">
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="flex items-center space-x-8">
                    <a href="index.php" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors duration-300 border-b-2 border-transparent hover:border-indigo-600 py-1">
                        Accueil
                    </a>
                    
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'auteur'): ?>
                            <a href="index.php?page=dashboard" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors duration-300 border-b-2 border-transparent hover:border-indigo-600 py-1">
                                Dashboard
                            </a>
                        <?php endif; ?>
                        
                        <a href="index.php?page=logout" class="bg-blue-500 text-gray-700 hover:bg-gray-200 px-4 py-2 rounded-lg font-medium transition-colors duration-300">
                            Déconnexion
                        </a>
                    <?php else: ?>
                        <a href="index.php?page=login" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors duration-300 border-b-2 border-transparent hover:border-indigo-600 py-1">
                            Connexion
                        </a>
                        
                        <a href="index.php?page=register" class="bg-indigo-600 text-white hover:bg-indigo-700 px-4 py-2 rounded-lg font-medium transition-colors duration-300">
                            Inscription
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </header>
    <main class="container mx-auto px-4 py-8 flex-grow">
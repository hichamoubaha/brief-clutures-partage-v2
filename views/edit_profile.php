<?php
$user_id = $_SESSION['user_id'];
$userData = $user->getUserById($user_id);
?>

<div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <h1 class="text-center text-4xl font-extrabold text-gray-900 mb-8">
            <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600">
                Modifier votre profil
            </span>
        </h1>
        
        <div class="bg-white py-8 px-6 shadow-xl rounded-2xl">
            <form id="editProfileForm" action="index.php?page=edit_profile" method="POST" enctype="multipart/form-data" class="space-y-6">
                <div>
                    <label for="nom_utilisateur" class="block text-sm font-medium text-gray-700">
                        Nom d'utilisateur
                    </label>
                    <div class="mt-1">
                        <input type="text" 
                               id="nom_utilisateur" 
                               name="nom_utilisateur" 
                               value="<?php echo htmlspecialchars($userData['nom_utilisateur']); ?>"
                               required 
                               class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        Email
                    </label>
                    <div class="mt-1">
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="<?php echo htmlspecialchars($userData['email']); ?>"
                               required 
                               class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                    </div>
                </div>

                <div>
                    <label for="new_password" class="block text-sm font-medium text-gray-700">
                        Nouveau mot de passe (laisser vide pour ne pas changer)
                    </label>
                    <div class="mt-1">
                        <input type="password" 
                               id="new_password" 
                               name="new_password" 
                               class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                               placeholder="Nouveau mot de passe">
                    </div>
                </div>

                <div>
                    <label for="photo" class="block text-sm font-medium text-gray-700">
                        Nouvelle photo de profil
                    </label>
                    <div class="mt-1">
                        <input type="file" 
                               id="photo" 
                               name="photo" 
                               accept="image/*"
                               class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                    </div>
                </div>

                <?php if (!empty($userData['photo'])): ?>
                    <div>
                        <p class="text-sm font-medium text-gray-700">Photo de profil actuelle :</p>
                        <img src="<?php echo htmlspecialchars($userData['photo']); ?>" alt="Photo de profil" class="mt-2 w-32 h-32 object-cover rounded-full">
                    </div>
                <?php endif; ?>

                <div class="pt-4">
                    <button type="submit" 
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-[1.02]">
                        Mettre à jour le profil
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById("editProfileForm").addEventListener("submit", function(event) {
        event.preventDefault();

        const nomUtilisateur = document.getElementById("nom_utilisateur").value;
        const email = document.getElementById("email").value;
        const newPassword = document.getElementById("new_password").value;
        const photo = document.getElementById("photo").files[0];

        const usernameRegex = /^[a-zA-Z]+$/;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!usernameRegex.test(nomUtilisateur)) {
            Swal.fire("Erreur", "Le nom d'utilisateur doit contenir uniquement des lettres.", "error");
            return;
        }

        if (!emailRegex.test(email)) {
            Swal.fire("Erreur", "Veuillez entrer une adresse email valide.", "error");
            return;
        }

        if (newPassword && newPassword.length < 6) {
            Swal.fire("Erreur", "Le nouveau mot de passe doit contenir au moins 6 caractères.", "error");
            return;
        }

        if (photo) {
            if (!['image/jpeg', 'image/png', 'image/gif'].includes(photo.type)) {
                Swal.fire("Erreur", "Veuillez sélectionner une image valide (JPEG, PNG, ou GIF).", "error");
                return;
            }

            if (photo.size > 5 * 1024 * 1024) {
                Swal.fire("Erreur", "La taille de l'image ne doit pas dépasser 5 Mo.", "error");
                return;
            }
        }

        Swal.fire({
            title: "Succès",
            text: "Profil mis à jour avec succès!",
            icon: "success",
            confirmButtonText: "OK"
        }).then(() => {
            this.submit();
        });
    });
</script>


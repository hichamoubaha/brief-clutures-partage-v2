<div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <h1 class="text-center text-4xl font-extrabold text-gray-900 mb-8">
            <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600">
                Inscription
            </span>
        </h1>
        
        <div class="bg-white py-8 px-6 shadow-xl rounded-2xl">
            <form id="registrationForm" action="index.php?page=register" method="POST" class="space-y-6">
                <div>
                    <label for="nom_utilisateur" class="block text-sm font-medium text-gray-700">
                        Nom d'utilisateur
                    </label>
                    <div class="mt-1">
                        <input type="text" 
                               id="nom_utilisateur" 
                               name="nom_utilisateur" 
                               required 
                               class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                               placeholder="Entrez votre nom d'utilisateur">
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
                               required 
                               class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                               placeholder="vous@exemple.com">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        Mot de passe
                    </label>
                    <div class="mt-1">
                        <input type="password" 
                               id="password" 
                               name="password" 
                               required 
                               class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                               placeholder="••••••••">
                    </div>
                </div>

                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700">
                        Rôle
                    </label>
                    <div class="mt-1">
                        <select id="role" 
                                name="role" 
                                required 
                                class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all bg-white">
                            <option value="auteur">Auteur</option>
                            <option value="utilisateur">Utilisateur</option>
                        </select>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" 
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-[1.02]">
                        S'inscrire
                    </button>
                </div>

                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Déjà inscrit ?
                        <a href="index.php?page=login" class="font-medium text-blue-600 hover:text-blue-500 transition-colors">
                            Connectez-vous
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById("registrationForm").addEventListener("submit", function(event) {
        event.preventDefault(); // Prevent form submission

        // Get form values
        const nomUtilisateur = document.getElementById("nom_utilisateur").value;
        const email = document.getElementById("email").value;
        const password = document.getElementById("password").value;

        // Define validation regex
        const usernameRegex = /^[a-zA-Z]+$/;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; 
        const passwordRegex = /^.{6,}$/; 

        // Validate inputs
        if (!usernameRegex.test(nomUtilisateur)) {
            Swal.fire("Erreur", "Le nom d'utilisateur doit contenir uniquement des lettres.", "error");
            return;
        }

        if (!emailRegex.test(email)) {
            Swal.fire("Erreur", "Veuillez entrer une adresse email valide.", "error");
            return;
        }

        if (!passwordRegex.test(password)) {
            Swal.fire("Erreur", "Le mot de passe doit contenir au moins 6 caractères.", "error");
            return;
        }

        // If validation passes
        Swal.fire({
            title: "Succès",
            text: "Inscription réussie!",
            icon: "success",
            confirmButtonText: "OK"
        }).then(() => {
            this.submit(); // Submit the form
        });
    });
</script>
</body>
</html>

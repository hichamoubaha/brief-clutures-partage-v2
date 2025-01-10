<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}
?>

<h1 class="text-3xl font-bold mb-6">Gestion des utilisateurs</h1>

<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom d'utilisateur</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rôle</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php foreach ($users as $user): ?>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($user['nom_utilisateur']); ?></div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500"><?php echo htmlspecialchars($user['email']); ?></div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            <?php echo htmlspecialchars($user['role']); ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <form action="index.php?page=manage_users" method="POST" class="inline-block">
                            <input type="hidden" name="action" value="change_role">
                            <input type="hidden" name="id" value="<?php echo $user['id_utilisateur']; ?>">
                            <select name="role" onchange="this.form.submit()" class="text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="utilisateur" <?php echo $user['role'] === 'utilisateur' ? 'selected' : ''; ?>>Utilisateur</option>
                                <option value="auteur" <?php echo $user['role'] === 'auteur' ? 'selected' : ''; ?>>Auteur</option>
                                <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                            </select>
                        </form>
                        <?php if ($user['role'] !== 'banned'): ?>
                            <form action="index.php?page=manage_users" method="POST" class="inline-block ml-2">
                                <input type="hidden" name="action" value="ban">
                                <input type="hidden" name="id" value="<?php echo $user['id_utilisateur']; ?>">
                                <button type="submit" class="text-red-600 hover:text-red-900">Bannir</button>
                            </form>
                        <?php else: ?>
                            <form action="index.php?page=manage_users" method="POST" class="inline-block ml-2">
                                <input type="hidden" name="action" value="unban">
                                <input type="hidden" name="id" value="<?php echo $user['id_utilisateur']; ?>">
                                <button type="submit" class="text-green-600 hover:text-green-900">Débannir</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


<!-- reset_password.php -->
<?php
require_once __DIR__ . '/../templates/session_start.php';
require_once __DIR__ . '/../models/database.php';
require_once '/../reset_modelPassword.php'; 

// Récupérer le token de l'URL
if (!isset($_GET['token'])) {
    die('Token manquant');
}

$token = $_GET['token'];
$model = new PasswordResetModel();
$tokenData = $model->verifyToken($token, $pdo);

if ($tokenData) {
    // Le token est valide
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Récupérer le nouveau mot de passe
        $newPassword = $_POST['new_password'];

        // Mettre à jour le mot de passe
        $model->updatePassword($tokenData['user_id'], $newPassword, $pdo);

        // Supprimer le token après utilisation
        $model->deleteToken($token, $pdo);

        $_SESSION['success'] = "Votre mot de passe a été réinitialisé avec succès.";
        header('Location: clara/views/auth/login.php');  // Redirige l'utilisateur vers la page de connexion
        exit();
    }
} else {
    $_SESSION['error'] = "Le token est invalide ou a expiré.";
    header('Location: clara/views/auth/reset_password.php');
    exit();
}
?>

<form action="clara/controllers/resetPassword-process.php?token=<?php echo htmlspecialchars($token); ?>" method="POST">
    <label for="new_password">Nouveau mot de passe :</label>
    <input type="password" id="new_password" name="new_password" required>
    <button type="submit">Réinitialiser le mot de passe</button>
</form>

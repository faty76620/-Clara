<?php
require_once __DIR__ . '/../config.php';  // Inclure la configuration des constantes
require_once MODEL_DIR . '/database.php';
require_once MODEL_DIR . '/user.php';
require_once MODEL_DIR . '/establishment.php';
require_once MODEL_DIR . '/logs.php';

$conn = getConnexion();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = intval($_POST['id']);
    $firstname = htmlspecialchars(trim($_POST['firstname']));
    $lastname = htmlspecialchars(trim($_POST['lastname']));
    $mail = htmlspecialchars(trim($_POST['mail']));
    $establishment_id = intval($_POST['establishment_id']);
    $role_id = intval($_POST['role_id']);
    $admin_username = $_SESSION['username'] ?? 'Inconnu';

    // Vérifier si l'utilisateur existe
    $oldUser = getUserById($conn, $id);
    if (!$oldUser) {
        $_SESSION['error'] = "Utilisateur non trouvé !";
        addLog('Erreur', $admin_username, "Tentative de mise à jour d'un utilisateur inexistant (ID: $id).");
        header("Location: " . ROOT_DIR . "/views/admin/users.php");
        exit();
    }

    // Log des anciennes données avant la modification
    addLog('Modification utilisateur', $admin_username, "Anciennes données de l'utilisateur ID $id - Nom: {$oldUser['firstname']}, Email: {$oldUser['mail']}, Rôle: {$oldUser['role_id']}");

    // Mise à jour de l'utilisateur
    if (updateUser($conn, $id, $firstname, $lastname, $mail, $establishment_id, $role_id)) {
        $_SESSION['success'] = "Utilisateur mis à jour avec succès.";
        addLog('Succès', $admin_username, "Utilisateur ID $id mis à jour - Nouveau Nom: $firstname, Email: $mail, Rôle: $role_id");
    } else {
        $_SESSION['error'] = "Erreur lors de la mise à jour de l'utilisateur.";
        addLog('Erreur', $admin_username, "Échec de mise à jour de l'utilisateur ID $id");
    }

    // Redirection après la mise à jour
    header("Location: clara/views/admin/users.php");
    exit();
} else {
    $_SESSION['error'] = "Aucune donnée soumise.";
    addLog('Erreur', $_SESSION['username'], "Accès non autorisé à la mise à jour utilisateur.");
    header("Location: clara/views/admin/users.php");
    exit();
}
?>

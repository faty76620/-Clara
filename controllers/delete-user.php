<?php

require_once __DIR__ . '/../config.php';        
require_once MODEL_DIR . '/database.php';        
require_once MODEL_DIR . '/user.php';  
require_once MODEL_DIR . '/logs.php';          
require_once TEMPLATE_DIR . '/session_start.php'; 
$conn = getConnexion();

// Vérifier si un ID est passé et s'il est valide
if (!isset($_GET['id']) || empty($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "ID invalide !";
    addLog('Erreur', $_SESSION['user_id'] ?? null, "Tentative de suppression avec un ID invalide : " . ($_GET['id'] ?? 'non défini'));
    header("Location: ../views/admin/users.php");  
    exit();
}

$id = intval($_GET['id']);  // Convertir l'ID en entier

// Vérifier si l'utilisateur existe avant suppression
$user = getUserById($conn, $id);
if (!$user) {
    addLog('Erreur', $_SESSION['user_id'] ?? null, "Tentative de suppression d'un utilisateur inexistant (ID: $id).");
    $_SESSION['error'] = "Utilisateur introuvable.";
    header("Location: ../views/admin/users.php");
    exit();
}

// Supprimer l'utilisateur
if (deleteUser($conn, $id)) {
    addLog('Suppression utilisateur', $_SESSION['user_id'] ?? null, "Utilisateur '{$user['username']}' (ID: $id) supprimé.");
    $_SESSION['success'] = "Utilisateur supprimé avec succès.";
} else {
    $_SESSION['error'] = "Erreur lors de la suppression de l'utilisateur.";
    addLog('Erreur', $_SESSION['user_id'] ?? null, "Échec de suppression de l'utilisateur (ID: $id).");
}

// Redirection après suppression
header("Location: ../views/admin/users.php"); 
exit();
?>




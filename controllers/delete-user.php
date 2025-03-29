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
    addLog('Erreur', $_SESSION['user_id'], "Tentative de suppression avec un ID invalide.");
    header("Location: ../views/admin/users.php");  
    exit();
}

$id = intval($_GET['id']);  // Convertir l'ID en entier

// Vérifier si l'utilisateur existe avant suppression
$user = getUserById($conn, $id);
if (!$user) {
    $_SESSION['error'] = "Utilisateur introuvable.";
    addLog('Erreur', $_SESSION['user_id'], "Tentative de suppression d'un utilisateur inexistant (ID: $id).");
    header("Location: ../views/admin/users.php");
    exit();
}

// Supprimer l'utilisateur
if (deleteUser($conn, $id)) {
    $_SESSION['success'] = "Utilisateur supprimé avec succès.";
    addLog('Succès', $_SESSION['user_id'], "Utilisateur '{$user['username']}' (ID: $id) a été supprimé.");
} else {
    $_SESSION['error'] = "Erreur lors de la suppression de l'utilisateur.";
    addLog('Erreur', $_SESSION['user_id'], "Échec de suppression de l'utilisateur ID $id.");
}

// Redirection après suppression
header("Location: ../views/admin/users.php"); 
exit();
?>




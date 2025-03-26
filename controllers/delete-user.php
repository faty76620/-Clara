<?php
require_once '../templates/session_start.php';
require_once '../models/database.php';         
require_once '../models/user.php';             

// Vérifier si un ID est passé en URL et s'il est valide
if (!isset($_GET['id']) || empty($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "ID invalide !";
    header("Location: ../views/admin/users.php");  
    exit();
}

$id = intval($_GET['id']);  // Convertir l'ID en entier

if ($id <= 0) {
    $_SESSION['error'] = "ID invalide !";
    header("Location: ../views/admin/users.php");  
    exit();
}

$conn = getConnexion();  

// Supprimer l'utilisateur de la base de données
if (deleteUser($conn, $id)) {
    $_SESSION['success'] = "Utilisateur supprimé avec succès.";
} else {
    $_SESSION['error'] = "Erreur lors de la suppression de l'utilisateur.";
}

// Redirection après suppression
header("Location: ../views/admin/users.php"); 
exit();
?>

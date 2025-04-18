<?php
require_once __DIR__ . '/../config.php';        
require_once MODEL_DIR . '/database.php';        
require_once MODEL_DIR . '/establishment.php';  
require_once MODEL_DIR . '/logs.php';          
require_once TEMPLATE_DIR . '/session_start.php'; 


// Vérifier si un ID est passé en URL et s'il est valide
if (!isset($_GET['id']) || empty($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "ID invalide !";
    header("Location: ../views/admin/establishment.php");
    exit();
}

$id = intval($_GET['id']); 
if ($id <= 0) {
    $_SESSION['error'] = "ID invalide !";
    header("Location: ../views/admin/establishment.php");
    exit();
}

$conn = getConnexion();  

// Supprimer l'établissement de la base de données
if (deleteEstablishment($conn, $id)) {
    $_SESSION['success'] = "Établissement supprimé avec succès.";
    addLog('Suppression d\'établissement', $_SESSION['user_id'] ?? null, "Établissement ID $id supprimé.");
} else {
    $_SESSION['error'] = "Erreur lors de la suppression.";
}

// Redirection après suppression
header("Location: ../views/admin/establishment.php");
exit();
?>
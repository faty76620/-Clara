<?php
require_once __DIR__ . '/../config.php';        
require_once MODEL_DIR . '/database.php';         
require_once MODEL_DIR . '/logs.php';          
require_once TEMPLATE_DIR . '/session_start.php'; 

// Ajout d'un log de déconnexion
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    addLog('Info', $userId, "Déconnexion de l'utilisateur ID: $userId.");
}

// Effacer toutes les variables de session
session_unset(); 

// Détruire la session pour déconnecter l'utilisateur
session_destroy();

// Redirection vers la page de connexion
header("Location: /clara/views/auth/login.php");
exit();
?>





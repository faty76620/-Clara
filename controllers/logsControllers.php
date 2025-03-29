<?php

require_once __DIR__ . '/../config.php';        
require_once MODEL_DIR . '/database.php';         
require_once MODEL_DIR . '/logs.php';          
require_once TEMPLATE_DIR . '/session_start.php'; 

$conn = getConnexion();

// Vérification si l'utilisateur est connecté et a les droits d'administrateur
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] !== 1) {
    $_SESSION['error'] = "Accès refusé : vous n'avez pas les permissions nécessaires.";
    header("Location: ../../home.php");
    exit();
}

try {
    // Vérification de la méthode POST et des données nécessaires
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Vérifier que l'action est définie et valide
        if (empty($_POST['action'])) {
            throw new Exception("L'action est requise pour enregistrer un log.");
        }

        $action = trim($_POST['action']);
        $description = trim($_POST['description'] ?? '');
        $user_id = $_SESSION['user_id'];

        // Validation de l'action pour assurer sa validité
        if (strlen($action) > 255) {
            throw new Exception("L'action est trop longue (max 255 caractères).");
        }

        // Enregistrement du log dans la base de données
        if (!addLog($conn, $user_id, $action, $description)) {
            throw new Exception("Erreur lors de l'enregistrement du log.");
        }

        // Message de succès
        $_SESSION['success'] = "Log enregistré avec succès.";
        header("Location: ../views/logs.php");
        exit();
    } else {
        throw new Exception("Requête invalide.");
    }
} catch (Exception $e) {
    // Gestion des erreurs
    $_SESSION['error'] = $e->getMessage();
    header("Location: ../views/logs.php");
    exit();
}
?>



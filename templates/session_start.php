<?php
// Démarre la session si elle n'est pas déjà active
if (session_status() == PHP_SESSION_NONE) {
    session_start();
    session_regenerate_id(true); // Renouvelle l'ID de session pour éviter les attaques de fixation
}

// Durée d'expiration en secondes (ex: 30 minutes)
$session_duration = 30 * 60; 

// Vérifier si l'utilisateur est connecté
if (isset($_SESSION['user_id'])) { 
    // Vérifier si une session est déjà active
    if (isset($_SESSION['last_activity'])) {
        $elapsed_time = time() - $_SESSION['last_activity'];

        if ($elapsed_time > $session_duration) {
            // Détruire la session et rediriger vers la page de connexion avec un message d'expiration
            session_unset();
            session_destroy();
            header("Location: /clara/views/auth/login.php?expired=1");
            exit();
        }
    }

    // Mettre à jour le temps de la dernière activité
    $_SESSION['last_activity'] = time();
}

// Option : Déconnexion après fermeture du navigateur (pas besoin si `session.gc_maxlifetime` est configuré)
ini_set('session.cookie_lifetime', 0);
?>



<?php
require_once '../models/request.php';
require_once '../models/user.php';

// FONCTION POUR TRAITER LES DONNÉES DU FORMULAIRE ET CRÉER UN NOUVEL UTILISATEUR
function registerUser($data) {
    createUser($data);
    header("Location: admin_dashboard.php");
}

// RÉCUPÉRATION DES DEMANDES EN ATTENTE POUR AFFICHAGE DANS LE TABLEAU DE BORD
$pendingRequests = getPendingRequests();
?>
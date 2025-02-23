<?php
require_once '../models/request.php';
require_once '../models/user.php';

// FONCTION POUR AFFICHER LE FORMULAIRE D'INSCRIPTION D'UN NOUVEL UTILISATEUR
function showRegistrationForm($requestId) {
    $request = getRequestById($requestId);
    include '../views/auth/request_registration.php';
}

// FONCTION POUR TRAITER LES DONNÉES DU FORMULAIRE ET CRÉER UN NOUVEL UTILISATEUR
function registerUser($data) {
    createUser($data);
    header("Location: admin_dashboard.php");
}

// GESTION DES ACTIONS (VALIDATION, REJET, AFFICHAGE DU FORMULAIRE D'INSCRIPTION, INSCRIPTION)
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'process_request':
            $requestId = $_GET['id'];
            $status = $_GET['status'];
            processRequest($requestId, $status);
            break;
        case 'show_registration_form':
            $requestId = $_GET['id'];
            showRegistrationForm($requestId);
            break;
        case 'register_user':
            $data = [
                'username' => $_POST['username'],
                'password' => $_POST['password'],
                'email' => $_POST['email'],
                'establishment_id' => $_POST['establishment_id'],
                'role_id' => $_POST['role_id']
            ];
            registerUser($data);
            break;
    }
}

// RÉCUPÉRATION DES DEMANDES EN ATTENTE POUR AFFICHAGE DANS LE TABLEAU DE BORD
$pendingRequests = getPendingRequests();
?>
<?php
require_once '../models/request.php';
require_once '../models/user.php';

// TRAITER LA DEMANDE D'INSCRIPTION
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'firstname_admin' => filter_input(INPUT_POST, 'firstname_admin', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
        'name_admin' => filter_input(INPUT_POST, 'name_admin', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
        'role' => filter_input(INPUT_POST, 'role', FILTER_VALIDATE_INT),
        'mail_admin' => filter_input(INPUT_POST, 'mail_admin', FILTER_VALIDATE_EMAIL),
        'firstname_establishment' => filter_input(INPUT_POST, 'firstname_establishment', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
        'adresse' => filter_input(INPUT_POST, 'adresse', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
        'type_role' => filter_input(INPUT_POST, 'type_role', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
        'siret' => filter_input(INPUT_POST, 'siret', FILTER_SANITIZE_NUMBER_INT),
        'mail' => filter_input(INPUT_POST, 'mail', FILTER_VALIDATE_EMAIL),
        'phone' => filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_NUMBER_INT), 
        'site' => filter_input(INPUT_POST, 'site', FILTER_VALIDATE_URL),
        'description' => filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
        'cgu' => isset($_POST['cgu']) ? 1 : 0
    ];
    
    createRequest($data);
    header("Location: /clara/views/home/request_success.php");
}

// TRAITER LA VALIDATION/REJET D'UNE DEMANDE
function processRequest($requestId, $status) {
    updateRequestStatus($requestId, $status);
    $request = getRequestById($requestId);
    if ($status === 'validé') {
        createUserFromRequest($request);
        // ENVOYER EMAIL (à implémenter)
    } elseif ($status === 'rejeté') {
        // ENVOYER EMAIL (à implémenter)
    }
    header("Location: /clara/views/admin/dashboard.php");
}

// CRÉER UN UTILISATEUR À PARTIR D'UNE DEMANDE
function createUserFromRequest($request) {
    $userData = [
        'username' => $request['mail_admin'], 'password' => generateRandomPassword(),
        'email' => $request['mail_admin'], 'establishment_id' => 1, 'role_id' => $request['role']
    ];
    createUser($userData);
}

// GÉNÉRER UN MOT DE PASSE ALÉATOIRE
function generateRandomPassword($length = 12) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $password;
}

// GÉRER LES ACTIONS (VALIDATION/REJET)
if (isset($_GET['action']) && $_GET['action'] === 'process_request') {
    $requestId = $_GET['id'];
    $status = $_GET['status'];
    processRequest($requestId, $status);
}

// RÉCUPÉRER LES DEMANDES EN ATTENTE
$pendingRequests = getPendingRequests();

// INCLURE LA VUE APRÈS AVOIR RÉCUPÉRÉ LES DONNÉES
include '../views/admin/dashboard.php'; 
?>










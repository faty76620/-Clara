<?php
session_start();
require_once __DIR__ . '/../models/request.php';
require_once __DIR__ . '/../models/user.php';

//VERIFIER QUE L'ACTION EST BIEN DEFINI DANS L'URL
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $id = intval($_GET['id']); // Sécurise l'ID en le forçant en entier

    // VERIFIER SI LA DEMANDE EXISTE AVANT DE LA TRAITER
    $request = getRequestById($id);
    if (!$request) {
        $_SESSION['error'] = "Demande non trouvée.";
        header("Location: /clara/views/admin/request_pending.php");

        exit();
    }

    // ACCEPTER LA DEMANDE
    if ($action === 'accept_request') {
        // CHANGER LE STATUS EN ACCEPTEE
        if (updateRequestStatus($id, 'acceptée')) {
            $_SESSION['success'] = "Demande acceptée avec succès.";
            // AJOUTER AUTOMATIQUEMENT L'UTILISATEUR DANS LA TABLE 'USERS'
            $userData = [
                'username' => $request['mail_admin'], // UTILISER EMAIL COMME IDENTIFIANT
                'password' => password_hash('MotDePasseParDefaut123!', PASSWORD_DEFAULT), //MOT DE PASSE PAR DEFAULT A CHANGER
                'email' => $request['mail_admin'],
                'establishment_id' => 1, 
                'role_id' => 1 
            ];

            createUser($userData);

        } else {
            $_SESSION['error'] = "Erreur lors de l'acceptation de la demande.";
        }
    }

         // REFUSER LA DEMANDE
    elseif ($action === 'reject_request') {
        if (updateRequestStatus($id, 'refusée')) {
            $_SESSION['success'] = "Demande refusée.";
        } else {
            $_SESSION['error'] = "Erreur lors du refus de la demande.";
        }
    }

    // REDIRIGER VERS LA PAGES DES DEMANDES
    header("Location: /clara/views/admin/request_pending.php");

    exit();

} else {
    $_SESSION['error'] = "Action invalide.";

    header("Location: /clara/views/admin/request_pending.php");
        header("Location: /clara/views/admin/gestion_demandes.php");
    
        exit();
    }

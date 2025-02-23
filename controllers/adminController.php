<?php
session_start(); // DÉMARRAGE DE LA SESSION (POUR VÉRIFIER L'ADMIN)

require_once '../models/request.php';
require_once '../models/user.php';

// VÉRIFIER SI UNE ACTION EST DEMANDÉE
if (isset($_GET['action'])) {
    $action = $_GET['action'];

    try {
        // INSCRIPTION D'UN NOUVEL UTILISATEUR
        if ($action === 'register_user' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['email'])) {
                createUser($_POST);
                $_SESSION['success'] = "Utilisateur inscrit avec succès !";
                header("Location: /clara/views/admin/dashboard.php");
                exit();
            } else {
                $_SESSION['error'] = "Tous les champs sont obligatoires.";
                header("Location: /clara/views/admin/register.php");
                exit();
            }
        }

        // ACCEPTER UNE DEMANDE D'INSCRIPTION
        if ($action === 'accept_request' && isset($_GET['id'])) {
            $id = intval($_GET['id']);
            updateRequestStatus($id, 'acceptée');
            $_SESSION['success'] = "Demande acceptée avec succès.";
            header("Location: admin_dashboard.php");
            exit();
        }

        // REJETER UNE DEMANDE D'INSCRIPTION
        if ($action === 'reject_request' && isset($_GET['id'])) {
            $id = intval($_GET['id']);
            updateRequestStatus($id, 'rejetée');
            $_SESSION['error'] = "Demande rejetée.";
            header("Location: admin_dashboard.php");
            exit();
        }

    } catch (Exception $e) {
        $_SESSION['error'] = "Une erreur est survenue : " . $e->getMessage();
        header("Location: admin_dashboard.php");
        exit();
    }
}

// RÉCUPÉRER LES DEMANDES EN ATTENTE POUR L'AFFICHER DANS LE TABLEAU DE BORD
$pendingRequests = getPendingRequests();
?>

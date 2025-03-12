<?php
session_start();
require_once '../models/user.php';
require_once '../models/send_mail.php';
require_once '../models/database.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        // CONNEXION AVEC IDENTIFIANT ET MOT DE PASSE
        $username = htmlspecialchars(trim($_POST['username']));
        $password = trim($_POST['password']);
        
        $user = getUserByUsername($username);

        if (!$user || !isset($user['password']) || !password_verify($password, $user['password'])) {
            $_SESSION['error'] = "Identifiant ou mot de passe incorrect";
            header("Location: /clara/views/auth/login.php");
            exit();
        }

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role_id'];
            $_SESSION['must_change_password'] = $user['must_change_password'];

            // REDIRECTION SELON LE ROLE OU CHANGEMENT DE MOT DE PASSE OBLIGATOIRE
            if ($user['must_change_password'] == 1) {
                header("Location: /clara/views/auth/change_password.php");
            } else {
                switch ($user['role_id']) {
                    case 1:
                        header("Location: /clara/views/admin/dashboard.php");
                        break;
                    case 2:
                        header("Location: /clara/views/manager/dashboard.php");
                        break;
                    default:
                        header("Location: /clara/views/user/dashboard.php");
                        break;
                }
            }
            exit();
        }
    }

    // CHANGEMENT DE MOT DE PASSE
    if (isset($_POST['old_password'], $_POST['new_password'], $_POST['confirm_password'])) {
        // Vérification que l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            header("Location: /clara/views/auth/login.php");
            exit();
        }

        $user_id = $_SESSION['user_id'];
        $old_password = trim($_POST['old_password']);
        $new_password = trim($_POST['new_password']);
        $confirm_password = trim($_POST['confirm_password']);

        $user = getUserById($user_id);

        // VÉRIFIER SI L'ANCIEN MOT DE PASSE EST CORRECT
        if (!password_verify($old_password, $user['password'])) {
            $_SESSION['error'] = "L'ancien mot de passe est incorrect.";
            header("Location: /clara/views/auth/change_password.php");
            exit();
        }

        // Vérifier si les nouveaux mots de passe sont identiques
        if ($new_password !== $confirm_password) {
            $_SESSION['error'] = "Les nouveaux mots de passe ne correspondent pas.";
            header("Location: /clara/views/auth/change_password.php");
            exit();
        }

        // Vérifier la validité du nouveau mot de passe
        if (strlen($new_password) < 8 || !preg_match('/[A-Z]/', $new_password) || !preg_match('/[0-9]/', $new_password)) {
            $_SESSION['error'] = "Le mot de passe doit contenir au moins 8 caractères, une majuscule et un chiffre.";
            header("Location: /clara/views/auth/change_password.php");
            exit();
        }

        // Mise à jour du mot de passe
        if (updatePassword($user_id, $new_password)) {
            $_SESSION['must_change_password'] = 0;

            // ENVOYER UN EMAIL DE CONFIRMATION
            $to = $user['email'];
            $subject = "Votre mot de passe a été modifié";
            $message = "Bonjour " . $user['username'] . ",\n\nVotre mot de passe a bien été changé.\nSi ce n'est pas vous, contactez l'administrateur.";

            $headers = "From: admin@clara.com\r\n";
            $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

            sendEmail($to, $subject, $message, $headers);

            // REDIRECTION
            $_SESSION['success'] = "Mot de passe mis à jour, reconnectez-vous.";
            header("Location: /clara/views/auth/login.php");
            exit();
        } else {
            $_SESSION['error'] = "Une erreur est survenue lors de la mise à jour du mot de passe.";
            header("Location: /clara/views/auth/change_password.php");
            exit();
        }
    }
}

// DÉCONNEXION
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['logout'])) {
    session_destroy();
    header("Location: /clara/views/auth/login.php?success=Déconnexion réussie");
    exit();
}
?>



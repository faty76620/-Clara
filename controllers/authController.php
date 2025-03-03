<?php
session_start();
require_once '../models/user.php';
require_once '../models/send_mail.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        // Connexion utilisateur
        $username = htmlspecialchars($_POST['username']);
        $password = $_POST['password'];

        $user = getUserByUsername($username);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role_id'];
            $_SESSION['must_change_password'] = $user['must_change_password'];

            // Vérifier si l'utilisateur doit changer son mot de passe
            if ($user['must_change_password'] == 1) {
                header("Location: /clara/views/auth/change_password.php");
            } else {
                // Redirection selon le rôle
                if ($user['role_id'] == 1) {
                    header("Location: /clara/views/admin/dashboard.php");
                } elseif ($user['role_id'] == 2) {
                    header("Location: /clara/views/manager/dashboard.php");
                } else {
                    header("Location: /clara/views/user/dashboard.php");
                }
            }
            exit();
        } else {
            header("Location: /clara/views/auth/login.php?error=Identifiant ou mot de passe incorrect");
            exit();
        }
    } 
    
    elseif (isset($_POST['old_password']) && isset($_POST['new_password']) && isset($_POST['confirm_password'])) {
        // Changement de mot de passe
        if (!isset($_SESSION['user_id'])) {
            header("Location: /clara/views/auth/login.php");
            exit();
        }

        $user_id = $_SESSION['user_id'];
        $old_password = trim($_POST['old_password']);
        $new_password = trim($_POST['new_password']);
        $confirm_password = trim($_POST['confirm_password']);

        $user = getUserById($user_id);

        if (!$user || !password_verify($old_password, $user['password'])) {
            header("Location: /clara/views/auth/change_password.php?error=L'ancien mot de passe est incorrect.");
            exit();
        }

        if ($new_password !== $confirm_password) {
            header("Location: /clara/views/auth/change_password.php?error=Les nouveaux mots de passe ne correspondent pas.");
            exit();
        }

        if (updatePassword($user_id, $new_password)) {
            $_SESSION['must_change_password'] = 0;

            // Envoyer un email de confirmation
            $to = $user['email'];
            $subject = "Votre mot de passe a été modifié";
            $message = "Bonjour " . $user['username'] . ",\n\nVotre mot de passe a bien été changé.\nSi ce n'est pas vous, contactez l'administrateur.";

            mail($to, $subject, $message, $headers);

            header("Location: /clara/views/auth/login.php?success=Mot de passe mis à jour, reconnectez-vous");
            exit();
        } else {
            header("Location: /clara/views/auth/change_password.php?error=Une erreur est survenue.");
            exit();
        }
    }
}
?>

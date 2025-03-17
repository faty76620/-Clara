<?php
session_start();
require_once '../models/database.php';
require_once '../models/user.php'; 
require_once '../models/establishment.php'; 
require_once '../models/send_mail.php'; 

if ($_SERVER["REQUEST_METHOD"] === "POST") { 
    //  VERIFICATION DES CHAMPS
    if (
        empty($_POST['firstname_user']) || 
        empty($_POST['lastname_user']) || 
        empty($_POST['mail_user']) || 
        empty($_POST['phone']) || 
        empty($_POST['role']) || 
        empty($_POST['establishment_id'])
    ) {
        $_SESSION['error'] = "Veuillez remplir tous les champs obligatoires.";
        header("Location: /clara/views/manager/register_user.php");
        exit();
    }

    // SECURISATION DES DONNES
    $firstname_user = htmlspecialchars(trim($_POST['firstname_user']));
    $lastname_user = htmlspecialchars(trim($_POST['lastname_user']));
    $mail_user = htmlspecialchars(trim($_POST['mail_user']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $establishment_id = intval($_POST['establishment_id']); 
    $role_user = 3; 

    $conn = getConnexion();

    // CREATION IDENTIFIANT UNIQUE
    $username = strtolower($firstname_user . '.' . $lastname_user);
    $username = preg_replace('/[^a-z0-9.]/', '', $username); // Supprime les caractères spéciaux
    $username_exists = checkUsernameExists($conn, $username); 
    if ($username_exists) {
        $username .= rand(1, 100); // Si identifiant existe, ajouter un nombre aléatoire
    }

    // MOT DE PASSE TEMPORAIRE
    $password_plain = bin2hex(random_bytes(4)); 
    $password_hashed = password_hash($password_plain, PASSWORD_BCRYPT);
    

    // INSERSION DANS BASE DE DONNES
    $user_created = createUser($conn, [
        'username' => $username,
        'firstname_user' => $firstname_user,
        'lastname_user' => $lastname_user,
        'mail_user' => $mail_user,
        'password' => $password_hashed,
        'establishment_id' => $establishment_id, 
        'role_user' => $role_user
    ]);

    // VERIFIER SI UTILISATERUR CREER
    if ($user_created) {
        $subject = "Votre inscription a été validée";
        $message = "
            Bonjour $firstname_user $lastname_user,
            Votre compte a été créé avec succès. Voici vos informations de connexion :
            Identifiant : $username
            Mot de passe temporaire : $password_plain
            Vous devrez vous connecter et changer votre mot de passe dès que possible.

            Cordialement,
            L'équipe de Clara
        ";

        // ENVOI EMAIL
        if (sendEmail($mail_user, $subject, $message)) {
            $_SESSION['success'] = "Utilisateur inscrit avec succès. Un email a été envoyé à $mail_user avec ses informations de connexion.";
        } else {
            $_SESSION['error'] = "L'utilisateur a été créé, mais l'envoi de l'email a échoué.";
        }
    } else {
        $_SESSION['error'] = "Erreur lors de l'inscription de l'utilisateur.";
    }

    header("Location: /clara/views/manager/register_user.php"); 
    exit();
}
?>
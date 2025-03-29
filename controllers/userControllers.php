<?php

require_once __DIR__ . '/../config.php';        
require_once MODEL_DIR . '/database.php';        
require_once MODEL_DIR . '/user.php';  
require_once MODEL_DIR . '/establishment.php';
require_once MODEL_DIR . '/logs.php';    
require_once MODEL_DIR . '/send_mail.php';       
require_once TEMPLATE_DIR . '/session_start.php'; 

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
        addLog('Erreur', $_SESSION['username'], "Tentative d'inscription échouée - Champs manquants");
        header("Location: /clara/views/manager/register_user.php");
        exit();
    }

    // SECURISATION DES DONNÉES
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
    addLog('Info', $_SESSION['username'], "Génération identifiant unique : $username");

    // MOT DE PASSE TEMPORAIRE
    $password_plain = bin2hex(random_bytes(4)); 
    $password_hashed = password_hash($password_plain, PASSWORD_BCRYPT);
    
    // INSERTION DANS BASE DE DONNÉES
    $user_created = createUser($conn, [
        'username' => $username,
        'firstname_user' => $firstname_user,
        'lastname_user' => $lastname_user,
        'mail_user' => $mail_user,
        'password' => $password_hashed,
        'establishment_id' => $establishment_id, 
        'role_user' => $role_user
    ]);

    // VERIFIER SI UTILISATEUR CRÉÉ
    if ($user_created) {
        addLog('Succès', $_SESSION['username'], "Utilisateur créé : $username ($mail_user)");

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
            addLog('Email envoyé', $_SESSION['username'], "Email d'inscription envoyé à $mail_user");
        } else {
            $_SESSION['error'] = "L'utilisateur a été créé, mais l'envoi de l'email a échoué.";
            addLog('Erreur envoi email', $_SESSION['username'], "Échec d'envoi du mail à $mail_user");
        }
    } else {
        $_SESSION['error'] = "Erreur lors de l'inscription de l'utilisateur.";
        addLog('Erreur', $_SESSION['username'], "Échec de création utilisateur : $username ($mail_user)");
    }

    header("Location: /clara/views/manager/register_user.php"); 
    exit();
}

// Liste des utilisateurs
if (isset($_GET['action']) && $_GET['action'] == 'list') {
    $conn = getConnexion();
    $users = getUsersByRole($conn, 'user'); // Récupère les utilisateurs
    addLog('Info', $_SESSION['username'], "Consultation de la liste des utilisateurs");
    require_once '../../views/manager/users_list.php';
}

// Suppression d'un utilisateur
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $conn = getConnexion();
    if (deleteUser($conn, $_GET['id'])) {
        $_SESSION['success'] = "Utilisateur supprimé avec succès.";
        addLog('Suppression', $_SESSION['username'], "Utilisateur ID " . $_GET['id'] . " supprimé");
    } else {
        $_SESSION['error'] = "Erreur lors de la suppression de l'utilisateur.";
        addLog('Erreur', $_SESSION['username'], "Échec suppression utilisateur ID " . $_GET['id']);
    }
    header("Location: /clara/views/manager/users_list.php");
    exit();
}
?>

<?php
require_once __DIR__ . '/../config.php';        
require_once MODEL_DIR . '/database.php';        
require_once MODEL_DIR . '/user.php';  
require_once MODEL_DIR . '/caregiver.php';  
require_once MODEL_DIR . '/send_mail.php';   
require_once MODEL_DIR . '/logs.php';       
require_once TEMPLATE_DIR . '/session_start.php'; 

if ($_SERVER["REQUEST_METHOD"] === "POST") { 

    // Vérification des champs du formulaire
    if (
        empty($_POST['firstname_user']) || 
        empty($_POST['lastname_user']) || 
        empty($_POST['mail_user']) || 
        empty($_POST['phone']) || 
        empty($_POST['role']) || 
        empty($_POST['establishment_id']) ||
        empty($_POST['specialite']) || 
        empty($_POST['diplome']) || 
        empty($_POST['experience']) 
    ) {
        $_SESSION['error'] = "Veuillez remplir tous les champs obligatoires.";
        addLog('Erreur', $_SESSION['username'], "Tentative d'inscription échouée - Champs manquants");
        header("Location: /clara/views/manager/registrations.php");
        exit();
    }

    // Sécurisation des données
    $firstname_user = htmlspecialchars(trim($_POST['firstname_user']));
    $lastname_user = htmlspecialchars(trim($_POST['lastname_user']));
    $mail_user = htmlspecialchars(trim($_POST['mail_user']));
    $establishment_id = htmlspecialchars(trim($_POST['establishment_id']));
    $phone = htmlspecialchars(trim($_POST['phone']));
   
    $specialite = htmlspecialchars(trim($_POST['specialite']));
    $diplome = htmlspecialchars(trim($_POST['diplome']));
    $experience = (int) $_POST['experience'];
    $competences = htmlspecialchars(trim($_POST['competences'])) ?? '';

    // Connexion à la base de données
    $conn = getConnexion();

    // Création d'un identifiant unique pour l'utilisateur
    $username = strtolower($firstname_user . '.' . $lastname_user);
    $username = preg_replace('/[^a-z0-9.]/', '', $username); // Supprime les caractères spéciaux
    $username_exists = checkUsernameExists($conn, $username);
    if ($username_exists) {
        $username .= rand(1, 100); // Si identifiant existe, ajouter un nombre aléatoire
    }
    addLog('Info', $_SESSION['username'], "Génération identifiant unique : $username");

    // Création du mot de passe temporaire
    $password_plain = bin2hex(random_bytes(4)); 
    $password_hashed = password_hash($password_plain, PASSWORD_BCRYPT);

    // Insérer l'utilisateur dans la table `users`
    $user_created = createUser($conn, [
        'username' => $username,
        'firstname_user' => $firstname_user,
        'lastname_user' => $lastname_user,
        'mail_user' => $mail_user,
        'password' => $password_hashed,
        'establishment_id' => $establishment_id,
        'role_user' => 3 // rôle pour l'utilisateur soignant
    ]);

    // Vérifier si l'utilisateur a été créé
    if ($user_created) {
        // Récupérer l'ID de l'utilisateur créé
        $user_id = $conn->lastInsertId();

        // Ajouter l'utilisateur à la table caregiver
        $data = [
            'user_id' => $user_id, 
            'specialite' => $specialite,
            'diplome' => $diplome,
            'experience' => $experience, 
            'competences' => $competences, 
        ];

        if (createCaregiver($conn, $data)) {
            addLog('Succès', $_SESSION['username'], "Utilisateur soignant créés : $username ($mail_user)");

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

            // Envoi de l'email de confirmation
            if (sendEmail($mail_user, $subject, $message)) {
                $_SESSION['success'] = "Utilisateur et soignant inscrits avec succès. Un email a été envoyé à $mail_user avec ses informations de connexion.";
                addLog('Email envoyé', $_SESSION['username'], "Email d'inscription envoyé à $mail_user");
            } else {
                $_SESSION['error'] = "L'utilisateur et soignant ont été créés, mais l'envoi de l'email a échoué.";
                addLog('Erreur envoi email', $_SESSION['username'], "Échec d'envoi du mail à $mail_user");
            }
        } else {
            $_SESSION['error'] = "Erreur lors de l'ajout du soignant.";
            addLog('Erreur', $_SESSION['username'], "Échec ajout soignant : $username ($mail_user)");
        }
    } else {
        $_SESSION['error'] = "Erreur lors de l'inscription de l'utilisateur.";
        addLog('Erreur', $_SESSION['username'], "Échec de création utilisateur : $username ($mail_user)");
    }

    // Rediriger vers la page d'inscription
    header("Location: /clara/views/manager/registrations.php");
    exit();
} else {
    $_SESSION['error'] = "Méthode non autorisée.";
    header("Location: /clara/views/manager/registrations.php");
    exit();
}
?>


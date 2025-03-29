<?php

require_once __DIR__ . '/../config.php';        
require_once MODEL_DIR . '/database.php';        
require_once MODEL_DIR . '/user.php';  
require_once MODEL_DIR . '/logs.php';    
require_once MODEL_DIR . '/send_mail.php';       
require_once TEMPLATE_DIR . '/session_start.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    
    // Vérifier si l'email existe
    $user = $model->checkEmailExists($email, $pdo);

    if ($user) {
        addLog('Demande de réinitialisation', $user['username'], 'Email trouvé en base, création du token');
        // Créer un token de réinitialisation
        $token = $model->createPasswordResetToken($user['id'], $pdo);

        if ($token) {
            addLog('Token généré', $user['username'], 'Token de réinitialisation créé');

            // Lien pour réinitialiser le mot de passe
            $resetLink = "http://localhost/clara/controllers/reset_password.php?token=" . $token;

            // Envoyer l'email avec le lien
            $subject = "Réinitialisation de votre mot de passe";
            $message = "
                Bonjour {$user['username']},
                Cliquez sur le lien suivant pour réinitialiser votre mot de passe :
                $resetLink
                Ce lien expirera dans 1 heure.
            ";

            // Envoi de l'email 
            if (sendEmail($email, $subject, $message)) {
                addLog('Email envoyé', $user['username'], 'Lien de réinitialisation envoyé avec succès');
                $_SESSION['success'] = "Un lien de réinitialisation a été envoyé à votre email.";
            } else {
                addLog('Erreur envoi email', $user['username'], 'Échec de l\'envoi du lien de réinitialisation');
                $_SESSION['error'] = "Une erreur est survenue lors de l'envoi du lien. Veuillez réessayer.";
            }
        } else {
            addLog('Erreur token', $user['username'], 'Impossible de générer le token');
            $_SESSION['error'] = "Une erreur est survenue. Veuillez réessayer.";
        }
    } else {
        addLog('Échec réinitialisation', $email, 'Email non trouvé en base');
        $_SESSION['error'] = "Cet email n'existe pas dans nos enregistrements.";
    }

    header('Location: /clara/views/auth/login.php');
    exit();
}
?>
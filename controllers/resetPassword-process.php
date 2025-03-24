<?php
require_once __DIR__ . '/../templates/session_start.php';
require_once __DIR__ . '/../models/send_mail.php';
require_once __DIR__ . '/../models/user.php';
require_once __DIR__ . '/../models/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    
    // Vérifier si l'email existe
    $user = $model->checkEmailExists($email, $pdo);

    if ($user) {
        // Créer un token de réinitialisation
        $token = $model->createPasswordResetToken($user['id'], $pdo);

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
            $_SESSION['success'] = "Un lien de réinitialisation a été envoyé à votre email.";
        } else {
            $_SESSION['error'] = "Une erreur est survenue lors de l'envoi du lien. Veuillez réessayer.";
        }
    } else {
        $_SESSION['error'] = "Cet email n'existe pas dans nos enregistrements.";
    }
    
    header('Location: /clara/views/auth/login.php');
    exit();
}

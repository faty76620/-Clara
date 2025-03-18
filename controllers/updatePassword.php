<?php
require_once __DIR__ . '/../templates/session_start.php';
require_once __DIR__ . '/../models/database.php';
require_once __DIR__ . '/../models/user.php';
require_once __DIR__ . '/../models/send_mail.php';

// Vérification de connexion
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Veuillez vous connecter.";
    header("Location: /clara/views/auth/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $conn = getConnexion();
    if (!$conn) {
        die("Erreur de connexion à la base de données.");
    }

    $user_id = $_SESSION['user_id'];
    $old_password = trim($_POST['old_password']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Récupérer l'utilisateur de la base de données
    $user = getUserById($conn, $user_id);

    // Vérification de l'ancien mot de passe
    if (!$user || !password_verify($old_password, $user['password'])) {
        $_SESSION['error'] = "L'ancien mot de passe est incorrect.";
        header("Location: /clara/views/auth/change_password.php");
        exit();
    }

    // Vérification si les nouveaux mots de passe correspondent
    if ($new_password !== $confirm_password) {
        $_SESSION['error'] = "Les nouveaux mots de passe ne correspondent pas.";
        header("Location: /clara/views/auth/change_password.php");
        exit();
    }

    // Vérification des critères du nouveau mot de passe
    if (strlen($new_password) < 8 || !preg_match('/[A-Z]/', $new_password) || !preg_match('/[0-9]/', $new_password)) {
        $_SESSION['error'] = "Le mot de passe doit contenir au moins 8 caractères, une majuscule et un chiffre.";
        header("Location: /clara/views/auth/change_password.php");
        exit();
    }

    // Hachage du nouveau mot de passe
    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

    // Mettre à jour le mot de passe dans la base de données
    if (updateUserPassword($conn, $user_id, $hashed_password)) {
        // Mettre à jour le champ must_change_password à 0 dans la base de données
        $stmt = $conn->prepare("UPDATE users SET must_change_password = 0 WHERE id = :id");
        $stmt->execute(['id' => $user_id]);

        // Mettre à jour la session pour ne plus demander de changement de mot de passe
        $_SESSION['must_change_password'] = 0;

        // Envoi de l'email de confirmation
        $to = $user['mail'];
        $subject = "Votre mot de passe a été modifié";
        $message = "Bonjour " . $user['username'] . ",\n\nVotre mot de passe a bien été changé.\nSi ce n'est pas vous, contactez l'administrateur.";
    
        $headers = "From: admin@clara.com\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    
        // Envoi de l'email
        sendEmail($to, $subject, $message, $headers);

        // Message de succès et déconnexion de la session
        $_SESSION['success'] = "Mot de passe mis à jour avec succès.";
        session_unset(); // Détruire toutes les variables de session
        session_destroy(); // Détruire la session

        header("Location: /clara/views/auth/login.php"); // Rediriger vers la page de connexion
        exit();
    } else {
        $_SESSION['error'] = "Une erreur est survenue lors de la mise à jour du mot de passe.";
        header("Location: /clara/views/auth/change_password.php");
        exit();
    }
}

?>









<?php
session_start();
require_once '../models/database.php';
require_once '../models/user.php';
require_once '../models/send_mail.php';

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

    $user = getUserById($conn, $user_id);

    if (!$user || !password_verify($old_password, $user['password'])) {
        $_SESSION['error'] = "L'ancien mot de passe est incorrect.";
        header("Location: /clara/views/auth/change_password.php");
        exit();
    }

    if ($new_password !== $confirm_password) {
        $_SESSION['error'] = "Les nouveaux mots de passe ne correspondent pas.";
        header("Location: /clara/views/auth/change_password.php");
        exit();
    }

    if (strlen($new_password) < 8 || !preg_match('/[A-Z]/', $new_password) || !preg_match('/[0-9]/', $new_password)) {
        $_SESSION['error'] = "Le mot de passe doit contenir au moins 8 caractères, une majuscule et un chiffre.";
        header("Location: /clara/views/auth/change_password.php");
        exit();
    }

    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
    
    if (updateUserPassword($conn, $user_id, $hashed_password)) {
        $_SESSION['must_change_password'] = 0;

        // Envoi d'un email de confirmation
        $to = $user['email'];
        $subject = "Votre mot de passe a été modifié";
        $message = "Bonjour " . $user['username'] . ",\n\nVotre mot de passe a bien été changé.\nSi ce n'est pas vous, contactez l'administrateur.";

        $headers = "From: admin@clara.com\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        sendEmail($to, $subject, $message, $headers);

        $_SESSION['success'] = "Mot de passe mis à jour, reconnectez-vous.";
        header("Location: /clara/views/auth/login.php");
        exit();
    } else {
        $_SESSION['error'] = "Une erreur est survenue lors de la mise à jour du mot de passe.";
        header("Location: /clara/views/auth/change_password.php");
        exit();
    }
}
?>


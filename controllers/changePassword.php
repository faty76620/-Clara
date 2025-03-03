<?php
session_start();
require_once '../models/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = getConnexion();
$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $old_password = trim($_POST['old_password']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Vérifier que le nouveau mot de passe est bien confirmé
    if ($new_password !== $confirm_password) {
        $error = "Les nouveaux mots de passe ne correspondent pas.";
    } else {
        // Récupérer l'ancien mot de passe depuis la base de données
        $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($old_password, $user['password'])) {
            // Hacher le nouveau mot de passe
            $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

            // Mettre à jour le mot de passe
            $stmt = $conn->prepare("UPDATE users SET password = ?, must_change_password = 0 WHERE id = ?");
            $stmt->execute([$hashed_password, $user_id]);

            $_SESSION['must_change_password'] = 0;
            header("Location: login.php?success=Mot de passe mis à jour, reconnectez-vous");
            exit();
        } else {
            $error = "L'ancien mot de passe est incorrect.";
        }
    }
}
?>



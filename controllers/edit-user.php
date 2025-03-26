<?php
require_once '../templates/session_start.php';
require_once '../models/database.php';
require_once '../models/user.php';
require_once '../models/establishment.php';

$conn = getConnexion();

// Vérifier si les données sont envoyées par POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Récupérer les valeurs du formulaire
    $id = $_POST['id'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $mail = $_POST['mail'];
    $establishment_id = $_POST['establishment_id'];
    $role_id = $_POST['role_id'];

    // Appeler la fonction de mise à jour
    if (updateUser($conn, $id, $firstname, $lastname, $mail, $establishment_id, $role_id)) {
        $_SESSION['success'] = "Utilisateur mis à jour avec succès.";
        header("Location: ../views/admin/users.php"); 
        exit();
    } else {
        $_SESSION['error'] = "Erreur lors de la mise à jour de l'utilisateur.";
        header("Location: ../views/admin/edit-user.php?id=$id");
        exit();
    }
} else {
    // Rediriger si la méthode n'est pas POST
    $_SESSION['error'] = "Aucune donnée soumise.";
    header("Location: ../views/admin/users.php");
    exit();
}

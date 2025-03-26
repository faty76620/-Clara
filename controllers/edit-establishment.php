<?php
require_once '../templates/session_start.php';
require_once '../models/database.php';
require_once '../models/establishment.php';

$conn = getConnexion();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = intval($_POST['id']);
    $firstname = $_POST['firstname'];
    $phone = $_POST['phone'];
    $adresse = $_POST['adresse'];
    $mail = $_POST['mail'];
    $description = $_POST['description'];

    if ($id <= 0) {
        $_SESSION['error'] = "ID invalide !";
        header("Location: ../views/admin/establishment.php");
        exit();
    }

    if (updateEstablishment($conn, $id, $firstname, $phone, $adresse, $mail, $description)) {
        $_SESSION['success'] = "Établissement mis à jour avec succès !";
    } else {
        $_SESSION['error'] = "Erreur lors de la mise à jour.";
    }

    header("Location: ../views/admin/establishment.php");
    exit();
}
?>

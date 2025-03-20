<?php
require_once '../../models/database.php';
require_once '../../models/establishment.php';

$conn = getConnexion();

// Vérifie si l'ID est présent dans l'URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    if (deleteEstablishment($conn, $id)) {
        // Redirection avec message de succès
        header("Location: establishments.php?success=1");
        exit();
    } else {
        // Redirection avec message d'erreur
        header("Location: establishments.php?error=1");
        exit();
    }
} else {
    // Si aucun ID valide, on redirige
    header("Location: establishments.php?error=1");
    exit();
}

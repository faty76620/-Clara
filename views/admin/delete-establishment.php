<?php
require_once __DIR__ . '/../../config.php';
require_once MODEL_DIR . '/database.php';
require_once MODEL_DIR . '/establishment.php';

$conn = getConnexion();

// Vérifie si l'ID est présent dans l'URL et est valide
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    if ($id > 0 && deleteEstablishment($conn, $id)) {
        // Redirection avec message de succès
        header("Location: " . BASE_URL . "/views/admin/establishments.php?success=1");
        exit();
    } else {
        // Redirection avec message d'erreur
        header("Location: " . BASE_URL . "/views/admin/establishments.php?error=1");
        exit();
    }
} else {
    // Si aucun ID valide, on redirige
    header("Location: " . BASE_URL . "/views/admin/establishments.php?error=1");
    exit();
}
?>


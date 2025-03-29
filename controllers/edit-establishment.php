<?php

require_once __DIR__ . '/../config.php';        
require_once MODEL_DIR . '/database.php';        
require_once MODEL_DIR . '/establishment.php';  
require_once MODEL_DIR . '/logs.php';          
require_once TEMPLATE_DIR . '/session_start.php'; 

$conn = getConnexion();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = intval($_POST['id']);
    $firstname = htmlspecialchars($_POST['firstname']);
    $phone = htmlspecialchars($_POST['phone']);
    $adresse = htmlspecialchars($_POST['adresse']);
    $mail = htmlspecialchars($_POST['mail']);
    $description = htmlspecialchars($_POST['description']);

    if ($id <= 0) {
        $_SESSION['error'] = "ID invalide !";
        addLog('Erreur', $_SESSION['user_id'], "Tentative de mise à jour avec un ID invalide");
        header("Location: ../views/admin/establishment.php");
        exit();
    }

    // Récupérer l'ancien état de l'établissement avant modification
    $oldEstablishment = getEstablishmentById($conn, $id);

    if (!$oldEstablishment) {
        $_SESSION['error'] = "Établissement non trouvé !";
        addLog('Erreur', $_SESSION['user_id'], "Tentative de mise à jour d'un établissement inexistant (ID: $id)");
        header("Location: ../views/admin/establishment.php");
        exit();
    }

    addLog('Info', $_SESSION['user_id'], "Mise à jour de l'établissement '{$oldEstablishment['firstname']}' (ID: $id)");

    if (updateEstablishment($conn, $id, $firstname, $phone, $adresse, $mail, $description)) {
        $_SESSION['success'] = "Établissement mis à jour avec succès !";

        // Ajout d'un log détaillant les modifications
        $descriptionLog = "Établissement '{$oldEstablishment['firstname']}' mis à jour : ";
        if ($oldEstablishment['firstname'] !== $firstname) $descriptionLog .= "Nom changé de '{$oldEstablishment['firstname']}' à '$firstname'. ";
        if ($oldEstablishment['phone'] !== $phone) $descriptionLog .= "Téléphone changé de '{$oldEstablishment['phone']}' à '$phone'. ";
        if ($oldEstablishment['adresse'] !== $adresse) $descriptionLog .= "Adresse changée de '{$oldEstablishment['adresse']}' à '$adresse'. ";
        if ($oldEstablishment['mail'] !== $mail) $descriptionLog .= "Email changé de '{$oldEstablishment['mail']}' à '$mail'. ";
        if ($oldEstablishment['description'] !== $description) $descriptionLog .= "Description mise à jour. ";

        addLog('Succès', $_SESSION['user_id'], $descriptionLog);
    } else {
        $_SESSION['error'] = "Erreur lors de la mise à jour.";
        addLog('Erreur', $_SESSION['user_id'], "Échec de mise à jour pour l'établissement '{$oldEstablishment['firstname']}' (ID: $id)");
    }

    header("Location: ../views/admin/establishment.php");
    exit();
}
?>




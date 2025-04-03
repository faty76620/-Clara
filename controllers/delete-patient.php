<?php
require_once __DIR__ . '/../config.php';        
require_once MODEL_DIR . '/database.php';        
require_once MODEL_DIR . '/patients.php'; 
require_once TEMPLATE_DIR . '/session_start.php'; 

// Vérifier si un ID est passé en URL et s'il est valide
if (!isset($_GET['id']) || empty($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "ID invalide !";
    header("Location: ../views/manager/folders_patients.php");
    exit();
}

$patient_id = intval($_GET['id']);  

if ($patient_id <= 0) {
    $_SESSION['error'] = "ID invalide !";
    header("Location: ../views/manager/folders_patients.php");
    exit();
}

$conn = getConnexion();  

if (deletePatientAndRelatedData($conn, $patient_id)) {
    $_SESSION['success'] = "Patient et ses données associées ont été supprimés avec succès.";
   
} else {
    $_SESSION['error'] = "Erreur lors de la suppression du patient.";
}

header("Location: ../views/manager/folders_patients.php");
exit();
?>

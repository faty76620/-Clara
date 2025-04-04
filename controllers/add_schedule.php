<?php
require_once __DIR__ . '/../../config.php';
require_once TEMPLATE_DIR . '/session_start.php';
require_once MODEL_DIR . '/database.php';
require_once MODEL_DIR . '/patients.php';
require_once MODEL_DIR . '/user.php';
require_once MODEL_DIR . '/planning.php';

$conn = getConnexion();

// Récupérer l'identifiant de l'établissement de l'utilisateur connecté
$establishment_id = $_SESSION['establishment_id']; 

$users = getCaregiversByEstablishment($conn, $establishment_id);
$patients = getPatients($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $patient_id = $_POST['patient_id'];
    $start_datetime = $_POST['start_datetime'];
    $end_datetime = $_POST['end_datetime'];

    // Validation des champs
    if (empty($user_id) || empty($patient_id) || empty($start_datetime) || empty($end_datetime)) {
        $_SESSION['error'] = "Tous les champs doivent être remplis.";
    } elseif (strtotime($start_datetime) >= strtotime($end_datetime)) {
        $_SESSION['error'] = "L'heure de début doit être avant l'heure de fin.";
    } else {
        // Ajouter le planning
        if (addSchedule($conn, $user_id, $patient_id, $start_datetime, $end_datetime)) {
            $_SESSION['success'] = "Planning ajouté avec succès !";
            header('Location: manage-planning.php');
            exit;
        } else {
            $_SESSION['error'] = "Erreur lors de l'ajout du planning.";
        }
    }
}
?>
<?php
// Inclusion des fichiers nécessaires
require_once __DIR__ . '/../config.php'; 
require_once TEMPLATE_DIR . '/session_start.php';
require_once MODEL_DIR . '/database.php';
require_once MODEL_DIR . '/caregiver.php';
require_once MODEL_DIR . '/user.php';
$conn = getConnexion();

// Vérification que le formulaire a bien été soumis via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupération des données du formulaire
    $caregiver_id = $_POST['caregiver_id'];
    $user_id = $_POST['user_id'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $mail = $_POST['mail'];
    $specialite = $_POST['specialite'];
    $diplome = $_POST['diplome'];
    $experience = $_POST['experience'];
    $competences = $_POST['competences'];
    $establishment_id = $_POST['establishment_id'];

    // Mise à jour des informations personnelles de l'utilisateur
    $userUpdated = updateCaregiverPerso($conn, $user_id, $firstname, $lastname, $mail, $establishment_id);

    // Mise à jour des informations professionnelles du soignant
    $caregiverUpdated = updateCaregiver($conn, $caregiver_id, $specialite, $diplome, $experience, $competences);

    // Vérification si les deux mises à jour ont réussi
    if ($userUpdated && $caregiverUpdated) {
   
        $_SESSION['success'] = "Les informations du soignant ont été mises à jour avec succès.";
        header("Location: " . BASE_URL . "/views/manager/folders_patients.php?section=caregiver");
        exit();
    } else {
      
        $_SESSION['error'] = "Une erreur est survenue lors de la mise à jour des informations.";
        header("Location: " . BASE_URL . "/views/manager/folders_patients.php?section=caregiver");
        exit();
    }
} else {
 
    $_SESSION['error'] = "Aucune donnée reçue. Veuillez soumettre à nouveau le formulaire.";
    header("Location: " . BASE_URL . "/views/manager/folders_patients.php?section=caregiver");
    exit();
}
?>



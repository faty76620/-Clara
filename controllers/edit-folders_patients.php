<?php
require_once __DIR__ . '/../config.php';  
require_once TEMPLATE_DIR . '/session_start.php';
require_once MODEL_DIR . '/database.php';
require_once MODEL_DIR . '/patients.php';
require_once MODEL_DIR . '/care.php';
require_once MODEL_DIR . '/transmission.php';
require_once MODEL_DIR . '/logs.php';
require_once MODEL_DIR . '/constante.php';

$conn = getConnexion();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type_form = $_POST['type_form'] ?? '';

    switch ($type_form) {
        case 'patient':
            $patient_id = intval($_POST['patient_id']);
            $lastname = htmlspecialchars($_POST['lastname']);
            $firstname = htmlspecialchars($_POST['firstname']);
            $date_of_birth = $_POST['date_of_birth'];
            $gender = htmlspecialchars($_POST['gender']);
            $address = htmlspecialchars($_POST['address']);
            $email = htmlspecialchars($_POST['email']);
            $phone = htmlspecialchars($_POST['phone']);
        
            $etage = htmlspecialchars($_POST['etage_appartement'] ?? '');
            $acces = htmlspecialchars($_POST['acces_domicile'] ?? '');
            $animaux = htmlspecialchars($_POST['animaux'] ?? '');
            $urgence_nom = htmlspecialchars($_POST['contact_urgence_nom'] ?? '');
            $urgence_lien = htmlspecialchars($_POST['contact_urgence_lien'] ?? '');
            $urgence_tel = htmlspecialchars($_POST['contact_urgence_tel'] ?? '');
            $medical_history = htmlspecialchars($_POST['medical_history'] ?? '');
            $psychological_history = htmlspecialchars($_POST['psychological_history'] ?? '');
            $social_history = htmlspecialchars($_POST['social_history'] ?? '');
            $radiologie = htmlspecialchars($_POST['radiologie'] ?? '');
            $radiologie_liste = htmlspecialchars($_POST['radiologie_liste'] ?? '');
            $medecin_traitant = htmlspecialchars($_POST['medecin_traitant'] ?? '');
            $personal_notes = htmlspecialchars($_POST['personal_notes'] ?? '');
        
            if (updatePatient($conn, $patient_id, $lastname, $firstname, $date_of_birth, $gender, $address, $email, $phone, $etage, $acces, $animaux, $urgence_nom, $urgence_lien, $urgence_tel, $medical_history, $psychological_history, $social_history, $radiologie, $radiologie_liste, $medecin_traitant, $personal_notes)) {
                addLog('Modification patient', $_SESSION['user_id'], "Patient ID $patient_id modifié.");
                $_SESSION['success'] = "Patient mis à jour avec succès !";
            } else {
                $_SESSION['error'] = "Erreur lors de la mise à jour du patient.";
                addLog('Erreur modification patient', $_SESSION['user_id'], "Échec de modification pour le patient ID $patient_id.");
            }
        break;
        

        case 'care':
            $care_id = intval($_POST['care_id']);
            $user_id = intval($_POST['user_id']); // L'utilisateur qui modifie ?
            $care_type = htmlspecialchars($_POST['care_type']);
            $care_description = htmlspecialchars($_POST['care_description']);
            $days_of_week = isset($_POST['days']) ? implode(',', $_POST['days']) : '';
            $care_hours = htmlspecialchars(trim($_POST['care_hours']));
            $categorie = isset($_POST['categorie']) ? implode(',', $_POST['categorie']) : ''; // au cas où tu ajoutes des catégories multiples
            $frequence = htmlspecialchars(trim($_POST['frequence']));
            $designed_caregiver = trim($_POST['designed_caregiver']);
        
            if (updateCare($conn, $care_id, $user_id, $care_type, $care_description, $categorie, $frequence, $days_of_week, $care_hours, $designed_caregiver)) {
                addLog('Modification soin', $_SESSION['user_id'], "Soin ID $care_id modifié.");
                $_SESSION['success'] = "Soin mis à jour avec succès !";
            } else {
                addLog('Erreur modification soin', $_SESSION['user_id'], "Échec de modification pour le soin ID $care_id.");
                $_SESSION['error'] = "Erreur lors de la mise à jour du soin.";
            }
        break;
        

        case 'constantes':
            $vital_sign_id = intval($_POST['vital_sign_id']);
            $temperature = htmlspecialchars($_POST['temperature']);
            $heart_rate = htmlspecialchars($_POST['heart_rate']);
            $respiratory_rate = htmlspecialchars($_POST['respiratory_rate']);
            $frequence_selles = htmlspecialchars($_POST['frequence_selles'] ?? '');
            $volume_urinaire = htmlspecialchars($_POST['volume_urinaire'] ?? '');
            
            if (updateVitalSigns($conn, $vital_sign_id, $temperature, $heart_rate, $respiratory_rate, $frequence_selles, $volume_urinaire)) {
                    addLog('Modification constantes', $_SESSION['user_id'], "Constantes ID $vital_sign_id mises à jour.");
                    $_SESSION['success'] = "Constantes mises à jour avec succès !";
                } else {
                    addLog('Erreur modification constantes', $_SESSION['user_id'], "Échec de mise à jour des constantes ID $vital_sign_id.");
                    $_SESSION['error'] = "Erreur lors de la mise à jour des constantes.";
            }
        break;
            

        case 'transmissions':
            $transmission_id = intval($_POST['transmission_id']);
            $transmission_description = htmlspecialchars($_POST['Transmission_description']);

            if (updateTransmission($conn, $transmission_id, $transmission_description)) {
                addLog('Modification transmission', $_SESSION['user_id'], "Transmission ID $transmission_id modifiée.");
                $_SESSION['success'] = "Transmission mise à jour avec succès !";
            } else {
                addLog('Erreur modification transmission', $_SESSION['user_id'], "Échec modification transmission ID $transmission_id.");
                $_SESSION['error'] = "Erreur lors de la mise à jour de la transmission.";
            }
            break;
    }

    header("Location: " . BASE_URL . "/views/manager/edit-patient.php?id=" . $_POST['patient_id']);
    exit();
} else {
    $_SESSION['error'] = "Requête invalide.";
    header("Location: " . BASE_URL . "/views/manager/folders_patients.php");
    exit();
}
?>


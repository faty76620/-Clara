<?php
require_once __DIR__ . '/../config.php';  
require_once TEMPLATE_DIR . '/session_start.php';
require_once MODEL_DIR . '/database.php';
require_once MODEL_DIR . '/patients.php';
require_once MODEL_DIR . '/care.php';
require_once MODEL_DIR . '/transmission.php';
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

            if (updatePatient($conn, $patient_id, $lastname, $firstname, $date_of_birth, $gender, $address, $email, $phone)) {
                $_SESSION['success'] = "Patient mis à jour avec succès !";
            } else {
                $_SESSION['error'] = "Erreur lors de la mise à jour du patient.";
            }
            break;

        case 'care':
            $care_id = intval($_POST['care_id']);
            $care_type = htmlspecialchars($_POST['care_type']);
            $care_description = htmlspecialchars($_POST['care_description']);

            if (updateCare($conn, $care_id, $care_type, $care_description)) {
                $_SESSION['success'] = "Soin mis à jour avec succès !";
            } else {
                $_SESSION['error'] = "Erreur lors de la mise à jour du soin.";
            }
            break;

        case 'constantes':
            $vital_sign_id = intval($_POST['vital_sign_id']);
            $temperature = htmlspecialchars($_POST['temperature']);
            $heart_rate = htmlspecialchars($_POST['heart_rate']);
            $respiratory_rate = htmlspecialchars($_POST['respiratory_rate']);

            if (updateVitalSigns($conn, $vital_sign_id, $temperature, $heart_rate, $respiratory_rate)) {
                $_SESSION['success'] = "Constantes mises à jour avec succès !";
            } else {
                $_SESSION['error'] = "Erreur lors de la mise à jour des constantes.";
            }
            break;

        case 'transmissions':
            $transmission_id = intval($_POST['transmission_id']);
            $transmission_description = htmlspecialchars($_POST['Transmission_description']);

            if (updateTransmission($conn, $transmission_id, $transmission_description)) {
                $_SESSION['success'] = "Transmission mise à jour avec succès !";
            } else {
                $_SESSION['error'] = "Erreur lors de la mise à jour de la transmission.";
            }
            break;
    }
    error_log(print_r($_POST, true)); // Affiche tout le contenu de $_POST dans le fichier log

    header("Location: " . BASE_URL . "/views/manager/edit-patient.php?id=" . $_POST['patient_id']);
    exit();
} else {
    $_SESSION['error'] = "Requête invalide.";
    header("Location: " . BASE_URL . "/views/manager/folders_patients.php");
    exit();
}
?>

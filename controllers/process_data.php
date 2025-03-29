<?php
// Inclusion des fichiers nécessaires
require_once __DIR__ . '/../config.php';
require_once MODEL_DIR . '/database.php';
require_once MODEL_DIR . '/patients.php';
require_once MODEL_DIR . '/care.php';
require_once MODEL_DIR . '/constante.php';
require_once MODEL_DIR . '/transmission.php';
require_once MODEL_DIR . '/logs.php';

// Connexion à la base de données
$conn = getConnexion();

// Vérifier si la requête POST a bien été envoyée
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $conn->beginTransaction(); // Démarre la transaction

        // Traitement des informations personnelles
        if (isset($_POST['firstname'], $_POST['lastname'], $_POST['email'])) {
            $firstname = htmlspecialchars(trim($_POST['firstname']));
            $lastname = htmlspecialchars(trim($_POST['lastname']));
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $phone = htmlspecialchars(trim($_POST['phone']));
            $address = htmlspecialchars(trim($_POST['address']));
            $date_of_birth = $_POST['date_of_birth'];
            $gender = htmlspecialchars(trim($_POST['gender']));
            $medical_history = htmlspecialchars(trim($_POST['medical_history']));
            $psychological_history = htmlspecialchars(trim($_POST['psychological_history']));
            $social_history = htmlspecialchars(trim($_POST['social_history']));
            $personal_notes = htmlspecialchars(trim($_POST['personal_notes']));

            $patient_id = createPatient($conn, [
                'firstname' => $firstname,
                'lastname' => $lastname,
                'email' => $email,
                'phone' => $phone,
                'address' => $address,
                'date_of_birth' => $date_of_birth,
                'gender' => $gender,
                'medical_history' => $medical_history,
                'psychological_history' => $psychological_history,
                'social_history' => $social_history,
                'personal_notes' => $personal_notes
            ]);

            if (!$patient_id) {
                die("Erreur lors de l'insertion du patient.");
            }
        } 

        // Traitement des informations sur les soins
        if (isset($_POST['care_type'], $_POST['care_description'])) {
            $care_type = htmlspecialchars(trim($_POST['care_type']));
            $care_description = htmlspecialchars(trim($_POST['care_description']));
            $days_of_week = isset($_POST['days']) ? implode(',', $_POST['days']) : ''; // Convertir les jours en chaîne
            $care_hours = $_POST['care_hours'];

            // Insérer dans la table 'care_sessions'
            createCareSession($conn, [
                'patient_id' => $patient_id,
                'care_type' => $care_type,
                'care_description' => $care_description,
                'days_of_week' => $days_of_week,
                'care_hours' => $care_hours
            ]);
        }

        // Traitement des constantes vitales
        if (isset($_POST['temperature'], $_POST['blood_pressure'], $_POST['heart_rate'])) {
            $temperature = $_POST['temperature'];
            $blood_pressure = htmlspecialchars(trim($_POST['blood_pressure']));
            $heart_rate = $_POST['heart_rate'];
            $respiratory_rate = $_POST['respiratory_rate'];
            $recorded_at = $_POST['recorded_at'];

            $transmitted_by = intval($_POST['transmitted_by']); // Convertit en entier

            // Insérer dans la table 'vital_signs'
            createVitalSigns($conn, [
                'patient_id' => $patient_id,
                'temperature' => $temperature,
                'blood_pressure' => $blood_pressure,
                'heart_rate' => $heart_rate,
                'respiratory_rate' => $respiratory_rate,
                'recorded_at' => $recorded_at
            ]);
        }

        // Traitement des transmissions
        if (isset($_POST['transmission_date'], $_POST['transmission_description'])) {
            $transmission_date = $_POST['transmission_date'];
            $transmission_description = htmlspecialchars(trim($_POST['transmission_description']));
            $transmitted_by = htmlspecialchars(trim($_POST['transmitted_by']));

            // Insérer dans la table 'transmissions'
            createTransmission($conn, [
                'patient_id' => $patient_id,
                'transmission_date' => $transmission_date,
                'transmission_description' => $transmission_description,
                'transmitted_by' => $transmitted_by,
            ]);
        }

        // Si tout s'est bien passé, valider la transaction
        $conn->commit();

        // Message de succès
        $_SESSION['success'] = "Les données ont été enregistrées avec succès!";
        header("Location: ../views/manager/folders_patients.php");
        exit();

    } catch (Exception $e) {
        // En cas d'erreur, annuler la transaction
        $conn->rollBack();

        // Message d'erreur
        $_SESSION['error'] = "Une erreur est survenue. Veuillez réessayer.";
        header("Location: ../views/register_patient.php");
        exit();
    }
} else {
    die("Requête invalide.");
}
?>
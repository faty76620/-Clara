<?php
require_once __DIR__ . '/../config.php';
require_once MODEL_DIR . '/database.php';
require_once MODEL_DIR . '/patients.php';
require_once MODEL_DIR . '/care.php';
require_once MODEL_DIR . '/constante.php';
require_once MODEL_DIR . '/transmission.php';
require_once MODEL_DIR . '/logs.php';

$conn = getConnexion();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    try {
        $conn->beginTransaction();

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
            $establishment_id = intval($_POST['establishment_id']); 

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
                'personal_notes' => $personal_notes,
                'establishment_id' => $establishment_id 
            ]);

            if (!$patient_id) {
                die("Erreur lors de l'insertion du patient.");
            }
        } 

        if (isset($_POST['care_type'], $_POST['care_description'], $_POST['care_hours'])) {
            // Sécuriser les entrées et valider les champs
            $care_type = htmlspecialchars(trim($_POST['care_type']));
            $care_description = htmlspecialchars(trim($_POST['care_description']));
            $days_of_week = isset($_POST['days']) ? implode(',', $_POST['days']) : '';
            $care_hours = htmlspecialchars(trim($_POST['care_hours']));  // Assurez-vous que care_hours est défini et propre
            $categorie = isset($_POST['categorie']) ? implode(',', $_POST['categorie']) : '';
            $frequence = htmlspecialchars(trim($_POST['frequence']));
    
            if (!empty($care_type) && !empty($care_description) && !empty($care_hours) && isset($patient_id)) {
                createCareSession($conn, [
                    'patient_id' => $patient_id,
                    'care_type' => $care_type,
                    'care_description' => $care_description,
                    'days_of_week' => $days_of_week,
                    'care_hours' => $care_hours,
                    'categorie' => $categorie,
                    'frequence' => $frequence
                ]);
            } else {
                echo "Veuillez remplir tous les champs obligatoires.";
            }
        } else {
            echo "Les données nécessaires sont manquantes.";
        }
        

        // Traitement des constantes vitales
        if (isset($_POST['temperature'], $_POST['blood_pressure'], $_POST['heart_rate'])) {
            $temperature = $_POST['temperature'];
            $blood_pressure = htmlspecialchars(trim($_POST['blood_pressure']));
            $heart_rate = $_POST['heart_rate'];
            $respiratory_rate = $_POST['respiratory_rate'];
            $recorded_at = $_POST['recorded_at'];

            $transmitted_by = intval($_POST['transmitted_by']); 

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
            $cible = htmlspecialchars(trim($_POST['cible']));

            // Insérer dans la table 'transmissions'
            createTransmission($conn, [
                'patient_id' => $patient_id,
                'transmission_date' => $transmission_date,
                'transmission_description' => $transmission_description,
                'transmitted_by' => $transmitted_by,
                'cible' => $cible,
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
        header("Location: ../views/registration.php");
        exit();
    }
} else {
    die("Requête invalide.");
}
?>
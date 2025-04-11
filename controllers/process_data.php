
<?php
// CHARGEMENT DES DÉPENDANCES
require_once __DIR__ . '/../config.php';
require_once MODEL_DIR . '/database.php';
require_once MODEL_DIR . '/patients.php';
require_once MODEL_DIR . '/care.php';
require_once MODEL_DIR . '/constante.php';
require_once MODEL_DIR . '/transmission.php';
require_once MODEL_DIR . '/logs.php';

// VÉRIFICATION DE LA MÉTHODE POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Requête invalide.");
}

$conn = getConnexion();

try {
    $conn->beginTransaction();
    if (
        isset($_POST['firstname'], $_POST['lastname'], $_POST['email']) &&
        !empty($_POST['firstname']) && !empty($_POST['lastname']) && !empty($_POST['email'])
    ) {
        // SÉCURISATION DES DONNÉES
        $firstname = htmlspecialchars(trim($_POST['firstname']));
        $lastname = htmlspecialchars(trim($_POST['lastname']));
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $phone = htmlspecialchars(trim($_POST['phone'] ?? ''));
        $address = htmlspecialchars(trim($_POST['address'] ?? ''));
        $etage_appartement = htmlspecialchars(trim($_POST['etage_appartement'] ?? ''));
        $acces_domicile = htmlspecialchars(trim($_POST['acces_domicile'] ?? ''));
        $animaux = htmlspecialchars(trim($_POST['animaux'] ?? ''));
        $contact_urgence_nom = htmlspecialchars(trim($_POST['contact_urgence_nom'] ?? ''));
        $contact_urgence_lien = htmlspecialchars(trim($_POST['contact_urgence_lien'] ?? ''));
        $contact_urgence_tel = htmlspecialchars(trim($_POST['contact_urgence_tel'] ?? ''));
        $date_of_birth = $_POST['date_of_birth'] ?? null;
        $gender = htmlspecialchars(trim($_POST['gender'] ?? ''));
        $medical_history = htmlspecialchars(trim($_POST['medical_history'] ?? ''));
        $psychological_history = htmlspecialchars(trim($_POST['psychological_history'] ?? ''));
        $social_history = htmlspecialchars(trim($_POST['social_history'] ?? ''));
        $radiologie = htmlspecialchars(trim($_POST['radiologie'] ?? ''));
        $radiologie_liste = htmlspecialchars(trim($_POST['radiologie_liste'] ?? ''));
        $medecin_traitant = htmlspecialchars(trim($_POST['medecin_traitant'] ?? ''));
        $personal_notes = htmlspecialchars(trim($_POST['personal_notes'] ?? ''));
        $establishment_id = intval($_POST['establishment_id'] ?? 0);

        // INSERTION PATIENT
        $patient_id = createPatient($conn, [
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'phone' => $phone,
            'address' => $address,
            'etage_appartement' => $etage_appartement,
            'acces_domicile' => $acces_domicile,
            'animaux' => $animaux,
            'contact_urgence_nom' => $contact_urgence_nom,
            'contact_urgence_lien' => $contact_urgence_lien,
            'contact_urgence_tel' => $contact_urgence_tel,
            'date_of_birth' => $date_of_birth,
            'gender' => $gender,
            'medical_history' => $medical_history,
            'psychological_history' => $psychological_history,
            'social_history' => $social_history,
            'radiologie' => $radiologie,
            'radiologie_liste' => $radiologie_liste,
            'medecin_traitant' => $medecin_traitant,
            'personal_notes' => $personal_notes,
            'establishment_id' => $establishment_id
        ]);

        if (!$patient_id) {
            throw new Exception("Erreur lors de l'insertion du patient.");
        }
    }

    // Utiliser l'ID du patient existant si non créé
    if (!isset($patient_id) && isset($_POST['patient_id'])) {
        $patient_id = intval($_POST['patient_id']);
    }

    if (
        isset($_POST['care_type'], $_POST['care_description'], $_POST['care_hours'], $_POST['designed_caregiver']) &&
        !empty($_POST['care_type']) && !empty($_POST['care_description']) &&
        !empty($_POST['care_hours']) && !empty($_POST['designed_caregiver']) && isset($patient_id)
    ) {
        $care_type = htmlspecialchars(trim($_POST['care_type']));
        $care_description = htmlspecialchars(trim($_POST['care_description']));
        $days_of_week = isset($_POST['days']) ? implode(',', $_POST['days']) : '';
        $care_hours = htmlspecialchars(trim($_POST['care_hours']));
        $categorie = isset($_POST['categorie']) ? implode(',', $_POST['categorie']) : '';
        $frequence = htmlspecialchars(trim($_POST['frequence'] ?? ''));
        $designed_caregiver = trim($_POST['designed_caregiver']);

        $success = createCareSession($conn, [
            'patient_id' => $patient_id,
            'care_type' => $care_type,
            'care_description' => $care_description,
            'days_of_week' => $days_of_week,
            'care_hours' => $care_hours,
            'categorie' => $categorie,
            'frequence' => $frequence,
            'designed_caregiver' => $designed_caregiver
        ]);

        if (!$success) {
            throw new Exception("Erreur lors de la création du soin.");
        }
    }

    if (
        isset($_POST['temperature'], $_POST['blood_pressure'], $_POST['heart_rate'], $_POST['respiratory_rate']) &&
        isset($patient_id)
    ) {
        $temperature = htmlspecialchars(trim($_POST['temperature']));
        $blood_pressure = htmlspecialchars(trim($_POST['blood_pressure']));
        $heart_rate = htmlspecialchars(trim($_POST['heart_rate']));
        $respiratory_rate = htmlspecialchars(trim($_POST['respiratory_rate']));
        $volume_urinaire = htmlspecialchars(trim($_POST['volume_urinaire'] ?? ''));
        $frequence_selles = htmlspecialchars(trim($_POST['frequence_selles'] ?? ''));
        $recorded_at = $_POST['recorded_at'] ?? date('Y-m-d H:i:s');

        $success = createVitalSigns($conn, [
            'patient_id' => $patient_id,
            'temperature' => $temperature,
            'blood_pressure' => $blood_pressure,
            'heart_rate' => $heart_rate,
            'respiratory_rate' => $respiratory_rate,
            'volume_urinaire' => $volume_urinaire,
            'frequence_selles' => $frequence_selles,
            'recorded_at' => $recorded_at
        ]);

        if (!$success) {
            throw new Exception("Erreur lors de l'enregistrement des constantes vitales.");
        }
    }

    if (
        isset($_POST['transmission_date'], $_POST['transmission_description']) &&
        isset($patient_id)
    ) {
        $transmission_date = $_POST['transmission_date'];
        $transmission_description = htmlspecialchars(trim($_POST['transmission_description']));
        $transmitted_by = htmlspecialchars(trim($_POST['transmitted_by'] ?? ''));
        $cible = htmlspecialchars(trim($_POST['cible'] ?? ''));

        createTransmission($conn, [
            'patient_id' => $patient_id,
            'transmission_date' => $transmission_date,
            'transmission_description' => $transmission_description,
            'transmitted_by' => $transmitted_by,
            'cible' => $cible,
        ]);
    }

    $conn->commit();
    $_SESSION['success'] = "Les données ont été enregistrées avec succès !";
    header("Location: ../views/manager/folders_patients.php");
    exit();

} catch (Exception $e) {
    $conn->rollBack();
    $_SESSION['error'] = "Une erreur est survenue : " . $e->getMessage();
    header("Location: ../views/manager/registrations.php");
    exit();
}
?>


<?php

require_once __DIR__ . '/../../config.php';
require_once MODEL_DIR . '/database.php';
require_once MODEL_DIR . '/patients.php';
require_once MODEL_DIR . '/constante.php';
require_once MODEL_DIR . '/care.php';
require_once MODEL_DIR . '/transmission.php';

$conn = getConnexion();

// Vérification de l'ID du patient
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: manager_patients.php");
    exit();
}

$patient_id = $_GET['id'];
$patient = getPatientById($conn, $patient_id); 
$care = getCareByPatient($conn, $patient_id); 
$vital_signs = getVitalSignsByPatient($conn, $patient_id);
$transmissions = getTransmissionsByPatient($conn, $patient_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/clara/assets/images/logo-onglet.png">
    <link rel="stylesheet" href="/clara/assets/css/style.css">
    <link rel="stylesheet" href="/clara/assets/css/responsive.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Raleway:wght@100&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/15ca748f1e.js" crossorigin="anonymous"></script>
    <script defer src="/clara/assets/js.js"></script>
    <title>Formulaire patient</title>
</head>
<body class="background">
    <main class="dashboard">
<div class="container-title"><h2>Dossier Patient : <?php echo $patient['firstname'] . " " . $patient['lastname']; ?></h2></div>
        
        <!-- Section Patient (Fiche Patient) -->
        <section id="fiche-patient">
            <h3>Fiche Patient</h3>
            <table>
                <tr><td><strong>ID</strong></td><td><?php echo $patient['patient_id']; ?></td></tr>
                <tr><td><strong>Nom</strong></td><td><?php echo $patient['firstname']; ?></td></tr>
                <tr><td><strong>Prénom</strong></td><td><?php echo $patient['lastname']; ?></td></tr>
                <tr><td><strong>Email</strong></td><td><?php echo $patient['email']; ?></td></tr>
                <tr><td><strong>Téléphone</strong></td><td><?php echo $patient['phone']; ?></td></tr>
                <tr><td><strong>Adresse</strong></td><td><?php echo $patient['address']; ?></td></tr>
                <tr><td><strong>Date de naissance</strong></td><td><?php echo $patient['date_of_birth']; ?></td></tr>
                <tr><td><strong>Sexe</strong></td><td><?php echo $patient['gender']; ?></td></tr>
                <tr><td><strong>Historique Médical</strong></td><td><?php echo $patient['medical_history']; ?></td></tr>
                <tr><td><strong>Historique Psychologique</strong></td><td><?php echo $patient['psychological_history']; ?></td></tr>
                <tr><td><strong>Historique Social</strong></td><td><?php echo $patient['social_history']; ?></td></tr>
                <tr><td><strong>Remarques Personnelles</strong></td><td><?php echo $patient['personal_notes']; ?></td></tr>
            </table>
        </section>
        <!-- Section Care (Soins) -->
        <section id="care">
            <h3>Care</h3>
            <table>
                <thead>
                    <tr><th>Type de Care</th><th>Description</th><th>Date</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($care as $care_item): ?>
                    <tr>
                        <td><?php echo $care_item['care_type']; ?></td>
                        <td><?php echo $care_item['care_description']; ?></td>
                        <td><?php echo $care_item['care_date']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
        <!-- Section Constantes -->
        <section id="constantes">
            <h3>Constantes Vitales</h3>
            <table>
                <thead>
                    <tr><th>Température</th><th>Tension</th><th>Fréquence Cardiaque</th><th>Fréquence Respiratoire</th><th>Heure d'Enregistrement</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($vital_signs as $vital): ?>
                    <tr>
                        <td><?php echo $vital['temperature']; ?> °C</td>
                        <td><?php echo $vital['blood_pressure']; ?></td>
                        <td><?php echo $vital['heart_rate']; ?> bpm</td>
                        <td><?php echo $vital['respiratory_rate']; ?> rpm</td>
                        <td><?php echo $vital['recorded_at']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
        <!-- Section Transmissions -->
        <section id="transmissions">
            <h3>Transmissions</h3>
            <table>
                <thead>
                    <tr><th>Transmis par</th><th>Date</th><th>Description</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($transmissions as $trans): ?>
                    <tr>
                    <td><?php echo getTransmissionsWithUserInfo($conn['transmitted_by']);?></td>
                        <td><?php echo $trans['transmission_date']; ?></td>
                        <td><?php echo $trans['transmission_description']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section> 
        <!-- Section Historique des Transmissions et Signalements -->
        <section id="historique">
            <h3>Historique des Transmissions et Signalements</h3>
            <div class="history">
                <h4>Transmissions</h4>
                <ul>
                    <?php foreach ($transmissions as $trans): ?>
                    <li>
                        <strong><?php echo getUserNameById($conn, $trans['transmitted_by']); ?></strong> : <?php echo $trans['transmission_description']; ?> (le <?php echo $trans['transmission_date']; ?>)
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </section>

    </main>
</body>
</html>

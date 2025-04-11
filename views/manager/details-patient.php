<?php
require_once __DIR__ . '/../../config.php';
require_once TEMPLATE_DIR . '/session_start.php'; 
require_once MODEL_DIR . '/database.php';
require_once MODEL_DIR . '/patients.php';
require_once MODEL_DIR . '/care.php';
require_once MODEL_DIR . '/transmission.php';
require_once MODEL_DIR . '/constante.php';

$conn = getConnexion();

// Vérification et récupération de l'ID du patient
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "ID patient non valide.";
    header("Location: " . BASE_URL . "/views/manager/folders_patients.php");
    exit();
}

$patient_id = intval($_GET['id']);

$patient = getPatientById($conn, $patient_id); 
$care =  getCareByPatientWithCaregiver($conn, $patient_id);
$vital_signs = getVitalSignsByPatient($conn, $patient_id);
$transmissions = getTransmissionsByPatientWithUser($conn, $patient_id);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/images/logo-onglet.png">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/responsive.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;800&family=Raleway:wght@100&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script defer src="<?= BASE_URL ?>/assets/js.js"></script>
    <title>Dossier Patient</title>
</head>
<body> 
    <?php include TEMPLATE_DIR . '/header_manager.php'; ?>

    <main class="dashboard">
        <div class="container-title"><h2>Dossier Patient : <?= htmlspecialchars($patient['firstname']) . " " . htmlspecialchars($patient['lastname']); ?></h2></div>
        
        <!-- Onglets pour naviguer entre les sections -->
        <div class="tabs">
            <button id="btn-patient" class="tab-button active" onclick="showTab('patient')"><i class="fas fa-user-injured"></i><span class="tab-text">Patient</span></button>
            <button id="btn-care" class="tab-button" onclick="showTab('care')"><i class="fas fa-stethoscope"></i><span class="tab-text">Soins</span></button>
            <button id="btn-constantes" class="tab-button" onclick="showTab('constantes')"><i class="fas fa-heartbeat"></i><span class="tab-text">Constantes Vitales</span></button>
            <button id="btn-transmissions" class="tab-button" onclick="showTab('transmissions')"><i class="fas fa-notes-medical"></i><span class="tab-text">Transmissions</span></button>
        </div>

        <!-- Informations personnelles -->
        <div class="details">
            <section class="tab-content active" id="patient">
                <h3>Informations personnelles</h3>
                <table class="table-responsive">
                    <thead>
                        <tr>
                            <th>Attribut</th>
                            <th>Valeur</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Etablissement :</strong></td>
                            <td><?= htmlspecialchars($patient['establishment_name']); ?></td>
                        </tr>
                        <tr>
                            <td><strong>ID :</strong></td>
                            <td><?= htmlspecialchars($patient['patient_id']); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Nom :</strong></td>
                            <td><?= htmlspecialchars($patient['firstname']); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Prénom :</strong></td>
                            <td><?= htmlspecialchars($patient['lastname']); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Email :</strong></td>
                            <td><?= htmlspecialchars($patient['email']); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Téléphone :</strong></td>
                            <td><?= htmlspecialchars($patient['phone']); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Adresse :</strong></td>
                            <td><?= htmlspecialchars($patient['address']); ?></td>
                        </tr>
                        </tr>
                        <tr>
                            <td><strong>Etage Appartement :</strong></td>
                            <td><?= htmlspecialchars($patient['etage_appartement']); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Accès domicile :</strong></td>
                            <td><?= htmlspecialchars($patient['acces_domicile']); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Animaux :</strong></td>
                            <td><?= htmlspecialchars($patient['animaux']); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Contact urgence (Nom) :</strong></td>
                            <td><?= htmlspecialchars($patient['contact_urgence_nom']); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Contact urgence (Lien) :</strong></td>
                            <td><?= htmlspecialchars($patient['contact_urgence_lien']); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Contact urgence (Téléphone) :</strong></td>
                            <td><?= htmlspecialchars($patient['contact_urgence_tel']); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Date de naissance :</strong></td>
                            <td><?= htmlspecialchars($patient['date_of_birth']); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Sexe :</strong></td>
                            <td><?= htmlspecialchars($patient['gender']); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Historique Médical :</strong></td>
                            <td><?= htmlspecialchars($patient['medical_history']); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Historique Psychologique :</strong></td>
                            <td><?= htmlspecialchars($patient['psychological_history']); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Historique Social :</strong></td>
                            <td><?= htmlspecialchars($patient['social_history']); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Radiologie :</strong></td>
                            <td><?= htmlspecialchars($patient['radiologie']); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Liste Radiologie :</strong></td>
                            <td><?= htmlspecialchars($patient['radiologie_liste']); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Médecin traitant :</strong></td>
                            <td><?= htmlspecialchars($patient['medecin_traitant']); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Remarques Personnelles :</strong></td>
                            <td><?= htmlspecialchars($patient['personal_notes']); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Date d'admission :</strong></td>
                            <td><?= htmlspecialchars($patient['created_at']); ?></td>
                        </tr>
                    </tbody>
                </table>

                <!-- Cartes info -->
                <div class="cards-container">
                    <div class="card-session">
                        <p><strong>Date d'admission :</strong> <?= htmlspecialchars($patient['created_at']); ?></p>
                        <p><strong>Nom :</strong> <?= htmlspecialchars($patient['lastname']); ?></p>
                        <p><strong>Prénom :</strong> <?= htmlspecialchars($patient['firstname']); ?></p>
                        <p><strong>Email :</strong> <?= htmlspecialchars($patient['email']); ?></p>
                        <p><strong>Téléphone :</strong> <?= htmlspecialchars($patient['phone']); ?></p>
                        <p><strong>Adresse :</strong> <?= htmlspecialchars($patient['address']); ?></p>
                        <p><strong>Etage Appartement :</strong> <?= htmlspecialchars($patient['etage_appartement']); ?></p>
                        <p><strong>Accès domicile :</strong> <?= htmlspecialchars($patient['acces_domicile']); ?></p>
                        <p><strong>Animaux :</strong> <?= htmlspecialchars($patient['animaux']); ?></p>
                        <p><strong>Contact urgence :</strong> <?= htmlspecialchars($patient['contact_urgence_nom']); ?></p>
                        <p><strong>Date de naissance :</strong> <?= htmlspecialchars($patient['date_of_birth']); ?></p>
                        <p><strong>Sexe :</strong> <?= htmlspecialchars($patient['gender']); ?></p>
                        <p><strong>Historique Médical :</strong> <?= htmlspecialchars($patient['medical_history']); ?></p>
                        <p><strong>Radiologie :</strong> <?= htmlspecialchars($patient['radiologie']); ?></p>
                        <p><strong>Médecin traitant :</strong> <?= htmlspecialchars($patient['medecin_traitant']); ?></p>
                    </div>
                </div>
            </section>
   <!-- Section Soins -->
   <section class="tab-content" id="care">
                <h3>Soins</h3>
                <table class="table-responsive">
                    <thead>
                        <tr>
                            <th>Type d'intervention</th>
                            <th>Catégorie</th>
                            <th>Description</th>
                            <th>Fréquence</th>
                            <th>Jours d'interventions</th>
                            <th>Heure de soins</th>
                            <th>Soignante désigné</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($care as $care_item): ?>
                        <tr>
                            <td><?= htmlspecialchars($care_item['care_type']); ?></td>
                            <td><?= htmlspecialchars($care_item['categorie']); ?></td>
                            <td><?= htmlspecialchars($care_item['care_description']); ?></td>
                            <td><?= htmlspecialchars($care_item['frequence']); ?></td>
                            <td><?= htmlspecialchars($care_item['days_of_week']); ?></td>
                            <td><?= htmlspecialchars($care_item['care_hours']); ?></td>
                            <td><?= htmlspecialchars($care_item['caregiver_firstname']) . " " . htmlspecialchars($care_item['caregiver_lastname']); ?></td>

                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <!-- Cartes Soins -->
                <div class="cards-container">
                    <?php foreach ($care as $care_item): ?>
                        <div class="card-session">
                            <p><strong>Type :</strong> <?= htmlspecialchars($care_item['care_type']); ?></p>
                            <p><strong>Catégories :</strong> <?= htmlspecialchars($care_item['categorie']); ?></p>
                            <p><strong>Description :</strong> <?= htmlspecialchars($care_item['care_description']); ?></p>
                            <p><strong>Fréquence :</strong> <?= htmlspecialchars($care_item['frequence']); ?></p>
                            <p><strong>jours d'interventions :</strong> <?= htmlspecialchars($care_item['days_of_week']); ?></p>
                            <p><strong>Heure de soins :</strong> <?= htmlspecialchars($care_item['care_hours']); ?></p>
                            <p><strong>Soignant désigné :</strong><?= htmlspecialchars($care_item['caregiver_firstname']) . " " . htmlspecialchars($care_item['caregiver_lastname']); ?></td>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- Section Constantes Vitales -->
            <section class="tab-content" id="constantes">
                <h3>Constantes Vitales</h3>
                <table class="table-responsive">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Température</th>
                            <th>Tension</th>
                            <th>Fréquence Cardiaque</th>
                            <th>Fréquence Respiratoire</th>
                            <th>Volume urinaire</th>
                            <th>Frequence selles</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($vital_signs as $vital): ?>
                        <tr>
                            <td><?= htmlspecialchars($vital['recorded_at']); ?></td>
                            <td><?= htmlspecialchars($vital['temperature']); ?> °C</td>
                            <td><?= htmlspecialchars($vital['blood_pressure']); ?></td>
                            <td><?= htmlspecialchars($vital['heart_rate']); ?> bpm</td>
                            <td><?= htmlspecialchars($vital['respiratory_rate']); ?> rpm</td>
                            <td><?= htmlspecialchars($vital['frequence_selles']); ?></td>
                            <td><?= htmlspecialchars($vital['volume_urinaire']); ?> ml</td>    
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- Cartes Constantes Vitales -->
                <div class="cards-container">
                    <?php foreach ($vital_signs as $vital): ?>
                        <div class="card-session">
                            <p><strong>Température :</strong> <?= htmlspecialchars($vital['temperature']); ?>°C</p>
                            <p><strong>Fréquence cardiaque :</strong> <?= htmlspecialchars($vital['heart_rate']); ?> BPM</p>
                            <p><strong>Pouls :</strong> <?= htmlspecialchars($vital['respiratory_rate']); ?> rpm</p>
                            <p><strong>Tension artérielle :</strong> <?= htmlspecialchars($vital['blood_pressure']); ?></p>
                            <p><strong>Volume urinaire :</strong> <?= htmlspecialchars($vital['volume_urinaire']); ?> ml</p>
                            <p><strong>Frequences selles :</strong> <?= htmlspecialchars($vital['frequence_selles']); ?></p>
                            <p><strong>Date :</strong> <?= htmlspecialchars($vital['recorded_at']); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
               <!-- Section Transmissions -->
               <section class="tab-content" id="transmissions">
                <h3>Transmissions</h3>
                <table class="table-responsive">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type cyble</th>
                            <th>Description</th>
                            <th>Transmis par</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($transmissions as $trans): ?>
                        <tr>
                            <td><?= htmlspecialchars($trans['transmission_date']); ?></td>
                            <td><?= htmlspecialchars($trans['cible']); ?></td>
                            <td><?= htmlspecialchars($trans['transmission_description']); ?></td>
                            <td><?= htmlspecialchars($trans['user_firstname']) . " " . htmlspecialchars($trans['user_lastname']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                 <!-- Cartes Transmissions -->
                <div class="cards-container">
                    <?php foreach ($transmissions as $transmission): ?>
                    <div class="card-session">
                    <p><strong>Date :</strong> <?= htmlspecialchars($trans['user_firstname']); ?></p>
                        <p><strong>Description :</strong> <?= htmlspecialchars($transmission['transmission_date']); ?></p>
                        <p><strong>Type cible :</strong> <?= htmlspecialchars($transmission['cible']); ?></p>
                        <p><strong>Transmit par :</strong><?= htmlspecialchars($transmission['transmission_description']) . " " . htmlspecialchars($trans['user_lastname']); ?> </p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </section>

        <div class="btn-container">
            <button>
                <a href="<?= BASE_URL ?>/views/manager/folders_patients.php" class="btn-back">Retour</a>
            </button>
        </div>
    </main>
</body>
</html>




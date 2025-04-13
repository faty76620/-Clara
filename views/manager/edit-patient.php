<?php
require_once __DIR__ . '/../../config.php';
require_once TEMPLATE_DIR . '/session_start.php';
require_once MODEL_DIR . '/database.php';
require_once MODEL_DIR . '/patients.php';
require_once MODEL_DIR . '/care.php';
require_once MODEL_DIR . '/caregiver.php';
require_once MODEL_DIR . '/transmission.php';
require_once MODEL_DIR . '/constante.php';

$conn = getConnexion();

// Vérifier si un ID valide est passé
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "ID invalide !";
    header("Location: " . BASE_URL . "/views/manager/folders_patients.php");
    exit();
}

$patient_id = intval($_GET['id']);

$patient = getPatientById($conn, $patient_id);
$care = getCareByPatient($conn, $patient_id);
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
    <title>Modifier dossier patient</title>
</head>

<body class="body-background">
<?php include TEMPLATE_DIR . '/header_manager.php'; ?>

<?php
if (isset($_SESSION['success'])) {
    echo '<div style="color: green; padding: 10px; border: 1px solid green;">' . $_SESSION['success'] . '</div>';
    unset($_SESSION['success']);
}
if (isset($_SESSION['error'])) {
    echo '<div style="color: red; padding: 10px; border: 1px solid red;">' . $_SESSION['error'] . '</div>';
    unset($_SESSION['error']);
}
?>

<main class="dashboard">
    <div class="container-title"><h2>Modification</h2></div>

    <!-- Onglets -->
    <div class="tabs">
        <button id="btn-patient" class="tab-button active" onclick="showTab('patient')"><i class="fas fa-user-injured"></i><span class="tab-text">Patient</span></button>
        <button id="btn-care" class="tab-button" onclick="showTab('care')"><i class="fas fa-stethoscope"></i><span class="tab-text">Soins</span></button>
        <button id="btn-constantes" class="tab-button" onclick="showTab('constantes')"><i class="fas fa-heartbeat"></i><span class="tab-text">Constantes Vitales</span></button>
        <button id="btn-transmissions" class="tab-button" onclick="showTab('transmissions')"><i class="fas fa-notes-medical"></i><span class="tab-text">Transmissions</span></button>
    </div>

    <!-- Section Patient -->
    <section class="tab-content active" id="patient">
        <div class="card">
            <form method="POST" action="../../controllers/edit-folders_patients.php" class="form-session">
                <input type="hidden" name="type_form" value="patient">
                <input type="hidden" name="patient_id" value="<?= $patient_id ?>">

                <div class="group-form">
                    <label>Nom :</label>
                    <input type="text" name="lastname" value="<?= htmlspecialchars($patient['lastname'] ?? '') ?>" required>
                </div>
                <div class="group-form">
                    <label>Prénom :</label>
                    <input type="text" name="firstname" value="<?= htmlspecialchars($patient['firstname'] ?? '') ?>" required>
                </div>
                <div class="group-form">
                    <label>Date de naissance :</label>
                    <input type="date" name="date_of_birth" value="<?= htmlspecialchars($patient['date_of_birth'] ?? '') ?>" required>
                </div>
                <div class="group-form">
                    <label>Sexe :</label>
                    <input type="text" name="gender" value="<?= htmlspecialchars($patient['gender'] ?? '') ?>" required>
                </div>
                <div class="group-form">
                    <label>Adresse :</label>
                    <input type="text" name="address" value="<?= htmlspecialchars($patient['address'] ?? '') ?>" required>
                </div>
                <div class="group-form">
                    <label>Email :</label>
                    <input type="text" name="email" value="<?= htmlspecialchars($patient['email'] ?? '') ?>" required>
                </div>
                <div class="group-form">
                    <label>Téléphone :</label>
                    <input type="tel" name="phone" pattern="[0-9]{10}" value="<?= htmlspecialchars($patient['phone'] ?? '') ?>" required>
                </div>
                <div class="group-form">
                    <label>Etage Appartement :</label> 
                    <input type="text" name="etage_appartement"  value="<?= htmlspecialchars($patient['etage_appartement']) ?? '' ?> "required>
                </div>  
                <div class="group-form">
                    <label>Accès domicile</label>
                    <input type="text" name="acces_domicile" value="<?= htmlspecialchars($patient['acces_domicile']); '' ?> " requiered>
                </div>     
                <div class="group-form">
                    <label>Animaux :</label>
                    <input type="text" name="animaux" value="<?= htmlspecialchars($patient['animaux'] ?? '') ?>">
                </div>
                <div class="group-form">
                    <label>Contact urgence - Nom :</label>
                    <input type="text" name="contact_urgence_nom" value="<?= htmlspecialchars($patient['contact_urgence_nom'] ?? '') ?>">
                </div>
                <div class="group-form">
                    <label>Contact urgence - Lien :</label>
                    <input type="text" name="contact_urgence_lien" value="<?= htmlspecialchars($patient['contact_urgence_lien'] ?? '') ?>">
                </div>
                <div class="group-form">
                    <label>Contact urgence - Téléphone :</label>
                    <input type="tel" name="contact_urgence_tel" pattern="[0-9]{10}" value="<?= htmlspecialchars($patient['contact_urgence_tel'] ?? '') ?>">
                </div>
                <div class="group-form">
                    <label>Historique Médical :</label>
                    <input type="text" name="medical_history" value="<?= htmlspecialchars($patient['medical_history'] ?? '') ?>">
                </div>      
                <div class="group-form">
                    <label>Historique Psychologique :</label>
                    <input type="text" name="psychological_history" value="<?= htmlspecialchars($patient['psychological_history'] ?? '') ?>">
                </div>
                <div class="group-form">
                    <label>Historique Social :</label>
                    <input type="text" name="social_history" value="<?= htmlspecialchars($patient['social_history'] ?? '') ?>">
                </div>
                <div class="group-form">
                    <label>Radiologie :</label>
                    <input type="text" name="radiologie" value="<?= htmlspecialchars($patient['radiologie'] ?? '') ?>">
                </div>
                <div class="group-form">
                    <label>Liste Radiologie :</label>
                    <input type="text" name="radiologie_liste" value="<?= htmlspecialchars($patient['radiologie_liste'] ?? '') ?>">
                </div>
                <div class="group-form">
                    <label>Médecin traitant :</label>
                    <input type="text" name="medecin_traitant" value="<?= htmlspecialchars($patient['medecin_traitant'] ?? '') ?>">
                </div>
                <div class="group-form">
                    <label>Notes personnelles :</label>
                    <textarea name="personal_notes"><?= htmlspecialchars($patient['personal_notes'] ?? '') ?></textarea>
                </div>
                <div class="btn-container">
                    <button type="submit">Modifier</button>
                </div>
            </form>
        </div>
    </section>

    <!-- Section Soins -->
    <section class="tab-content" id="care">
        <?php foreach ($care as $c) : ?>
            <div class="card">
                <form method="POST" action="../../controllers/edit-folders_patients.php" class="form-session">
                    <input type="hidden" name="type_form" value="care">
                    <input type="hidden" name="patient_id" value="<?= $patient_id ?>">
                    <input type="hidden" name="care_id" value="<?= $c['care_id'] ?>">

                    <div class="group-form">
                        <label>Type de soin :</label>
                        <input type="text" name="care_type" value="<?= htmlspecialchars($c['care_type']) ?>" required>
                    </div>

                    <div class="group-form">
                        <label>Description :</label>
                        <textarea name="care_description"><?= htmlspecialchars($c['care_description']) ?></textarea>
                    </div>

                    <div class="group-form">
                        <label>Jours d'intervention :</label>
                        <div class="checkbox">
                            <label><input type="checkbox" name="days[]" value="lundi"> Lundi</label>
                            <label><input type="checkbox" name="days[]" value="mardi"> Mardi</label>
                            <label><input type="checkbox" name="days[]" value="mercredi"> Mercredi</label>
                            <label><input type="checkbox" name="days[]" value="jeudi"> Jeudi</label>
                            <label><input type="checkbox" name="days[]" value="vendredi"> Vendredi</label>
                            <label><input type="checkbox" name="days[]" value="samedi"> Samedi</label>
                            <label><input type="checkbox" name="days[]" value="dimanche"> Dimanche</label>
                        </div>
                    </div>

                    <div class="group-form">
                        <label>Heure du soin :</label>
                        <input type="time" name="care_hours" value="<?= htmlspecialchars($c['care_hours']) ?>" required>
                    </div>

                    <div class="group-form">
                        <label>Fréquence :</label>
                        <input type="text" name="frequence" value="<?= htmlspecialchars($c['frequence']) ?>" required>
                    </div>

                    <div class="group-form">
                        <label>Soignant assigné :</label>
                        <select name="designed_caregiver" required>
                            <?php 
                            $caregivers = getCaregivers($conn, $search, $establishmentId = null);
                            foreach ($caregivers as $caregiver): ?>
                                <option value="<?= $caregiver['id'] ?>"><?= $caregiver['firstname'] . ' ' . $caregiver['lastname'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="btn-container">
                        <button type="submit">Modifier</button>
                    </div>
                </form>
            </div>
        <?php endforeach; ?>
    </section>

    <!-- Section Constantes -->
    <section class="tab-content" id="constantes">
        <?php foreach ($vital_signs as $vs) : ?>
            <div class="card">
                <form method="POST" action="../../controllers/edit-folders_patients.php" class="form-session">
                    <input type="hidden" name="type_form" value="constantes">
                    <input type="hidden" name="vital_sign_id" value="<?= $vs['vital_sign_id'] ?>">
                    <input type="hidden" name="patient_id" value="<?= $patient_id ?>">

                    <div class="group-form">
                        <label>Température :</label>
                        <input type="text" name="temperature" value="<?= htmlspecialchars($vs['temperature']) ?>" required>
                    </div>
                    <div class="group-form">
                        <label>Pouls :</label>
                        <input type="text" name="heart_rate" value="<?= htmlspecialchars($vs['heart_rate']) ?>" required>
                    </div>
                    <div class="group-form">
                        <label>SAT :</label>
                        <input type="text" name="respiratory_rate" value="<?= htmlspecialchars($vs['respiratory_rate']) ?>" required>
                    </div>
                    <div class="group-form">
                        <label>Fréquence des selles :</label>
                        <input type="text" name="frequence_selles" value="<?= htmlspecialchars($vs['frequence_selles'] ?? '') ?>">
                    </div>
                    <div class="group-form">
                        <label>Volume urinaire (ml) :</label>
                        <input type="text" name="volume_urinaire" value="<?= htmlspecialchars($vs['volume_urinaire'] ?? '') ?>" min="0">
                    </div>
                    <div class="btn-container">
                        <button type="submit">Modifier</button>
                    </div>
                </form>
            </div>
        <?php endforeach; ?>
    </section>

    <!-- Section Soins -->
    <section class="tab-content" id="care">
        <?php foreach ($care as $c) : ?>
            <div class="card">
                <form method="POST" action="../../controllers/edit-folders_patients.php" class="form-session">
                    <input type="hidden" name="type_form" value="care">
                    <input type="hidden" name="patient_id" value="<?= $patient_id ?>">
                    <input type="hidden" name="care_id" value="<?= $c['care_id'] ?>">

                    <div class="group-form">
                        <label>Type de soin :</label>
                        <input type="text" name="care_type" value="<?= htmlspecialchars($c['care_type']) ?>" required>
                    </div>

                    <div class="group-form">
                        <label>Description :</label>
                        <textarea name="care_description"><?= htmlspecialchars($c['care_description']) ?></textarea>
                    </div>

                    <div class="group-form">
                        <label>Jours d'intervention :</label>
                        <div class="checkbox">
                            <label><input type="checkbox" name="days[]" value="lundi"> Lundi</label>
                            <label><input type="checkbox" name="days[]" value="mardi"> Mardi</label>
                            <label><input type="checkbox" name="days[]" value="mercredi"> Mercredi</label>
                            <label><input type="checkbox" name="days[]" value="jeudi"> Jeudi</label>
                            <label><input type="checkbox" name="days[]" value="vendredi"> Vendredi</label>
                            <label><input type="checkbox" name="days[]" value="samedi"> Samedi</label>
                            <label><input type="checkbox" name="days[]" value="dimanche"> Dimanche</label>
                        </div>
                    </div>

                    <div class="group-form">
                        <label>Heure du soin :</label>
                        <input type="time" name="care_hours" value="<?= htmlspecialchars($c['care_hours']) ?>" required>
                    </div>

                    <div class="group-form">
                        <label>Fréquence :</label>
                        <input type="text" name="frequence" value="<?= htmlspecialchars($c['frequence']) ?>" required>
                    </div>

                    <div class="group-form">
                        <label>Soignant assigné :</label>
                        <select name="designed_caregiver" required>
                            <?php 
                            $caregivers = getCaregivers($conn, $search, $establishmentId = null);
                            foreach ($caregivers as $caregiver): ?>
                                <option value="<?= $caregiver['id'] ?>"><?= $caregiver['firstname'] . ' ' . $caregiver['lastname'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="btn-container">
                        <button type="submit">Modifier</button>
                    </div>
                </form>
            </div>
        <?php endforeach; ?>
    </section>

    <!-- Section Constantes -->
    <section class="tab-content" id="constantes">
        <?php foreach ($vital_signs as $vs) : ?>
            <div class="card">
                <form method="POST" action="../../controllers/edit-folders_patients.php" class="form-session">
                    <input type="hidden" name="type_form" value="constantes">
                    <input type="hidden" name="vital_sign_id" value="<?= $vs['vital_sign_id'] ?>">
                    <input type="hidden" name="patient_id" value="<?= $patient_id ?>">

                    <div class="group-form">
                        <label>Température :</label>
                        <input type="text" name="temperature" value="<?= htmlspecialchars($vs['temperature']) ?>" required>
                    </div>
                    <div class="group-form">
                        <label>Pouls :</label>
                        <input type="text" name="heart_rate" value="<?= htmlspecialchars($vs['heart_rate']) ?>" required>
                    </div>
                    <div class="group-form">
                        <label>SAT :</label>
                        <input type="text" name="respiratory_rate" value="<?= htmlspecialchars($vs['respiratory_rate']) ?>" required>
                    </div>
                    <div class="group-form">
                        <label>Fréquence des selles :</label>
                        <input type="text" name="frequence_selles" value="<?= htmlspecialchars($vs['frequence_selles'] ?? '') ?>">
                    </div>
                    <div class="group-form">
                        <label>Volume urinaire (ml) :</label>
                        <input type="text" name="volume_urinaire" value="<?= htmlspecialchars($vs['volume_urinaire'] ?? '') ?>" min="0">
                    </div>
                    <div class="btn-container">
                        <button type="submit">Modifier</button>
                    </div>
                </form>
            </div>
        <?php endforeach; ?>
    </section>

    <!-- Section Transmissions -->
    <section class="tab-content" id="transmissions">
        <?php foreach ($transmissions as $trans) : ?>
            <div class="card">
                <form method="POST" action="../../controllers/edit-folders_patients.php" class="form-session">
                    <input type="hidden" name="type_form" value="transmissions">
                    <input type="hidden" name="patient_id" value="<?= $patient_id ?>">
                    <input type="hidden" name="transmission_id" value="<?= $trans['transmission_id'] ?>">

                    <div class="group-form">
                        <label>Transmis par :</label>
                        <input type="text" name="transmitted_by" value="<?= htmlspecialchars($trans['user_firstname'] . ' ' . $trans['user_lastname']) ?>" readonly>
                    </div>
                    <div class="group-form">
                        <label>Date :</label>
                        <input type="text" name="transmission_date" value="<?= htmlspecialchars($trans['transmission_date']) ?>" readonly>
                    </div>
                    <?php
                    // On transforme la ou les cibles en tableau, même si une seule est enregistrée
                    $cibles = isset($trans['cible']) ? explode(',', $trans['cible']) : [];
                    ?>

                    <div class="group-form">
                        <label for="cible">Besoin ciblé :</label>
                        <select name="cible[]" id="cible" required multiple>
                            <option value="respirer" <?= in_array("respirer", $cibles) ? 'selected' : '' ?>>Respirer</option>
                            <option value="boire_et_manger" <?= in_array("boire_et_manger", $cibles) ? 'selected' : '' ?>>Boire et manger</option>
                            <option value="éliminer" <?= in_array("éliminer", $cibles) ? 'selected' : '' ?>>Éliminer</option>
                            <option value="se_mouvoir" <?= in_array("se_mouvoir", $cibles) ? 'selected' : '' ?>>Se mouvoir et maintenir une bonne posture</option>
                            <option value="dormir" <?= in_array("dormir", $cibles) ? 'selected' : '' ?>>Dormir et se reposer</option>
                            <option value="s_habiller" <?= in_array("s_habiller", $cibles) ? 'selected' : '' ?>>Se vêtir et se dévêtir</option>
                            <option value="maintenir_température" <?= in_array("maintenir_température", $cibles) ? 'selected' : '' ?>>Maintenir la température du corps</option>
                            <option value="être_propre" <?= in_array("être_propre", $cibles) ? 'selected' : '' ?>>Être propre et protéger ses téguments</option>
                            <option value="éviter_dangers" <?= in_array("éviter_dangers", $cibles) ? 'selected' : '' ?>>Éviter les dangers</option>
                            <option value="communiquer" <?= in_array("communiquer", $cibles) ? 'selected' : '' ?>>Communiquer avec ses semblables</option>
                            <option value="agir_selon_valeurs" <?= in_array("agir_selon_valeurs", $cibles) ? 'selected' : '' ?>>Agir selon ses croyances et valeurs</option>
                            <option value="s_occuper" <?= in_array("s_occuper", $cibles) ? 'selected' : '' ?>>S’occuper en vue de se réaliser</option>
                            <option value="se_recreer" <?= in_array("se_recreer", $cibles) ? 'selected' : '' ?>>Se récréer</option>
                            <option value="apprendre" <?= in_array("apprendre", $cibles) ? 'selected' : '' ?>>Apprendre</option>
                        </select>
                    </div>
                    <div class="group-form">
                        <label>Description :</label>
                        <textarea name="Transmission_description"><?= htmlspecialchars($trans['transmission_description']) ?></textarea>
                    </div>
                    <div class="btn-container">
                        <button type="submit">Modifier</button>
                    </div>
                </form>
            </div>
        <?php endforeach; ?>
    </section>
</main>
</body>
</html>
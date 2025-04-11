<?php
require_once __DIR__ . '/../../config.php';
require_once TEMPLATE_DIR . '/session_start.php';
require_once MODEL_DIR . '/database.php';
require_once MODEL_DIR . '/patients.php';
require_once MODEL_DIR . '/caregiver.php';
require_once MODEL_DIR . '/user.php';
require_once MODEL_DIR . '/establishment.php';
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script defer src="/clara/assets/js.js"></script>
    <title>Demandes inscription</title>
</head>
<body class="body-background">
    <?php 
    include TEMPLATE_DIR . '/header_manager.php'; 
    $conn = getConnexion();
    ?>
     <?php
        if (isset($_SESSION['success'])) {
            echo '<div style="color: green; padding: 10px; border: 1px solid green; margin-bottom: 10px;">' . $_SESSION['success'] . '</div>';
            unset($_SESSION['success']); 
         }

        if (isset($_SESSION['error'])) {
            echo '<div style="color: red; padding: 10px; border: 1px solid red; margin-bottom: 10px;">' . $_SESSION['error'] . '</div>';
            unset($_SESSION['error']); 
        }
    ?>
    <main class="dashboard">
        <div class="container-title"><h2>Inscription</h2></div>

        <div class="tabs">
            <button id="btn-form-patient" class="tab-button active" onclick="showTab('form-patient')">
                <i class="fas fa-user-injured"></i> <span class="tab-text">Patients</span>
            </button>
            <button id="btn-form-caregiver" class="tab-button" onclick="showTab('form-caregiver')">
                <i class="fas fa-user-md"></i> <span class="tab-text">Soignants</span>
            </button>
        </div>
        <!--section patient -->
        <section id="form-patient" class="tab-content active">
            <form action="../../controllers/process_data.php" method="POST" class="form-session">
                <fieldset>
                    <legend>Informations Personnelles</legend>
                    <div class="group-form">
                        <label>Prénom :</label>
                        <input type="text" name="firstname" required>
                    </div>
                    <div class="group-form">
                        <label>Nom :</label>
                        <input type="text" name="lastname" required>
                    </div>
                    <div class="group-form">
                        <label>Email :</label>
                        <input type="email" name="email">
                    </div>
                    <div class="group-form">
                        <label>Téléphone :</label>
                        <input type="text" name="phone">
                    </div>
                    <div class="group-form">
                        <label>Adresse :</label>
                        <input type="text" name="address">
                    </div>
                    <div class="group-form">
                        <label>Date de naissance :</label>
                        <input type="date" name="date_of_birth">
                    </div>
                    <div class="group-form">
                        <label>Sexe :</label>
                        <select name="gender">
                            <option value="Homme">Homme</option>
                            <option value="Femme">Femme</option>
                        </select>
                    </div>
                    <select hidden name="establishment_id" id="establishment_id" required>
                        <?php
                        $establishments = getEstablishmentsFromRole($conn);
                        foreach ($establishments as $establishment) {
                            $establishmentId = htmlspecialchars($establishment['establishment_id']);
                            $establishmentLabel = htmlspecialchars($establishment['establishment_id']);
                            echo "<option value='{$establishmentId}'>Etablissement #{$establishmentLabel}</option>";
                        }
                        ?>
                    </select>
                    <div class="group-form">
                        <label>Étage / Appartement :</label>
                        <input type="text" name="etage_appartement" placeholder="Ex : 3e étage, Apt B">
                    </div>
                    <div class="group-form">
                        <label>Code / Clé / Interphone :</label>
                        <input type="text" name="acces_domicile" placeholder="Code porte, digicode, etc.">
                    </div>
                    <div class="group-form">
                        <label>Présence d’animaux :</label>
                        <input type="text" name="animaux" placeholder="Ex : Chien, chat...">
                    </div>
                </fieldset>
                <fieldset>
                    <legend>Contact d'Urgence</legend>
                    <div class="group-form">
                        <label for="contact_urgence_nom">Nom complet :</label>
                        <input type="text" name="contact_urgence_nom" id="contact_urgence_nom" placeholder="Nom et prénom">
                    </div>
                    <div class="group-form">
                        <label for="contact_urgence_lien">Lien avec le patient :</label>
                        <input type="text" name="contact_urgence_lien" id="contact_urgence_lien" placeholder="Ex : Fille, Frère, Voisin, Ami...">
                    </div>
                    <div class="group-form">
                        <label for="contact_urgence_tel">Numéro de téléphone :</label>
                        <input type="text" name="contact_urgence_tel" id="contact_urgence_tel">
                    </div>
                </fieldset>
                <fieldset>
                    <legend>Informations Medicaux</legend>
                    <div class="group-form">
                        <label>Traitement(s) prescrit(s) :</label>
                        <textarea name="traitements" placeholder="Ex : Antibiotique, Antalgiques, Diurétiques..."></textarea>
                    </div>
                    <div class="group-form">
                        <label>Historique Médical :</label>
                        <textarea name="medical_history"></textarea>
                    </div>
                    <div class="group-form">
                        <label>Historique Psychologique :</label>
                        <textarea name="psychological_history"></textarea>
                    </div>
                    <div class="group-form">
                        <label>Historique Social :</label>
                        <textarea name="social_history"></textarea>
                    </div>
                    <div class="group-form">
                        <label for="radiologie">Examens radiologiques :</label>
                        <select name="radiologie" id="radiologie" onchange="toggleRadiologyDetails()">
                            <option value="">-- Sélectionnez --</option>
                            <option value="non">Non</option>
                            <option value="oui">Oui</option>
                        </select>
                    </div>
                    <div class="group-form" id="radiologie-details" style="display: none;">
                        <label for="radiologie_liste">Précisez les examens :</label>
                        <input type="text" name="radiologie_liste" id="radiologie_liste" placeholder="Ex : IRM, Scanner, Radiographie...">
                    </div>

                    <div class="group-form">
                        <label for="medecin_traitant">Médecin traitant :</label>
                        <input type="text" name="medecin_traitant" id="medecin_traitant" placeholder="Nom Prénom du médecin">
                    </div>
                    <div class="group-form">
                        <label>Remarques Personnelles :</label>
                        <textarea name="personal_notes"></textarea>
                    </div>
                </fieldset>
                <!--section soins -->
                <fieldset>
                    <legend>Soins</legend>
                    <div class="group-form">
                        <label>Type de soins :</label>
                        <input type="text" name="care_type" placeholder="Type de soin" required>
                    </div>
                    <div class="group-form">
                        <label for="care_description">Description :</label>
                        <textarea name="care_description" id="care_description" required></textarea>
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
                        <label for="care_hours">Heure du soin :</label>
                        <input type="time" name="care_hours" id="care_hours" step="1" required>
                    </div>
                    <div class="group-form">
                        <label>Catégories :</label>
                        <div class="checkbox">
                            <label><input type="checkbox" name="categorie[]" value="Hygiène"> Hygiène</label>
                            <label><input type="checkbox" name="categorie[]" value="Nutrition"> Nutrition</label>
                            <label><input type="checkbox" name="categorie[]" value="Médical"> Médical</label>
                        </div>
                    </div>
                    <div class="group-form">
                        <label for="frequence">Fréquence :</label>
                        <input type="text" name="frequence" id="frequence" placeholder="Ex : Quotidien, Hebdomadaire" required>
                    </div>
                    <div class="group-form">
                        <label for="designed_caregiver">Soignant assigné :</label>
                        <select name="designed_caregiver" id="designed_caregiver" required>
                            <?php 
                            $caregivers = getCaregivers($conn, $search, $establishmentId = null);
                            foreach ($caregivers as $caregiver): ?>
                                <option value="<?= $caregiver['id'] ?>"><?= $caregiver['firstname'] . ' ' . $caregiver['lastname'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </fieldset>
                <!-- Section Constantes Vitales -->
                <fieldset>
                    <legend>Constantes Vitales</legend>

                    <div class="group-form">
                        <label>Température (°C) :</label>
                        <input type="number" step="0.1" name="temperature">
                    </div>
                    <div class="group-form">
                        <label>Tension :</label>
                        <input type="text" name="blood_pressure">
                    </div>
                    <div class="group-form">
                        <label>Fréquence Cardiaque (bpm) :</label>
                        <input type="number" name="heart_rate">
                    </div>
                    <div class="group-form">
                        <label>Fréquence Respiratoire (rpm) :</label>
                        <input type="number" name="respiratory_rate">
                    </div>
                    <div class="group-form">
                        <label>Date d'enregistrement :</label>
                        <input type="datetime-local" name="recorded_at">
                    </div>
                    <div class="group-form">
                        <label>Volume urinaire / jour (en mL) :</label>
                        <input type="number" name="volume_urinaire" placeholder="Ex : 1500 mL">
                    </div>
                    <div class="group-form">
                        <label>Fréquence des selles :</label>
                        <input type="text" name="frequence_selles" placeholder="Ex : 1x/jour, tous les 3 jours...">
                    </div>
                </fieldset>
                 <!-- Section Transmissions -->
                 <fieldset>
                    <legend>Transmissions</legend>
                    <div class="group-form">
                        <label>Date :</label>
                        <input type="datetime-local" name="transmission_date"><br></br>
                    </div>
                    <div class="group-form">
                        <label for="cible">Besoin cyblé:</label>
                        <select name="cible" id="cible" required multiple>
                            <option value="">-- Sélectionnez des besoins --</option>
                            <option value="respirer">Respirer</option>
                            <option value="boire_et_manger">Boire et manger</option>
                            <option value="éliminer">Éliminer</option>
                            <option value="se_mouvoir">Se mouvoir et maintenir une bonne posture</option>
                            <option value="dormir">Dormir et se reposer</option>
                            <option value="s_habiller">Se vêtir et se dévêtir</option>
                            <option value="maintenir_température">Maintenir la température du corps</option>
                            <option value="être_propre">Être propre et protéger ses téguments</option>
                            <option value="éviter_dangers">Éviter les dangers</option>
                            <option value="communiquer">Communiquer avec ses semblables</option>
                            <option value="agir_selon_valeurs">Agir selon ses croyances et valeurs</option>
                            <option value="s_occuper">S’occuper en vue de se réaliser</option>
                            <option value="se_recreer">Se récréer</option>
                            <option value="apprendre">Apprendre</option>
                        </select>
                    </div>
                    <label>Description :</label>
                    <textarea name="transmission_description"></textarea>

                    <input type="hidden" name="transmitted_by" value="<?php echo $_SESSION['user_id']; ?>">
                </fieldset>
                <div class="btn-container">
                    <button type="submit">Enregistrer</button>
                </div>
            </form>
        </section>
         <!-- FORMULAIRE SOIGNANT -->
         <section id="form-caregiver" class="tab-content">
            <form action="../../controllers/process-caregiver.php" method="POST" class="form-session">
                <fieldset>
                    <legend>Informations Personnelles</legend>
                    <div class="group-form">
                        <label for="lastname_user">Nom</label>
                        <input type="text" name="lastname_user" id="lastname_user" required><br>
                    </div>
                    <div class="group-form">
                        <label for="firstname_user">Prénom</label>
                        <input type="text" name="firstname_user" id="firstname_user" required><br>
                    </div>
                    <input type="hidden" name="role" value="3">
                    <div class="group-form">
                        <label for="mail_user">Email</label>
                        <input type="email" name="mail_user" id="mail_user" required><br>
                    </div>
                    <div class="group-form">
                        <label for="phone">Numéro de téléphone</label>
                        <input type="text" name="phone" id="phone" required><br>
                    </div>
                    <input type="hidden" name="role_id" value="3"> <!-- Champ caché pour le role_id -->
                    <div class="group-form">
                        <select hiden name="establishment_id" id="establishment_id" required>
                        <?php
                            $establishments = getEstablishmentsFromRole($conn);
                            foreach ($establishments as $establishment) {
                            $establishmentId = htmlspecialchars($establishment['establishment_id']);
                            $establishmentLabel = htmlspecialchars($establishment['establishment_id']);
                            echo "<option value='{$establishmentId}'>Etablissement #{$establishmentLabel}</option>";
                        }
                        ?>
                        </select>
                    </div>
                </fieldset>
                <fieldset>
                    <legend>Informations professionnelles</legend>
                    <div class="group-form">
                        <label for="lastname_user">Spécialités :</label>
                        <input type="text" name="specialite" id="specialite" required><br>
                    </div>
                    <div class="group-form">
                        <label for="diplome">Diplômes :</label>
                        <input type="text" name="diplome" id="diplome" required><br>
                    </div>
                    <input type="hidden" name="role" value="3">
                    <div class="group-form">
                        <label for="experience">Expériences (années) :</label>
                        <input type="number" name="experience" id="experience" required><br>
                    </div>
                    <div class="group-form">
                        <label for="competences">Compétences :</label>
                        <textarea type="competences" name="competences" id="competences" required></textarea><br>
                    </div>
                </fieldset>

                <div class="btn-container">
                    <button type="submit" name="submit">Enregistrer</button>
                </div>
            </form>
        </section>
    </main>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        <?php if (!empty($search)) : ?>
            <?php if (!empty($responsables)) : ?>
                showTab('form-patient');
            <?php elseif (!empty($utilisateurs)) : ?>
                showTab('form-soignant');
            <?php endif; ?>
        <?php endif; ?>
    });
    </script>
</body>
</html>

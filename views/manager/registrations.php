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
            <button id="btn-form-patient" class="tab-button" onclick="showTab('form-patient')">
                <i class="fas fa-user-injured"></i> <span class="tab-text">Patients</span>
            </button>
            <button id="btn-form-caregiver" class="tab-button" onclick="showTab('form-caregiver')">
                <i class="fas fa-user-md"></i> <span class="tab-text">Soignants</span>
            </button>
        </div>

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
                        <label>Remarques Personnelles :</label>
                        <textarea name="personal_notes"></textarea>
                    </div>
                </fieldset>

                <fieldset>
                    <legend>Soins</legend>
                    <div class="group-form">
                        <label for="care_type">Type de soin :</label>
                        <input type="text" name="care_type" id="care_type" required>
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
                <div class="btn-container">
                    <button type="submit">Enregistrer</button>
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

<?php

require_once __DIR__ . '/../../config.php';
require_once TEMPLATE_DIR . '/session_start.php';
require_once MODEL_DIR . '/database.php';
require_once MODEL_DIR . '/patients.php';
require_once MODEL_DIR . '/user.php';
require_once MODEL_DIR . '/establishment.php';

?>

<?php if (isset($_SESSION['success'])) : ?>
    <div class="alert success">
        <?= $_SESSION['success']; ?>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])) : ?>
    <div class="alert error">
        <?= $_SESSION['error']; ?>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

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

    // Récupérer la recherche
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    
    $conn = getConnexion();
    
    ?>
    <main class="dashboard">
        <div class="container-title"><h2>Inscription</h2></div>

        <!-- Onglets pour changer de formulaire -->
        <div class="tabs">
            <button id="tab-form-patient" class="tab-button active" onclick="showTab('form-patient')">
                <i class="fas fa-user-injured"></i> <span class="tab-text">Patients</span>
            </button>
            <button id="tab-form-caregiver" class="tab-button" onclick="showTab('form-caregiver')">
                <i class="fas fa-user-md"></i> <span class="tab-text">Soignants</span>
            </button>
            
        </div>

        <!-- FORMULAIRE PATIENTS -->
        <div id="form-patient" class="tab-content active">

            <form action="../../controllers/process_data.php" method="POST" class="form-session">
            <!-- Section Informations Personnelles -->
            <fieldset>
                <legend>Informations Personnelles</legend>
                <label>Prénom :</label>
                <input type="text" name="firstname" required>

                <label>Nom :</label>
                <input type="text" name="lastname" required>

                <label>Email :</label>
                <input type="email" name="email">

                <label>Téléphone :</label>
                <input type="text" name="phone">

                <label>Adresse :</label>
                <input type="text" name="address">

                <label>Date de naissance :</label>
                <input type="date" name="date_of_birth">

                <label>Sexe :</label>
                <select name="gender">
                    <option value="Homme">Homme</option>
                    <option value="Femme">Femme</option>
                </select>

                <label>Historique Médical :</label>
                <textarea name="medical_history"></textarea>

                <label>Historique Psychologique :</label>
                <textarea name="psychological_history"></textarea>

                <label>Historique Social :</label>
                <textarea name="social_history"></textarea>

                <label>Remarques Personnelles :</label>
                <textarea name="personal_notes"></textarea>
            </fieldset>

            <!-- Section Soins -->
            <fieldset>
                <legend>Soins</legend>
                <label>Type de soin :</label>
                <input type="text" name="care_type">

                <label>Description :</label>
                <textarea name="care_description"></textarea>

                <label>Jours de soins attribués :</label>
                <div class="days-of-week">
                    <label><input type="checkbox" name="days[]" value="lundi"> Lundi</label>
                    <label><input type="checkbox" name="days[]" value="mardi"> Mardi</label>
                    <label><input type="checkbox" name="days[]" value="mercredi"> Mercredi</label>
                    <label><input type="checkbox" name="days[]" value="jeudi"> Jeudi</label>
                    <label><input type="checkbox" name="days[]" value="vendredi"> Vendredi</label>
                    <label><input type="checkbox" name="days[]" value="samedi"> Samedi</label>
                    <label><input type="checkbox" name="days[]" value="dimanche"> Dimanche</label>
                </div>

                <label>Heure du soin :</label>
                <input type="time" name="care_hours" step="1" required>
            </fieldset>

            <!-- Section Constantes Vitales -->
            <fieldset>
                <legend>Constantes Vitales</legend>
                <label>Température (°C) :</label>
                <input type="number" step="0.1" name="temperature">

                <label>Tension :</label>
                <input type="text" name="blood_pressure">

                <label>Fréquence Cardiaque (bpm) :</label>
                <input type="number" name="heart_rate">

                <label>Fréquence Respiratoire (rpm) :</label>
                <input type="number" name="respiratory_rate">

                <label>Date d'enregistrement :</label>
                <input type="datetime-local" name="recorded_at">
                </fieldset>

                <!-- Section Transmissions -->
                <fieldset>
                    <legend>Transmissions</legend>
                    <label>Date :</label>
                    <input type="datetime-local" name="transmission_date">

                    <label>Description :</label>
                    <textarea name="transmission_description"></textarea>

                    <input type="hidden" name="transmitted_by" value="<?php echo $_SESSION['user_id']; ?>">
                </fieldset>

                <br>
                <div class="btn-container">
                    <button type="submit">Enregistrer</button>
                </div>
            </form>
        </div>
        <!-- FORMULAIRE SOIGNANT -->
        <div id="form-caregiver" class="tab-content">
            <form action="../../controllers/userControllers.php" method="POST" class="form-session">
                <fieldset>
                    <legend>Informations soignants</legend>
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
                        <label for="establishment_id">Choisir un établissement</label>
                        <select name="establishment_id" id="establishment_id" required>
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
                    <button type="submit" name="submit">Enregistrer</button>
                </fieldset>
            </form>
        </div>
    </main>

</body>
</html>
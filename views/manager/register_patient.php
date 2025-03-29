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
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300..800&family=Raleway:wght@100&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/15ca748f1e.js" crossorigin="anonymous"></script>
    <script defer src="/clara/assets/js.js"></script>
    <title>Formulaire D'informations</title>
</head>
<body class="background">

    <?php 
       if (isset($_SESSION['success'])) {
        echo '<div class="success-message">' . htmlspecialchars($_SESSION['success']) . '</div>';
        unset($_SESSION['success']);
    }
    if (isset($_SESSION['error'])) {
        echo '<div class="error-message">' . htmlspecialchars($_SESSION['error']) . '</div>';
        unset($_SESSION['error']);
        }
        
        require_once __DIR__ . '/../../config.php';
        require_once TEMPLATE_DIR . '/session_start.php'; 
        include TEMPLATE_DIR . '/header_admin.php';
    ?>
<main class="dashboard">
    <div class="container-title"><h2>Formulaire d'Entrée des Informations</h2></div>

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
</main>

</body>
</html>


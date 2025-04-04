<?php
require_once __DIR__ . '/../../config.php';
require_once TEMPLATE_DIR . '/session_start.php';
require_once MODEL_DIR . '/database.php';
require_once MODEL_DIR . '/patients.php';
require_once MODEL_DIR . '/care.php';
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

<body>
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
            <button class="tab-button active" onclick="showTab('patient')"><i class="fas fa-user-injured"></i><span class="tab-text"> Patient</span></button>
            <button class="tab-button" onclick="showTab('care')"><i class="fas fa-stethoscope"></i><span class="tab-text"> Soins</span></button>
            <button class="tab-button" onclick="showTab('constantes')"><i class="fas fa-heartbeat"></i><span class="tab-text"> Constantes</span></button>
            <button class="tab-button" onclick="showTab('transmissions')"><i class="fas fa-notes-medical"></i><span> Transmissions</span></button>
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
                        <input type="text" id="gender" name="gender" value="<?= htmlspecialchars($patient['gender'] ?? '') ?>" required>
                    </div>
                    <div class="group-form">
                        <label>Adresse :</label>
                        <input type="tex" name="address" value="<?= htmlspecialchars($patient['address'] ?? '') ?>" required>
                    </div>
                    <div class="group-form">
                        <label>Email :</label>
                        <input type="text" name="email" value="<?= htmlspecialchars($patient['email'] ?? '') ?>" required>
                    </div>
                    <div class="group-form">
                        <label>Telephone :</label>
                        <input type="tel" id="phone" name="phone" pattern="[0-9]{10}" value="<?= htmlspecialchars($patient['phone'] ?? '') ?>" required>
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
                        <input type="hidden" name="patient_id" value="<?= htmlspecialchars($patient_id) ?>">
                        <input type="hidden" name="care_id" value="<?= htmlspecialchars($c['care_id']) ?>">

                        <label for="care_type">Type de soin :</label>
                    <select name="care_type" id="care_type" required>
                        <option value="">-- Sélectionnez un besoin --</option>
                        <?php
                        $needs = [
                            "respirer" => "Respirer",
                            "boire_et_manger" => "Boire et manger",
                            "éliminer" => "Éliminer",
                            "se_mouvoir" => "Se mouvoir et maintenir une bonne posture",
                            "dormir" => "Dormir et se reposer",
                            "s_habiller" => "Se vêtir et se dévêtir",
                            "maintenir_température" => "Maintenir la température du corps",
                            "être_propre" => "Être propre et protéger ses téguments",
                            "éviter_dangers" => "Éviter les dangers",
                            "communiquer" => "Communiquer avec ses semblables",
                            "agir_selon_valeurs" => "Agir selon ses croyances et valeurs",
                            "s_occuper" => "S’occuper en vue de se réaliser",
                            "se_recreer" => "Se récréer",
                            "apprendre" => "Apprendre"
                        ];

                        foreach ($needs as $key => $label) {
                            $selected = ($c['care_type'] == $key) ? 'selected' : '';
                            echo "<option value=\"$key\" $selected>$label</option>";
                        }
                        ?>
                    </select>
                        <div class="group-form">
                            <label>Description :</label>
                            <textarea name="care_description"><?= htmlspecialchars($c['care_description']) ?></textarea>
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
                        <input type="hidden" name="vital_sign_id" value="<?= htmlspecialchars($vs['vital_sign_id']) ?>">
                        <input type="hidden" name="patient_id" value="<?= htmlspecialchars($patient_id) ?>">

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
                        <div class="btn-container">
                            <button type="submit">Modifier</button>
                        </div>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Section Transmissions -->
        <section class="tab-content" id="transmissions">
            <?php foreach ($transmissions as $trans) : ?>
                <div class="card">
                    <form method="POST" action="../../controllers/edit-folders_patients.php"class="form-session">
                        <input type="hidden" name="type_form" value="transmissions">
                        <input type="hidden" name="patient_id" value="<?= htmlspecialchars($patient_id) ?>">
                        <input type="hidden" name="transmission_id" value="<?= htmlspecialchars($trans['transmission_id']) ?>">


                        <div class="group-form">
                            <label>Transmit par :</label>
                            <input name="transmitted_by"<?= htmlspecialchars($trans['user_firstname']) . " " . htmlspecialchars($trans['user_lastname']); ?>required>
                        </div> 
                        <div class="group-form">
                            <label>Date :</label>
                            <input name="transmission_date"<?= htmlspecialchars($trans['transmission_date']) ?>required>
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
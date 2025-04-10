<?php
require_once __DIR__ . '/../../config.php';
require_once TEMPLATE_DIR . '/session_start.php';
require_once MODEL_DIR . '/database.php';
require_once MODEL_DIR . '/patients.php';
require_once MODEL_DIR . '/caregiver.php';
require_once MODEL_DIR . '/user.php';
require_once MODEL_DIR . '/establishment.php';

// Vérification de l'ID soignant pour chargement modification
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $conn = getConnexion();
    $id = intval($_GET['id']);
    $caregiver = getCaregiverById($conn, $id);
    $establishments = getAllEstablishments($conn);
    if (!$caregiver) {
        $_SESSION['error'] = "Utilisateur ou soignant non trouvé.";
        header("Location: " . BASE_URL . "/views/manager/folders_patients.php?section=caregiver");
        exit();
    }
}
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
        <div class="container-title"><h2>Modification Soignant</h2></div>
        <div class="card">
            <form method="POST" action="../../controllers/edit-caregiver.php" class="form-session">
                <input type="hidden" name="caregiver_id" value="<?= htmlspecialchars($caregiver['id']) ?>">
                <input type="hidden" name="user_id" value="<?= htmlspecialchars($caregiver['user_id']) ?>">

                <h3>Informations personnelles</h3>
                <div class="group-form">
                    <label for="firstname">Prénom :</label>
                    <input type="text" id="firstname" name="firstname" value="<?= htmlspecialchars($caregiver['firstname']) ?>" required>
                </div>

                <div class="group-form">
                    <label for="lastname">Nom :</label>
                    <input type="text" id="lastname" name="lastname" value="<?= htmlspecialchars($caregiver['lastname']) ?>" required>
                </div>

                <div class="group-form">
                    <label for="mail">Email :</label>
                    <input type="email" id="mail" name="mail" value="<?= htmlspecialchars($caregiver['mail']) ?>" required>
                </div>

                <h3>Informations professionnelles</h3>
                <div class="group-form">
                    <label for="specialite">Spécialité :</label>
                    <input type="text" id="specialite" name="specialite" value="<?= htmlspecialchars($caregiver['specialite']) ?>" required>
                </div>

                <div class="group-form">
                    <label for="diplome">Diplôme :</label>
                    <input type="text" id="diplome" name="diplome" value="<?= htmlspecialchars($caregiver['diplome']) ?>" required>
                </div>

                <div class="group-form">
                    <label for="experience">Expérience (années) :</label>
                    <input type="number" id="experience" name="experience" value="<?= intval($caregiver['experience']) ?>" required>
                </div>

                <div class="group-form">
                    <label for="competences">Compétences :</label>
                    <textarea id="competences" name="competences"><?= htmlspecialchars($caregiver['competences']) ?></textarea>
                </div>

                <div class="group-form">
                    <label for="establishment">Établissement :</label>
                    <select id="establishment" name="establishment_id" required>
                        <option value="">-- Sélectionner un établissement --</option>
                        <?php foreach ($establishments as $establishment): ?>
                            <option value="<?= htmlspecialchars($establishment['id']) ?>">
                                <?= htmlspecialchars($establishment['firstname']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="btn-container">
                    <button type="submit">Modifier</button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>




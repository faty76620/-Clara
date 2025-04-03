<?php
require_once __DIR__ . '/../../config.php';
require_once TEMPLATE_DIR . '/session_start.php'; 
require_once MODEL_DIR . '/database.php';
require_once MODEL_DIR . '/caregiver.php';
require_once MODEL_DIR . '/user.php';
$conn = getConnexion();

// Vérification et récupération de l'ID du soignant
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
} else {
    $_SESSION['error'] = "ID soignant non valide.";
    header("Location: " . BASE_URL . "/views/manager/folders_caregivers.php");
    exit();
}

// Récupérer les informations du soignant
$caregiver = getCaregiverById($conn, $id);

// Vérification si les informations du soignant existent
if (!$caregiver) {
    $_SESSION['error'] = "Soignant introuvable.";
    header("Location: " . BASE_URL . "/views/manager/folders_caregivers.php");
    exit();
}

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
    <title>Détails Soignant</title>
</head>
<body> 
    <?php include TEMPLATE_DIR . '/header_manager.php'; ?>

    <main class="dashboard">
        <div class="container-title">
            <h2>Détails du Soignant : <?= isset($caregiver['firstname']) ? htmlspecialchars($caregiver['firstname']) . " " . htmlspecialchars($caregiver['lastname']) : 'Non spécifié'; ?></h2>
        </div>
        <table class="table-responsive">
        <thead>
            <tr>
                <th>Attribut</th>
                <th>Valeur</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>id :</strong></td>
                <td><?= isset($caregiver['user_id']) ? htmlspecialchars($caregiver['user_id']) : 'Non spécifié'; ?></td>
            </tr>
            <tr>
                <td><strong>Nom :</strong></td>
                <td><?= isset($caregiver['firstname']) ? htmlspecialchars($caregiver['firstname']) : 'Non spécifié'; ?></td>
            </tr>
            <tr>
                <td><strong>Prénom :</strong></td>
                <td><?= isset($caregiver['lastname']) ? htmlspecialchars($caregiver['lastname']) : 'Non spécifié'; ?></td>
            </tr>
            <tr>
                <td><strong>Email :</strong></td>
                <td><?= isset($caregiver['mail']) ? htmlspecialchars($caregiver['mail']) : 'Non spécifié'; ?></td>
            </tr>
            <tr>
                <td><strong>Date d'embauche :</strong></td>
                <td><?= isset($caregiver['date_create']) ? htmlspecialchars($caregiver['date_create']) : 'Non spécifié'; ?></td>
            </tr>
            <tr>
                <td><strong>Etablissement :</strong></td>
                <td><?= isset($caregiver['establishment_name']) ? htmlspecialchars($caregiver['establishment_name']) : 'Non spécifié'; ?></td>
            </tr>
            <tr>
                <td><strong>Spécialité :</strong></td>
                <td><?= isset($caregiver['specialite']) ? htmlspecialchars($caregiver['specialite']) : 'Non spécifié'; ?></td>
            </tr>
            <tr>
            <td><strong>Diplome :</strong></td>
                <td><?= isset($caregiver['diplome']) ? htmlspecialchars($caregiver['diplome']) : 'Non spécifié'; ?></td>
            </tr>
            <tr>
                <td><strong>Compétences :</strong></td>
                <td><?= isset($caregiver['competences']) ? htmlspecialchars($caregiver['competences']) : 'Non spécifié'; ?></td>
            </tr>
        </table>
        <!-- Card pour afficher les informations du soignant -->
        <div class="cards-container">
            <div class="card-session">
                <h3><?= isset($caregiver['firstname']) ? htmlspecialchars($caregiver['firstname']) . " " . htmlspecialchars($caregiver['lastname']) : 'Non spécifié'; ?></h3>
                <p><strong>ID :</strong></p> 
                <p><?= isset($caregiver['user_id']) ? htmlspecialchars($caregiver['user_id']) : 'Non spécifié'; ?></p>                    <strong>Email :</strong> <?= isset($caregiver['mail']) ? htmlspecialchars($caregiver['mail']) : 'Non spécifié'; ?>
                <p><strong>Date d'embauche :</strong></p>
                <p><?= isset($caregiver['date_create']) ? htmlspecialchars($caregiver['date_create']) : 'Non spécifié'; ?></p>
                <p><strong>Spécialité :</strong></p>
                <p><?= isset($caregiver['specialite']) ? htmlspecialchars($caregiver['specialite']) : 'Non spécifié'; ?></p>
                <p><strong>Diplôme :</strong></p> 
                <p><?= isset($caregiver['diplome']) ? htmlspecialchars($caregiver['diplome']) : 'Non spécifié'; ?></p>
                <p><strong>Compétences :</strong></p> 
                <p><?= isset($caregiver['competences']) ? htmlspecialchars($caregiver['competences']) : 'Non spécifié'; ?></p>
            </div>
        </div>

        <!-- Bouton retour -->
        <div class="btn-container">
            <button>
                <a href="<?= BASE_URL ?>/views/manager/folders_patients.php" class="btn-back">Retour</a>
            </button>
        </div>
    </main>

</body>
</html>


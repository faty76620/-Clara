<?php
require_once __DIR__ . '/../../config.php';
require_once TEMPLATE_DIR . '/session_start.php';
require_once MODEL_DIR . '/database.php';
require_once MODEL_DIR . '/caregiver.php';
require_once MODEL_DIR . '/user.php';
require_once MODEL_DIR . '/establishment.php';
$conn = getConnexion();

// Vérification de l'ID soignant
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "Soignant introuvable.";
    header("Location: " . BASE_URL . "/views/manager/folders_patients.php?section=caregiver");
    exit();
}

$id = intval($_GET['id']);  // Récupérer et sécuriser l'ID du soignant
$caregiver = getCaregiverById($conn, $id); 
$establishments = getAllEstablishments($conn);

if (!$caregiver) {
    $_SESSION['error'] = "Utilisateur ou soignant non trouvé.";
    header("Location: " . BASE_URL . "/views/manager/folders_patients.php?section=caregiver");
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
    <title>Modification Soignant</title>
</head>
<body>
    <?php include TEMPLATE_DIR . '/header_manager.php'; ?>

    <main class="dashboard">
        <div class="container-title"><h2>Modification Soignant</h2></div>

        <!-- Affichage des messages d'erreur et de succès -->
        <?php if (isset($_SESSION['error'])): ?>
            <div style="color: red; padding: 10px; border: 1px solid red;">
                <?= htmlspecialchars($_SESSION['error']) ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div style="color: green; padding: 10px; border: 1px solid green;">
                <?= htmlspecialchars($_SESSION['success']) ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <div class="card">
            <form method="POST" action="../../controllers/edit-caregiver.php" class="form-session">
                <input type="hidden" name="caregiver_id" value="<?= htmlspecialchars($caregiver['id']) ?>">
                <input type="hidden" name="user_id" value="<?= htmlspecialchars($caregiver['user_id']) ?>">

                <h3>Informations personnelles</h3>
                <label for="firstname">Prénom :</label>
                <input type="text" id="firstname" name="firstname" value="<?= htmlspecialchars($caregiver['firstname']) ?>" required>

                <label for="lastname">Nom :</label>
                <input type="text" id="lastname" name="lastname" value="<?= htmlspecialchars($caregiver['lastname']) ?>" required>

                <label for="mail">Email :</label>
                <input type="email" id="mail" name="mail" value="<?= htmlspecialchars($caregiver['mail']) ?>" required>

                <h3>Informations professionnelles</h3>
                <label for="specialite">Spécialité :</label>
                <input type="text" id="specialite" name="specialite" value="<?= htmlspecialchars($caregiver['specialite']) ?>" required>

                <label for="diplome">Diplôme :</label>
                <input type="text" id="diplome" name="diplome" value="<?= htmlspecialchars($caregiver['diplome']) ?>" required>

                <label for="experience">Expérience (années) :</label>
                <input type="number" id="experience" name="experience" value="<?= intval($caregiver['experience']) ?>" required>

                <label for="competences">Compétences :</label>
                <textarea id="competences" name="competences"><?= htmlspecialchars($caregiver['competences']) ?></textarea>

                <select id="establishment" name="establishment_id" required>
                    <option value="">-- Sélectionner un établissement --</option>
                        <?php foreach ($establishments as $establishment): ?>
                        <option value="<?= htmlspecialchars($establishment['id']) ?>">
                            <?= htmlspecialchars($establishment['firstname']) ?>
                        </option>
                        <?php endforeach; ?>
                </select>
        
                <div class="btn-container">
                    <button type="submit">Modifier</button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>



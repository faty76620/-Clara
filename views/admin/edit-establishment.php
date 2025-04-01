<?php
require_once __DIR__ . '/../../config.php';
require_once TEMPLATE_DIR . '/session_start.php';
require_once MODEL_DIR . '/database.php';
require_once MODEL_DIR . '/establishment.php';

$conn = getConnexion();

// Vérifier si un ID valide est passé
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "ID invalide !";
    header("Location: " . BASE_URL . "/views/admin/establishment.php");
    exit();
}

$id = intval($_GET['id']);
$establishment = getEstablishmentById($conn, $id);

if (!$establishment) {
    $_SESSION['error'] = "Établissement non trouvé !";
    header("Location: " . BASE_URL . "/views/admin/establishment.php");
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
    <title>Dossier Patient</title>
</head>

<body>
    <?php include TEMPLATE_DIR . '/header_admin.php'; ?>

    <!-- Affichage des messages -->
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
    <div class="container-title"><h2>Modifier l'établissement</h2></div>
    <div class="card">
        <form method="POST" action="../../controllers/edit-establishment.php" class="form-session">
            <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">

            <div class="group-form">
                <label>Nom de domaine :</label>
                <input type="text" name="firstname" value="<?= htmlspecialchars($establishment['firstname'] ?? '') ?>" required>
            </div>
            <div class="group-form">
                <label>Téléphone :</label>
                <input type="text" name="phone" value="<?= htmlspecialchars($establishment['phone'] ?? '') ?>" required>
            </div>
            <div class="group-form">
                <label>Adresse :</label>
                <input type="text" name="adresse" value="<?= htmlspecialchars($establishment['adresse'] ?? '') ?>" required>
            </div>
            <div class="group-form">
                <label>Email :</label>
                <input type="email" name="mail" value="<?= htmlspecialchars($establishment['mail'] ?? '') ?>" required>
            </div>
            <div class="group-form">
                <label>Description :</label>
                <textarea name="description" required><?= htmlspecialchars($establishment['description'] ?? '') ?></textarea>
            </div>
            <button type="submit">Mettre à jour</button>
        </form>
    </div>
    <div class="btn-container">
        <button><a href="establishment.php">Retour</a></button>
    </div>
</main>
</body>
</html>

  
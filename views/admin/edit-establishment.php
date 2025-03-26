<?php
include __DIR__ . '/../../templates/session_start.php'; 
require_once '../../models/database.php';
require_once '../../models/establishment.php';

$conn = getConnexion();

// Vérifier si un ID est passé
if (!isset($_GET['id']) || empty($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "ID invalide !";
    header("Location: establishment.php");
    exit();
}

$id = intval($_GET['id']);
$establishment = getEstablishmentById($conn, $id);

if (!$establishment) {
    $_SESSION['error'] = "Établissement non trouvé !";
    header("Location: establishment.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un établissement</title>
    <link rel="stylesheet" href="/clara/assets/css/style.css">
</head>
<body>
<?php include __DIR__ . '/../../templates/header_admin.php'; ?>

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


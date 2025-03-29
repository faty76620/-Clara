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
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un établissement</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>
    <?php include TEMPLATE_DIR . '/header_admin.php'; ?>

    <!-- Affichage des messages -->
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
        <div class="container-title"><h2>Modifier l'établissement</h2></div>
        <div class="card">
            <form method="POST" action="<?= BASE_URL ?>/controllers/edit-establishment.php" class="form-session">
                <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">

                <?php
                $fields = [
                    "Nom de domaine" => ["firstname", "text"],
                    "Téléphone" => ["phone", "text"],
                    "Adresse" => ["adresse", "text"],
                    "Email" => ["mail", "email"]
                ];
                foreach ($fields as $label => [$name, $type]) :
                ?>
                    <div class="group-form">
                        <label><?= $label ?> :</label>
                        <input type="<?= $type ?>" name="<?= $name ?>" value="<?= htmlspecialchars($establishment[$name] ?? '') ?>" required>
                    </div>
                <?php endforeach; ?>

                <div class="group-form">
                    <label>Description :</label>
                    <textarea name="description" required><?= htmlspecialchars($establishment['description'] ?? '') ?></textarea>
                </div>

                <button type="submit">Mettre à jour</button>
            </form>
        </div>
        <div class="btn-container">
            <button><a href="<?= BASE_URL ?>/views/admin/establishment.php">Retour</a></button>
        </div>
    </main>
</body>
</html>



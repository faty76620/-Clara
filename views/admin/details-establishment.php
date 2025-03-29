<?php
require_once __DIR__ . '/../../config.php';
require_once TEMPLATE_DIR . '/session_start.php'; 
require_once MODEL_DIR . '/database.php';
require_once MODEL_DIR . '/establishment.php';

$conn = getConnexion();

// Vérifier si l'ID est passé dans l'URL et est valide
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "ID non valide.";
    header("Location: " . BASE_URL . "/views/admin/establishment.php");
    exit();
}

$id = intval($_GET['id']);
$establishment = getEstablishmentById($conn, $id);

// Vérifier si l'établissement existe
if (!$establishment) {
    $_SESSION['error'] = "Aucun établissement trouvé avec cet ID.";
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
    <title>Demandes d'inscription</title>
</head>
<body> 
    <?php include TEMPLATE_DIR . '/header_admin.php'; ?>

    <main class="dashboard">
        <div class="container-title"><h2>Détails</h2></div>
        <div class="details">
            <table class="table-details">
                <?php
                $fields = [
                    "ID" => "id",
                    "Nom domaine" => "firstname",
                    "Téléphone" => "phone",
                    "Adresse" => "adresse",
                    "Email" => "mail",
                    "Siret" => "siret",
                    "Type d'établissement" => "type_role",
                    "Site web" => "site",
                    "Description" => "description"
                ];
                foreach ($fields as $label => $key) {
                    echo "<tr><td><strong>$label :</strong></td><td>" . htmlspecialchars($establishment[$key]) . "</td></tr>";
                }
                ?>
            </table>

            <div class="cards-container">
                <div class="card-session">
                    <h3><?= htmlspecialchars($establishment['firstname']); ?></h3><br>
                    <?php
                    foreach ($fields as $label => $key) {
                        echo "<p><strong>$label :</strong></p><p>" . htmlspecialchars($establishment[$key]) . "</p>";
                    }
                    ?>
                </div>
            </div> 
        </div>

        <div class="btn-container">
            <button>
                <a href="<?= BASE_URL ?>/views/admin/establishment.php" class="btn-back">Retour</a>
            </button>
        </div>
    </main>
</body>
</html>

<?php
require_once '../../models/database.php';
require_once '../../models/establishment.php';
$conn = getConnexion();

// Vérifier si un ID est passé en URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID invalide !");
}

$id = intval($_GET['id']);
$establishment = getEstablishmentById($conn, $id);

if (!$establishment) {
    die("Établissement non trouvé !");
}

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $firstname = $_POST['firstname'];
    $phone = $_POST['phone'];
    $adresse = $_POST['adresse'];
    $mail = $_POST['mail'];
    $description = $_POST['description'];

    if (updateEstablishment($conn, $id, $firstname, $phone, $adresse, $mail, $description)) {
        header("Location: establishment.php?success=1");
        exit();
    } else {
        echo "Erreur lors de la mise à jour.";
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
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script defer src="/clara/assets/js.js"></script>
    <title>Modifier un etablissement</title>
</head>   
<body class="body-background">
<?php
    include __DIR__ . '/../../templates/header_admin.php'; ?>
    <main class="dashboard">
        <div class ="container-title"><h2>Modifier l'établissement</h2></div>
        <div class="card">
            <form method="POST">
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
                    <label>Siret :</label>
                    <input type="text" name="siret" value="<?= htmlspecialchars($establishment['siret'] ?? '') ?>" required>
                </div>
                <div class="group-form">
                    <label>Type d'établissement :</label>
                    <input type="text" name="type_role" value="<?= htmlspecialchars($establishment['type_role'] ?? '') ?>" required>
                </div>
                <div class="group-form">
                    <label>Site web :</label>
                    <input type="url" name="site" value="<?= htmlspecialchars($establishment['site'] ?? '') ?>" required>
                </div>
                <div class="group-form">
                    <label>Description :</label>
                    <textarea name="description" required><?= htmlspecialchars($establishment['description'] ?? '') ?></textarea>
                </div>


                <button type="submit">Mettre à jour</button>
            </form>
        </div>
        <div class="btn-container">
            <button><a href="establishments.php">Retour</a></button>
        </div>
    </main>
</body>
</html>

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
    <title>Modifier l'établissement</title>
    <link rel="stylesheet" href="/clara/assets/css/style.css">
</head>
<body>
    <main class="main form-edit">
        <h2>Modifier l'établissement</h2>
        <div class="card">
            <form method="POST">
                <div class="group-form">
                    <label>Nom :</label>
                    <input type="text" name="firstname" value="<?= htmlspecialchars($establishment['firstname']) ?>" required>
                </div>
                <div class="group-form">
                    <label>Téléphone :</label>
                    <input type="text" name="phone" value="<?= htmlspecialchars($establishment['phone']) ?>" required>
                </div>
                <div>
                    <label>Adresse :</label>
                    <input type="text" name="adresse" value="<?= htmlspecialchars($establishment['adresse']) ?>" required>
                </div>
                <div class="group-form">
                    <label>Email :</label>
                    <input type="email" name="mail" value="<?= htmlspecialchars($establishment['mail']) ?>" required>
                </div>
                <div class="group-form">
                    <label>Description :</label>
                    <textarea name="description" style="padding: 15px;"><?= htmlspecialchars($establishment['description']) ?></textarea>
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

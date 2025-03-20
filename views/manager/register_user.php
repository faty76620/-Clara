<?php
require_once '../../models/establishment.php';
$conn = getConnexion();
$establishments = getEstablishmentsFromRole($conn);
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
    <script src="https://kit.fontawesome.com/15ca748f1e.js" crossorigin="anonymous"></script>
    <script defer src="/clara/assets/js.js"></script>
    <title>Tableau de bord</title>
</head>
<body>
<?php
 include __DIR__ . '/../../templates/session_start.php'; 
 
if (isset($_SESSION['success'])) {
    echo '<div style="color: green; padding: 10px; border: 1px solid green; margin-bottom: 10px;">' . $_SESSION['success'] . '</div>';
    unset($_SESSION['success']); 
}
if (isset($_SESSION['error'])) {
    echo '<div style="color: red; padding: 10px; border: 1px solid red; margin-bottom: 10px;">' . $_SESSION['error'] . '</div>';
    unset($_SESSION['error']);
}
?>
    <?php include __DIR__ . '/../../templates/header_manager.php'; ?>
    <main class="dashboard">
        <h2>Inscription utilisateur</h2>
        <div class="register-sessions">
            <form action="/clara/controllers/userControllers.php" method="POST">
                <div class="group-form">
                    <label for="lastname_user">Nom</label>
                    <input type="text" name="lastname_user" id="lastname_user" required><br>
                </div>
                <div class="group-form">
                    <label for="firstname_user">Prénom</label>
                    <input type="text" name="firstname_user" id="firstname_user" required><br>
                </div>
                <input type="hidden" name="role" value="3">
                <div class="group-form">
                    <label for="mail_user">Email</label>
                    <input type="email" name="mail_user" id="mail_user" required><br>
                </div>
                <div class="group-form">
                    <label for="phone">Numéro de téléphone</label>
                    <input type="text" name="phone" id="phone" required><br>
                </div>
                <input type="hidden" name="role_id" value="3"> <!-- Champ caché pour le role_id -->
                <div class="group-form">
                    <label for="establishment_id">Choisir un établissement</label>
                    <select name="establishment_id" id="establishment_id" required>
                        <?php
                        // Afficher chaque établissement dans la liste déroulante
                        foreach ($establishments as $establishment) {
                        echo "<option value='{$establishment['establishment_id']}'>Etablissement #{$establishment['establishment_id']}</option>";
                        }
                        ?> 
                    </select>
                </div>
                <button type="submit" name="submit">Enregistrer</button>
            </form>
        </div>
    </main>
</body>
</html>






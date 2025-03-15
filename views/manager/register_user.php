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
    <?php include __DIR__ . '/../../templates/header_manager.php'; 
        require_once '../models/database.php'; 
        require_once '../models/establishment.php'; 
        
        $conn = getConnexion();
        
        // Récupérer les établissements approuvés
        $establishments = getApprovedEstablishments($conn);
    ?>
    <main class="dashboard">
        <h2>Inscription utilisateur</h2>
        <div class="register-sessions">
            <form action="process_user_registration.php" method="POST">
                <div class="group-form">
                    <label for="lastname_admin">Nom</label>
                    <input type="text" name="lastname_admin" id="lastname_admin" required><br>
                </div>
                <div class="group-form">
                    <label for="firstname_admin">Prénom</label>
                    <input type="text" name="firstname_admin" id="firstname_admin" required><br>
                </div>
                <input type="hidden" name="role" value="3">
                <div class="group-form">
                    <label for="mail_admin">Email</label>
                    <input type="email" name="mail_admin" id="mail_admin" required><br>
                </div>
                <div class="group-form">
                    <label for="phone">Numéro de téléphone</label>
                    <input type="text" name="phone" id="phone" required><br>
                </div>
                <div class="group-form">
                <label for="status">Statut de l'établissement</label>
                    <select name="status" id="status" required>
                        <option value="pending">En attente</option>
                        <option value="approved">Approuvé</option>
                        <option value="rejected">Rejeté</option>
                    </select>
                </div>
                <div class="group-form">
                    <label for="establishment_id">Choisir un établissement</label>
                    <select name="establishment_id" id="establishment_id" required>
                        <?php
                        // Afficher les établissements approuvés
                        foreach ($establishments as $establishment) {
                        echo "<option value='{$establishment['id']}'>{$establishment['name']}</option>";
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


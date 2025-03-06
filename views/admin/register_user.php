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
    <title>Inscription utilisateur</title>
</head>
<body>
    <?php 
        include __DIR__ . '/../../templates/header_admin.php'; ?>
    <main class="dashboard">
    <div class="container-title"><h2>Inscription d'un utilisateur</h2></div>
    <div  class="flex-register">
        <div class="register-container">
            <form action="../controllers/adminController.php?action=register_user" method="post">
                <!-- CHAMPS CACHÉS POUR L'ID DE L'ÉTABLISSEMENT ET LE RÔLE -->
                <input type="hidden" name="establishment_id" required><br>
                <input type="hidden" name="role_id" value="2"> 
        
                <!-- NOM D'UTILISATEUR -->
                <label for="username">Nom d'utilisateur :</label>
                <input type="text" id="username" name="username" required><br>

                <!-- MOT DE PASSE -->
                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required><br>

                <!-- CONFIRMATION MOT DE PASSE -->
                <label for="confirm_password">Confirmer le mot de passe :</label>
                <input type="password" id="confirm_password" name="confirm_password" required><br>

                <!-- EMAIL -->
                <label for="email">Email :</label>
                <input type="email" id="email" name="email" required><br>

                <div class="btn-container">
            <button>
                <a href="/clara/views/admin/requests.php" class="btn-back">Retour</a>
            </button>
        </div>
            </form>
        </div>
    </div>
    </main>
</body>
</html>

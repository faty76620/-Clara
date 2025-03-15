<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/clara/assets/images/logo-onglet.png">
    <link rel="stylesheet" href="/clara/assets/css/style.css">
    <link rel="stylesheet" href="/clara/assets/css/responsive.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"> 
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Raleway:wght@100&display=swap" rel="stylesheet">
    <script defer src="/clara/assets/js.js"></script>
    <title>Connexion</title>
</head>

<body class="body-background">
<?php
session_start();
// Affichage des messages d'erreur
if (isset($_SESSION['error'])) {
    echo '<p>' . $_SESSION['error'] . '</p>';
    unset($_SESSION['error']);  // Supprimer le message après l'affichage
}

// Affichage du message de succès
if (isset($_SESSION['success'])) {
    echo '<p>' . $_SESSION['success'] . '</p>';
    unset($_SESSION['success']);  // Supprimer le message après l'affichage
}
?>
    <div class="part-connect">
        <div class="logo-connect">
            <a href="/clara/views/home/home.php"><img id="logo-connexion" src="/clara/assets/images/logo.png"></a>
        </div>
        <div class="container-form-login">
            <form action="/clara/controllers/authController.php" method="POST" class="form-login">
                <div class="group-form">
                    <i class="fa-solid fa-user" style="color:#49667f;"></i>
                    <input type="text" name="username" id="username" placeholder="Identifiant" required> 
                </div>
                <div class="group-form">
                    <i class="fa-solid fa-eye" style="color:#49667f;"></i>
                    <input type="password" name="password" id="password" placeholder="Mot de passe" required>
                </div>
                <div class="group-form">
                </div>
                <div class="group-form">
                    <button class="btn" type="submit">Connexion</button>
                </div>
                <div class="password-forgot">
                    <a href="#" class="open-modal">Mot de passe oublié ?</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

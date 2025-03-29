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
require_once __DIR__ . '/../../config.php'; 
require_once TEMPLATE_DIR . '/session_start.php';  

if (isset($_GET['expired'])) {
    echo '<p style="color: red; font-weight: bold;">Votre session a expiré. Veuillez vous reconnecter.</p>';  
}

if (isset($_SESSION['success'])) {
    echo '<div style="color: green; padding: 10px; border: 1px solid green; margin-bottom: 10px;">' . $_SESSION['success'] . '</div>';
    unset($_SESSION['success']); 
}
if (isset($_SESSION['error'])) {
    echo '<div style="color: red; padding: 10px; border: 1px solid red; margin-bottom: 10px;">' . $_SESSION['error'] . '</div>';
    unset($_SESSION['error']);
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
                    <a href="forgot_pass.php" class="open-modal">Mot de passe oublié ?</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/clara/assets/images/logo-onglet.png">
    <link rel="stylesheet" href="/clara/assets/css/style.css">
    <link rel="stylesheet" href="/clara/assets/css/responsive.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Raleway:wght@100&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://kit.fontawesome.com/15ca748f1e.js" crossorigin="anonymous"></script>
    <script defer src="/clara/assets/js.js"></script>
    <title>Changer le mot de passe</title>
</head>
<body class="body-background">

    <div class="new_password">
        <h2>Changer votre mot de passe</h2>
        <?php if (isset($_GET['error'])): ?>
        <p style="color: red;"><?php echo htmlspecialchars($_GET['error']); ?></p>
        <?php endif; ?>
        <?php if (isset($_GET['success'])): ?>
        <p style="color: green;"><?php echo htmlspecialchars($_GET['success']); ?></p>
        <?php endif; ?>
        <form method="POST"action="/clara/controllers/authController.php" class="form-login">
            <div class="group-form">
                <label>Ancien mot de passe :</label>
                <input type="password" name="old_password" required>
            </div>
            <div class="group-form">
                <label>Nouveau mot de passe :</label>
                <input type="password" name="new_password" required>
            </div>
            <div class="group-form">
                <label>Confirmer le nouveau mot de passe :</label>
                <input type="password" name="confirm_password" required>
            </div>
                <button type="submit">Mettre à jour</button>
        </form>
        <br>
        <a href="dashboard.php">Retour au tableau de bord</a>
    </div>
</body>
</html>
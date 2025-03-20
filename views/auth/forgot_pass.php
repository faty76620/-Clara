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
    <title>Mot de passe oublié</title>
</head>
<body>
        <?php 
            include __DIR__ . '/../../templates/header.php'; ?>
         <?php
    
        if (isset($_SESSION['error'])) {
            echo "<p style='color: red;'>".$_SESSION['error']."</p>";
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['success'])) {
            echo "<p style='color: green;'>".$_SESSION['success']."</p>";
            unset($_SESSION['success']);
        }
        ?>
        <main class="main">
            <h2>Reinitialisation du mot de passe</h2>
            <div class="register-sessions">
                <form action="/clara/controllers/regetPassword-process.php" method="POST">
                    <div class="form-group">
                        <label for="email">Email :</label>
                        <input type="email" id="email" name="email" style="width: 50%;" required>
                    </div>
                    <div class="flex-btn-submit">
                        <button type="submit">Envoyer</button>
                    </div>
                </form>
            </div>
         </main>
</body>
</html>
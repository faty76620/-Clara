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
    <title>Succés</title>
</head>
<body>
    <?php 
    include __DIR__ . '/../../templates/header.php'; ?>
    <main class="main">
        <div class="flex-container">
            <div class="success-message">
                <h2>Votre demande d'inscription a bien été prise en compte.</h2>
                <a href="/clara/views/home/home.php">Retour à la page d'accueil</a>
            </div>
        </div>
    </main>
        <?php include __DIR__ . '/../../templates/footer.php';?>  
</body>
</html>
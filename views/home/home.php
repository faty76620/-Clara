<!DOCTYPE html>
<html lang="en"> 
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/clara/assets/images/logo-onglet.png">
    <link rel="preload" as="image" href="/clara/assets/images/img-banner.jpg">
    <link rel="stylesheet" href="/clara/assets/css/style.css">
    <link rel="stylesheet" href="/clara/assets/css/responsive.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Raleway:wght@100&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/15ca748f1e.js" crossorigin="anonymous"></script>
    <script defer src="/clara/assets/js.js"></script>
    <title>Accueil</title>
</head>
    <body>
        <?php 
        include __DIR__ . '/../../templates/header.php'; ?>
        <section class="section-banner">
            <div class="banner">
                <div class="overlay"></div>
                <div class="banner-content">
                    <h1>AU SERVICE DES SOIGNANTS LIBÉRAUX</h1>
                    <p>Accompagner. Prévenir. Soigner</p>
                    <a href="/clara/views/auth/request_registration.php" class="btn">Demander l'inscription</a>
                </div>
            </div>
            <div class="banner-img">
                <div>
                    <img src="/clara/assets/images/img-banner.jpg" alt="soignante aidant une patiente a se lever">
                </div>
            </div>
        </section>
        <section class="info-section">
            <h2>Pourquoi s'inscrire sur CLARA ?</h2>
            <p>Notre site est spécialement conçu pour les services de soins a domicile et les cabinets de soins libéraux ayant maximum 10 employes. En inscrivant votre établissement, vous bénéficiez de nombreux avantages :</p>
            <div class="advantages">
                <div class="advantage"><i class="fas fa-check-circle"></i> Une organisation simplifiée gérant efficacement votre équipe</div>
                <div class="advantage"><i class="fas fa-check-circle"></i> Un accèes rapide au informations patients</div>
                <div class="advantage"><i class="fas fa-check-circle"></i> Une coordination améliorée entre soignants  </div>
                <div class="advantage"><i class="fas fa-check-circle"></i>Un espace sécurisé, avec une protection des données de vos patients </div>
            </div>
            <div class="register">
                <a href="/clara/views/auth/request_registration.php" class="btn">Demander l'inscription</a>
            </div>
        </section>
        <footer>
            <?php include __DIR__ . '/../../templates/footer.php';?>
        </footer>
   
    </body>
</html>

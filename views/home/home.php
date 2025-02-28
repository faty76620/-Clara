<!DOCTYPE html>
<html lang="en"> 
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/clara/assets/images/logo-onglet.png">
    <link rel="stylesheet" href="/clara/assets/css/style.css">
    <link rel="stylesheet" href="/clara/assets/css/responsive.css">
    <link rel="preload" as="image" href="/clara/assets/images/img-banner.jpg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Raleway:wght@100&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
            <p>Notre site est spécialement conçu pour les services de soins a domicile et les cabinets de soins libéraux ayant maximum 10 salariés. En inscrivant votre établissement, vous bénéficiez de nombreux avantages :</p>
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
        <section>
            <div class="caregiver">
                <div class="line">
                    <div class="card">
                        <div class="card-content">
                            <a href=""><img src="/clara/assets/images/infirmier.jpg" alt="infirmier"></a> 
                            <h3>Infirmier</h3>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-content">
                            <a href=""><img src="/clara/assets/images/aide-soignante.jpg" alt="aide-soignante"></a>
                            <h3>Aide-soignant</h3>
                        </div>
                    </div>
                </div> 
                <div class="line">
                    <div class="card">
                        <div class="card-content">
                            <a href=""><img src="/clara/assets/images/kine.jpg" alt="kinésithérapeute"></a>
                            <h3>kinésithérapeute</h3>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-content">
                            <a href=""><img src="/clara/assets/images/ergo.jpg" alt="ergothérapeute"></a> 
                            <h3>Ergothérapeute</h3>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <footer>
            <?php include __DIR__ . '/../../templates/footer.php';?>
        </footer>
   
    </body>
</html>

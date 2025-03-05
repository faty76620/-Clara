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
                <img src="/clara/assets/images/img-banner.jpg" alt="soignante aidant une patiente a se lever">
            </div>
        </div>
        </section>
        <section class="info-section">
            <h2>Bienvenue sur CLARA</h2>
            <p>Clara est une plateforme dédiée aux établissements de soins (SSIAD, cabinets médicaux, services infirmiers) qui facilite la gestion et la coordination des soins à domicile. Grâce à un accès sécurisé et structuré, chaque professionnel dispose des outils adaptés à son rôle pour assurer un suivi optimal des patients.</p>
            <h3>Comment accéder aux fonctionnalités ?</h3>
            <p>L’utilisation de Clara est réservée aux établissements de santé. Pour bénéficier des fonctionnalités, il est nécessaire de faire une demande d’inscription, qui doit être validée avant l’accès à la plateforme.</p>
            <h3>Qui peut s’inscrire ?</h3>
            <p>Seul le responsable soignant (infirmière coordinatrice ou gestionnaire de cabinet médical) peut effectuer cette demande. Une fois l’établissement validé, il pourra créer des comptes pour son équipe soignante.</p>
            <h3>Pourquoi s’inscrire ?</h3>
            <div class="advantages">
                <div class="advantage"><i class="fas fa-check-circle"></i>Gérez facilement les plannings et les soins</div>
                <div class="advantage"><i class="fas fa-check-circle"></i>Accédez aux dossiers patients (antécédents, constantes, transmissions).</div>
                <div class="advantage"><i class="fas fa-check-circle"></i>Facilitez la communication entre soignants.</div>
                <div class="advantage"><i class="fas fa-check-circle"></i>Assurez une meilleure traçabilité des soins.</div>
            </div>
            <div class="register">
                <a href="/clara/views/auth/request_registration.php" class="btn">Demander l'inscription</a>
            </div>
        </section>
        <section class="caregiver">
            <h2>Les métiers du soin à domicile</h2> 
            <div class="caregiver-container">
                <div class="card">
                    <div class="card-content">
                        <a href="#"><img src="/clara/assets/images/medecin.jpg" alt="Médecin"></a>
                        <h3>Médecin</h3>
                    </div>
                </div>
                <div class="card">
                    <div class="card-content">
                        <a href="#"><img src="/clara/assets/images/kine.jpg" alt="Kinésithérapeute"></a>
                        <h3>Kinésithérapeute</h3>
                    </div>
                </div>
                <div class="card">
                    <div class="card-content">
                        <a href="#"><img src="/clara/assets/images/ergo.jpg" alt="Ergothérapeute"></a>
                        <h3>Ergothérapeute</h3>
                    </div>
                </div>
                <div class="card">
                    <div class="card-content">
                        <a href="#"><img src="/clara/assets/images/infirmier.jpg" alt="Infirmier"></a>
                        <h3>Infirmiér(e)</h3>
                    </div>
                </div>
                <div class="card">
                    <div class="card-content">
                        <a href="#"><img src="/clara/assets/images/aide-soignant.jpg" alt="Aide-soignante"></a>
                        <h3>Aide-soignant(e)</h3>
                    </div>
                </div>
                <div class="card">
                    <div class="card-content">
                        <a href="#"><img src="/clara/assets/images/auxiliaire.jpg" alt="Auxiliaire de vie"></a>
                        <h3>Auxiliaire de vie</h3>
                    </div>
                </div>
            </div>
        </section>
        <?php include __DIR__ . '/../../templates/footer.php';?>
</body>
</html>

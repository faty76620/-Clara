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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
            </div>
            <div class="banner-img">
                <img src="/clara/assets/images/img-banner.jpg" alt="soignante aidant une patiente a se lever">
            </div>
        </section>
        <section class="info-section">
            <h2>Bienvenue sur CLARA</h2>
            <p>Clara est une plateforme dédiée aux établissements de soins (SSIAD, cabinets médicaux, services infirmiers) qui facilite la gestion et la coordination des soins à domicile. Grâce à un accès sécurisé et structuré, chaque professionnel dispose des outils adaptés à son rôle pour assurer un suivi optimal des patients.</p><br>
            <h3>Comment accéder aux fonctionnalités ?</h3>
            <p>L’utilisation de Clara est réservée aux établissements de santé. Pour bénéficier des fonctionnalités, il est nécessaire de faire une demande d’inscription, qui doit être validée avant l’accès à la plateforme.</p>
            <h3>Qui peut s’inscrire ?</h3>
            <p>Seul le responsable soignant (responsable de coordination ou gestionnaire de cabinet médical) peut effectuer cette demande. Une fois l’établissement validé, il pourra créer des comptes pour son équipe soignant.</p>
            <h3>Pourquoi s’inscrire ?</h3>
            <div class="advantages">
                <p class="advantage"><i class="fas fa-check-circle"></i>Gérez facilement les plannings et les soins</p>
                <p class="advantage"><i class="fas fa-check-circle"></i>Accédez aux dossiers patients (antécédents et constantes).</p>
                <p class="advantage"><i class="fas fa-check-circle"></i>Facilitez la communication entre soignants avec les transmissions.</p>
                <p class="advantage"><i class="fas fa-check-circle"></i>Assurez une meilleure traçabilité des soins.</p>
                <p class="advantage"><i class="fas fa-check-circle"></i>Savoir si des urgences ou des modifications ont été éffectuées.</p>
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
                        <a href="/clara/views/home/job_1.php"><img src="/clara/assets/images/infirmier.jpg" alt="Infirmier"></a>
                        <h3>Infirmiér(e)</h3>
                    </div>
                </div>
                <div class="card">
                    <div class="card-content">
                        <a href="/clara/views/home/job_2.php"><img src="/clara/assets/images/aide-soignant.jpg" alt="Aide-soignante"></a>
                        <h3>Aide-soignant(e)</h3>
                    </div>
                </div>
                <div class="card">
                    <div class="card-content">
                        <a href="/clara/views/home/job_3.php"><img src="/clara/assets/images/auxiliaire.jpg" alt="Auxiliaire de vie"></a>
                        <h3>Auxiliaire de vie</h3>
                    </div>
                </div>
            </div>
        </section>
        <section class="container-job">
            <h2>L'utilité de la plateforme</h2><br>
            <h3>Gestion simplifié du planning</h3>
            <p>Le site permet aux professionnelles de soins de centraliser tout les plannings des soins à prodiguer, ils pouvez :</p>
            <ul>
                <li>Accéder facilement à leur plannings en temps réel.</li>
                <li>En cas d'urgence il est possible de modifier et adapter ses horraires.</li>
                <li>Valider ses interventions selon les disponibilités et les priorités.</li>
            </ul>
            <h3>Suivi des soins et de l'historique médical des patients</h3>
            <p>Des informations détaillées de chaque patients sont concerver dans des dossiers medicaux, vous pouvez :</p>
            <ul>
                <li>Consulter l'historique des soins, des transmissions, constantes de santé du patient, ce qui lui permet de mieux comprendre l'evolution de son état</li>
                <li>Consulter les informations telles que les antécédant médicaux et les prescriptions de traitement sont accessibles rapidement, afin de prodiguer des soins adaptés</li>
            </ul>
            <h3>Communication améliorée</h3>
            <p>Les transmissions permettent :</P>
            <ul>
                <li>D'echanger facilement avec les autres soignants ou avec le responsable coordinateur, en transmettant des informations importante ou en signalant des probléme ou des changements de situations</li>
                <li>Signaler toute situation urgente ou particulière, pour que le responsable coordinateur puisse prendre des mesures nécessaires</li>
            </ul>
            <h3>Validation des interventions</h3>
            <p>La validation des interventions est un processus essentiel qui garantit ques les soins prodiguées aux patients sont correctement plannifiés, executés et validés par les responsable. Ce processus de validation est important pour plusieurs raisons :</p>
            <ul>
                <li>La validation permet d'assurer que les interventions sont réalisées selon les prescription médicales, en respectant les protocoles de soin et les besoins specifique du patient.</li>
                <li>Permet de vérifier que chaque intervention a bien été effectuée comme prevu, avec le bon timing et les bonnes actions.</li>
                <li>Permet de delimité les responsabilités, par exemple si un soins n'a pas été réalisé plus correctement ou n'a pas été fait, il est plus facile de remonter l'erreur à la personne responsable.</li>
                <li>Elle offre une tracabilité des actions, ce qui est particulièrement important dans un environement médical ou a moindre erreur peu avoir des conséquences.</li>
                <li>Une fois validé l'intervention est confirmée comme accomplie ce qui permet de libérer les creaneaux pour les autre soins.</li>
                <li>Une intervention n'a pas pu être réalisée, il est possible pour le responsable coordinateur de prévoir un nouveau créneau </li>
            </ul>
            <h3>Utilité des signalements</h3>
            <p>Les signalements sont essentiels pour la sécurité des patients et coordination des soins, il permettent de remonter toute anomalie ou situation nécissitant une attention particulière</p>
                <ul>
                    <li>Si un patient présente une aggravation de son etat (ex: fièvre, plaie infectée, agitation inhabituelle), un signalement permet d'allerter le responsable coordinateur ou le medecin rapidement</li>
                    <li>Un signalement peut concerner une situation dangereuse (domicile insalubre, comportement agregressif du patient, matériel deffectueux). </li>
                    <li>Permet d'anticiper des mesures de sécurité adaptées.</li>
                </ul>
                <div class="btn-register">
                    <a href="/clara/views/auth/request_registration.php" class="btn">Demander l'inscription</a>
                <div>
        </section>
        <?php include __DIR__ . '/../../templates/footer.php';?>
</body>
</html>

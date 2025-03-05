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
    <title>Fonctionnalités</title>
</head>
<body>
    <?php 
        include __DIR__ . '/../../templates/header.php'; ?>
    <main class="main">
        <section class="features">
            <h2>Nos Fonctionnalités</h2>
            <p class="intro-text">
                Notre plateforme offre plusieurs fonctionnalités essentielles pour améliorer la gestion des soins à domicile et la communication entre les soignants.
            </p>
            <div class="features-container"> 
                <div class="feature">
                    <i class="fa-solid fa-calendar-check"></i>
                    <h3>Planning Interactif</h3>
                    <p>Gérez facilement vos interventions grâce à un calendrier intelligent.</p>
                    <ul>
                        <li>Affichage des heures d'intervention.</li>
                        <li>Visualisation des noms des patients et des soins à apporter.</li>
                        <li>Possibilité de cliquer sur une intervention pour voir tous les détails.</li>
                        <li>Modification et annulation des interventions en quelques clics.</li>
                    </ul>
                </div>
                <div class="feature">
                    <i class="fa-solid fa-folder-open"></i>
                    <h3>Dossier Patient Sécurisé</h3>
                    <p>Accédez aux dossiers médicaux des patients en toute sécurité.</p>
                    <ul>
                        <li>Informations médicales détaillées et mises à jour en temps réel.</li>
                        <li>Historique des soins administrés.</li>
                        <li>Notes et remarques des soignants pour un meilleur suivi.</li>
                    </ul>
                </div>
                <div class="feature">
                    <i class="fa-solid fa-share-from-square"></i>
                    <h3>Transmission des Informations</h3>
                    <p>Transmettez les informations essentielles entre soignants.</p>
                    <ul>
                        <li>Enregistrement des transmissions liées aux interventions.</li>
                        <li>Possibilité de créer une transmission indépendante d'une intervention.</li>
                        <li>Accès rapide aux transmissions précédentes.</li>
                        <li>Marquage des transmissions comme "lues" ou "urgentes".</li>
                    </ul>
                </div>
                <div class="feature">
                    <i class="fa-solid fa-check-double"></i>
                    <h3>Validation des Interventions</h3>
                    <p>Confirmez chaque intervention effectuée pour un meilleur suivi des soins.</p>
                    <ul>
                        <li>Possibilité de valider une intervention après sa réalisation.</li>
                        <li>Ajout d’un commentaire ou d’une note sur la prestation effectuée.</li>
                        <li>Historique des interventions validées pour assurer la traçabilité.</li>
                        <li>Vérification par l'administration pour s'assurer du bon déroulement des soins.</li>
                    </ul>
                </div>
                <div class="feature">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <h3>Signalement des Accidents</h3>
                    <p>Signalez rapidement un incident survenu lors d’une intervention.</p>
                    <ul>
                        <li>Formulaire rapide pour déclarer un accident ou incident.</li>
                        <li>Possibilité d'ajouter des photos et une description détaillée.</li>
                        <li>Notification immédiate aux responsables pour prise en charge rapide.</li>
                        <li>Archivage des signalements pour analyse et prévention.</li>
                    </ul>
                </div>
            </div>
        </section>
    </main>
    <?php include __DIR__ . '/../../templates/footer.php';?>
</body>
</html>
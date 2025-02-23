<!DOCTYPE html>
<html lang="en">
<?php 
    $title = "Accueil"; 
    include __DIR__ . '/../../templates/head.php'; ?>
    <body>
        <?php 
        include __DIR__ . '/../../templates/header.php'; ?>
        <main class="main">
            <h2>Mentions Légales</h2>
            <section class="mentions-legales">
                <table border="1">
                    <tr>
                        <th>Editeur du site</th>
                        <td>Clara Solutions</td>
                    </tr>
                    <tr>
                        <th>Adresse</th>
                        <td>123, Rue des Soins, 75001 Paris, France</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>contact@clara-solutions.com</td>
                    </tr>
                    <tr>
                        <th>Téléphone</th>
                        <td>+33 1 23 45 67 89</td>
                    </tr>
                    <tr>
                        <th>Numéro SIRET</th>
                        <td>123 456 789 00012</td>
                    </tr>
                </table>

                <h3>Conditions d'utilisation</h3>
                <p>Le site Clara Solutions est destiné aux professionnels du secteur médical pour la gestion des plannings et des dossiers patients. Toute reproduction ou utilisation du contenu sans autorisation est interdite.</p>

                <h3>Propriété intellectuelle</h3>
                <p>Tout le contenu du site (textes, images, logos) est la propriété exclusive de Clara Solutions, sauf mention contraire.</p>

                <h3>Politique de confidentialité</h3>
                <p>Nous collectons des informations uniquement pour améliorer notre service. Aucune donnée personnelle n'est vendue à des tiers.</p>

                <h3>Contact</h3>
                <p>Pour toute question, vous pouvez nous contacter à <a href="mailto:">contact@clara-solutions.com</a> ou dans contact qui se trouve dans le menu.</a></p>
            </section>
        </main>
        <footer>
            <p>&copy; 2024 Clara - Tous droits réservés</p>
        </footer>
    </body>
</html>

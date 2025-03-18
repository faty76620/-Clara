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
    <script defer src="/clara/assets/js.js"></script>
    <title>Gestion des établissements</title>
</head>   
<body class="body-background">
    <?php 
    include __DIR__ . '/../../templates/header_admin.php';
    require_once '../../models/establishment.php';
    require_once '../../models/database.php';
    
    $conn = getConnexion();
    $establishments = getAllEstablishments(($conn));
    ?>
    <main class="dashboard"> 
        <div class="container-title"><h2>Liste des établissements</h2></div> 
        
        <?php if (!empty($establishments)) : ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Téléphone</th>
                        <th>Ville</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($establishments as $establishment) : ?>
                    <tr>
                        <td><?= htmlspecialchars($establishment['id']) ?></td>
                        <td><?= htmlspecialchars($establishment['firstname']) ?></td>
                        <td><?= htmlspecialchars($establishment['phone']) ?></td>
                        <td><?= htmlspecialchars($establishment['adresse']) ?></td>
                        <td>
                            <a href="edit_establishment.php?id=<?= htmlspecialchars($establishment['id']) ?>" class="btn-dashboard edit">Modifier</a>
                            <a href="delete_establishment.php?id=<?= htmlspecialchars($establishment['id']) ?>" class="btn-dashboard delete" onclick="return confirm('Voulez-vous vraiment supprimer cet établissement ?');">Supprimer</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- AFFICHAGE TABLETTE ET MOBILE -->
            <div class="cards-request">
                <?php foreach ($establishments as $establishment) : ?>
                    <div class="cards">
                        <h3><?= htmlspecialchars($establishment['firstname']) ?></h3>
                        <p>ID : <?= htmlspecialchars($establishment['id']) ?></p>
                        <p>Téléphone : <?= htmlspecialchars($establishment['phone']) ?></p>
                        <p>Ville : <?= htmlspecialchars($establishment['adresse']) ?></p>
                        <div>
                            <a href="edit_establishment.php?id=<?= htmlspecialchars($establishment['id']) ?>" class="btn-dashboard edit">Modifier</a>
                            <a href="delete_establishment.php?id=<?= htmlspecialchars($establishment['id']) ?>" class="btn-dashboard delete" onclick="return confirm('Voulez-vous vraiment supprimer cet établissement ?');">Supprimer</a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php else : ?>
                    <p class="no-data">Aucun établissement trouvé.</p>
                <?php endif; ?>
            </div>
    </main>
</body>
</html>

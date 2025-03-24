<?php
require_once '../../models/database.php';
require_once '../../models/establishment.php';
include __DIR__ . '/../../templates/header_admin.php';

$conn = getConnexion();

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$pendingEstablishments = getPendingEstablishments($conn, $search);
$approvedEstablishments = getApprovedEstablishments($conn, $search);
$rejectedEstablishments = getRejectedEstablishments($conn, $search);
?>
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
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script defer src="/clara/assets/js.js"></script>
    <title>Gestion des établissements</title>
</head>   
<body class="body-background">
    <main class="dashboard"> 
    <div class="container-title"><h2>Demandes d'inscription</h2></div>
        <!-- FORMULAIRE DE RECHERCHE -->
        <form method="GET">
            <div class="dashboard-search">
                <input type="text" name="search" placeholder="Rechercher..." value="<?= htmlspecialchars($search); ?>">
                <button type="submit">Rechercher</button>
                <!-- Bouton pour réinitialiser la recherche -->
                <div class="reset"><a href="establishment.php" class="btn-reset"><i class="fas fa-redo"></i></a></div> 
            </div>
        </form>
         <!-- ONGLET POUR BASCULER SUR LES STATUS -->
        <div class="tabs">
            <button id="tab-pending" class="tab-button active" onclick="showTab('pending')"><i class="fas fa-clock"></i><span class="tab-text">Etablissements en attente</span></button>
            <button id="tab-approved" class="tab-button" onclick="showTab('approved')"><i class="fas fa-check-circle"></i><span class="tab-text">Etablissements acceptées</span></button>
            <button id="tab-rejected" class="tab-button" onclick="showTab('rejected')"> <i class="fas fa-times-circle"></i><span class="tab-text">Etablissements refusées</span></button>
        </div>
         <!-- SECTION DES DEMANDES EN ATTENTE -->
        <div id="pending" class="tab-content active">
            <table class="table-request">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Nom</th>
                        <th>Téléphone</th>
                        <th>Adresse</th>
                        <th>Email</th>
                        <th>En savoir plus</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($pendingEstablishments)) : ?>
                        <?php foreach ($pendingEstablishments as $establishment) : ?>
                            <tr>
                                <td><?= htmlspecialchars($establishment['id']) ?></td>
                                <td><?= htmlspecialchars($establishment['created_at']) ?></td>
                                <td><?= htmlspecialchars($establishment['firstname']) ?></td>
                                <td><?= htmlspecialchars($establishment['phone']) ?></td>
                                <td><?= htmlspecialchars($establishment['adresse']) ?></td>
                                <td><?= htmlspecialchars($establishment['mail']) ?></td>
                                <td>
                                    <a href="details-establishment.php?id=<?= htmlspecialchars($establishment['id']); ?>">En savoir plus</a>
                                </td>
                                <td class="action">
                                    <a href="edit-establishment.php?id=<?= htmlspecialchars($establishment['id']) ?>" class="btn-dashboard edit">Modifier</a>
                                    <a href="delete-establishment.php?id=<?= htmlspecialchars($establishment['id']) ?>" class="btn-dashboard delete" onclick="return confirm('Voulez-vous vraiment supprimer cet établissement ?');">Supprimer</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="6">Aucun établissement en attente.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <!-- AFFICHAGE TABLETTE ET MOBILE -->
            <div class="cards-requests">
                <?php if (!empty($pendingEstablishments)) : ?>
                <?php foreach ($pendingEstablishments as $establishment) : ?>
                <div class="card">
                    <h3><?= htmlspecialchars($establishment['firstname']); ?></h3>
                    <p>Date :<?= htmlspecialchars($establishment['created_at']); ?></p>
                    <p>ID :<?= htmlspecialchars($establishment['id']); ?></p>
                    <p>Telephone :<?= htmlspecialchars($establishment['phone']); ?></p>
                    <p>Adresse :<?= htmlspecialchars($establishment['adresse']); ?></p>
                    <p>Email :<?= htmlspecialchars($establishment['mail']); ?></p>
                    <div>
                        <a href="details-estblishment.php?id=<?= htmlspecialchars($establishment['id']); ?>" class="detail">En savoir plus</a>
                    </div>
                    <div class="action">
                        <a href="edit-establishment.php?id=<?= htmlspecialchars($establishment['id']) ?>" class="btn-dashboard edit">Modifier</a>
                        <a href="delete-establishment.php?id=<?= htmlspecialchars($establishment['id']) ?>" class="btn-dashboard delete" 
                        onclick="return confirm('Voulez-vous vraiment supprimer cet établissement ?');">Supprimer</a>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php else : ?>
                <p>Aucune etablissement en attente.</p>
                <?php endif; ?>
            </div>
        </div>
        <!-- SECTION DES ETABLISSEMENT APPROUVE -->
        <div id="approved" class="tab-content">
            <table class="table-request">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Nom</th>
                        <th>Téléphone</th>
                        <th>Adresse</th>
                        <th>Email</th>
                        <th>En savoir plus</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($approvedEstablishments)) : ?>
                        <?php foreach ($approvedEstablishments as $establishment) : ?>
                            <tr>
                                <td><?= htmlspecialchars($establishment['id']) ?></td>
                                <td><?= htmlspecialchars($establishment['created_at']) ?></td>
                                <td><?= htmlspecialchars($establishment['firstname']) ?></td>
                                <td><?= htmlspecialchars($establishment['phone']) ?></td>
                                <td><?= htmlspecialchars($establishment['adresse']) ?></td>
                                <td><?= htmlspecialchars($establishment['mail']) ?></td>
                                <td>
                                    <a href="details-establishment.php?id=<?= htmlspecialchars($establishment['id']); ?>">En savoir plus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="6">Aucun établissement accepté.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <!-- AFFICHAGE TABLETTE ET MOBILE -->
            <div class="cards-requests">
                <?php if (!empty($approvedEstablishments)) : ?>
                <?php foreach ($approvedEstablishments as $establishment) : ?>
                <div class="card">
                    <h3><?= htmlspecialchars($establishment['firstname']); ?></h3>
                    <p>ID :<?= htmlspecialchars($establishment['id']); ?></p>
                    <p>Date :<?= htmlspecialchars($establishment['created_at']); ?></p>
                    <p>Telephone :<?= htmlspecialchars($establishment['phone']); ?></p>
                    <p>Adresse:<?= htmlspecialchars($establishment['adresse']); ?></p>
                    <p>Email :<?= htmlspecialchars($establishment['mail']); ?></p>
                    <div>
                        <a href="details-estblishment.php?id=<?= htmlspecialchars($establishment['id']); ?>" class="detail">En savoir plus</a>
                    </div>
                    <div class="action">
                        <a href="edit-establishment.php?id=<?= htmlspecialchars($establishment['id']) ?>" class="btn-dashboard edit">Modifier</a>
                        <a href="delete-establishment.php?id=<?= htmlspecialchars($establishment['id']) ?>" class="btn-dashboard delete" 
                        onclick="return confirm('Voulez-vous vraiment supprimer cet établissement ?');">Supprimer</a>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php else : ?>
                    <p>Aucune etablissement en attente.</p>
                <?php endif; ?>
            </div>
        </div>
        <!-- SECTIONS DES ESTABLISSEMENT REJETER -->
        <div id="rejected" class="tab-content">
            <table class="table-request">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Nom</th>
                        <th>Téléphone</th>
                        <th>Adresse</th>
                        <th>Email</th>
                        <th>En savoir plus</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($rejectedEstablishments)) : ?>
                        <?php foreach ($rejectedEstablishments as $establishment) : ?>
                            <tr>
                                <td><?= htmlspecialchars($establishment['id']) ?></td>
                                <td><?= htmlspecialchars($establishment['created_at']) ?></td>
                                <td><?= htmlspecialchars($establishment['firstname']) ?></td>
                                <td><?= htmlspecialchars($establishment['phone']) ?></td>
                                <td><?= htmlspecialchars($establishment['adresse']) ?></td>
                                <td><?= htmlspecialchars($establishment['mail']) ?></td>
                                <td>
                                    <a href="details-establishment.php?id=<?= htmlspecialchars($establishment['id']); ?>">En savoir plus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="6">Aucun établissement refusé.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
              <!-- AFFICHAGE TABLETTE ET MOBILE -->
              <div class="cards-requests">
                <?php if (!empty($rejectedEstablishments)) : ?>
                <?php foreach ($rejectedEstablishments as $establishment) : ?>
                <div class="card">
                    <h3><?= htmlspecialchars($establishment['firstname']); ?></h3>
                    <p>ID : <?= htmlspecialchars($establishment['id']); ?></p>
                    <p>Date : <?= htmlspecialchars($establishment['created_at']); ?></p>
                    <p>Telephone : <?= htmlspecialchars($establishment['phone']); ?></p>
                    <p>Adresse : <?= htmlspecialchars($establishment['adresse']); ?></p>
                    <p>Email : <?= htmlspecialchars($establishment['mail']); ?></p>
                    <div>
                        <a href="details-estblishment.php?id=<?= htmlspecialchars($establishment['id']); ?>" class="detail">En savoir plus</a>
                    </div>
                    <div class="action">
                        <a href="edit-establishment.php?id=<?= htmlspecialchars($establishment['id']) ?>" class="btn-dashboard edit">Modifier</a>
                        <a href="delete-establishment.php?id=<?= htmlspecialchars($establishment['id']) ?>" class="btn-dashboard delete" 
                        onclick="return confirm('Voulez-vous vraiment supprimer cet établissement ?');">Supprimer</a>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php else : ?>
                    <p>Aucune etablissement en attente.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>
</body>
</html>



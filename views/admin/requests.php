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
    <title>Demandes inscription</title>
</head>   
<body>
    <?php 
    include __DIR__ . '/../../templates/header_admin.php';
    require_once '../../models/database.php';
    require_once '../../models/send_mail.php';
    require_once '../../models/request.php';
    
    // RECUPERER LES RECHERCHE
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    // RECUPERER LES DEMANDES SELON LEUR STATUTS
    $pendingRequests = getPendingRequests($search);
    $approvedRequests = getApprovedRequests($search);
    $rejectedRequests = getRejectedRequests($search);
    ?>
    <main class="dashboard">
        <!--MESSAGE ALERTE-->
        <div id="alert-info" class="alert-info">
            <i class="fas fa-info-circle"></i>
            <p>En tant qu'administrateur, vous pouvez <strong>"valider"</strong> ou <strong>"refuser"</strong> les demandes d'inscription.
               Cette procédure de sécurité vous permet de vérifier la légitimité des établissements.  
               <i class="fas fa-check-circle" style="color: green;"></i> <strong>Si la demande est approuvée :</strong> Un email automatique sera envoyé au représentant avec un <strong>identifiant</strong> et un <strong>mot de passe provisoire</strong>.  
               <i class="fas fa-times-circle" style="color: red;"></i> <strong>En cas de refus :</strong> Un email contenant un message expliquant le rejet sera envoyé.
            </p>
            <div id="close-alert"><i class="fa-solid fa-square-xmark"></i></div>
        </div>
        <div class="container-title"><h2>Demandes d'inscription</h2></div>
        <!-- FORMULAIRE DE RECHERCHE -->
        <form method="GET">
            <div class="dashboard-search">
                <input type="text" name="search" placeholder="Rechercher..." value="<?= htmlspecialchars($search); ?>">
                <button type="submit">Rechercher</button>
            </div>
        </form>
        <!-- ONGLET POUR BASCULER SUR AUTRE TYPE DE DEMANDES -->
        <div class="tabs">
            <button id="tab-pending" class="tab-button active" onclick="showTab('pending')">Demandes en attente</button>
            <button id="tab-approved" class="tab-button" onclick="showTab('approved')">Demandes acceptées</button>
            <button id="tab-rejected" class="tab-button" onclick="showTab('rejected')">Demandes refusées</button>
        </div>
        <!-- SECTION DES DEMANDES EN ATTENTE -->
        <div id="pending" class="tab-content active">
            <table class="table-request">
                <thead>
                    <tr>
                        <th>Nom de l'entreprise</th>
                        <th>Email</th>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Statut</th>
                        <th>En savoir plus</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($pendingRequests)) : ?>
                    <?php foreach ($pendingRequests as $request) : ?>
                    <tr>
                        <td><?= htmlspecialchars($request['firstname_establishment']); ?></td>
                        <td><?= htmlspecialchars($request['mail_admin']); ?></td>
                        <td><?= htmlspecialchars($request['created_at']); ?></td>
                        <td><?= htmlspecialchars($request['type_role']); ?></td>
                        <td><?= htmlspecialchars($request['status']); ?></td>
                        <td>
                            <a href="details-request.php?id=<?= htmlspecialchars($request['id']); ?>">En savoir plus</a>
                        </td>
                        <td>
                            <div class="action">
                                <a href="/clara/controllers/adminController.php?action=approve&id=<?= htmlspecialchars($request['id']); ?>" class="btn-dashboard btn-success">Approuver</a>
                                <a href="/clara/controllers/adminController.php?action=reject&id=<?= htmlspecialchars($request['id']); ?>" class="btn-dashboard btn-reject">Rejeter</a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php else : ?>
                    <tr>
                        <td colspan="7">Aucune demande en attente.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <!-- AFFICHAGE TABLETTE ET MOBILE -->
            <div class="cards-request">
                <?php if (!empty($pendingRequests)) : ?>
                <?php foreach ($pendingRequests as $request) : ?>
                <div class="card">
                    <h3><?= htmlspecialchars($request['firstname_establishment']); ?></h3>
                    <p><?= htmlspecialchars($request['mail_admin']); ?></p>
                    <p><?= htmlspecialchars($request['created_at']); ?></p>
                    <p><?= htmlspecialchars($request['type_role']); ?></p>
                    <p><?= htmlspecialchars($request['status']); ?></p>
                    <div>
                        <a href="details-request.php?id=<?= htmlspecialchars($request['id']); ?>">Détails</a>
                    </div>
                    <div class="action">
                        <a href="/clara/controllers/adminController.php?action=approve&id=<?= htmlspecialchars($request['id']); ?>" class="btn-dashboard btn-success">Approuver</a>
                        <a href="/clara/controllers/adminController.php?action=reject&id=<?= htmlspecialchars($request['id']); ?>" class="btn-dashboard btn-reject">Rejeter</a>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php else : ?>
                <p>Aucune demande en attente.</p>
                <?php endif; ?>
            </div>
        </div>
        <!-- SECTION DES DEMANDE EN ATTENTES-->
        <div id="approved" class="tab-content">
            <table class="table-request">
                <thead>
                    <tr>
                        <th>Nom de l'entreprise</th>
                        <th>Email</th>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Statut</th>
                        <th>En savoir plus</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($approvedRequests)) : ?>
                    <?php foreach ($approvedRequests as $request) : ?>
                    <tr>
                        <td><?= htmlspecialchars($request['firstname_establishment']); ?></td>
                        <td><?= htmlspecialchars($request['mail_admin']); ?></td>
                        <td><?= htmlspecialchars($request['created_at']); ?></td>
                        <td><?= htmlspecialchars($request['type_role']); ?></td>
                        <td><?= htmlspecialchars($request['status']); ?></td>
                        <td>
                            <a href="details-request.php?id=<?= htmlspecialchars($request['id']); ?>">En savoir plus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php else : ?>
                    <tr>
                        <td colspan="6">Aucune demande acceptée.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <!-- AFFICHAGE TABLETTE ET MOBILE -->
            <div class="cards-request">
                <?php if (!empty($approvedRequests)) : ?>
                <?php foreach ($approvedRequests as $request) : ?>
                <div class="card">
                    <h3><?= htmlspecialchars($request['firstname_establishment']); ?></h3>
                    <p><?= htmlspecialchars($request['mail_admin']); ?></p>
                    <p><?= htmlspecialchars($request['created_at']); ?></p>
                    <p><?= htmlspecialchars($request['type_role']); ?></p>
                    <p><?= htmlspecialchars($request['status']); ?></p>
                    <div>
                        <a href="details-request.php?id=<?= htmlspecialchars($request['id']); ?>">Détails</a>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php else : ?>
                <p>Aucune demande acceptée.</p>
                <?php endif; ?>
            </div>
        </div>
        <!-- SECTION DES DEMANDES REFUSER -->
        <div id="rejected" class="tab-content">
        <table class="table-request">
            <thead>
                <tr>
                    <th>Nom de l'entreprise</th>
                    <th>Email</th>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Statut</th>
                    <th>En savoir plus</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($rejectedRequests)) : ?>
                <?php foreach ($rejectedRequests as $request) : ?>
                <tr>
                    <td><?= htmlspecialchars($request['firstname_establishment']); ?></td>
                    <td><?= htmlspecialchars($request['mail_admin']); ?></td>
                    <td><?= htmlspecialchars($request['created_at']); ?></td>
                    <td><?= htmlspecialchars($request['type_role']); ?></td>
                    <td><?= htmlspecialchars($request['status']); ?></td>
                    <td>
                        <a href="details-request.php?id=<?= htmlspecialchars($request['id']); ?>">En savoir plus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php else : ?>
                <tr>
                    <td colspan="6">Aucune demande refusée.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <!-- AFFICHES DES DEMANDES VERSION TABLETTE MOBILE -->
        <div class="cards-request">
            <?php if (!empty($rejectedRequests)) : ?>
            <?php foreach ($rejectedRequests as $request) : ?>
            <div class="card">
                <h3><?= htmlspecialchars($request['firstname_establishment']); ?></h3>
                <p><?= htmlspecialchars($request['mail_admin']); ?></p>
                <p><?= htmlspecialchars($request['created_at']); ?></p>
                <p><?= htmlspecialchars($request['type_role']); ?></p>
                <p><?= htmlspecialchars($request['status']); ?></p>
                <div>
                    <a href="details-request.php?id=<?= htmlspecialchars($request['id']); ?>">Détails</a>
                </div>
            </div>
            <?php endforeach; ?>
            <?php else : ?>
            <p>Aucune demande refusée.</p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
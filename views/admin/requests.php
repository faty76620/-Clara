<?php
session_start();
?>

<?php if (isset($_SESSION['success'])) : ?>
    <div class="alert success">
        <?= $_SESSION['success']; ?>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])) : ?>
    <div class="alert error">
        <?= $_SESSION['error']; ?>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

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
<body class="body-background">
    <?php 
    include __DIR__ . '/../../templates/header_admin.php';
    require_once '../../models/database.php';
    require_once '../../models/send_mail.php';
    require_once '../../models/request.php';
    
    // Récupérer la recherche
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    
    $conn = getConnexion();
    
    // Récupérer les demandes selon leur statut
    $pendingRequests = getPendingRequests($conn, $search); 
    $approvedRequests = getApprovedRequests($conn, $search); 
    $rejectedRequests = getRejectedRequests($conn, $search); 
    ?>
    <main class="dashboard">
        <!-- Message alerte -->
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
        
        <!-- Formulaire de recherche -->
        <form method="GET">
            <div class="dashboard-search">
                <input type="text" name="search" placeholder="Rechercher..." value="<?= htmlspecialchars($search); ?>">
                <button type="submit">Rechercher</button>
                <div class="reset"><a href="requests.php" class="btn-reset"><i class="fas fa-redo"></i></a></div> 
            </div>
        </form>

        <!-- Onglets pour changer de statut -->
        <div class="tabs">
            <button id="tab-pending" class="tab-button active" onclick="showTab('pending')"><i class="fas fa-clock"></i> <span class="tab-text">En attente</span></button>
            <button id="tab-approved" class="tab-button" onclick="showTab('approved')"><i class="fas fa-check-circle"></i> <span class="tab-text">Acceptées</span></button>
            <button id="tab-rejected" class="tab-button" onclick="showTab('rejected')"><i class="fas fa-times-circle"></i> <span class="tab-text">Refusées</span></button>
        </div>

        <!-- TABLEAU DES DEMANDES EN ATTENTE -->
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
                        <th>Détails</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($pendingRequests)) : ?>
                        <?php foreach ($pendingRequests as $request) : ?>
                            <tr>
                                <td><?= htmlspecialchars($request['id']) ?></td>
                                <td><?= htmlspecialchars($request['created_at']) ?></td>
                                <td><?= htmlspecialchars($request['firstname_establishment']) ?></td>
                                <td><?= htmlspecialchars($request['phone']) ?></td>
                                <td><?= htmlspecialchars($request['adresse']) ?></td>
                                <td><?= htmlspecialchars($request['mail']) ?></td>
                                <td>
                                    <a href="details-request.php?id=<?= htmlspecialchars($request['id']); ?>" class="btn-card">Détails</a>
                                </td>
                                <td>
                                    <div class="action">
                                        <a href="/clara/controllers/adminController.php?action=approve&id=<?= htmlspecialchars($request['id']); ?>" class=" btn-dashboard btn-success">Approuver</a>
                                        <a href="/clara/controllers/adminController.php?action=reject&id=<?= htmlspecialchars($request['id']); ?>" class="btn-dashboard btn-reject">Rejeter</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="8">Aucune demande en attente.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- VERSION CARTE -->
            <div class="cards-container">
                <?php if (!empty($pendingRequests)) : ?>
                    <?php foreach ($pendingRequests as $request) : ?>
                        <div class="card-session">
                            <h3><?= htmlspecialchars($request['firstname_establishment']) ?></h3>
                            <p><strong>ID :</strong> <?= htmlspecialchars($request['id']) ?></p>
                            <p><strong>Date :</strong> <?= htmlspecialchars($request['created_at']) ?></p>
                            <p><strong>Téléphone :</strong> <?= htmlspecialchars($request['phone']) ?></p>
                            <p><strong>Adresse :</strong> <?= htmlspecialchars($request['adresse']) ?></p>
                            <p><strong>Email :</strong> <?= htmlspecialchars($request['mail']) ?></p>
                            <br>
                            <div class="card-actions">
                                <a href="details-request.php?id=<?= htmlspecialchars($request['id']); ?>" class="btn-card details">Détails</a>
                                <a href="/clara/controllers/adminController.php?action=approve&id=<?= htmlspecialchars($request['id']); ?>" class="btn-dashboard btn-success"><i class="fas fa-check-circle" style="color: white;"></i></a>
                                        <a href="/clara/controllers/adminController.php?action=reject&id=<?= htmlspecialchars($request['id']); ?>" class="btn-dashboard btn-reject"><i class="fas fa-times-circle" style="color: white;"></i></a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p>Aucune demande en attente.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- DEMANDE APPROUVEE -->
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
                        <th>Détails</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($approvedRequests)) : ?>
                        <?php foreach ($approvedRequests as $request) : ?>
                            <tr>
                                <td><?= htmlspecialchars($request['id']) ?></td>
                                <td><?= htmlspecialchars($request['created_at']) ?></td>
                                <td><?= htmlspecialchars($request['firstname_establishment']) ?></td>
                                <td><?= htmlspecialchars($request['phone']) ?></td>
                                <td><?= htmlspecialchars($request['adresse']) ?></td>
                                <td><?= htmlspecialchars($request['mail']) ?></td>
                                <td>
                                    <a href="details-request.php?id=<?= htmlspecialchars($request['id']); ?>" class="btn-card detail-plus">Détails</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="7">Aucune demande acceptée.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- VERSION CARTE -->
            <div class="cards-container">
                <?php if (!empty($approvedRequests)) : ?>
                    <?php foreach ($approvedRequests as $request) : ?>
                        <div class="card-session">
                            <h3><?= htmlspecialchars($request['firstname_establishment']) ?></h3>
                            <p><strong>ID :</strong> <?= htmlspecialchars($request['id']) ?></p>
                            <p><strong>Date :</strong> <?= htmlspecialchars($request['created_at']) ?></p>
                            <p><strong>Téléphone :</strong> <?= htmlspecialchars($request['phone']) ?></p>
                            <p><strong>Adresse :</strong> <?= htmlspecialchars($request['adresse']) ?></p>
                            <p><strong>Email :</strong> <?= htmlspecialchars($request['mail']) ?></p>
                            <br>
                            <div class="card-actions">
                                <a href="details-request.php?id=<?= htmlspecialchars($request['id']); ?>" class="btn-card detail-plus">Détails</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p>Aucune demande acceptée.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- DEMANDE REJETER -->
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
                        <th>Détails</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($rejectedRequests)) : ?>
                        <?php foreach ($rejectedRequests as $request) : ?>
                            <tr>
                                <td><?= htmlspecialchars($request['id']) ?></td>
                                <td><?= htmlspecialchars($request['created_at']) ?></td>
                                <td><?= htmlspecialchars($request['firstname_establishment']) ?></td>
                                <td><?= htmlspecialchars($request['phone']) ?></td>
                                <td><?= htmlspecialchars($request['adresse']) ?></td>
                                <td><?= htmlspecialchars($request['mail']) ?></td>
                                <td>
                                    <a href="details-request.php?id=<?= htmlspecialchars($request['id']); ?>" class="btn-card detail-plus">Détails</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="7">Aucune demande rejetée.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- VERSION CARTE -->
            <div class="cards-container">
                <?php if (!empty($rejectedRequests)) : ?>
                    <?php foreach ($rejectedRequests as $request) : ?>
                        <div class="card-session">
                            <h3><?= htmlspecialchars($request['firstname_establishment']) ?></h3>
                            <p><strong>ID :</strong> <?= htmlspecialchars($request['id']) ?></p>
                            <p><strong>Date :</strong> <?= htmlspecialchars($request['created_at']) ?></p>
                            <p><strong>Téléphone :</strong> <?= htmlspecialchars($request['phone']) ?></p>
                            <p><strong>Adresse :</strong> <?= htmlspecialchars($request['adresse']) ?></p>
                            <p><strong>Email :</strong> <?= htmlspecialchars($request['mail']) ?></p>
                            <br>
                            <div class="card-actions">
                                <a href="details-request.php?id=<?= htmlspecialchars($request['id']); ?>" class="btn-card detail-plus">Détails</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p>Aucune demande rejetée.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <script src="/clara/assets/js.js"></script>
</body>
</html>




